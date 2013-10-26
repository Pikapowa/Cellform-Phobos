<?php
/**
 * CellForm Google Connect
 * If you want used directly Cellform_Sns_Google don't forget to call Initialize()
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Sns_Google extends Cellform_Sns_OAuth
{
	protected $_GOAuth;

	public function Initialize()
	{
		if (!$this->_config['keys']['id'] || !$this->_config['keys']['secret'])
		{
			throw new Exception('Argument ID or secret are missing in ' . $this->_service);
		}

		if (!$this->_config['url'])
		{
			throw new Exception('Argument URL is missing');
		}

		require_once(ROOT . 'lib/SocialConnect/' . $this->_service . '/Google_Client.php');
		require_once(ROOT . 'lib/SocialConnect/' . $this->_service . '/contrib/Google_Oauth2Service.php');

		$this->_api = new Google_Client();

		$this->_api->setClientId($this->_config['keys']['id']);
		$this->_api->setClientSecret($this->_config['keys']['secret']);
		$this->_api->setRedirectUri($this->_config['url']);

		$this->_GOAuth = new Google_Oauth2Service($this->_api);
	}

	public function Bind()
	{
		if (isset($_REQUEST['reset']))
		{
			unset($_SESSION['token']);
			$this->_api->revokeToken();
			header('Location: ' . filter_var($this->_config['url'], FILTER_SANITIZE_URL));
		}

		if (isset($_GET['code']))
		{
			$this->_api->authenticate($_GET['code']);
			$_SESSION['token'] = $this->_api->getAccessToken();
			header('Location: ' . filter_var($this->_config['url'], FILTER_SANITIZE_URL));
		
			return;
		}

		if (isset($_SESSION['token']))
		{
			$this->_api->setAccessToken($_SESSION['token']);
		}

		if ($this->_api->getAccessToken())
		{
			$_SESSION['token'] = $this->_api->getAccessToken();
		}
		else
		{
			header('Location: ' . $this->_api->createAuthUrl());
		}
	}

	public function GetUserProfile()
	{
		if ($this->_api->getAccessToken())
		{
			$user = $this->_GOAuth->userinfo->get();

			return array(
				'oauth_id'	=> $user['id'],
				'email'		=> filter_var($user['email'], FILTER_SANITIZE_SPECIAL_CHARS),
				'sex'		=> filter_var($user['gender'], FILTER_SANITIZE_SPECIAL_CHARS),
			);
		}
	}
}

?>