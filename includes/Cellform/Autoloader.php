<?php
/**
 * CellForm Autoload Class
 * Configuration de l'autoload.
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Autoloader
{	
    public static function Autoload($class)
    {
        if (is_file($file = ROOT . '/includes/' . str_replace(array('_', '\0'), array('/', ''), $class) . '.php'))
        {
            require_once $file;
        }
    }

    public static function Register()
	{
		spl_autoload_register(array(new self, 'Autoload'));
    }
}

?>