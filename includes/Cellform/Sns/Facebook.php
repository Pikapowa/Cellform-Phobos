<?php
/**
 * CellForm Facebook Connect
 * If you want used directly Cellform_Sns_Facebook don't forget to call Initialize()
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Sns_Facebook extends Cellform_Sns_OAuth
{
	protected $_session;
	protected $_me;

	public function Initialize()
	{
		if (!$this->_config['keys']['id'] || !$this->_config['keys']['secret'])
		{
			throw new Exception('Your application id and secret are required in order to connect to ' . $this->_service);
		}

		require_once(ROOT . 'lib/SocialConnect/' . $this->_service . '/facebook.php');

		$this->_api = new Facebook(array(
				'appId'		=> $this->_config['keys']['id'],
				'secret'	=> $this->_config['keys']['secret'],
		));
	}

	public function Bind()
	{
		$this->_session = $this->_api->getUser();

		if ($this->_session)
		{
			try
			{
				$this->_me = $this->_api->api('/me');
			}
			catch (FacebookApiException $e)
			{
				error_log($e);
				$this->_session = null;
			}
		}

		if (empty($this->_session))
		{
			header('Location:'. $this->_api->getLoginUrl(array(
				'locale' 	=> 'fr_FR',
				'scope' 	=> 'email,read_stream'
			)));
		}
	}

	public function GetUserProfile()
	{
		if (!empty($this->_me))
		{
			$sql = "select uid, name, email, sex, pic_big from user where uid = " . $this->_session;
			$param = array(
				'method'	=> 'fql.query',
				'query'		=> $sql,
				'callback'	=> ''
			);

			$fb = $this->_api->api($param);
			$fb = $fb[0];

			return array(
				'oauth_id'	=> $fb['uid'],
				'email'		=> $fb['email'],
				'sex'		=> $fb['sex'],
			);
		}
		else
		{
			return false;
		}
	}
}

?>