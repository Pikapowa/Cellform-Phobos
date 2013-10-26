<?php
/**
 * CellForm Users Class
 * Component Users
 *
 * @author rootofgeno@gmail.com
 * @param MediaController
 */

class Media_Users extends Media_Controller
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
	* Account manager (Change email or avatar).
	*
	* @access public
	* @return array
	*/
	public function Account()
	{
		$response = array();
		$valide_rules = array(
			'email'				=> 'Required|Email|MaxLen,30',
			'password'			=> 'Required|MaxLen,30',
			'password_confirm'	=> 'Required|MaxLen,30',
			'csrf'				=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->Is_Set('send'))
		{
			if ($this->_http->IsValid())
			{
				if (strcmp($input['password'], $input['password_confirm']) == 0)
				{
					$password = Cellform_Cypher::Encode($input['password'] . SALT);

					if ($this->_user->IsRegistered($this->_info['email'], $password, null, 'init'))
					{
						if (strcasecmp($input['email'], $this->_info['email']) !== 0)
						{
							if ($this->_user->ChangeEmail($input['email'], $input['password']))
							{
								$this->_errno->AppendNotif('Account_EmailChanged');
							}
							else
							{
								$this->_errno->AppendError('Account_EmailAlreadyExist');
							}
						}
					}
					else
					{
						$this->_errno->AppendError('Account_NotChange');
					}
				}
				else
				{
					$this->_errno->AppendError('General_PasswordNoMatch');
				}
			}

			$config = array(
				'files'	=> array(
					'avatar'	=> array(
						'Size'	=> FILE_MAXSIZE,
						'Exts'	=> array('jpeg', 'jpg')
					)
				)
			);

			$uploader = Cellform_Injector::Instanciate('Cellform_Services_Uploader', array($config));

			if ($file = $uploader->Upload('avatar', 'media/avatars/'))
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

				$file->Handle(function(Cellform_Services_Upload_File $file)
				{
					$image = Cellform_Injector::Instanciate('Cellform_Services_Image', array($file->GetFullQualifiedName()));

					if ($image->MaxSize(200, 200))
					{
						if ($this->_info['avatar'] != 'defaults.jpg')
						{
							unlink_evolved(ROOT . 'media/avatars/' . $this->_info['avatar']);
						}

						$this->_user->ChangeAvatar($file->GetName());
					}
					else
					{
						$this->_errno->AppendError('Error_ImageBadSize');
						$file->Delete();
					}
				});
			}

			$response['errnos'] = $this->_errno->GetErrnos();
		}
	
		return array(
			'template'	=> 'account',
			'response'	=> $response,
		);
	}

	/**
	* Change password
	*
	* @access public
	* @return array
	*/
	public function ChangePasswd()
	{
		$response = array();
		$valide_rules = array(
			'password'				=> 'Required|MaxLen,30',
			'newpassword'			=> 'Required|MaxLen,30',
			'newpassword_confirm'	=> 'Required|MaxLen,30',
			'csrf'					=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->Is_Set('send'))
		{
			if ($this->_http->IsValid())
			{
				if (strcmp($input['newpassword'], $input['newpassword_confirm']) == 0)
				{
					if ($this->_user->ChangePassword($input['password'], $input['newpassword']))
					{
						$this->_errno->AppendNotif('Account_PasswordChanged');
					}
					else
					{
						$this->_errno->AppendError('General_PasswordIncorrect');
					}
				}
				else
				{
					$this->_errno->AppendError('General_PasswordNoMatch');
				}
			}

			$response['errnos'] = $this->_errno->GetErrnos();
		}

		return array(
			'template'	=> 'changepasswd',
			'response'	=> $response,
		);
	}

	/**
	* List all members & print
	*
	* @access public
	* @return array
	*/
	public function Members()
	{
		$response = array();

		$clause = "WHERE u.valid = '1'";

		$response['users'] = $this->_Lists($clause);

		return array(
			'template'	=> 'members',
			'response'	=> $response,
		);
	}

	/**
	* Print the profil of a specific user
	*
	* @access public
	* @return array|false
	*/
	public function Profil()
	{
		$response = array();
		$valide_rules = array(
			'user'	=> 'Required|MaxLen,30',
			'page'	=> 'Numeric',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			$s_user = $this->_db->SqlEscape($input['user']);

			$user  = $this->_Lists("WHERE u.valid = '1' AND u.username = '" . $s_user . "'");

			if ($user)
			{
				$response['user']  = $user[0];

				$response['masonry_run'] = 'true';
				$response['masonry_url'] = '/media/ticket/postuser?user=' . $s_user;

				return array(
					'template'	=> 'profil',
					'response'	=> $response,
				);
			}
			else
			{
				return false;
			}
		}
	}

	/**
	* List all users and returns JSON.
	* For Autocompletion.
	*
	* @access public
	*/
	public function UserList()
	{
		$sql = "SELECT username AS label
			FROM cellform_users
			WHERE valid = '1'";

		$data = $this->_db->SqlQuery($sql);

		echo $this->_http->GetPostBody('application/json', $data);
	}

	/**
	* Delete cookie & redirect to home
	*
	* @access public
	*/
	public function Logout()
	{
		$this->_http->DelCookie('email');
		$this->_http->DelCookie('session_id');

		$this->_http->Header('location', '/');
		$this->_http->SendHeaders();
	}

	/**
	* Returns users with $clause layout
	*
	* @access protected
	* @return array
	*/
	protected function _Lists($clause = '')
	{
		$sql = "SELECT u.id,
			u.username,
			u.avatar,
			u.sex,
			u.score,
			(SELECT COUNT(*) FROM cellform_coms WHERE user = u.username) AS nbcomments,
			COUNT(p.id) AS nbtickets,
			u.likes,
			u.regdate,
			u.lastvisit,
			f.id AS friend
				FROM cellform_users AS u
					LEFT JOIN cellform_friends AS f
						ON f.username = '" . $this->_db->SqlEscape($this->_info['username']) . "'
							AND f.friends = u.username
					LEFT JOIN cellform_posts AS p
						ON p.user = u.username
				" . $clause . " GROUP BY u.id ORDER BY u.score DESC;";

		return $this->_db->SqlQuery($sql);
	}
}

?>