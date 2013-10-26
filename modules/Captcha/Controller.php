<?php
/**
 * CellForm Captcha Controller Class [TRANSVERSAL MODULE]
 * Captcha module - Eligible for ajax API
 *
 * @author rootofgeno@gmail.com
 */

require_once(MODULES_PATH . '/Captcha/Defaults.php');

class Captcha_Controller
{
	protected $_toolbox;

	protected $_http;
	protected $_translate;

	protected $_defaults;

	public function __construct()
	{
		$this->_toolbox = Cellform_Front::GetToolBox();

		$this->_http 		= $this->_toolbox->GetHTTP();
		$this->_translate 	= Cellform_Injector::Instanciate('Cellform_Translate', array('Captcha'));

		$this->_defaults 	= Cellform_Injector::Instanciate('Captcha_Defaults', array($this));
	}

	public function GetName()
	{
		return 'Captcha';
	}

	public function CaptchaRaw()
	{
		return $this->_defaults->CaptchaRaw();
	}

	public function ReturnAnswer()
	{
		return $this->_defaults->ReturnAnswer();
	}
}

?>