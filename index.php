<?php
/**
 * CellForm Main Controller
 *
 * @author rootofgeno@gmail.com
 */

define('ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR);

require_once(ROOT . 'common.php');

$translate 	= $toolbox->GetTranslate();
$dispatcher = $toolbox->GetDispatcher();
$user		= $toolbox->GetUser();

if (MAINTENANCE == 'ON')
{
	header('Location: /maintenance.html');
}

$controller = $dispatcher->LoadController(ACTIVE_MODULE);

if ($controller)
{
	if (method_exists($controller, 'GetLevel'))
	{
		$user->StartSecureArea($controller->GetLevel());
	}

	$data = $dispatcher->Dispatcher($request['component'], $request['action']);

	$response = !empty($data['response']) ? $data['response'] : array();
	$template = !empty($data['template']) ? $data['template'] : DEFAULTS_VIEW;

	Cellform_Front::GetRender($template, array_merge($translate->GetAllTranslation(), (array)$response, (array)$request, array(
		'userbase'	=> $user->GetInfo(),
		'csrf'		=> base64_encode(session_id()),
	)));
}
else
{
	header('Location: /');
}

?>