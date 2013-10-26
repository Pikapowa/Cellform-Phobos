<?php
/**
 * CellForm Auth Class
 * Authentication functions
 *
 * @author rootofgeno@gmail.com
 * @param Cellform_DB
 * @param Cellform_HTTP
 */

class Cellform_Auth
{
	protected $_db;
	protected $_http;
	protected $_info = array();
	protected $_level = 1;

	public function __construct(Cellform_DB $db,
								Cellform_HTTP $http)
	{
		$this->_db = $db;
		$this->_http = $http;
	}

	/**
	* Function to call for secured the current page
	*
	* @access public
	* @return bool
	*/
	public function StartSecureArea($level)
	{
		$this->SetLevel($level);

		if (!$this->Checking())
		{
			if ($this->_http->Is_Set('ajax'))
			{
				echo $this->_http->GetPostBody('application/json', 'denied');
			}
			else
			{
				$this->_http->Header('Location', '/home/denied');
				$this->_http->SendHeaders();
			}

			exit();
		}

		return true;
	}

	/**
	* Check if user is connected
	*
	* @access public
	* @return bool
	*/
	public function Checking()
	{
		if (!isset($_COOKIE['email']) || !isset($_COOKIE['session_id']))
		{
			return false;
		}

		$s_email = filter_input(INPUT_COOKIE, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$session = filter_input(INPUT_COOKIE, 'session_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if (!$this->IsRegistered($s_email, null, $session, 'check'))
		{
			return false;
		}

		return true;
	}

	/**
	* Check for user registration, user-level & return user info
	*
	* @access public
	* @param string $email
	* @param string $password
	* @param string $token
	* @param string $mode
	* @return bool
	*/
	public function IsRegistered($email, $password, $token, $mode)
	{
		if ($token == '1')
		{
			return false;
		}

		$s_email = $this->_db->SqlEscape($email);
		$s_token = $this->_db->SqlEscape($token);

		switch($mode)
		{
			case 'init' :
				$sql = "SELECT u.id,
					u.username,
					u.email,
					u.avatar,
					u.level
						FROM cellform_users AS u
						WHERE u.email = '" . $s_email . "'
							AND u.password = '" . $password . "'
							AND u.valid = '1'";
				break;

			case 'check' :
				$sql = "SELECT u.id,
					u.username,
					u.email,
					u.avatar,
					u.level,
					(SELECT COUNT(*) FROM cellform_mp WHERE username_d = u.username AND useread = 'no') AS mp
						FROM cellform_users AS u
						WHERE (u.token = '" . $s_token . "' OR u.oauth_id = '" . $s_token . "')
							AND u.email = '" . $s_email . "'
							AND u.valid = '1'";
				break;

			default : return false;
		}

		if (($info = $this->_db->SqlQuery($sql)))
		{
			$this->_info = $info;
		}

		return ($this->CheckLevel() && $this->_db->SqlNumRows() == 1) ? true : false;
	}

	/**
	* Set the required level for current page
	*
	* @access protected
	* @param int $level
	*/
	protected function SetLevel($level)
	{
		$this->_level = $level;
	}

	/**
	* Check the user role
	*
	* @access protected
	* @return bool
	*/
	protected function CheckLevel()
	{
		if (!empty($this->_info[0]['level']))
		{
			return $this->_info[0]['level'] >= $this->_level ? true : false;
		}
		else
		{
			return false;
		}
	}
}

?>