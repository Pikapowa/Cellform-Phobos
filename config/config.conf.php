<?php
/**
*	CONFIG-SAVE.CONF.PHP
*	VOUS POUVEZ COMMENTER LA LIGNE EMAIL POUR DESACTIVER L'ENVOI DE MAILS.
*
*	WARNING : NOT USE TAB IN CONFIG FILE !
*/

define('DMS',              'Mysql');
define('DB_HOST',          'hostname');
define('DB_NAME',          'cellform');
define('DB_USER',          'cellform_db_user');
define('DB_PASSWORD',      'cellform_db_password');

define('MAINTENANCE',      'OFF');

define('SITENAME',         'cellform_name');
define('TITLE',            'cellform_title');
define('DESCRIPTION',      'cellform_desc');
define('EMAIL',            'cellform_mail@cellform.com');

define('SALT',             'cellform_salt');
define('ID',               'cellform_id');

define('DEFAULTS_LANG',    'fr');

define('FACEBOOK_ENABLED', 'false');
define('FACEBOOK_APPID',   'defaults');
define('FACEBOOK_SECRET',  'defaults');

define('GOOGLE_ENABLED',   'false');
define('GOOGLE_APPID',     'defaults');
define('GOOGLE_SECRET',    'defaults');

?>