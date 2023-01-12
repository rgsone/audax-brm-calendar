<?php

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as DB;

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

if (!$capsule::schema()->hasTable('settings')) {
	$capsule::schema()->create('settings', function (Blueprint $table) {
		$table->id();
		$table->string('item')->unique()->index();
		$table->string('value')->nullable();
	});
}

if (!DB::table('settings')->where('item', '=', 'last_update')->exists()) {
	$result = DB::table('settings')->insert([
		'item' => 'last_update',
		'value' => Carbon::now('Europe/Paris')->getTimestamp()
	]);
}
