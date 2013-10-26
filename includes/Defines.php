<?php
/**
 * CellForm Defines  File
 * Defined variables
 *
 * @author rootofgeno@gmail.com
 */

define('PHP_VERSION_REQUIRED', '5.4.0');
define('BASE_URI', 'http://' . $_SERVER['SERVER_NAME']);

define('LEVEL_BANN', 0);
define('LEVEL_USER',  1);
define('LEVEL_ADMIN', 2);

define('TEMPLATE_PATH', ROOT . 'themes/');
define('LANGUAGE_PATH', ROOT . 'lang/');
define('MODULES_PATH', 	ROOT . 'modules/');

define('DEFAULTS_VIEW', 	 'defaults');

define('DEFAULTS_MODULE', 	 'Loginbox');
define('DEFAULTS_ACTION', 	 'Defaults');
define('DEFAULTS_COMPONENT', 'Defaults');

define('TIMECOOKIE', time()+60*60*24*6004);
define('FILE_MAXSIZE', 1253600);

define('VOTE_POST', 10);
define('VOTE_COM', 5);

?>