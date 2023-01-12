<?php

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;

require dirname(__DIR__) . '/vendor/autoload.php';

$capsule = new Capsule();

$capsule->addConnection([
	'driver'    => 'sqlite',
	'database'  => dirname(__DIR__) . '/database/data.db',
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => ''
]);

$capsule->setAsGlobal();

$filename = 'brm2023-all_2023-01-12.json';
$rawJson = file_get_contents(dirname(__DIR__) . '/raw-data/' . $filename);
$races = json_decode($rawJson);

Capsule::table('races')->truncate();

echo "truncate table\n\n";

$errorCount = 0;
$insertCount = 0;

foreach ($races as $race) {
	$website = filter_var(trim($race->SiteWeb), FILTER_SANITIZE_URL);
	$website = !filter_var($website, FILTER_VALIDATE_URL) ? null : $website;
	$date = Carbon::createFromFormat('d/m/Y', $race->Date, 'Europe/Paris');

	$data = [
		'date' => $date->toDateString(),
		'distance' => $race->Distance,
		'status' => $race->Statut,
		'contact' => $race->Contact,
		'contact_mail' => $race->MailContact,
		'country' => $race->Pays,
		'datetime' => $date->startOfDay()->getTimestamp(),
		'web_site' => $website,
		'city' => $race->Ville,
		'county' => $race->Departement,
		'region' => $race->Region,
		'roadmap' => $race->RoadMap,
		'elevation' => (int)$race->Denivele,
		'club_name' => $race->NomClub
	];

	$insertCount++;
	echo ">> insert n°$insertCount\n";

	$result = Capsule::table('races')->insert($data);

	if (!$result) {
		$errorCount++;
		echo "\t> error when insert n°$insertCount\n";
	}
}

Capsule::table('settings')->upsert(
	[
		'item' => 'last_update',
		'value' => Carbon::now('Europe/Paris')->getTimestamp()
	],
	['item'],
	['value']
);

$updateTimestamp = Capsule::table('settings')
	->where('item', '=', 'last_update')
	->first();
$updateDatetime = Carbon::createFromTimestamp($updateTimestamp->value, 'Europe/Paris')->toDateTimeString();

echo "-> update date : $updateDatetime\n";
echo "-> total insertion : $insertCount\n";
echo "-> total error : $errorCount\n";
