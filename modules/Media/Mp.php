<?php
/**
 * CellForm Mp Class
 * Component Mp
 *
 * @author rootofgeno@gmail.com
 * @param MediaController
 */

class Media_Mp extends Media_Controller
{
	public function __construct(Media_Controller $controller)
	{
		$this->_db		= $controller->_db;
		$this->_errno 	= $controller->_errno;
		$this->_http	= $controller->_http;
		$this->_user	= $controller->_user;

		$this->_info	= $this->_user->GetInfo();
	}

	/**
	* Print all private message
	*
	* @access public
	* @return array
	*/
	public function Inbox()
	{
		$response = array();

		$clause = "WHERE m.username_d = '" . $this->_db->SqlEscape($this->_info['username']) . "' ORDER BY id DESC";

		$response['mps'] = $this->_Lists($clause);

		return array(
			'template'	=> 'mp',
			'response'	=> $response,
		);
	}

	/**
	* Delete private message & returns JSON.
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
				$sql = "DELETE FROM cellform_mp
					WHERE id = '" . $this->_db->SqlEscape($nodes) . "'
						AND username_d = '" . $this->_db->SqlEscape($this->_info['username']) . "'";

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
	* View a specific private message & update useread to yes
	*
	* @access public
	* @return array
	*/
	public function View()
	{
		$response = array();

		$valide_rules = array(
			'id'	=> 'Required|Numeric',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			$s_id = $this->_db->SqlEscape($input['id']);
			$s_username = $this->_db->SqlEscape($this->_info['username']);

			$clause = "WHERE m.id = '" . $s_id . "'";
			$data = $this->_Lists($clause);

			if ($data[0]['useread'] == 'no')
			{
				$sql = "UPDATE cellform_mp SET
					useread='yes'
					WHERE id = '" . $s_id . "'
						AND username_d = '" . $s_username . "'";

				$this->_db->SqlQuery($sql);
			}

			$response['mp'] = $data[0];
		}

		return array(
			'template'	=> 'mp_view',
			'response'	=> $response,
		);
	}

	/**
	* Send a private message
	*
	* @access public
	* @return array
	*/
	public function Send()
	{
		$response = array();

		$valide_rules = array(
			'username_d'	=> 'Required|MaxLen,30',
			'subject'		=> 'Required|MaxLen,128',
			'message'		=> 'Required|MaxLen,2048',
			'csrf'			=> 'Required|Csrf',
		);

		$filter_rules = array(
			'subject'	=> 'Htmlencode',
			'message'	=> 'Safe',
		);

		$this->_http->ValidatorRules($valide_rules);
		$this->_http->FiltratorRules($filter_rules);

		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->Is_Set('send'))
		{
			if ($this->_http->IsValid())
			{
				if (strcasecmp($input['username_d'], 'all') == 0)
				{
					$users = $this->_user->Lists();

					if ($users)
					{
						foreach($users AS $user)
						{
							if (!$this->_Send($input['subject'], $user['username'], $input['message']))
							{
								$this->_errno->AppendError('Mp_NotSend');
							}
						}

						if (!$this->_errno->ErrorsExist())
						{
							$this->_errno->AppendNotif('Mp_Send');
						}
					}
				}
				else
				{
					if ($this->_user->UserExists($input['username_d']))
					{
						if ($this->_Send($input['subject'], $input['username_d'], $input['message']))
						{
							$this->_errno->AppendNotif('Mp_Send');
						}
					}
					else
					{
						$this->_errno->AppendError('General_UserNotFound');
					}
				}
			}

			$response['errnos'] = $this->_errno->GetErrnos();
		}

		return array(
			'template'	=> 'mp_send',
			'response'	=> $response,
		);
	}

	/**
	* Insert new private message
	*
	* @access protected
	* @param string $subject
	* @param string $destinataire
	* @param string $message
	* @return bool
	*/
	private function _Send($subject, $destinataire, $message)
	{
		$sql = "INSERT INTO cellform_mp (subject, username, username_d, message, date, useread)
			VALUES (
			'" . $this->_db->SqlEscape($subject) . "',
			'" . $this->_db->SqlEscape($this->_info['username']) . "',
			'" . $this->_db->SqlEscape($destinataire) . "',
			'" . $this->_db->SqlEscape($message) . "',
			NOW(),
			'no'
			);";

		$this->_db->SqlQuery($sql);

		return ($this->_db->SqlAffectedRows() == 1) ? true : false;
	}

	/**
	* Returns private message with $clause layout
	*
	* @access protected
	* @return array
	*/
	protected function _Lists($clause)
	{
		$sql = "SELECT m.id,
			m.subject,
			m.message,
			m.username,
			m.date,
			m.useread,
			u.avatar
				FROM cellform_mp AS m
					LEFT JOIN cellform_users AS u
						ON u.username = m.username
				" . $clause . ";";

		return $this->_db->SqlQuery($sql);
	}
}
?>