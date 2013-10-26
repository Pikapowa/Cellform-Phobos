<?php
/**
 * CellForm Startup File Version 1.0
 * Initializations of global methods and classes
 *
 * @author rootofgeno@gmail.com
 */

session_start();
session_cache_limiter('none');

require_once(ROOT . 'includes/Defines.php');
require_once(ROOT . 'includes/Functions.php');
require_once(ROOT . 'includes/Twig/Autoloader.php');
require_once(ROOT . 'includes/Cellform/Autoloader.php');

if (version_compare(PHP_VERSION, PHP_VERSION_REQUIRED, '<'))
{
	exit('Sorry, Cellform will only run on PHP version 5.4.0 or greater!\n');
}

Twig_Autoloader::Register();
Cellform_Autoloader::Register();

require_once(ROOT . 'config/config.conf.php');
require_once(ROOT . 'config/router.conf.php');

// Initialize routing
$request = Cellform_Router::RequestRoute($_GET['p']);
$lang = Cellform_Router::GetLanguage();

define('ACTIVE_MODULE', $request['module']);
define('ACTIVE_LANG', $lang);

// Initialize classes
Cellform_Front::Initialize();
$toolbox = Cellform_Front::GetToolBox();

// Connect to database
$toolbox->GetDB()->SqlConnect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, false);

?>