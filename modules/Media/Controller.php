<?php
/**
 * CellForm Media Controller Class
 * Media module - Eligible for ajax API
 *
 * @author rootofgeno@gmail.com
 */


class Media_Controller
{
	protected $_toolbox;

	protected $_db;
	protected $_errno;
	protected $_http;
	protected $_user;
	protected $_info;

	public function __construct()
	{
		$this->_toolbox = Cellform_Front::GetToolBox();

		$this->_db 			= $this->_toolbox->GetDB();
		$this->_errno		= $this->_toolbox->GetErrno();
		$this->_http 		= $this->_toolbox->GetHTTP();
		$this->_user		= $this->_toolbox->GetUser();
	}

	public function GetName()
	{
		return 'Media';
	}

	public function GetLevel()
	{
		return LEVEL_USER;
	}
}

?>