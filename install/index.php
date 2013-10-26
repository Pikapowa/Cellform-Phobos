<?php
/**
 * CellForm Installer File Version 1.0
 *
 * @author rootofgeno@gmail.com
 */

define('ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '../');

require_once(ROOT . 'includes/Defines.php');
require_once(ROOT . 'includes/Functions.php');
require_once(ROOT . 'includes/Twig/Autoloader.php');
require_once(ROOT . 'includes/Cellform/Autoloader.php');

if (version_compare(PHP_VERSION, PHP_VERSION_REQUIRED, '<'))
{
	exit('Sorry, Cellform will only run on PHP version 5.4.0 or greater!\n');
}

session_start();
session_cache_limiter('none');

Twig_Autoloader::Register();
Cellform_Autoloader::Register();

require_once(ROOT . 'config/config.conf.php');

$lang = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : DEFAULTS_LANG;

define('ACTIVE_MODULE', 'Install');
define('ACTIVE_LANG', $lang);

// Initialize classes
Cellform_Front::Initialize();

$installer = new Cellform_Install();

class Cellform_Install
{
	protected static $toolbox;

	protected static $db;
	protected static $translate;
	protected static $errno;
	protected static $http;
	protected static $user;

	public function __construct()
	{
		self::$db = Cellform_Front::GetToolBox()->GetDB();
		self::$translate = Cellform_Front::GetToolBox()->GetTranslate();
		self::$errno = Cellform_Front::GetToolBox()->GetErrno();
		self::$http = Cellform_Front::GetToolBox()->GetHTTP();
		self::$user = Cellform_Front::GetToolBox()->GetUser();

		@self::$db->SqlConnect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, false);

		$valide_rules = array(
			'mode'	=> 'Alphanumeric|MaxLen,20',
		);

		self::$http->ValidatorRules($valide_rules);
		$input = self::$http->Analyzer($_GET);

		if (!empty($input['mode']) && method_exists($this, $input['mode']) && is_callable(array($this, $input['mode'])))
		{
			$this->$input['mode']();
		}
		else
		{
			$this->Intro();
		}
	}

	/**
	* Change language & redirect to install page
	*
	* @access public
	*/
	public function Language()
	{
		$valide_rules = array(
			'lang'	=> 'Required|Alphanumeric|MaxLen,2',
		);
	
		self::$http->ValidatorRules($valide_rules);
		$input = self::$http->Analyzer($_GET);
	
		if (self::$http->IsValid())
		{
			$_SESSION['lang'] = $input['lang'];
		}

		self::$http->Header('Location', '/install/index.php');
		self::$http->SendHeaders();
	}

	public function Intro()
	{
		return Cellform_Front::GetRender('intro', Cellform_Front::GetAllTranslation());
	}

	public function Licence()
	{
		return Cellform_Front::GetRender('licence', Cellform_Front::GetAllTranslation());
	}

	public function Required()
	{
		$response = array();
		$modules  = array();
		$require  = array(
			'Config_Php'			=> array(
				'phpversion'		=> array()
			),
			'Extensions_Php'		=> array(
				'gd'				=> array(),
				'json'				=> array(),
				'simplexml'			=> array()
			),
			'Extensions_Opt_Php'	=> array(
				'curl'				=> array()
			),
			'Extensions_Database'	=> array(
				'mysql'				=> array()
			),
			'Modules_Http'			=> array(
				'mod_rewrite'		=> array()
			),
			'Files_Check'			=> array(
				'cache/'						=> array(),
				'cache/_db/'					=> array(),
				'config/config.conf.php'		=> array(),
				'media/img/'					=> array(),
				'media/img/min/'				=> array(),
				'media/avatars/'				=> array(),
				'images/loginbox/bg.jpg'		=> array(),
				'images/loginbox/present.jpg'	=> array(),
				'images/media/banner.png'		=> array()
			)
		);

		if (function_exists('apache_get_modules'))
		{
			$modules = apache_get_modules();
		}

		if (version_compare(PHP_VERSION, PHP_VERSION_REQUIRED, '<'))
		{
			$require['Config_Php']['phpversion']['status'] = 'missing';
		}

		foreach($require as $key => $values)
		{
			foreach($values as $subkey => $subvalues)
			{
				if (strpos($key, 'Modules_') === 0)
				{
					if (!in_array($subkey, $modules))
					{
						$require[$key][$subkey]['status'] = 'missing';
					}
				}

				if (strpos($key, 'Extensions_') === 0)
				{
					if (!extension_loaded($subkey))
					{
						$require[$key][$subkey]['status'] = 'missing';
					}
				}

				if (strpos($key, 'Files_') === 0)
				{
					$filepath = ROOT . $subkey;
					$require[$key][$subkey]['name'] = $subkey;

					if (!is_writable($filepath))
					{
						$require[$key][$subkey]['status'] = 'missing';
					}
				}
			}
		}

		$response = $this->_CheckList($require);

		return Cellform_Front::GetRender('required', array_merge(Cellform_Front::GetAllTranslation(), $response));
	}
	
	public function ConfigMain()
	{
		$response = array();
		$valide_rules = array(
			'sitename'		=> 'Required|MaxLen,20',
			'sitetitle'		=> 'Required|MaxLen,64',
			'description'	=> 'Required|MaxLen,128',
			'email'			=> 'Required|Email|MaxLen,30',
			'salt'			=> 'Required|AlphaNumeric',
			'lang'			=> 'Required|MaxLen,2',
		);

		self::$http->ValidatorRules($valide_rules);
		$input = self::$http->Analyzer($_POST);

		if (self::$http->Is_Set('send'))
		{
			if (self::$http->IsValid())
			{
				$config = array(
					'SITENAME'		=> $input['sitename'],
					'TITLE'			=> $input['sitetitle'],
					'DESCRIPTION'	=> $input['description'],
					'EMAIL'			=> $input['email'],
					'SALT'			=> $input['salt'],
					'DEFAULTS_LANG'	=> $input['lang'],
				);

				Cellform_Config::Set($config);

				if (Cellform_Config::Save())
				{
					self::$translate->MergeTranslation($config);
				}

				self::$http->Header('Location', '/install/index.php?mode=configdb');
				self::$http->SendHeaders();
			}

			$response['errnos'] = self::$errno->GetErrnos();
		}

		return Cellform_Front::GetRender('config_main', array_merge(Cellform_Front::GetAllTranslation(), $response));
	}

	public function ConfigDb()
	{
		$response = array();
		$valide_rules = array(
			'hostname'		=> 'Required|MaxLen,20',
			'name'			=> 'Required|MaxLen,20',
			'username'		=> 'Required|MaxLen,20',
			'password'		=> 'Required|AlphaNumeric|MaxLen,20',
		);
		
		self::$http->ValidatorRules($valide_rules);
		$input = self::$http->Analyzer($_POST);
		
		if (self::$http->Is_Set('send'))
		{
			if (self::$http->IsValid())
			{
				$config = array(
					'DB_HOST'		=> $input['hostname'],
					'DB_NAME'		=> $input['name'],
					'DB_USER'		=> $input['username'],
					'DB_PASSWORD'	=> $input['password'],
				);

				if (@self::$db->SqlConnect($input['hostname'], $input['username'], $input['password'], $input['name'], false))
				{
					Cellform_Config::Set($config);

					if (Cellform_Config::Save())
					{
						self::$http->Header('Location', '/install/index.php?mode=install');
						self::$http->SendHeaders();
					}
					else
					{
						self::$errno->AppendError('Error_FileWrite');
					}
				}
				else
				{
					self::$errno->AppendError('Error_MySqlConnect');
				}
			}

			$response['errnos'] = self::$errno->GetErrnos();
		}

		return Cellform_Front::GetRender('config_db', array_merge(Cellform_Front::GetAllTranslation(), $response));
	}

	public function Install()
	{
		$response = array();
		$require  = array(
			'Install_CreateTable'	=> array(
				'installquery'		=> array()
			)
		);

		if (!file_exists('install.sql'))
		{
			Cellform_Errno::Error('Need file install.sql !');
		}

		$shemas = file_get_contents('install.sql');
		$shemas = remove_remarks($shemas);

		$reqs = explode(';', $shemas);

		$nbreqs = count($reqs);

		for ($i = 0; $i < $nbreqs; $i++)
		{
			if (($i != ($nbreqs - 1)) || (strlen($reqs[$i] > 0)))
			{
				if (!empty($reqs[$i]))
				{
					try
					{
						@self::$db->SqlQuery($reqs[$i] . ';');
					}
					catch(Exception $e)
					{
						$require['Install_CreateTable']['installquery']['status'] = 'missing';
						echo $e->getMessage();
					}
				}
			}
		}

		$response = $this->_CheckList($require);

		return Cellform_Front::GetRender('install', array_merge(Cellform_Front::GetAllTranslation(), $response));
	}

	public function ConfigAdmin()
	{
		$response = array();
		$valide_rules = array(
			'email'				=> 'Required|Email|MaxLen,30',
			'username'			=> 'Required|AlphaNumeric,MaxLen,12',
			'password'			=> 'Required|AlphaNumeric,MaxLen,30',
			'password_confirm'	=> 'Required|AlphaNumeric|MaxLen,30',
		);

		self::$http->ValidatorRules($valide_rules);
		$input = self::$http->Analyzer($_POST);

		if (self::$http->Is_Set('send'))
		{
			if (self::$http->IsValid())
			{
				if (strcmp($input['password'], $input['password_confirm']) == 0)
				{
					self::$user->Register($input['email'], $input['username'], $input['password'], 1, false, null, LEVEL_ADMIN);

					self::$http->Header('Location', '/install/index.php?mode=installfinish');
					self::$http->SendHeaders();
				}
				else
				{
					self::$errno->AppendError('General_PasswordNoMatch');
				}
			}

			$response['errnos'] = self::$errno->GetErrnos();
		}

		return Cellform_Front::GetRender('config_admin', array_merge(Cellform_Front::GetAllTranslation(), $response));
	}

	public function InstallFinish()
	{
		return Cellform_Front::GetRender('install_finish', Cellform_Front::GetAllTranslation());
	}

	private function _CheckList(array $require = array())
	{
		$ready = 'yes';
	
		foreach($require as $key => $values)
		{
			$require[$key]['title'] = self::$translate->GetTranslation($key);
	
			if (is_array($values))
			{
				foreach($values as $subkey => $subvalues)
				{
					if (is_array($subvalues))
					{
						if (empty($require[$key][$subkey]['name']))
						{
							$translate = 'Required_' . $subkey;
							$require[$key][$subkey]['name'] = self::$translate->GetTranslation($translate);
						}
	
						if (empty($require[$key][$subkey]['status']))
						{
							$require[$key][$subkey]['status'] = 'ok';
						}
						
						if (!strstr($key, 'Opt_') && $require[$key][$subkey]['status'] == 'missing')
						{
							$ready = 'no';
						}
					}
				}
			}
		}
	
		return array(
			'required'	=> $require,
			'ready'		=> $ready,
		);
	}
}




?>