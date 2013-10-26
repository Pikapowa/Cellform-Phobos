<?php
/**
 * CellForm LoginBox Class
 * Component LoginBox
 *
 * @author rootofgeno@gmail.com
 * @param LoginboxController
 */

class Loginbox_Home extends Loginbox_Controller
{
	public function __construct(Loginbox_Controller $controller)
	{
		$this->_db 			= $controller->_db;
		$this->_errno 		= $controller->_errno;
		$this->_http 		= $controller->_http;
		$this->_user 		= $controller->_user;

		$this->_captcha 	= $controller->_captcha;
		$this->_mail		= $controller->_mail;
	}

	/**
	* Sign in method & returns JSON.
	*
	* @access public
	* @return bool
	*/
	public function SignIn()
	{
		$valide_rules = array(
			'login'		=> 'Required|Email|MaxLen,30',
			'password'	=> 'Required|MaxLen,30',
			'csrf'		=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			$encoded = Cellform_Cypher::Encode($input['password'] . SALT);
			$token = Cellform_Cypher::GenerateToken($encoded);

			if (!$this->_user->IsRegistered($input['login'], $encoded, $token, 'init'))
			{
				$this->_errno->AppendNotif('Login_Failure');
			}
			else
			{
				$s_login = $this->_db->SqlEscape($input['login']);

				$this->_db->SqlQuery("UPDATE cellform_users
					SET token = '" . $token . "',
						lastvisit = NOW()
					WHERE email  = '" . $s_login . "'
						AND password = '" . $encoded . "'
						AND valid 	 = '1'");

				$this->_http->AddCookie('email', $s_login);
				$this->_http->AddCookie('session_id', $token);

				echo $this->_http->GetPostBody('application/json', 'success');
			}
		}

		return $this->_http->CheckErrnos();
	}

	/**
	* Sign up method & returns JSON.
	*
	* @access public
	* @return bool
	*/
	public function SignUp()
	{
		$valide_rules = array(
			'email'				=> 'Required|Email|MaxLen,30',
			'password'			=> 'Required|MaxLen,30',
			'password_confirm'	=> 'Required|MaxLen,30',
			'username'			=> 'Required|AlphaNumeric|MaxLen,12',
			'sex'				=> 'Required|AlphaNumeric|MaxLen,6',
			'captcha'			=> 'Required|Captcha,' . $this->_captcha->ReturnAnswer(),
			'csrf'				=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			if (strcmp($input['password'], $input['password_confirm']) == 0)
			{
				$token = $this->_user->Register($input['email'], $input['username'], $input['password'], $input['sex']);

				if (!$token)
				{
					$this->_errno->AppendError('SignUp_AlreadyExist');
				}
				else
				{
					$data = array(
						'email'	=> $input['email'],
						'token'	=> $token,
					);

					if ($this->_mail->Send('Validator', $input['email'], $data))
					{
						$this->_errno->AppendNotif('SignUp_CreateWithConfirm');
					}
					else
					{
						$this->_errno->AppendNotif('SignUp_CreateWithoutConfirm');
					}
				}
			}
			else
			{
				$this->_errno->AppendError('General_PasswordNoMatch');
			}
		}

		return $this->_http->CheckErrnos();
	}

	/**
	* Sign up validation (In PHP standalone)
	*
	* @access public
	* @return array
	*/
	public function Validator()
	{
		$response = array();
		$valide_rules = array(
			'email'		=> 'Required|Email|MaxLen,30',
			'token'		=> 'Required|MaxLen,128',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			if (!$this->_user->Validator($input['email'], $input['token']))
			{
				$this->_errno->AppendError('Validator_Error');
			}
			else
			{
				$this->_errno->AppendNotif('Validator_Success');
			}

			$response['errnos'] = $this->_errno->GetErrnos();

			return array(
				'template'	=> 'states',
				'response'	=> $response,
			);
		}
	}

	/**
	* Reset user password method (In PHP Standalone)
	*
	* @access public
	* @return array
	*/
	public function Recovery()
	{
		$response = array();
		$valide_rules = array(
			'email'		=> 'Required|Email|MaxLen,30',
			'forget'	=> 'Required|AlphaNumeric|MinLen,32|MaxLen,52',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			$newpassword = $this->_user->RecoveryPasswd($input['email'], $input['forget']);

			if (!$newpassword)
			{
				$this->_errno->AppendError('Recovery_Error');
			}
			else
			{
				$data = array(
					'email'			=> $input['email'],
					'newpassword'	=> $newpassword,
				);

				$this->_mail->Send('Recovery', $input['email'], $data);

				$this->_errno->AppendNotif('Recovery_Success');
			}

			$response['errnos'] = $this->_errno->GetErrnos();

			return array(
				'template'	=> 'states',
				'response'	=> $response,
			);
		}
	}

	/**
	* Request to reset the user password & returns JSON.
	*
	* @access public
	* @return bool
	*/
	public function RequestRecovery()
	{
		$valide_rules = array(
			'email'		=> 'Required|Email|MaxLen,30',
			'csrf'		=> 'Required|Csrf',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_POST);

		if ($this->_http->IsValid())
		{
			$forget = $this->_user->RequestRecovery($input['email']);

			if (!$forget)
			{
				$this->_errno->AppendError('Account_NotFound');
			}
			else
			{
				$data = array(
					'email'		=> $input['email'],
					'forget'	=> $forget,
				);

				if ($this->_mail->Send('Request_Recovery', $input['email'], $data))
				{
					$this->_errno->AppendNotif('Account_RequestPasswordSend');
				}
				else
				{
					$this->_errno->AppendError('Error_MailDisable');
				}
			}
		}

		return $this->_http->CheckErrnos();
	}

    /**
	* OAuth connect social network service
	*
	* @access public
	* @return array
	*/
	public function OAuth()
	{
		$response = array();
		$config = array(
			'providers'	=> array(
				'Facebook'	=> array(
					'enabled'	=> FACEBOOK_ENABLED,
					'keys'		=> array('id'	=> FACEBOOK_APPID, 'secret'	=> FACEBOOK_SECRET),
				),
				'Google'	=> array(
					'enabled'	=> GOOGLE_ENABLED,
					'keys'		=> array('id'	=> GOOGLE_APPID, 'secret'	=> GOOGLE_SECRET),
					'url'		=> BASE_URI . '/home/oauth?service=Google',
				),
			)
		);

		$valide_rules = array(
			'service'	=> 'Required|MaxLen,30',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);
	
		if ($this->_http->IsValid())
		{
			$service = $input['service'];

			if (!empty($config['providers'][$service]))
			{
				$sns = Cellform_Injector::Instanciate('Cellform_Sns', array($config));
	
				$adapter = $sns->Authenticate($service);
	
				$adapter->Bind();
	
				$userinfo = $adapter->GetUserProfile();
	
				if ($userinfo)
				{
					if ($this->_user->IsRegistered($userinfo['email'], null, $userinfo['oauth_id'], 'check'))
					{
						$this->_http->AddCookie('email', $userinfo['email']);
						$this->_http->AddCookie('session_id', $userinfo['oauth_id']);

						$this->_http->Header('Location', '/media');
						$this->_http->SendHeaders();
					}
					else
					{
						$valide_rules = array(
							'username'	=> 'Required|AlphaNumeric|MaxLen,12',
						);

						$this->_http->ValidatorRules($valide_rules);
						$input = $this->_http->Analyzer($_POST);

						if ($this->_http->Is_Set('send'))
						{
							if ($this->_http->IsValid())
							{
								if ($this->_user->Register($userinfo['email'], $input['username'], random(10), $userinfo['sex'], false, $userinfo['oauth_id'], LEVEL_USER))
								{
									$this->_errno->AppendNotif('OAuth_Signup');
								}
								else
								{
									$this->_errno->AppendError('SignUp_AlreadyExist');
								}
							}

							$response['errnos']  = $this->_errno->GetErrnos();
						}

						$response['service'] = $service;

						return array(
							'template'	=> 'oauth',
							'response'	=> $response,
						);
					}
				}
			}
		}
	}

	/**
	* Print signup page
	*
	* @access public
	* @return array
	*/
	public function Subscribe()
	{
		$response['CurrentCaptcha'] = $this->_captcha->CaptchaRaw();

		return array(
			'template'	=> 'signup',
			'response'	=> $response,
		);
	}

	/**
	 * Print recovery password page
	 *
	 * @access public
	 * @return array
	 */
	public function ForgotPassword()
	{
		return array(
			'template'	=> 'recovery',
		);
	}

	/**
	* Print denied page
	*
	* @access public
	* @return array
	*/
	public function Denied()
	{
		return array(
			'template'	=> 'denied',
		);
	}

	/**
	* Print no javascript page
	*
	* @access public
	* @return array
	*/
	public function Nojs()
	{
		return array(
			'template'	=> 'nojs',
		);
	}

	/**
	* Change language & redirect to home page
	*
	* @access public
	*/
	public function ChangeLanguage()
	{
		$valide_rules = array(
			'lang'	=> 'Required|Alphanumeric|MaxLen,2',
		);

		$this->_http->ValidatorRules($valide_rules);
		$input = $this->_http->Analyzer($_GET);

		if ($this->_http->IsValid())
		{
			$_SESSION['lang'] = $input['lang'];
		}

		$this->_http->Header('Location', '/');
		$this->_http->SendHeaders();
	}
}


?>