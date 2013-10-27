<?php
/**
 * CellForm Ticket Class
 * Component Ticket
 *
 * @author rootofgeno@gmail.com
 * @param Media_Controller
 */

class Media_Ticket extends Media_Controller
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
	* Returns all tickets order by score desc & returns JSON. [CACHED]
	*
	* @access public
	* @return array
	*/
	public function Top()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$ttl = '';
			$valide_rules = array(
				'page'	=> 'Required|Numeric',
			);

			$this->_http->ValidatorRules($valide_rules);
			$input = $this->_http->Analyzer($_GET);

			if ($this->_http->IsValid())
			{
				if ($input['page'] == 1)
				{
					$clause = "WHERE p.score > 0 ORDER BY p.score DESC, p.id DESC";
				}
				else
				{
					$clause = "WHERE p.id < " . $_SESSION['id'] . " AND p.score <= " . $_SESSION['score'] . " AND p.score != 0 ORDER BY p.score DESC, p.id DESC";
					$ttl = 60;
				}

				$posts = $this->_Lists($clause, true, $ttl);
				echo $this->_http->GetPostBody('application/json', $posts);
				exit;
			}
		}

		$response['masonry_run'] = 'true';
		$response['masonry_url'] = '/media/ticket/top';

		return array(
			'template'	=> 'ticket',
			'response'	=> $response,
		);
	}

	/**
	* Returns all tickets order by id desc & returns JSON. [CACHED]
	*
	* @access public
	* @return array
	*/
	public function Recent()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$ttl = '';
			$valide_rules = array(
				'page'	=> 'Required|Numeric',
			);

			$this->_http->ValidatorRules($valide_rules);
			$input = $this->_http->Analyzer($_GET);

			if ($this->_http->IsValid())
			{
				if ($input['page'] == 1)
				{
					$clause = 'ORDER BY p.id DESC';
				}
				else
				{
					$clause = "WHERE p.id < '" . $_SESSION['id'] . "' ORDER BY p.id DESC";
					$ttl = 60;
				}

				$posts = $this->_Lists($clause, true, $ttl);

				echo $this->_http->GetPostBody('application/json', $posts);
				exit;
			}
		}

		$response['masonry_run'] = 'true';
		$response['masonry_url'] = '/media/ticket/recent';

		return array(
			'template'	=> 'ticket',
			'response'	=> $response,
		);
	}

	/**
	* Returns a specific ticket & returns JSON.
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

			if ($this->_http->Is_Set('ajax'))
			{
				if ($input['page'] == 1)
				{
					$clause = "WHERE p.id = '" . $s_id . "'";

					$posts = $this->_Lists($clause);
					echo $this->_http->GetPostBody('application/json', $posts);
				}

				exit;
			}

			$response['masonry_run'] = 'true';
			$response['masonry_url'] = '/media/ticket/view?id=' . $s_id;
		}

		return array(
			'template'	=> 'ticket',
			'response'	=> $response
		);
	}

	/**
	* Return posts that match your search & returns JSON.
	*
	* @access public
	* @return array
	*/
	public function Search()
	{
		$response = array();
		$valide_rules = array(
			'keyword'	=> 'Required|MaxLen,30',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			$s_keyword = $this->_db->SqlEscape($input['keyword']);

			if ($this->_http->Is_Set('ajax'))
			{
				if ($input['page'] == 1)
				{
					$clause = "WHERE p.title LIKE '%" . $s_keyword . "%' ORDER BY p.id DESC";
				}
				else
				{
					$clause = "WHERE p.title LIKE '%" . $s_keyword . "%' AND p.id < '" . $_SESSION['id'] . "' ORDER BY p.id DESC";
				}

				$posts = $this->_Lists($clause);
				echo $this->_http->GetPostBody('application/json', $posts);
				exit;
			}

			$response['masonry_run'] = 'true';
			$response['masonry_url'] = '/media/ticket/search?keyword=' . $s_keyword;
		}

		return array(
			'template'	=> 'ticket',
			'response'	=> $response
		);
	}

	/**
	* Force image post download.
	*
	* @access public
	* @return int
	*/
	public function Download()
	{
		$valide_rules = array(
			'file'	=> 'Required|Filename|MaxLen,30',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			$file = ROOT . '/media/img/' . $input['file'];

			if (file_exists($file))
			{
				$this->_http->Header('Content-Type', 'application/force-download');
				$this->_http->Header('Content-Disposition', 'attachment; filename=' . basename($file));

				$this->_http->SendHeaders();

				return readfile($file);
			}
		}
	}

	/**
	* Return your favorites ticket & returns JSON.
	*
	* @access public
	*/
	public function Favorites()
	{
		if ($this->_http->Is_Set('ajax'))
		{
			$valide_rules = array(
				'page'	=> 'Required|Numeric',
			);

			$this->_http->ValidatorRules($valide_rules);
			$input = $this->_http->Analyzer($_GET);

			if ($this->_http->IsValid())
			{
				$s_username = $this->_db->SqlEscape($this->_info['username']);

				if ($input['page'] == 1)
				{
					$clause = "WHERE f.user = '" . $s_username . "' ORDER BY f.post_id DESC";
				}
				else
				{
					$clause = "WHERE f.user = '" . $s_username . "' AND f.post_id < '" . $_SESSION['id'] . "' ORDER BY f.post_id DESC";
				}

				$posts = $this->_Lists($clause);

				echo $this->_http->GetPostBody('application/json', $posts);
			}
		}
	}

	/**
	* Add an alert for inapropriate post & returns JSON.
	*
	* @access public
	* @return bool
	*/
	public function Alert()
	{
		$valide_rules = array(
			'id'	=> 'Required|Numeric',
			'csrf'	=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			$s_id = $this->_db->SqlEscape($input['id']);
			$s_username = $this->_db->SqlEscape($this->_info['username']);

			$sql = "SELECT id
				FROM cellform_alerts
				WHERE post_id = '" . $s_id . "'
					AND user = '" . $s_username . "'";

			if ($this->_db->SqlQuery($sql) === false)
			{
				$sql = "INSERT INTO cellform_alerts (post_id, date, user)
					VALUES (
					'" . $s_id . "',
					NOW(),
					'" . $s_username . "');";

				$this->_db->SqlQuery($sql);

				$this->_errno->AppendNotif('Alert_Send');
			}
			else
			{
				$this->_errno->AppendError('General_AlreadySend');
			}
		}

		return $this->_http->CheckErrnos();
	}

	/**
	* Return all tickets from specific user & returns JSON.
	*
	* @access public
	*/
	public function PostUser()
	{
		$response = array();
		$valide_rules = array(
			'user'	=> 'Required',
			'page'	=> 'Required|Numeric',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			if ($this->_http->Is_Set('ajax'))
			{
				$s_user = $this->_db->SqlEscape($input['user']);

				if ($input['page'] == 1)
				{
					$clause = "WHERE p.user = '" . $s_user . "' ORDER BY p.id DESC";
				}
				else
				{
					$clause = "WHERE p.user = '" . $s_user . "' AND p.id < " . $_SESSION['id'] . " ORDER BY p.id DESC";
				}

				$posts = $this->_Lists($clause);
				echo $this->_http->GetPostBody('application/json', $posts);
				exit;
			}
		}
	}

	/**
	* Returns all ticket informations & return JSON.
	*
	* @access public
	*/
	public function PostInfo()
	{
		$valide_rules = array(
			'id'	=> 'Required|Numeric',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			$clause = "WHERE p.id = '" . $this->_db->SqlEscape($input['id']) . "'";

			$post = $this->_Lists($clause, false);

			$response['post'] = $post;

			echo $this->_http->GetPostBody('application/json', $response);
		}
	}

	/**
	* List all posts and returns JSON.
	* For Autocompletion.
	*
	* @access public
	*/
	public function PostList()
	{
		$sql = "SELECT title AS label
			FROM cellform_posts";

		$data = $this->_db->SqlQuery($sql);

		echo $this->_http->GetPostBody('application/json', $data);
	}

	/**
	* Public interface for insert or update a ticket.
	*
	* @access public
	* @return array
	*/
	public function Write()
	{
		$view 	= 'add_image';
		$id 	= false;

		$response = array();
		$valide_rules = array(
			'mode'	=> 'Required|MaxLen,30',
			'type'	=> 'Required|MaxLen,30',
			'id'	=> 'Numeric',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			if (($input['mode'] == 'add' || $input['mode'] == 'change') && ($input['type'] == 'image' || $input['type'] == 'video' || $input['type'] == 'link'))
			{
				$mode = $input['mode'];
				$type = $input['type'];
				$view = $mode . '_' . $type;
			}
			else
			{
				return false;
			}

			$valide_rules = array(
				'title'			=> 'Required|MaxLen,30',
				'description'	=> 'Required|MaxLen,2048',
				'media'			=> 'MaxLen,128|Url',
				'csrf'			=> 'Required|Csrf',
			);

			$filter_rules = array(
				'title'			=> 'Htmlencode',
				'description'	=> 'Safe',
				'media'			=> 'Htmlencode',
			);

			if ($mode == 'add' && ($input['type'] == 'video' || $input['type'] == 'link'))
			{
				$valide_rules['media'] .= '|Required';
			}

			if ($mode == 'change' && !empty($input['id']))
			{
				$id = $input['id'];
			}

			$this->_http->ValidatorRules($valide_rules);
			$this->_http->FiltratorRules($filter_rules);

			$input = $this->_http->Analyzer($_POST);

			if ($this->_http->Is_Set('send'))
			{
				if ($this->_http->IsValid())
				{
					$media = (!empty($input['media'])) ? $input['media'] : null;

					if ($this->_HandleTicket($type, $input['title'], $input['description'], $this->_info['username'], $media, $id))
					{
						if ($mode == 'add')
						{
							$this->_errno->AppendNotif('Post_Online');
						}
						else
						{
							$this->_errno->AppendNotif('Post_Modified');
						}
					}
					else
					{
						$this->_errno->AppendError('Post_NotSend');
					}
				}

				$response['errnos'] = $this->_errno->GetErrnos();
			}

			if ($mode == 'change' && $id)
			{
				$clause = "WHERE p.id = '" . $this->_db->SqlEscape($id) . "'
						AND p.user = '" . $this->_db->SqlEscape($this->_info['username']) . "'";

				$post = $this->_Lists($clause, false);
				$response['post'] = $post[0];

				$response['post']['title'] = html_entity_decode($response['post']['title']);
			}
		}

		return array(
			'template'	=> $view,
			'response'	=> $response,
		);
	}

	/**
	* Insert or update a ticket. (Image, Video or Link) & returns string errors
	*
	* @access protected
	* @param string $type
	* @param string $title
	* @param string $description
	* @param string $user
	* @return string|true
	*/
	protected function _HandleTicket($type, $title, $description, $user, $media = null, $id = false)
	{
		$is_change 		= false;
		$type_change 	= false;
		$accepted		= false;

		$old_post 	= null;

		$s_image 	= null;
		$s_media	= null;
		$s_phone 	= null;

		$s_user = $this->_db->SqlEscape($user);

		switch($type)
		{
			case 'image':
				$config = array(
					'files'	=> array(
						'image'		=> array(
							'Size'	=> FILE_MAXSIZE,
							'Exts'	=> array('jpeg', 'jpg', 'png', 'gif'),
						)
					)
				);

				$uploader = Cellform_Injector::Instanciate('Cellform_Services_Uploader', array($config));

				if ($file = $uploader->Upload('image', 'media/img/'))
				{
					$file->Error(function($error)
					{
						$this->_errno->AppendError($error);
					});

					$file->Save(function(Cellform_Services_Upload_File $file)
					{
						$filename = Cellform_Cypher::GenerateRand();
						$file->SetName($filename);

						return true;
					});

					$file->Handle(function(Cellform_Services_Upload_File $file) use(& $s_image, & $s_media, & $s_phone, & $accepted)
					{
						$image = Cellform_Injector::Instanciate('Cellform_Services_Image', array($file->GetFullQualifiedName()));

						if ($image->GetRatio() >= 0.4)
						{
							$image->Save('media/img/min/' . $file->GetName());

							$s_image  = $this->_db->SqlEscape($file->GetName());
							$s_media  = '0';
							$s_phone  = '0';

							$accepted = true;
						}
						else
						{
							$this->_errno->AppendError('Error_ImageBadRatio');
							$file->Delete();
						}
					});
				}

				break;

			case 'video':
				$video_service = new Cellform_Services_Video;

				if (!empty($media))
				{
					if ($video_service->Load($media))
					{
						$s_image = $this->_db->SqlEscape($video_service->GetThumbnail());
						$s_media = $this->_db->SqlEscape($video_service->GetVideo());
						$s_phone = $this->_db->SqlEscape($video_service->GetVideoMobile());

						if ($s_image == null)
						{
							$s_image = '/images/img_video_404.jpg';
						}

						if ($s_image && $s_media)
						{
							$accepted = true;
						}
					}
					else
					{
						$this->_errno->AppendError('Post_VideoNotMatch');
					}
				}

				break;

			case 'link':
				if (!empty($media))
				{
					$s_media = $this->_db->SqlEscape($media);
					$s_image = '0';
					$s_phone = '0';

					$accepted = true;
				}

				break;

			default:
				return false;
		}

		if ($id && is_numeric($id))
		{
			$sql = "SELECT p.id,
				p.type,
				p.image
				FROM cellform_posts AS p
				WHERE user = '" . $s_user . "'
					AND p.id = '" . $id . "'";

			$old_post = $this->_db->SqlQuery($sql);
			$old_post = $old_post[0];

			if ($old_post)
			{
				$is_change = true;

				if ($type != $old_post['type'])
				{
					$type_change = true;
				}
			}
		}

		if ((!$accepted && ($type_change || !$id)) || $this->_errno->ErrorsExist())
		{
			return false;
		}

		$s_title 		= $this->_db->SqlEscape($title);
		$s_description 	= $this->_db->SqlEscape($description);

		if ($is_change)
		{
			$s_image = (isset($s_image)) ? "image = '" . $s_image . "'," : null;
			$s_media = (isset($s_media)) ? "media = '" . $s_media . "'," : null;
			$s_phone = (isset($s_phone)) ? "phone = '" . $s_phone . "'," : null;

			if ($old_post['type'] == 'image' && $type_change)
			{
				unlink_evolved(ROOT . 'media/img/' . $old_post['image']);
				unlink_evolved(ROOT . 'media/img/min/' . $old_post['image']);
			}

			$sql = "UPDATE cellform_posts
				SET " . $s_image . "
					" . $s_media . "
					" . $s_phone . "
					type  = '" . $type . "',
					title = '" . $s_title . "',
					description = '" . $s_description . "'
					WHERE user = '" . $s_user . "'
						AND id = '" . $id . "'";
		}
		else
		{
			$s_image = (isset($s_image)) ? $s_image : '0';
			$s_media = (isset($s_media)) ? $s_media : '0';
			$s_phone = (isset($s_phone)) ? $s_phone : '0';

			$sql = "INSERT INTO cellform_posts (type, title, image, media, phone, date, nbcomments, score, description, user)
				VALUES (
				'" . $type . "',
				'" . $s_title . "',
				'" . $s_image . "',
				'" . $s_media . "',
				'" . $s_phone . "',
				NOW(),
				'0',
				'0',
				'" . $s_description . "',
				'" . $s_user . "');";		
		}

		$this->_db->SqlQuery($sql);

		return ($this->_db->SqlAffectedRows() == 1) ? true : false;
	}

	/**
	* Returns ticket with $clause layout
	*
	* @access protected
	* @param string $clause
	* @param bool $session
	* @return array
	*/
	protected function _Lists($clause = '', $session = true, $ttl = '')
	{
		$s_username = $this->_db->SqlEscape($this->_info['username']);

		$sql = "SELECT p.id,
			p.type,
			p.title,
			p.image,
			p.media,
			p.phone,
			p.date,
			p.nbcomments,
			p.score,
			p.description,
			p.user,
			v.vote,
			u.avatar,
			f.id AS favoris
				FROM cellform_posts AS p
					LEFT JOIN cellform_postsvote AS v
						ON v.post_id = p.id
							AND v.uservoted = '" . $s_username . "'
					LEFT JOIN cellform_users AS u
						ON u.username = p.user
					LEFT JOIN cellform_favorites AS f
						ON f.post_id = p.id
							AND f.user = '" . $s_username . "'
					" . $clause . " LIMIT 11;";

		$data = $this->_db->SqlQuery($sql, $ttl);

		if ($session)
		{
			$offset = count($data) - 1;
			$_SESSION['id'] = $data[$offset]['id'];
			$_SESSION['score'] = $data[$offset]['score'];
		}

		return $data;
	}
}

?>