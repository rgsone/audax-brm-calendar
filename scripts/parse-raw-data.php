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

$rawJson = file_get_contents(dirname(__DIR__) . '/raw-data/brm2023-all_2023-01-06.json');
$races = json_decode($rawJson);

Capsule::table('races')->truncate();

echo "truncate table\n\n";

$errorCount = 0;
$insertCount = 0;

foreach ($races as $race) {
	$website = filter_var(trim($race->SiteWeb), FILTER_SANITIZE_URL);
	$website = !filter_var($website, FILTER_VALIDATE_URL) ? null : $website;
	$date = Carbon::createFromFormat('d/m/Y', $race->Date, 'Europe/Paris')->toDateString();

	$data = [
		'date' => $date,
		'distance' => $race->Distance,
		'status' => $race->Statut,
		'contact' => $race->Contact,
		'contact_mail' => $race->MailContact,
		'country' => $race->Pays,
		'datetime' => $race->TimeDate,
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

echo "-> total insertion : $insertCount\n";
echo "-> total error : $errorCount\n";
