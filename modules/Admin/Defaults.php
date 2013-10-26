<?php
/**
 * CellForm Defaults Admin Class
 * Component Defaults
 *
 * @author rootofgeno@gmail.com
 * @param AdminController
 */

class Admin_Defaults extends Admin_Controller
{
	public function __construct(Admin_Controller $controller)
	{
		$this->_db			= $controller->_db;
		$this->_translate 	= $controller->_translate;
		$this->_http		= $controller->_http;
		$this->_user		= $controller->_user;
		$this->_errno		= $controller->_errno;

		$this->_info		= $this->_user->GetInfo();
	}

	/**
	* Front page of admin panel
	*
	* @access public
	* @return array
	*/
	public function Defaults()
	{
		$response = array();
		$valide_rules = array(
			'sitename'		=> 'Required|MaxLen,20',
			'sitetitle'		=> 'Required|MaxLen,64',
			'description'	=> 'Required|MaxLen,128',
			'email'			=> 'Required|Email|MaxLen,30',
			'id'			=> 'Required|Numeric',
			'lang'			=> 'Required|MaxLen,2',
			'csrf'			=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->Is_Set('send'))
		{
			if ($this->_http->IsValid())
			{
				$config = array(
					'SITENAME'		=> $input['sitename'],
					'TITLE'			=> $input['sitetitle'],
					'DESCRIPTION'	=> $input['description'],
					'EMAIL'			=> $input['email'],
					'ID'			=> $input['id'],
					'DEFAULTS_LANG'	=> $input['lang'],
				);

				Cellform_Config::Set($config);

				if (Cellform_Config::Save())
				{
					$this->_translate->MergeTranslation($config);
					$this->_errno->AppendNotif('Admin_ConfChanged');
				}

				$config = array(
					'files'	=> array(
						'present'		=> array(
							'Size'	=> FILE_MAXSIZE,
							'Exts'	=> array('jpeg', 'jpg'),
						),
						'bg'			=> array(
							'Size'		=> FILE_MAXSIZE,
							'Exts'		=> array('jpeg', 'jpg'),
						),
						'banner'		=> array(
							'Size'		=> FILE_MAXSIZE,
							'Exts'		=> array('png'),
						),
					)
				);

				$uploader = Cellform_Injector::Instanciate('Cellform_Services_Uploader', array($config));

				// PRESENT IMAGE UPLOAD
				if ($file = $uploader->Upload('present', 'images/loginbox/'))
				{
					$file->Error(function($errors)
					{
						$this->_errno->AppendError($errors);
					});

					$isuploaded = $file->Save(function(Cellform_Services_Upload_File $file)
					{
						$file->SetName('present');
						$image = Cellform_Injector::Instanciate('Cellform_Services_Image', array($file->GetTempName()));

						if ($image->MinSize(480, 0))
						{
							return true;
						}
						else
						{
							$this->_errno->AppendError('Error_ImageBadSize');
							return false;
						}
					});

					if ($isuploaded)
					{
						$this->_errno->AppendNotif('Admin_ConfPicturePresentChanged');
					}
				}

				// BACKGROUND IMAGE UPLOAD
				if ($file = $uploader->Upload('bg', 'images/loginbox/'))
				{
					$file->Error(function($errors)
					{
						$this->_errno->AppendError($errors);
					});

					$isuploaded = $file->Save(function(Cellform_Services_Upload_File $file)
					{
						$file->SetName('bg');

						return true;
					});

					if ($isuploaded)
					{
						$this->_errno->AppendNotif('Admin_ConfPictureBgChanged');
					}
				}

				// BANNER IMAGE IN MEDIA CENTER
				if ($file = $uploader->Upload('banner', 'images/media/'))
				{
					$file->Error(function($errors)
					{
						$this->_errno->AppendError($errors);
					});

					$isuploaded = $file->Save(function(Cellform_Services_Upload_File $file)
					{
						$file->SetName('banner');

						return true;
					});

					if ($isuploaded)
					{
						$this->_errno->AppendNotif('Admin_ConfPictureBannerChanged');
					}
				}
			}

			$response['errnos'] = $this->_errno->GetErrnos();
		}

		return array(
			'template'	=> 'config',
			'response'	=> $response,
		);
	}

   /**
	* OAuth configuration
	*
 	* @access public
	* @return array
	*/
	public function OAuth()
	{
		$response = array();
		$valide_rules = array(
			'facebook_enabled'	=> 'Required|Bool',
			'facebook_appid'	=> 'Required|MaxLen,128',
			'facebook_secret'	=> 'Required|Alphanumeric|MaxLen,128',
			'google_enabled'	=> 'Required|Bool',
			'google_appid'		=> 'Required|MaxLen,128',
			'google_secret'		=> 'Required|Alphanumeric|MaxLen,128',
			'csrf'				=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->Is_Set('send'))
		{
			if ($this->_http->IsValid())
			{
				$config = array(
					'FACEBOOK_ENABLED'	=> $input['facebook_enabled'],
					'FACEBOOK_APPID'	=> $input['facebook_appid'],
					'FACEBOOK_SECRET'	=> $input['facebook_secret'],
					'GOOGLE_ENABLED'	=> $input['google_enabled'],
					'GOOGLE_APPID'		=> $input['google_appid'],
					'GOOGLE_SECRET'		=> $input['google_secret'],
				);

				Cellform_Config::Set($config);

				if (Cellform_Config::Save())
				{
					$this->_translate->MergeTranslation($config);
					$this->_errno->AppendNotif('Admin_ConfChanged');
				}
			}

			$response['errnos'] = $this->_errno->GetErrnos();
		}

		return array(
			'template'	=> 'oauth',
			'response'	=> $response,
		);
	}

