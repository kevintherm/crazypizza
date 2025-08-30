import './bootstrap';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import grow from 'alpinejs-textarea-grow'

import { alpineBanner, alpineModal, alpineToast } from './notifiers';
import { fileUploaderStore, dataTableStore } from './stores';

const IMAGE_NOT_FOUND = "https://placehold.co/300";
window.IMAGE_NOT_FOUND = IMAGE_NOT_FOUND

Alpine.plugin(grow);
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(mask);

Alpine.store('notifiers', {
    alpineBanner, alpineModal, alpineToast
});

Alpine.store('fileUploader', fileUploaderStore);
Alpine.store('dataTable', dataTableStore);

Alpine.store('when', {
    imageError: (e) => {
        const element = e.target;
        element.src = IMAGE_NOT_FOUND;
        element.title = "Failed to load image.";
        element.alt = "Failed to load image.";
    }
});

window.$ = Alpine;

Alpine.start();
