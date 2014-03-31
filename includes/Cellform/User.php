<?php
/**
 * CellForm User Class & Auth parents
 * User management functions
 *
 * @author rootofgeno@gmail.com
 * @param Cellform_DB
 * @param Cellform_HTTP
 */

class Cellform_User extends Cellform_Auth
{
	public function __construct(Cellform_DB $db,
								Cellform_HTTP $http)
	{
		parent::__construct($db, $http);
	}

	/**
	* Return true if user is connected else false
	*
	* @access public
	* @return bool
	*/
	public function IsConnected()
	{
		return !empty($this->_info);
	}

	/**
	* Get user informations
	*
	* @access public
	* @return array|false
	*/
	public function GetInfo()
	{
		return ($this->_info) ? $this->_info[0] : false;
	}

	/**
	* User register (Signup basic or OAuth)
	*
	* @access public
	* @param string $email
	* @param string $username
	* @param string $password
	* @param string $sex
	* @param bool $valid
	* @return string|false
	*/
	public function Register($email, $username, $password, $sex, $valid, $oauth_id = '', $level = LEVEL_USER)
	{
		$s_email = $this->_db->SqlEscape($email);
		$s_username = $this->_db->SqlEscape($username);

		$this->_db->SqlQuery("SELECT id
			FROM cellform_users
			WHERE email = '" . $s_email . "'
				OR username = '" . $s_username . "'");

		if ($this->_db->SqlNumRows())
		{
			return false;
		}

		$encoded = Cellform_Cypher::Encode($password . SALT);
		$token = Cellform_Cypher::GenerateToken($encoded);

		$sql = "INSERT INTO cellform_users (email, username, password, avatar, sex, score, likes, regdate, lastvisit, level, token, valid, forget, oauth_id)
				VALUES ('" . $s_email . "',
						'" . $s_username . "',
						'" . $encoded . "',
						'defaults.jpg',
						'" . (($sex == 'male') ? 'male' : 'female') . "',
						'10',
						'0',
						NOW(),
						NOW(),
						'" . (int)$level . "',
						'" . $token . "',
						'" . (isdefined('EMAIL') && $valid ? 0 : 1) . "',
						'1',
						'" . (($oauth_id) ? $oauth_id : 1) . "')";

		$this->_db->SqlQuery($sql);

		return $this->_db->SqlAffectedRows() ? $token : false;
	}

	/**
	* User validation
	*
	* @access public
	* @param string $email
	* @param string $token
	* @return bool
	*/
	public function Validator($email, $token)
	{
		$s_email = $this->_db->SqlEscape($email);
		$s_token = $this->_db->SqlEscape($token);

		$sql = "UPDATE cellform_users
			SET valid = '1'
			WHERE email = '" . $s_email . "'
				AND token = '" . $s_token . "'
				AND valid = '0'";
		
		$this->_db->SqlQuery($sql);

		return $this->_db->SqlAffectedRows() ? true : false;
	}

	/**
	* User reset password
	*
	* @access public
	* @param string $email
	* @param string $uniqid
	* @return string|false
	*/
	public function RecoveryPasswd($email, $forget)
	{
		if ($forget == '1')
		{
			return false;
		}

		$s_email  = $this->_db->SqlEscape($email);
		$s_forget = $this->_db->SqlEscape($forget);

		$passwd = random(8);
		$encoded = Cellform_Cypher::Encode($passwd . SALT);

		$sql = "UPDATE cellform_users
			SET password = '" . $encoded . "',
			forget = ''
			WHERE email = '". $s_email ."'
				AND forget = '" . $s_forget . "'
				AND valid = '1'";
		
		$this->_db->SqlQuery($sql);

		return $this->_db->SqlAffectedRows() ? $passwd : false;
	}

	/**
	* Activate the reset password
	*
	* @access public
	* @param string $email
	* @return string|false
	*/
	public function RequestRecovery($email)
	{
		$s_email = $this->_db->SqlEscape($email);

		$hash = Cellform_Cypher::GenerateToken(rand());

		$sql = "UPDATE cellform_users
			SET forget = '" . $hash . "'
			WHERE email = '" . $s_email . "'
				AND valid = '1'";
		
		$this->_db->SqlQuery($sql);

		return $this->_db->SqlAffectedRows() ? $hash : false;
	}

	/**
	* Change user password (when user is connected)
	*
	* @access public
	* @param string $oldpasswd
	* @param string $newpasswd
	* @return bool
	*/
	public function ChangePassword($oldpasswd, $newpasswd)
	{
		if (!$this->IsConnected())
		{
			throw new Exception('To change password the user must be connect.');
		}

		$email = $this->_info[0]['email'];

		$oldpasswd = Cellform_Cypher::Encode($oldpasswd . SALT);
		$newpasswd = Cellform_Cypher::Encode($newpasswd . SALT);

		$sql = "UPDATE cellform_users
			SET password = '" . $newpasswd . "'
			WHERE email = '". $this->_db->SqlEscape($email) ."'
				AND password = '" . $oldpasswd . "'
				AND valid = '1'";
		
		$this->_db->SqlQuery($sql);

		return $this->_db->SqlAffectedRows() ? true : false;
	}

	/**
	* Change user email (when user is connected)
	*
	* @access public
	* @param string $newemail
	* @return bool
	*/
	public function ChangeEmail($newmail, $password)
	{
		if (!$this->IsConnected())
		{
			throw new Exception('To change email the user must be connect.');
		}

		$s_newmail = $this->_db->SqlEscape($newmail);

		$sql = "SELECT id
			FROM cellform_users
			WHERE email = '" . $s_newmail . "'";

		if ($this->_db->SqlQuery($sql))
		{
			return false;
		}

		$passwd = Cellform_Cypher::Encode($password . SALT);
		$email = $this->_info[0]['email'];

		$sql = "UPDATE cellform_users
			SET email = '" . $s_newmail . "'
			WHERE email = '" . $this->_db->SqlEscape($email) . "'
				AND password = '" . $passwd . "'
				AND valid = '1'";

		$this->_db->SqlQuery($sql);

		return $this->_db->SqlAffectedRows() ? true : false;
	}

	/**
	* Change user avatar (when user is connected)
	*
	* @access public
	* @param string $avatar
	* @return bool
	*/
	public function ChangeAvatar($avatar)
	{
		if (!$this->IsConnected())
		{
			throw new Exception('To change avatar the user must be connect.');
		}

		$email = $this->_info[0]['email'];

		$sql = "UPDATE cellform_users
			SET avatar = '" . $this->_db->SqlEscape($avatar) . "'
			WHERE email = '" . $this->_db->SqlEscape($email) . "'
				AND valid = '1'";

		$this->_db->SqlQuery($sql);

		$this->_info[0]['avatar'] = $avatar;

		return $this->_db->SqlAffectedRows() ? true : false;
	}

	/**
	* Check if user exist with username
	*
	* @access public
	* @param string $username
	* @return bool
	*/
	public function UserExists($username)
	{
		$sql = "SELECT u.id
			FROM cellform_users AS u
			WHERE u.username = '" . $this->_db->SqlEscape($username) . "';";

		return $this->_db->SqlQuery($sql) ? true : false;
	}

	/**
	* Returns username of all active members
	*
	* @access public
	* @return array|false
	*/
	public function Lists()
	{
		$sql = "SELECT u.username
			FROM cellform_users AS u
			WHERE u.valid = '1';";

		$data = $this->_db->SqlQuery($sql);

		return ($data) ? $data : false;
	}
}

?>