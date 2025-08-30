import './bootstrap';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

const IMAGE_NOT_FOUND = "https://placehold.co/300";

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

Alpine.when = {};
Alpine.when.imageError = (e) => {
    const element = e.target;
    element.src = IMAGE_NOT_FOUND;
    element.title = "Failed to load image.";
    element.alt = "Failed to load image.";
}

Alpine.store('fileUploader', (options = {}) => ({
    // Overrideable by options
    fileInputElement: null,
    allowedTypes: ['image/jpeg', 'image/png', 'image/webp'],
    maxFiles: 1, // Max files count
    maxSize: 3 * 1024 * 1024, // Bytes
    maxDimensions: {
        width: 9999,
        height: 9999
    },

    ...options,

    files: [],
    dragActive: false,
    errors: {},

    dropFile(event) {
        this.dragActive = false;
        this.validateAndAddFiles(event.dataTransfer.files);
    },

    handleFiles(fileList) {
        this.validateAndAddFiles(fileList);
    },

    removeFile(index) {
        this.files.splice(index, 1);
        this.updateFileInput();
    },

    clearFiles() {
        this.files = [];
        this.errors = {};
        this.dragActive = false;
        this.updateFileInput();
    },

    dragOver() {
        this.dragActive = true;
    },
    dragLeave() {
        this.dragActive = false;
    },

    async validateAndAddFiles(newFiles) {
        this.errors = {};

        const checkImageDimensions = (file) => new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    (img.width > this.maxDimensions.width || img.height > this.maxDimensions
                        .height) ?
                        reject(
                            `Image dimensions exceed ${this.maxDimensions.width}x${this.maxDimensions.height}px`
                        ) :
                        resolve();
                };
                img.onerror = () => reject('Could not read image dimensions.');
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        const validations = Array.from(newFiles).map(async (file) => {
            if (!this.allowedTypes.includes(file.type))
                throw `Invalid file type. Allowed: ${this.allowedTypes.join(', ')}`;

            if (file.size > this.maxSize)
                throw `File is too large (max ${this.maxSize / 1024 / 1024}MB)`;

            if (file.type.startsWith('image/'))
                await checkImageDimensions(file);

            return {
                file,
                type: file.type.startsWith('image/') ? 'image' : 'file'
            };
        });

        const results = await Promise.allSettled(validations);

        results.forEach((result, i) => {
            if (result.status === 'fulfilled') {

                if (this.files.length < this.maxFiles) this.files.push(result.value);
                else this.errors[newFiles[i].name] = "Max files exceeded!";

            } else {
                this.errors[newFiles[i].name] = result.reason;
            }
        });

        this.updateFileInput();
    },

    updateFileInput() {
        const dataTransfer = new DataTransfer();
        this.files.forEach(fileWrapper => {
            dataTransfer.items.add(fileWrapper.file);
        });

        if (this.fileInputElement) {
            this.fileInputElement.files = dataTransfer.files;
        } else {
            throw new Error("File Input is required.");
        }

    }
}));

window.$ = Alpine;

Alpine.start();
