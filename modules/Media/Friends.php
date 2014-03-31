<?php
/**
 * CellForm Users Class
 * Component Users
 *
 * @author rootofgeno@gmail.com
 * @param MediaController
 */

class Media_Friends extends Media_Controller
{
	public function __construct(Media_Controller $controller)
	{
		$this->_controller	= $controller;

		$this->_db			= $controller->_db;
		$this->_errno 		= $controller->_errno;
		$this->_http		= $controller->_http;
		$this->_user		= $controller->_user;

		$this->_info		= $this->_user->GetInfo();
	}

	/**
	* Add a user to friend list & returns JSON.
	*
	* @access public
	* @return bool
	*/
	public function Add()
	{
		$valide_rules = array(
			'user'	=> 'Required|MaxLen,30',
			'csrf'	=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);

		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			$s_user = $this->_db->SqlEscape($input['user']);
			$s_username = $this->_db->SqlEscape($this->_info['username']);

			$sql = "SELECT f.id
				FROM cellform_friends AS f
				WHERE f.friends = '" . $s_user . "'
					AND f.username = '" . $s_username . "'";

			$data = $this->_db->SqlQuery($sql);

			if (empty($data) && strcasecmp($s_user, $s_username) != 0)
			{
				$sql = "INSERT INTO cellform_friends (username, friends)
					VALUES (
					'" . $s_username . "',
					'" . $s_user . "');";

				$this->_db->SqlQuery($sql);

				$this->_errno->AppendNotif('Friend_Added');
			}
			else
			{
				$this->_errno->AppendError('General_AlreadyAdd');
			}
		}

		return $this->_http->CheckErrnos();
	}

	/**
	* Delete a user to friend list
	*
	* @access public
	* @return bool
	*/
	public function Del()
	{
		$valide_rules = array(
			'csrf'	=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			foreach($input['nodes'] as $nodes)
			{
				$sql = "DELETE FROM cellform_friends
					WHERE id = '" . $this->_db->SqlEscape($nodes) . "'
						AND username = '" . $this->_db->SqlEscape($this->_info['username']) . "'";

				$this->_db->SqlQuery($sql);
			}

			if ($this->_db->SqlAffectedRows() == 1)
			{
				$this->_errno->AppendNotif('General_Deleted');
			}
			else
			{
				$this->_errno->AppendError('General_Error');
			}
		}

		return $this->_http->CheckErrnos();
	}

	/**
	* List all friends
	*
	* @access public
	* @return array
	*/
	public function Lists()
	{
		$response['friends'] = $this->_Lists();

		return array(
			'template'	=> 'friends',
			'response'	=> $response,
		);
	}

	/**
	* List all online friends (accuracy of 3 minutes)
	*
	* @access public
	* @return array
	*/
	public function Online()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$response = array();

			$data = $this->_Lists();

			foreach($data as $key => $val)
			{
				$expire = strtotime($val['lastvisit']);

				$bool = ($expire >= (time() - (60 * 3)));

				if ($bool)
				{
					$response['friends'][] = $val;
				}
			}

			if (!empty($response))
			{
				echo $this->_http->GetPostBody('application/json', $response);
			}
			else
			{
				echo $this->_http->GetPostBody('application/json', 'empty');
			}
		}
	}

	/**
	* Returns users-friends with $clause layout
	*
	* @access protected
	* @return array
	*/
	protected function _Lists()
	{
		$sql = "SELECT f.id,
			f.friends,
			u.score,
			u.avatar,
			u.lastvisit
			FROM cellform_friends AS f
				LEFT JOIN cellform_users AS u
					ON f.friends = u.username
			WHERE f.username = '" . $this->_db->SqlEscape($this->_info['username']) . "'";

		return $this->_db->SqlQuery($sql);
	}
}

?>