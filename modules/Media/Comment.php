<?php
/**
 * CellForm Comment Class
 * Component Comment
 *
 * @author rootofgeno@gmail.com
 * @param MediaController
 */

class Media_Comment extends Media_Controller
{
	public function __construct(Media_Controller $controller)
	{
		$this->_db		= $controller->_db;
		$this->_http	= $controller->_http;
		$this->_user	= $controller->_user;

		$this->_info	= $this->_user->GetInfo();
	}

	/**
	* Add a comment & return JSON entries
	*
	* @access public
	* @return bool|null
	*/
	public function AddComment()
	{
		$response = array();

		$valide_rules = array(
			'id'		=> 'Required|Numeric',
			'message'	=> 'Required|MaxLen,2048',
			'csrf'		=> 'Required|Csrf',
		);

		$filter_rules = array(
			'message'	=> 'Safe',
		);

		$this->_http->ValidatorRules($valide_rules);
		$this->_http->FiltratorRules($filter_rules);

		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			$s_message = $this->_db->SqlEscape($input['message']);
			$s_id = $this->_db->SqlEscape($input['id']);

			$sql = "INSERT INTO cellform_coms (msg, date, score, user, post_id)
				VALUES (
				'" . $s_message . "',
				NOW(),
				'0',
				'" . $this->_info['username'] . "',
				'" . $s_id . "');";

			$this->_db->SqlQuery($sql);

			if ($this->_db->SqlAffectedRows() === 1)
			{
				$com_id = $this->_db->SqlLastId();

				$sql = "UPDATE cellform_posts
					SET nbcomments = nbcomments+1
					WHERE id = '" . $s_id . "'";

				$this->_db->SqlQuery($sql);

				$clause = "c.post_id = '" . $s_id . "' AND c.id = '" . $com_id . "'";

				$data = $this->_Lists($clause);

				$response['data'] = $data;
			}

			echo $this->_http->GetPostBody('application/json', $response);
		}
		else
		{
			return $this->_http->CheckErrnos();
		}
	}

	/**
	* Get all comments to a specific post & return JSON entries
	*
	* @access public
	*/
	public function GetComments()
	{
		$valide_rules = array(
			'id'	=> 'Required|Numeric',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			$clause = "c.post_id = '" . $this->_db->SqlEscape($input['id']) . "' ORDER BY c.id DESC";
	
			$data = $this->_Lists($clause);

			$response['data'] = $data;

			echo $this->_http->GetPostBody('application/json', $response);
		}
	}

	/**
	* Returns comments with $clause layout
	*
	* @access protected
	* @param string $clause
	* @return array
	*/
	protected function _Lists($clause = '')
	{
		$s_username = $this->_db->SqlEscape($this->_info['username']);

		$sql = "SELECT c.id,
			c.msg,
			c.date,
			c.score,
			c.user,
			c.post_id,
			u.avatar,
			v.vote
			FROM cellform_coms AS c
				LEFT JOIN cellform_comsvote AS v
					ON v.com_id = c.id
						AND v.uservoted = '" . $s_username . "'
				LEFT JOIN cellform_users AS u
					ON u.username = c.user
			WHERE " . $clause . ";";

		$data = $this->_db->SqlQuery($sql);

		return $data;
	}
}