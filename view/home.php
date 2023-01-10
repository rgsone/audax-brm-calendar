<?php

	use Rgsone\BrmCalendar\Distance;
	use Rgsone\BrmCalendar\Month;
	use Rgsone\BrmCalendar\Country;

	require dirname(__DIR__).'/api/Distance.php';
	require dirname(__DIR__).'/api/Month.php';
	require dirname(__DIR__).'/api/Country.php';

?>
<!doctype html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title>Calendrier BRM 2023</title>
		<meta name="description" content="Calendrier des BRM 2023">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- detect host and add/remove style tag -->
	</head>

	<body class="bg-slate-100 flex flex-col">

		<h1 class="text-2xl font-bold p-3 mt-4 max-w-5xl mx-auto w-full">BRM 2023</h1>

		<!-- form filter -->
		<form x-data="filterFormHandler" @submit.prevent="submit" class="flex flex-col md:flex-row max-w-5xl mx-auto w-full p-3">

			<div class="flex flex-col sm:flex-row sm:mb-3 md:mb-0">

				<div class="flex flex-col mb-4 sm:mb-0 sm:mr-2">
					<select name="distance" id="distance" x-model="formData.distance" class="rounded-md">
							<option value="" disabled>Distance</option>
			  		<?php foreach (Distance::cases() as $distance): ?>
							<option value="<?= $distance->value ?>" <?php if ($distance === Distance::TWO_HUNDRED) echo 'selected="selected"'; ?>><?= $distance->frenchName() ?></option>
			  		<?php endforeach; ?>
					</select>
				</div>

				<div class="flex flex-col mb-4 sm:mb-0 sm:mr-2">
					<select name="month" id="month" x-model="formData.month" class="rounded-md">
							<option value="" disabled>Mois</option>
			  		<?php foreach (Month::cases() as $month): ?>
							<option value="<?= $month->value ?>"><?= $month->frenchName() ?></option>
			  		<?php endforeach; ?>
					</select>
				</div>

				<div class="flex flex-col mb-4 sm:mb-0 sm:mr-2">
					<select name="country" id="country" x-model="formData.country" class="rounded-md">
							<option value="" disabled>Pays</option>
			  		<?php foreach (Country::cases() as $country): ?>
							<option value="<?= $country->value ?>"><?= $country->frenchName() ?></option>
			  		<?php endforeach; ?>
					</select>
				</div>

			</div>

			<button :disabled="isSubmitBtnDisabled" x-text="$store.brmData.filterBtnText" class="bg-indigo-500 disabled:bg-indigo-100 text-white px-8 py-2 rounded-md hover:bg-indigo-600 self-end sm:self-start md:self-auto">Filtrer</button>

		</form>

		<!-- results container -->
		<div id="results" x-data="brmList" >

			<!-- top pagination-->
			<div class="flex justify-between items-center p-3 max-w-5xl mx-auto w-full">

				<div>
					<button class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500">&lt;&lt;</button>
					<button class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500">&lt;</button>
				</div>

				<div class="">
					1-10 / 60
				</div>

				<div>
					<button class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500">&gt;</button>
					<button class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500">&gt;&gt;</button>
				</div>

			</div>

			<!-- results -->
			<ul class="p-3 max-w-5xl mx-auto w-full">
				<li class="bg-white shadow mb-4 px-4 py-5 last:mb-0">
					01/01/2023 / 200 km / 1200 D+
					<br>
					Debra BANKS (rba@davisbikeclub.org)
					<br>
					club -> Davis Bike Club
					<br>
					USA / CA / Departement / Davis
					<br>
					site : https://www.davisbikeclub.org/ultra-distance-brevets-and-randonneuring
					<br>
					trace :
				</li>
			</ul>

			<!-- bottom pagination -->
			<div class="flex justify-between items-center p-4 mb-10 max-w-5xl mx-auto w-full">

				<div>
					<button class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500">&lt;&lt;</button>
					<button class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500">&lt;</button>
				</div>

				<div class="">
					1-10 / 60
				</div>

				<div>
					<button class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500">&gt;</button>
					<button class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500">&gt;&gt;</button>
				</div>

			</div>

		</div>

		<!--<script src="js/app.js"></script>-->
		<!-- detect host and switch dev/prod script tag -->
		<script type="module" src="http://localhost:5173/@vite/client"></script>
		<script type="module" src="http://localhost:5173/main.js"></script>

	</body>

</html>


