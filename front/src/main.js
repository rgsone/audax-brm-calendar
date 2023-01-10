import './style.css'
import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {

	//// STORE ////

	Alpine.store('brmData', {
		hasData: false,
		data: null,
		filterBtnText: 'Filtrer',
		isFilterBtnDisabled: false,

		init() {
			this.filterBtnText = '•••';
			this.isFilterBtnDisabled = true;
			const data = this.fetchData({

			});
			this.updateListData(data);
		},

		async fetchData(formData) {
			//const response = await fetch()
		},

		updateListData(data) {
			// handle dist data
		}
	});

	//// DATA ////

	Alpine.data('filterFormHandler', () => ({

		formData: {
			distance: '',
			month: '',
			country: ''
		},

		submit() {
			console.log(this.formData);

			// test if data are defined
			if (this.formData.distance === '' || this.formData.month === '' || this.formData.country === '') return;

			console.log('send data');
		},

		get isSubmitBtnDisabled() {
			return this.formData.distance === '' || this.formData.month === '' || this.formData.country === '' || Alpine.store('brmData').isFilterBtnDisabled;
		}

	}));

	Alpine.data('brmList', () => ({
		init() {

		}
	}));

});

Alpine.start();


