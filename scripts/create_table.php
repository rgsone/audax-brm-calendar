<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

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

$capsule::schema()->create('races', function (Blueprint $table) {
	$table->id();
	$table->date('date')->nullable(false)->index();
	$table->tinyInteger('distance')->nullable(false)->index();
	$table->string('status')->nullable();
	$table->string('contact')->nullable();
	$table->string('contact_mail')->nullable();
	$table->string('country')->nullable(false)->index();
	$table->integer('datetime')->nullable();
	$table->string('web_site')->nullable();
	$table->string('city')->nullable();
	$table->string('county')->nullable()->index();
	$table->string('region')->nullable()->index();
	$table->string('roadmap')->nullable();
	$table->integer('elevation')->nullable();
	$table->string('club_name')->nullable();
});

