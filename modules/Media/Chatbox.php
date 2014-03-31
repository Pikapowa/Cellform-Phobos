<?php
/**
 * CellForm Chatbox Class
 * Component Chatbox
 *
 * @author rootofgeno@gmail.com
 * @param Media_Controller
 */

class Media_Chatbox extends Media_Controller
{
	public function __construct(Media_Controller $controller)
	{
		$this->_db		= $controller->_db;
		$this->_http	= $controller->_http;
		$this->_user	= $controller->_user;

		$this->_info	= $this->_user->GetInfo();
	}

	/**
	* Get all instant message not read & returns JSON. [CACHED]
	*
	* @access public
	*/
	public function Comet()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$response = array();

			$s_username = $this->_db->SqlEscape($this->_info['username']);

			$sql = "SELECT id,
				username,
				username_d,
				message,
				date,
				useread 
					FROM cellform_chat 
					WHERE username_d = '" . $s_username . "' 
						AND useread = 0 ORDER BY id ASC";

			$data = $this->_db->SqlQuery($sql);

			if ($data)
			{
				$response['data'] = $data;

				$sql = "UPDATE cellform_chat 
					SET useread = 1
					WHERE username_d = '" . $s_username . "' 
						AND useread = 0";

				$this->_db->SqlQuery($sql);

				echo $this->_http->GetPostBody('application/json', $response);
			}
			else
			{
				echo $this->_http->GetPostBody('application/json', 'empty');
			}
		}
	}

	public function Start()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$response = array();
			$valide_rules = array(
				'username_d'	=> 'Required|MaxLen,64',
			);

			$this->_http->ValidatorRules($valide_rules);

			$input = $this->_http->Analyzer($_GET);

			if ($this->_http->IsValid())
			{
				$s_username	= $this->_db->SqlEscape($this->_info['username']);
				$s_username_d = $this->_db->SqlEscape($input['username_d']);

				$sql = "SELECT id,
					username,
					username_d,
					message,
					date,
					useread 
						FROM cellform_chat 
						WHERE (username_d = '" . $s_username_d . "' AND username = '" . $s_username . "')
							OR (username_d = '" . $s_username . "' AND username = '" . $s_username_d . "')
							ORDER BY id DESC LIMIT 5";

				$data = $this->_db->SqlQuery($sql);

				$data = array_reverse($data);

				$response['data'] = $data;

				echo $this->_http->GetPostBody('application/json', $response);
			}
			else
			{
				echo $this->_http->GetPostBody('application/json', 'fail');
			}
		}
	}

	/**
	* Simple function to send a instant message & returns JSON. [CACHED]
	*
	* @access public
	*/
	public function Send()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$valide_rules = array(
				'username_d'	=> 'Required|MaxLen,64',
				'message'		=> 'Required|MaxLen,2048',
				'csrf'			=> 'Required|Csrf',
			);

			$filter_rules = array(
				'message'	=> 'SanitizeString',
			);

			$this->_http->ValidatorRules($valide_rules);
			$this->_http->FiltratorRules($filter_rules);

			$input = $this->_http->Analyzer($_POST);

			if ($this->_http->IsValid())
			{
				$s_username	= $this->_db->SqlEscape($this->_info['username']);
				$s_username_d = $this->_db->SqlEscape($input['username_d']);
				$s_message	= $this->_db->SqlEscape($input['message']);

				$sql = "INSERT INTO cellform_chat (username, username_d, message, date, useread) 
					VALUES ('" . $s_username . "',
					'" . $s_username_d . "',
					'" . $s_message . "',
					NOW(),
					'0')";

				$this->_db->SqlQuery($sql);

				if (!isset($_SESSION['chatbox_history'][$s_username_d]))
				{
					$_SESSION['chatbox_history'][$s_username_d] = '';
				}

				$_SESSION['chatbox_history'][$s_username_d][] = $s_message;

				echo $this->_http->GetPostBody('application/json', 'success');
			}
			else
			{
				echo $this->_http->GetPostBody('application/json', 'fail');
			}
		}
	}
}