	/**
	* List all alerts
	*
	* @access public
	* @return array
	*/
	public function ViewAlerts()
	{
		$response = array();

		$sql = "SELECT p.id,
			a.user AS user_alert,
			a.date,
			p.title,
			p.user
			FROM cellform_alerts AS a
				LEFT JOIN cellform_posts AS p
					ON a.post_id = p.id;";

		$response['alerts'] = $this->_db->SqlQuery($sql);

		return array(
			'template'	=> 'view_alerts',
			'response'	=> $response,
		);
	}

	/**
	* Delete one or more alert(s) & returns JSON response
	*
	* @access public
	*/
	public function DelAlerts()
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
				$sql = "DELETE FROM cellform_alerts
					WHERE post_id = '" . $this->_db->SqlEscape($nodes) . "'";

				$this->_db->SqlQuery($sql);
			}

			if ($this->_db->SqlAffectedRows() >= 1)
			{
				echo $this->_http->GetPostBody('application/json', 'success');
			}
			else
			{
				echo $this->_http->GetPostBody('application/json', 'fail');
			}
		}
	}

	/**
	* Delete one or more ticket(s) & returns JSON response
	*
	* @access public
	*/
	public function DelTickets()
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
				$sql = "SELECT p.id,
					p.type,
					p.image
					FROM cellform_posts AS p
					WHERE p.id = '" . $this->_db->SqlEscape($nodes) . "';";

				$post = $this->_db->SqlQuery($sql);

				if ($post)
				{
					$type  = $post[0]['type'];
					$image = $post[0]['image'];

					$sql = "DELETE p, pv, c, cv, f
						FROM cellform_posts AS p
							LEFT JOIN cellform_postsvote AS pv
								ON p.id = pv.post_id
							LEFT JOIN cellform_coms AS c
								ON p.id = c.post_id
							LEFT JOIN cellform_comsvote AS cv
								ON c.id = cv.com_id
							LEFT JOIN cellform_favorites AS f
								ON c.id = f.post_id
						WHERE p.id = '" . $this->_db->SqlEscape($nodes) . "';";

					$this->_db->SqlQuery($sql);

					if ($type == 'image' && $this->_db->SqlAffectedRows() >= 1)
					{
						unlink_evolved(ROOT . 'media/img/' . $image);
						unlink_evolved(ROOT . 'media/img/min/' . $image);
					}
				}
			}

			if ($this->_db->SqlAffectedRows() >= 1)
			{
				echo $this->_http->GetPostBody('application/json', 'success');
			}
			else
			{
				echo $this->_http->GetPostBody('application/json', 'fail');
			}
		}
	}

	/**
	* Delete an specific comment & returns JSON response
	*
	* @access public
	*/
	public function DelComment()
	{
		$valide_rules = array(
			'id'	=> 'Required|Numeric',
			'csrf'	=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			$id = $this->_db->SqlEscape($input['id']);

			$sql = "SELECT c.post_id
				FROM cellform_coms AS c
				WHERE c.id = '" . $id . "'";

			$com = $this->_db->SqlQuery($sql);

			if ($com)
			{
				$com = $com[0];

				$sql = "DELETE FROM cellform_coms WHERE id = '" . $id . "'";
				$this->_db->SqlQuery($sql);
			}

			if ($this->_db->SqlAffectedRows() == 1)
			{
				$sql = "UPDATE cellform_posts
					SET nbcomments = nbcomments-1
					WHERE id = '" . $this->_db->SqlEscape($com['post_id']) . "'";

				$this->_db->SqlQuery($sql);

				echo $this->_http->GetPostBody('application/json', 'success');
			}
			else
			{
				echo $this->_http->GetPostBody('application/json', 'fail');
			}
		}
	}

	/**
	 * Print form & delete an specific user
	 *
	 * @access public
	 * @return array
	 */
	public function DelUser()
	{
		$response = array();
		$valide_rules = array(
			'username'	=> 'Required|MaxLen,30',
			'csrf'		=> 'Required|Csrf',
		);
	
		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);
	
		if ($this->_http->Is_Set('send'))
		{
			if ($this->_http->IsValid())
			{
				$sql = "UPDATE cellform_users
					SET valid = 'banned',
						level = '" . LEVEL_BANN . "'
					WHERE username = '" . $this->_db->SqlEscape($input['username']) . "'";

				$this->_db->SqlQuery($sql);
	
				if ($this->_db->SqlAffectedRows() == 1)
				{
					$this->_errno->AppendNotif('Admin_UserDeleted');
				}
				else
				{
					$this->_errno->AppendError('General_UserNotFound');
				}
			}
	
			$response['errnos'] = $this->_errno->GetErrnos();
		}
	
		return array(
				'template'	=> 'deluser',
				'response'	=> $response,
		);
	}
}