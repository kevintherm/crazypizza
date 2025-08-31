import './bootstrap';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import grow from 'alpinejs-textarea-grow';
import anchor from '@alpinejs/anchor';

import { alpineBanner as banner, alpineModal as modal, alpineToast as toast } from './notifiers';
import { fileUploaderStore, dataTableStore } from './stores';

const IMAGE_NOT_FOUND = "https://placehold.co/300";
window.IMAGE_NOT_FOUND = IMAGE_NOT_FOUND;

const DEFAULT_SELECT_OPTION = {label: 'All', value: 'All'};
window.DEFAULT_SELECT_OPTION = DEFAULT_SELECT_OPTION;

Alpine.plugin(grow);
Alpine.plugin(anchor);
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(mask);

Alpine.store('notifiers', {
    banner, modal, toast
});

Alpine.store('fileUploader', fileUploaderStore);
Alpine.store('dataTable', dataTableStore);

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
    }
});

Alpine.data('combobox', (comboboxData = {
    allOptions: [], selectedOption: null
},) => ({
    options: comboboxData.allOptions,
    isOpen: false,
    openedWithKeyboard: false,
    selectedOption: comboboxData.selectedOption,
    setSelectedOption(option) {
        this.selectedOption = option
        this.isOpen = false
        this.openedWithKeyboard = false
        this.$refs.hiddenTextField.value = option.value
    },
    getFilteredOptions(query) {
        this.options = comboboxData.allOptions.filter((option) =>
            option.label.toLowerCase().includes(query.toLowerCase()),
        )
        if (this.options.length === 0) {
            this.$refs.noResultsMessage.classList.remove('hidden')
        } else {
            this.$refs.noResultsMessage.classList.add('hidden')
        }
    },
    // if the user presses backspace or the alpha-numeric keys, focus on the search field
    handleKeydownOnOptions(event) {
        if ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 48 && event
            .keyCode <= 57) || event.keyCode === 8) {
            this.$refs.searchField.focus()
        }
    },
}))

window.$ = Alpine;

Alpine.start();
