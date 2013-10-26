<?php
/**
 * CellForm Social Network Service Base
 * Management class connectivity services OAuth (with main Sns)
 * Component Sns
 *
 * @author rootofgeno@gmail.com
 */

abstract class Cellform_Sns_OAuth
{
	protected $_api = null;
	protected $_service = null;
	protected $_config  = array();

	abstract protected function Initialize();
	abstract protected function Bind();
	abstract protected function GetUserProfile();

	public function __construct($service = null, array $config = array())
	{
		$this->_service = $service;
		$this->_config  = $config;

		$this->Initialize();
	}
}

?>