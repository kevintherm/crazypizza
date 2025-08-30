export const alpineToast = (detail = {}) => {
    if (!detail.variant) throw new Error("Variant parameter is required.");
    if (!detail.title) throw new Error("Title parameter is required.");
    if (!detail.message) throw new Error("Message parameter is required.");
    if (detail.sender && !detail.sender.name) throw new Error("Sender name is required.");
    if (detail.sender && !detail.sender.avatar) throw new Error("Sender avatar is required.");

    window.dispatchEvent(new CustomEvent('notify', { detail }));
};

export const alpineBanner = (detail = {}) => {
    if (!detail.message) throw new Error("Message parameter is required.");

    window.dispatchEvent(new CustomEvent('banner', { detail }));
};

export const alpineModal = (selector, action) => {
    if (!action) throw new Error("Action is required.");

    if (selector instanceof Element) {
        selector.dispatchEvent(new CustomEvent('modal', { detail: { action } }));
    } else {
        document.querySelector(selector).dispatchEvent(new CustomEvent('modal', { detail: { action } }));
    }

};
