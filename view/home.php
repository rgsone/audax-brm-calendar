<?php

	use Rgsone\BrmCalendar\Distance;
	use Rgsone\BrmCalendar\Month;
	use Rgsone\BrmCalendar\Country;

?>
<!doctype html>
<html lang="fr">

	<head>
		<meta charset="utf-8">
		<title>Calendrier BRM 2023</title>
		<meta name="description" content="Calendrier des BRM 2023">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php if (!$isDevEnv): ?>
		<link rel="stylesheet" href="/assets/style.css">
		<?php endif; ?>
	</head>

	<body class="bg-slate-100 flex flex-col">

		<h1 class="text-2xl font-bold p-3 mt-4 max-w-5xl mx-auto w-full">BRM 2023</h1>

		<!-- form filter -->
		<form x-data="filterFormHandler" @submit.prevent="submit" class="flex flex-col md:flex-row max-w-5xl mx-auto w-full p-3">

			<div class="flex flex-col sm:flex-row sm:mb-3 md:mb-0">

				<div class="flex flex-col mb-4 sm:mb-0 sm:mr-2">
					<select name="distance" id="distance" x-model="$store.brmData.formData.distance" class="rounded-md">
							<option value="" disabled>Distance</option>
			  		<?php foreach (Distance::cases() as $distance): ?>
							<option value="<?= $distance->value ?>" <?php if ($distance === Distance::TWO_HUNDRED) echo 'selected="selected"'; ?>><?= $distance->frenchName() ?></option>
			  		<?php endforeach; ?>
					</select>
				</div>

				<div class="flex flex-col mb-4 sm:mb-0 sm:mr-2">
					<select name="month" id="month" x-model="$store.brmData.formData.month" class="rounded-md">
							<option value="" disabled>Mois</option>
			  		<?php foreach (Month::cases() as $month): ?>
							<option value="<?= $month->value ?>"><?= $month->frenchName() ?></option>
			  		<?php endforeach; ?>
					</select>
				</div>

				<div class="flex flex-col mb-4 sm:mb-0 sm:mr-2">
					<select name="country" id="country" x-model="$store.brmData.formData.country" class="rounded-md">
							<option value="" disabled>Pays</option>
			  		<?php foreach (Country::cases() as $country): ?>
							<option value="<?= $country->value ?>"><?= $country->frenchName() ?></option>
			  		<?php endforeach; ?>
					</select>
				</div>

			</div>

			<button :disabled="isSubmitBtnDisabled" x-text="filterBtnTxt" class="bg-indigo-500 disabled:bg-indigo-100 text-white px-8 py-2 rounded-md hover:bg-indigo-600 self-end sm:self-start md:self-auto">Filtrer</button>

		</form>

		<!-- results list container -->
		<div class="relative mx-auto max-w-5xl w-full">

			<!-- error container -->
			<div x-data
					 x-show="!$store.brmData.isLoadingData && $store.brmData.fetchError"
					 x-transition:enter="transition origin-top ease-out duration-700"
					 x-transition:enter-start="opacity-0"
					 x-transition:enter-end="opacity-100"
					 x-transition:leave="transition origin-top ease-out duration-500"
					 x-transition:leave-start="opacity-100"
					 x-transition:leave-end="opacity-0"
					 class="p-3 w-full absolute">
				<p class="bg-red-500 text-red-50 p-4 rounded-md">Erreur durant la récupération des données.</p>
			</div>

			<!-- loading container -->
			<div x-data
					 x-show="$store.brmData.isLoadingData"
					 x-transition:enter="transition origin-top ease-out duration-700"
					 x-transition:enter-start="transform -translate-y-12 opacity-0"
					 x-transition:enter-end="transform translate-y-0 opacity-100"
					 x-transition:leave="transition origin-top ease-out duration-500"
					 x-transition:leave-start="transform translate-y-0 opacity-100"
					 x-transition:leave-end="transform -translate-y-12 opacity-0"
					 class="p-3 w-full absolute">
				<p class="bg-white p-4 rounded-md">Récupération des données...</p>
			</div>

			<!-- results container -->
			<div x-data="brmList"
					 x-show="!$store.brmData.isLoadingData && !$store.brmData.fetchError"
					 x-transition:enter="transition origin-top ease-out duration-700"
					 x-transition:enter-start="transform translate-y-4 opacity-0"
					 x-transition:enter-end="transform translate-y-0 opacity-100"
					 x-transition:leave="transition origin-top ease-out duration-500"
					 x-transition:leave-start="transform translate-y-0 opacity-100"
					 x-transition:leave-end="transform translate-y-6 opacity-0"
					 id="results">

				<!-- top pagination-->
				<div class="flex justify-between items-center p-3 w-full">

					<div>
						<button @click="firstPage" class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&lt;&lt;</button>
						<button @click="prevPage" class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&lt;</button>
					</div>

					<div x-text="paginationInfoText" class="font-bold"></div>

					<div>
						<button @click="nextPage" class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&gt;</button>
						<button @click="lastPage" class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&gt;&gt;</button>
					</div>

				</div>

				<!-- results -->
				<ul class="p-3 w-full">
					<template x-for="brm in $store.brmData.brmData" :key="brm.id">
						<li class="bg-white shadow mb-4 px-4 py-5 last:mb-0">
							<div class="flex">
								<div x-text="brm.date" class="bg-slate-500 text-white rounded px-2 py-1 text-sm mr-1"></div>
								<div x-text="formatDistance(brm.distance)" :class="distanceClass(brm.distance)" class="text-white rounded px-2 py-1 text-sm mr-1"></div>
								<template x-if="brm.elevation > 0">
									<div x-text="formatElevation(brm.elevation)" class="px-2 py-1 text-sm"></div>
								</template>
							</div>
							<p x-text="formatContact(brm)"></p>
						</li>
					</template>
				</ul>

				<!-- bottom pagination -->
				<div class="flex justify-between items-center p-4 mb-10 w-full">

					<div>
						<button @click="firstPage" class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&lt;&lt;</button>
						<button @click="prevPage" class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&lt;</button>
					</div>

					<div x-text="paginationInfoText" class="font-bold"></div>

					<div>
						<button @click="nextPage" class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&gt;</button>
						<button @click="lastPage" class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&gt;&gt;</button>
					</div>

				</div>

			</div>

		</div>

		<?php if ($isDevEnv): ?>
			<script type="module" src="http://localhost:5173/@vite/client"></script>
			<script type="module" src="http://localhost:5173/main.js"></script>
		<?php else: ?>
			<script src="/assets/main.js" defer></script>
		<?php endif; ?>

	</body>

</html>


