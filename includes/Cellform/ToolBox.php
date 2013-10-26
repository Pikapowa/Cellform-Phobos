<?php
/**
 * CellForm Class ToolBox (All basic adaptators)
 *
 * @author rootofgeno@gmail.com
 * @return
 */

class Cellform_ToolBox
{
	protected $_db;
	protected $_translate;
	protected $_template;
	protected $_dispatcher;
	protected $_errno;
	protected $_http;
	protected $_user;

    public function __construct(Cellform_DB $db,
                                Cellform_Translate $translate,
								Cellform_Template $template,
								Cellform_Dispatcher $dispatcher,
								Cellform_Errno $errno,
								Cellform_HTTP $http,
								Cellform_User $user)
	{
        $this->_db = $db;
        $this->_translate = $translate;
		$this->_template = $template;
		$this->_dispatcher = $dispatcher;
		$this->_errno = $errno;
		$this->_http = $http;
		$this->_user = $user;
    }

    public function GetDB()
	{
        return $this->_db;
    }

    public function GetTranslate()
	{
        return $this->_translate;
    }

    public function GetTemplate()
	{
        return $this->_template;
    }

    public function GetDispatcher()
    {
    	return $this->_dispatcher;
    }

	public function GetErrno()
	{
		return $this->_errno;
	}

	public function GetHTTP()
	{
        return $this->_http;
    }

	public function GetUser()
	{
		return $this->_user;
	}
}

?>