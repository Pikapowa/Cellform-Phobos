<?php
/**
 * CellForm Vote Class
 * Component Vote
 *
 * @author rootofgeno@gmail.com
 * @param Media_Controller
 */

class Media_Vote extends Media_Controller
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
	* Main runtime for system vote (On multiple table)
	* $table_name is the parent of table vote, example : [cellform_coms] is parent of [cellform_comsvote]
	* $field_id is the id of object voted
	* $vote_int is the value added to score
	* 
	* Returns JSON on this format : {"vote":"up" OR "down","score":"score to add"}
	*
	* @access protected
	* @param string $table_name
	* @param string $field_id
	* @param int $vote_int
	*/
	protected function _Vote($table_name, $field_id, $vote_int)
	{
		$score = 0;
		$like  = 0;

		$response = array();
		$valide_rules = array(
			'id'	=> 'Required|Numeric',
			'vote'	=> 'Required',
			'csrf'	=> 'Required|Csrf',
		);

		$table 		= $table_name;
		$table_vote = $table_name . 'vote';

		$this->_http->ValidatorRules($valide_rules);

		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			$s_username = $this->_db->SqlEscape($this->_info['username']);
			$s_id 	 	= $this->_db->SqlEscape($input['id']);
			$s_vote	 	= $this->_db->SqlEscape($input['vote']);

			$sql = "SELECT COUNT(*) AS count,
				vote
				FROM " . $table_vote . "
				WHERE " . $field_id . " = '" . $s_id . "'
					AND uservoted = '" . $s_username . "'";

			$data = $this->_db->SqlQuery($sql);

			$vote_active = ($data[0]['count']) ? $data[0]['vote'] : false;

			if (!$vote_active)
			{
				if ($s_vote == 'up')
				{
					$score += $vote_int;
					$like  += 1;
				}
				else
				{
					$score -= $vote_int;
				}

				$sql = "INSERT INTO " . $table_vote . " (" . $field_id . ", uservoted, vote)
					VALUES ('" . $s_id . "',
					'" . $s_username . "',
					'" . $s_vote . "')";

				$this->_db->SqlQuery($sql);
			}
			else
			{
				if ($vote_active == 'up' && $s_vote == 'down')
				{
					$score -= ($vote_int * 2);
					$like  -= 1;
				}

				if ($vote_active == 'down' && $s_vote == 'up')
				{
					$score += ($vote_int * 2);
					$like  += 1;
				}
			}

			if ($score !== 0)
			{
				$sql = "UPDATE " . $table . " AS t, cellform_users AS u, " . $table_vote . " AS v
					SET t.score = t.score+" . $score . ",
						u.score = u.score+" . $score . ",
						u.likes = u.likes+" . $like . ",
						v.vote = '" . $s_vote . "'
					WHERE t.id = '" . $s_id . "'
						AND u.username = t.user
							AND v." . $field_id . " = t.id
								AND v.uservoted = '" . $s_username . "'";

				$response['vote']  = $s_vote;
				$response['score'] = $score;

				$this->_db->SqlQuery($sql);
			}
			else
			{
				$response['vote'] = 'fail';
			}

			echo $this->_http->GetPostBody('application/json', $response);
		}
	}

	/**
	* Add vote on posts. Pure JSON.
	* Return the result on JSON format
	*
	* @access public
	*/
	public function PostsVote()
	{
		$this->_Vote('cellform_posts', 'post_id', VOTE_POST);
	}

	/**
	* Add vote on comments. Pure JSON.
	* Return the result on JSON format
	*
	* @access public
	*/
	public function ComsVote()
	{
		$this->_Vote('cellform_coms', 'com_id', VOTE_COM);
	}
}

?>