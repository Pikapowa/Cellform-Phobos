<?php
/**
 * CellForm Mail Controller Class [TRANSVERSAL MODULE]
 * Mail module - Not Eligible for ajax API
 *
 * @author rootofgeno@gmail.com
 */

require_once(MODULES_PATH . '/Mail/Defaults.php');

class Mail_Controller
{
	protected $_toolbox;

	protected $_translate;
	protected $_template;

	protected $_defaults;

	public function __construct()
	{
		$this->_toolbox = Cellform_Front::GetToolBox();

		$this->_translate 	= Cellform_Injector::Instanciate('Cellform_Translate', array('Mail'));
		$this->_template 	= Cellform_Injector::Instanciate('Cellform_Template', array('Mail'));

		$this->_defaults 	= Cellform_Injector::Instanciate('Mail_Defaults', array($this));
	}

	public function GetName()
	{
		return 'Mail';
	}

	public function GetLevel()
	{
		return LEVEL_ADMIN;
	}

	public function Send($view, $to, array $data = array())
	{
		return $this->_defaults->Send($view, $to, $data);
	}
}

?>