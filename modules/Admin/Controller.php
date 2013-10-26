<?php
/**
 * CellForm Admin Controller Class
 * Admin module - Eligible for ajax API
 *
 * @author rootofgeno@gmail.com
 */


class Admin_Controller
{
	protected $_toolbox;

	protected $_db;
	protected $_translate;
	protected $_errno;
	protected $_http;
	protected $_user;

	public function __construct()
	{
		$this->_toolbox = Cellform_Front::GetToolBox();

		$this->_db 			= $this->_toolbox->GetDB();
		$this->_translate	= $this->_toolbox->GetTranslate();
		$this->_errno		= $this->_toolbox->GetErrno();
		$this->_http 		= $this->_toolbox->GetHTTP();
		$this->_user		= $this->_toolbox->GetUser();
	}

	public function GetName()
	{
		return 'Admin';
	}

	public function GetLevel()
	{
		return LEVEL_ADMIN;
	}
}

?>