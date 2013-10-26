<?php
/**
 * CellForm Controller Class
 * Chargement de tous les composants du noyau.
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Controller
{
    public static $adapters = array(
        'db'			=> null,
        'translate' 	=> null,
        'template'		=> null,
    	'dispatcher'	=> null,
		'errno'			=> null,
		'http'			=> null,
		'user'			=> null,
    );

    public static function Setup()
	{
        $adapters = array(
            'db'			=> (array)self::$adapters['db'] + array(
                'adapter'	=> 'Cellform_DB_' . DMS,
                'defaults'	=> null,
            ),
			'translate'		=> (array)self::$adapters['translate'] + array(
                'adapter'	=> 'Cellform_Translate',
                'defaults'	=> null,
            ),
            'template'		=> (array)self::$adapters['template'] + array(
                'adapter'	=> 'Cellform_Template',
                'defaults'	=> null,
            ),
            'dispatcher'	=> (array)self::$adapters['dispatcher'] + array(
                'adapter'	=> 'Cellform_Dispatcher',
                'defaults'	=> null,
            ),
            'errno'			=> (array)self::$adapters['errno'] + array(
                'adapter'	=> 'Cellform_Errno',
                'defaults'	=> null,
            ),
            'http'			=> (array)self::$adapters['http'] + array(
                'adapter'	=> 'Cellform_HTTP',
                'defaults'	=> function() use (& $errno)
				{
					return compact('errno');
				},
            ),
			'user'			=> (array)self::$adapters['user'] + array(
				'adapter'	=> 'Cellform_User',
				'defaults'	=> function() use (& $db, & $http)
				{
					return compact('db', 'http');
				},
			),
        );

        $db = self::_instance($adapters['db']);
        $translate = self::_instance($adapters['translate']);
        $template = self::_instance($adapters['template']);
        $dispatcher = self::_instance($adapters['dispatcher']);
		$errno = self::_instance($adapters['errno']);
		$http = self::_instance($adapters['http']);
		$user = self::_instance($adapters['user']);

	    return new Cellform_ToolBox($db, $translate, $template, $dispatcher, $errno, $http, $user);
	}

    protected static function _instance(array $adapter)
	{
        $class = $adapter['adapter'];
        $context = is_callable($adapter['defaults']) ? $adapter['defaults']() : ($adapter['defaults'] ?: array());
        return Cellform_Injector::Instanciate($class, $context);
    }
}

?>