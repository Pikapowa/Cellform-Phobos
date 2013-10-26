<?php
/**
 * CellForm Loginbox Controller Class
 * LoginBox module - Eligible for ajax API
 *
 * @author rootofgeno@gmail.com
 */

class Loginbox_Controller
{
	protected $_toolbox;

	protected $_db;
	protected $_dispatcher;
	protected $_errno;
	protected $_http;
	protected $_user;

	protected $_loginbox;
	protected $_captcha;
	protected $_mail;

	public function __construct()
	{
		$this->_toolbox = Cellform_Front::GetToolBox();

		$this->_db 			= $this->_toolbox->GetDB();
		$this->_dispatcher	= $this->_toolbox->GetDispatcher();
		$this->_errno		= $this->_toolbox->GetErrno();
		$this->_http 		= $this->_toolbox->GetHTTP();
		$this->_user 		= $this->_toolbox->GetUser();

		$this->_captcha  = $this->_dispatcher->LoadController('Captcha');
		$this->_mail	 = $this->_dispatcher->LoadController('Mail');
	}

	public function GetName()
	{
		return 'Loginbox';
	}
}

?>