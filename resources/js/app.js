import './bootstrap';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

const alpineToast = () => {
    return (detail = {}) => {
        if (!detail.variant) throw new Error("Variant parameter is required.");
        if (!detail.title) throw new Error("Title parameter is required.");
        if (!detail.message) throw new Error("Message parameter is required.");
        if (detail.sender && !detail.sender.name) throw new Error("Sender name is required.");
        if (detail.sender && !detail.sender.avatar) throw new Error("Sender avatar is required.");

        window.dispatchEvent(new CustomEvent('notify', { detail }));
    };
}

const alpineBanner = () => {
    return (detail = {}) => {
        if (!detail.message) throw new Error("Message parameter is required.");

        window.dispatchEvent(new CustomEvent('banner', { detail }));
    };
}

const alpineModal = () => {
    return (selector, action) => {
        if (!action) throw new Error("Action is required.");

        if (selector instanceof Element) {
            selector.dispatchEvent(new CustomEvent('modal', { detail: { action } }));
        } else {
            document.querySelector(selector).dispatchEvent(new CustomEvent('modal', { detail: { action } }));
        }

    };
}

Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(mask);

Alpine.magic('toast', alpineToast);
Alpine.magic('modal', alpineModal);

Alpine.notifier = {};
Alpine.notifier.toast = (detail) => alpineToast()(detail);
Alpine.notifier.modal = (selector, action) => alpineModal()(selector, action);
Alpine.notifier.banner = (detail) => alpineBanner()(detail);

Alpine.data('skeletonLoader', (rows = 2, cols = 6, details = {}) => ({
    loading: true,
    rows,
    cols,
    details,
    items: [],

    get data() {
        return Array.from({ length: this.rows }, () =>
            Array.from({ length: this.cols })
        );
    },
}));


window.$ = Alpine;

Alpine.start();
