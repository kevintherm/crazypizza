import 'air-datepicker/air-datepicker.css';
import localeEn from 'air-datepicker/locale/en';

import './bootstrap';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import grow from 'alpinejs-textarea-grow';
import anchor from '@alpinejs/anchor';

import { alpineBanner as banner, alpineModal as modal, alpineToast as toast } from './notifiers';
import { createDataTableStore, fileUploaderStore, prefs } from './stores';
import { combobox } from './components';
import AirDatepicker from 'air-datepicker';

import Chart from 'chart.js/auto';

const IMAGE_NOT_FOUND = "https://placehold.co/300";
window.IMAGE_NOT_FOUND = IMAGE_NOT_FOUND;

const DEFAULT_SELECT_OPTION = { label: 'All', value: 'All' };
window.DEFAULT_SELECT_OPTION = DEFAULT_SELECT_OPTION;

const CURRENCY_CODE = "usd";
window.CURRENCY_CODE = CURRENCY_CODE;

Alpine.plugin(grow);
Alpine.plugin(anchor);
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(mask);

Alpine.store('notifiers', {
    banner, modal, toast
});

Alpine.store('fileUploader', fileUploaderStore);
window.createDataTableStore = createDataTableStore;

Alpine.store('when', {
    imageError: (e) => {
        const element = e.target;
        element.src = IMAGE_NOT_FOUND;
        element.title = "Failed to load image.";
        element.alt = "Failed to load image.";
    },
    inputNumber: (e) => {
        const input = e.target;
        input.value = input.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    },
    displayMoney: new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: CURRENCY_CODE,
    }).format
});

Alpine.data('combobox', combobox);
Alpine.store('prefs', prefs);

window.DatePicker = (el, options = {}) => new AirDatepicker(el, {
    locale: localeEn,
    timepicker: true,
    buttons: [
        {
            content: 'Today',
            onClick(dp) {
                let today = new Date()
                dp.selectDate(today)
                dp.setViewDate(today)
            }
        },
        {
            content: 'Clear',
            onClick(dp) {
                dp.clear()
            }
        }
    ],
    dateFormat: 'yyyy-MM-dd',
    timeFormat: 'HH:mm:00',
    ...options
});

window.Chart = Chart;

window.$ = Alpine;

Alpine.start();
