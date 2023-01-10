<?php

use Rgsone\BrmCalendar\Api;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

$req = Request::createFromGlobals();

if ($req->getMethod() === 'POST' && $req->request->get('action') === 'getData') {

	require dirname(__DIR__).'/api/Api.php';
	require dirname(__DIR__).'/api/Country.php';
	require dirname(__DIR__).'/api/Distance.php';
	require dirname(__DIR__).'/api/Month.php';

	$api = new Api($req);
	$response = $api->getData();
	$response->send();

} else {

	require dirname(__DIR__).'/view/home.php';

}
