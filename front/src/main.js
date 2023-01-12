import './style.css'
import Alpine from 'alpinejs';
import linkifyStr from 'linkify-string';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {

	//// STORE ////

	Alpine.store('brmData', {
		brmData: [],
		paginationData: null,
		formData: {
			action: 'getData',
			distance: '',
			month: '',
			country: '',
			page: ''
		},
		isLoadingData: false,
		fetchError: false,

		init() { },

		async submitForm() {
			if (this.isLoadingData) return;

			this.isLoadingData = true;
			this.fetchError = false;

			// oneliner timer :) for better smooth transition between states
			await new Promise(resolve => setTimeout(resolve, 1500));

			const resData = this.fetchData(this.formData);

			resData.then((data) => {
				if (data.isError) {
					this.fetchError = true;
				} else {
					this.updateListData(data);
				}
				this.isLoadingData = false;
			});
		},

		async fetchData(formData) {
			const res = await fetch(import.meta.env.PUBLIC_API_URL, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams(formData)
			});

			if (res.status !== 200 || !res.ok) {
				return {
					isError: true,
					pagination: null,
					data: null,
				};
			}

			let content = null;

			try {
				content = await res.json();
			} catch (e) {
				return {
					isError: true,
					pagination: null,
					data: null,
				};
			}

			return {
				isError: false,
				pagination: content.pagination,
				data: content.data,
			};
		},

		updateListData(data) {
			this.brmData = (data.data == null) ? [] : data.data;
			this.paginationData = data.pagination;
		}
	});

	//// DATA ////

	Alpine.data('filterFormHandler', () => ({

		submit() {
			Alpine.store('brmData').formData.page = '';
			Alpine.store('brmData').submitForm();
		},

		get isSubmitBtnDisabled() {
			const store = Alpine.store('brmData');
			return store.formData.distance === '' ||
				store.formData.month === '' ||
				store.formData.country === '' ||
				store.isLoadingData;
		},

		get filterBtnTxt() {
			return Alpine.store('brmData').isLoadingData ? '•••' : 'Filtrer';
		}

	}));

	Alpine.data('brmList', () => ({

		init() {

		},

		nextPage() {
			const store = Alpine.store('brmData');
			if (store.paginationData.pages.nextPageNumber === null) return;
			store.formData.page = store.paginationData.pages.nextPageNumber;
			store.submitForm();
		},

		lastPage() {
			const store = Alpine.store('brmData');
			if (store.paginationData.pages.isLastPage) return;
			store.formData.page = store.paginationData.pages.lastPageNumber;
			store.submitForm();
		},

		prevPage() {
			const store = Alpine.store('brmData');
			if (store.paginationData.pages.prevPageNumber === null) return;
			store.formData.page = store.paginationData.pages.prevPageNumber;
			store.submitForm();
		},

		firstPage() {
			const store = Alpine.store('brmData');
			if (store.paginationData.pages.isFirstPage) return;
			store.formData.page = store.paginationData.pages.firstPageNumber;
			store.submitForm();
		},

		formatDistance(data) { return `${data}`; },

		formatElevation(data) { return `${data} D+`; },

		formatWebsite(data) {
			console.log(data);
			return linkifyStr(data, {
				attributes: {
					title: 'lien externe',
				},
				//className: 'new-link--url',
				truncate: 40,
				nl2br: true,
			});
		},

		formatRoadmap(data) {
			console.log(data);
			return linkifyStr(data, {
				attributes: {
					title: 'lien externe',
				},
				//className: 'new-link--url',
				truncate: 40,
				nl2br: true,
			});
		},

		formatLocation(data) {
			let location = data.country;
			location += data.region !== '' ? ` — ${data.region}` : '';
			location += data.county !== '' ? ` — ${data.county}` : '';
			location += data.city !== '' ? ` — ${data.city}` : '';
			return location;
		},

		get paginationInfoText() {
			const store = Alpine.store('brmData');

			if (store.paginationData == null) return '';
			if (store.brmData.length === 0) return '0 — 0 / 0';

			return store.paginationData.items.pageFirstItemIndex +
				' — ' + store.paginationData.items.pageLastItemIndex +
				' / ' + store.paginationData.items.totalItems;
		}

	}));

	//// LAUNCH ////

	Alpine.store('brmData').formData.distance = 0;
	Alpine.store('brmData').formData.month = 0;
	Alpine.store('brmData').formData.country = 'world';
	Alpine.store('brmData').submitForm();

});

Alpine.start();


