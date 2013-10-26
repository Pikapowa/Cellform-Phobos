<?php
/**
 * CellForm Config Class
 * Management config file
 * 
 * @author rootofgeno@gmail.com
 */

define('FILECONF', ROOT . '/config/config.conf.php');

class Cellform_Config
{
	static protected $_newconfig = array();

	/**
	* Format a valid configuration file & returns array with changed
	*
	* @access public
	* @param array $config
	* @return array
	*/
	public static function Set(array $config = array())
	{
		if (!file_exists(FILECONF))
		{
			throw new Exception('Sorry, I need a config.conf.php file to work.');
		}

		if (!($config_file = file(FILECONF)))
		{
			return false;
		}

		foreach($config_file as $line_num => $line)
		{
			if (!preg_match('/^define\(\'([A-Z_]+)\',([ ]+)/', $line, $match))
			{
				continue;
			}

			$constant = $match[1];
			$padding  = $match[2];

			$value = (!empty($config[$constant])) ? $config[$constant] : constant($constant);

			$config_file[$line_num] = "define('" . $constant . "'," . $padding . "'" . addcslashes($value, "\\'") . "');\r\n";
		}

		self::$_newconfig = $config_file;
	}

	/**
	* Write new data in config.conf.php
	*
	* @access public
	* @param array $new_config
	* @return bool
	*/
	public static function Save()
	{
		if (!empty(self::$_newconfig))
		{
			if (!($fd = @fopen(FILECONF, 'w')))
			{
				throw new Exception('Sorry, config.conf.php file need to be writable.');
			}

			foreach (self::$_newconfig as $line)
			{
				if (!fwrite($fd, $line))
				{
					return false;
				}
			}

			fclose($fd);

			return true;
		}
	}
}