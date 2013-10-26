<?php
/**
 * CellForm Notifications Class
 * Component Notifications
 *
 * @author rootofgeno@gmail.com
 * @param MediaController
 */

class Media_Notifications extends Media_Controller
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
	* Returns last five comments to format JSON (Only comments relevant to the user post)
	*
	* @access public
	*/
	public function OverviewComments()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$clause = "WHERE p.user = '" . $this->_db->SqlEscape($this->_info['username']) . "' ORDER BY j.id DESC LIMIT 5";
			$notifs = $this->_Lists('coms', $clause);

			echo $this->_http->GetPostBody('application/json', $notifs);
		}
	}

	/**
	* Returns last five votes to format JSON (Only votes relevant to the user post)
	*
	* @access public
	*/
	public function OverviewVotes()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$clause = "WHERE p.user = '" . $this->_db->SqlEscape($this->_info['username']) . "' ORDER BY j.id DESC LIMIT 5";
			$notifs = $this->_Lists('votes', $clause);

			echo $this->_http->GetPostBody('application/json', $notifs);
		}
	}

	/**
	* Returns all comments or votes (Only relevant to the user post)
	*
	* @access public
	* @return array
	*/
	public function All()
	{
		$response = array();
		$valide_rules = array(
			'user'	=> 'Required|MaxLen,30',
			'type'	=> 'Required|MaxLen,30',
			'top'	=> 'Numeric',
		);

		$this->_http->ValidatorRules($valide_rules);

		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			if (!empty($input['top']) && is_numeric($input['top']) && $input['top'] < 20)
			{
				$limit = 'LIMIT ' . $this->_db->SqlEscape($input['top']);
			}
			else
			{
				$limit = '';
			}

			$clause = "WHERE p.user = '" . $this->_db->SqlEscape($input['user']) . "' ORDER BY j.id DESC " . $limit;

			$response['notifs'] = $this->_Lists($input['type'], $clause);
		}

		return array(
			'template'	=> 'notifications',
			'response'	=> $response,
		);
	}

	/**
	* Returns all comments or votes (Only relevant to the user post)
	*
	* @access protected
	* @return array|false
	*/
	protected function _Lists($type, $clause)
	{
		$sql = '';

		if ($type == 'coms')
		{
			$sql = "SELECT p.id,
				p.title,
				j.user,
				j.date
				FROM cellform_posts AS p
					RIGHT JOIN cellform_coms AS j
						ON j.post_id = p.id
				" . $clause . ";";
		}

		if ($type == 'votes')
		{
			$sql = "SELECT p.id,
				p.title,
				j.uservoted,
				j.vote
				FROM cellform_posts AS p
					RIGHT JOIN cellform_postsvote AS j
						ON j.post_id = p.id
				" . $clause . ";";
		}

		if (!empty($sql))
		{
			return $this->_db->SqlQuery($sql);
		}
		else
		{
			return false;
		}
	}
}