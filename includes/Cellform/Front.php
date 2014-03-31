<?php

class Cellform_Front
{
    public static $toolbox;

	public static $http;
	public static $translate;
	public static $template;
	public static $dispatcher;

	/**
	* Get the version of Cellform API
	*
	* @access public
	* @return float
	*/
    public static function GetVersion()
	{
        return '1.1.0';
    }

	/**
	* Initialize the API Core
	*
	* @access public
	*/
    public static function Initialize()
	{
        self::$toolbox = Cellform_Controller::Setup();

		self::$http = self::$toolbox->GetHTTP();
		self::$translate = self::$toolbox->GetTranslate();
		self::$template = self::$toolbox->GetTemplate();
		self::$dispatcher = self::$toolbox->GetDispatcher();
    }

    /**
    * Return module translations
    *
    * @access public
    */
    public static function GetAllTranslation()
    {
    	return self::$translate->GetAllTranslation();
    }

	/**
	* Print template with request array if header is empty
	*
	* @access public
	* @param string $view
	*/
    public static function GetRender($view, array $args = array())
	{
		if (!self::$http->GetHeaderStatus())
		{
			echo self::$template->Render($view, array_merge($args, array('request'	=> $_REQUEST)));
		}
    }

   	/**
	* Get toolbox (ex : used by modules)
	*
	* @access public
	* @return object
	*/
	public static function GetToolBox()
	{
		return self::$toolbox;
	}
}

?>