<?php
/**
 * CellForm Favorites Class
 * Component Favorites
 *
 * @author rootofgeno@gmail.com
 * @param Media_Controller
 */

class Media_Favorites extends Media_Controller
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
	* View all your favorites selection.
	*
	* @access public
	* @return array
	*/
	public function View()
	{
		$response = array();

		$response['masonry_run'] = 'true';
		$response['masonry_url'] = '/media/ticket/favorites';

		return array(
			'template'	=> 'favorites',
			'response'	=> $response,
		);
	}

	/**
	* Add a ticket to your favorites list & returns JSON.
	*
	* @access public
	* @return bool
	*/
	public function Add()
	{
		$valide_rules = array(
			'id'	=> 'Required|Numeric',
			'csrf'	=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);

		$input = $this->_http->Analyzer($_POST);

		$s_id = $this->_db->SqlEscape($input['id']);
		$s_username = $this->_db->SqlEscape($this->_info['username']);

		$sql = "SELECT id
			FROM cellform_favorites
			WHERE post_id = '" . $s_id . "'
				AND user = '" . $s_username . "'";

		if ($this->_http->IsValid() && !$this->_db->SqlQuery($sql))
		{
			$sql = "INSERT INTO cellform_favorites (post_id, user)
				VALUES (
				'" . $s_id . "',
				'" . $s_username . "'
				);";

			$this->_db->SqlQuery($sql);

			$this->_errno->AppendNotif('Favorite_Added');
		}
		else
		{
			$this->_errno->AppendError('General_AlreadyAdd');
		}

		return $this->_http->CheckErrnos();
	}

	/**
	* Delete a ticket in your favorites list & returns JSON.
	*
	* @access public
	*/
	public function Del()
	{
		$valide_rules = array(
			'id'	=> 'Required|Numeric',
			'csrf'	=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);

		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			$sql = "DELETE FROM cellform_favorites
				WHERE post_id = '" . $this->_db->SqlEscape($input['id']) . "'
					AND user = '" . $this->_db->SqlEscape($this->_info['username']) . "';";

			$this->_db->SqlQuery($sql);
		}

		if ($this->_db->SqlAffectedRows() == 1)
		{
			echo $this->_http->GetPostBody('application/json', 'success');
		}
		else
		{
			echo $this->_http->GetPostBody('application/json', 'fail');
		}
	}
}