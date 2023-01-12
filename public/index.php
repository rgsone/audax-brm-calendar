<?php

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;
use Rgsone\BrmCalendar\Api;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload.php';
require_once dirname(__DIR__).'/api/Distance.php';
require_once dirname(__DIR__).'/api/Month.php';
require_once dirname(__DIR__).'/api/Country.php';

$req = Request::createFromGlobals();

if ($req->getMethod() === 'POST' && $req->request->get('action') === 'getData') {

	require_once dirname(__DIR__).'/api/Api.php';

	$api = new Api($req);
	$response = $api->getData();
	$response->send();

} else {

	// check if local or prod env with uri
	$isDevEnv = false;
	if ($req->server->get('SERVER_NAME') === 'brm-calendar.local') $isDevEnv = true;

	// get last update date
	$db = new DB();
	$db->addConnection([
		'driver'    => 'sqlite',
		'database'  => dirname(__DIR__) . '/database/data.db',
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => ''
	]);
	$db->setAsGlobal();
	$lastUpdate = DB::table('settings')->where('item', '=', 'last_update')->first();
	$lastUpdate = Carbon::createFromTimestamp($lastUpdate->value, 'Europe/Paris')->format('d/m/Y');

	require_once dirname(__DIR__).'/view/home.php';

}
