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

	<body class="bg-slate-100 flex flex-col font-sans">

		<h1 class="text-3xl text-[#0A0061] font-bold px-3 pt-3 pb-0 mt-4 max-w-3xl mx-auto w-full">BRM 2023</h1>
		<p class="text-sm text-[#0A0061] italic px-3.5 pb-4 max-w-3xl mx-auto w-full">maj le <?= $lastUpdate ?></p>

		<!-- form filter -->
		<form x-data="filterFormHandler" @submit.prevent="submit" class="flex flex-col md:flex-row max-w-3xl mx-auto w-full p-3">

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
		<div class="relative mx-auto max-w-3xl w-full">

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
						<button @click.prevent="firstPage" class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&lt;&lt;</button>
						<button @click.prevent="prevPage" class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&lt;</button>
					</div>

					<div x-text="paginationInfoText" class="text-[#0A0061] font-bold"></div>

					<div>
						<button @click.prevent="nextPage" class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&gt;</button>
						<button @click.prevent="lastPage" class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&gt;&gt;</button>
					</div>

				</div>

				<!-- results -->
				<ul class="p-3 w-full">
					<template x-for="brm in $store.brmData.brmData" :key="brm.id">
						<li class="flex flex-col md:flex-row prose prose-stone prose-sm max-w-none bg-white shadow-md mb-4 last:mb-0 rounded-xl">

							<div class="flex md:flex-col md:items-center md:justify-center px-3 py-6 md:py-8 md:px-10 rounded-t-xl md:rounded-l-xl md:rounded-tr-none bg-[#E6FCFF] text-[#0A0061]">

								<div class="flex flex-col md:items-center md:order-1 ml-2 md:ml-0 relative">
									<!-- distance -->
									<div x-text="formatDistance(brm.distance)" class="relative md:-rotate-180 md:[writing-mode:vertical-lr] md:my-4 text-7xl md:text-7xl leading-none font-bold"></div>
									<!-- elevation -->
									<template x-if="brm.elevation > 0">
										<div x-text="formatElevation(brm.elevation)"
												 class="text-sm font-bold leading-[0.5] md:leading-none pl-0.5 md:pl-0"></div>
									</template>

								</div>
								<!-- date -->
								<div x-text="brm.date" class="ml-auto mr-2 md:mr-0 md:ml-0 text-2xl md:text-xl font-bold"></div>

							</div>

							<div class="flex flex-col items-start px-3 py-5 md:pl-8 md:py-7 text-[#0A0061]">

								<template x-if="brm.club_name !== ''">
									<p x-text="brm.club_name" class="bg-[#E6FCFF] rounded text-sm font-bold px-2 py-1 m-0"></p>
								</template>

								<p x-text="brm.contact" class="text-xl leading-none font-bold mt-4 md:mt-2 mb-0 ml-1"></p>
								<p x-text="brm.contact_mail" class="text-sm italic my-0 ml-1"></p>
								<template x-if="brm.web_site !== ''">
									<p x-html="formatWebsite(brm.web_site)" class="text-sm italic my-0 ml-1"></p>
								</template>

								<template x-if="brm.roadmap !== ''">
									<div class="ml-1 mt-6">
										<p class="font-bold text-xs uppercase leading-[0.5] m-0">Trace</p>
										<p x-html="formatRoadmap(brm.roadmap)" class="text-sm m-0"></p>
									</div>
								</template>

								<p x-text="formatLocation(brm)" class="ml-1 mt-6 mb-0 text-sm"></p>

							</div>

						</li>
					</template>
				</ul>

				<!-- bottom pagination -->
				<div class="flex justify-between items-center p-3 w-full">

					<div>
						<button @click.prevent="firstPage" class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&lt;&lt;</button>
						<button @click.prevent="prevPage" class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&lt;</button>
					</div>

					<div x-text="paginationInfoText" class="text-[#0A0061] font-bold"></div>

					<div>
						<button @click.prevent="nextPage" class="border-2 font-bold px-4 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&gt;</button>
						<button @click.prevent="lastPage" class="border-2 font-bold px-3 py-2 rounded-md border-indigo-300 text-indigo-500 hover:bg-indigo-300 hover:text-indigo-900">&gt;&gt;</button>
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


