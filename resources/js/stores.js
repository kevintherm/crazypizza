import { alpineModal as modal, alpineToast as toast } from './notifiers';

/**
 * Creates a file uploader store with configurable options and methods for file validation and management.
 *
 * @param {Object} [options={}] - Optional overrides for default store properties.
 * @param {HTMLInputElement} [options.fileInputElement=null] - The file input DOM element.
 * @param {string[]} [options.allowedTypes=['image/jpeg', 'image/png', 'image/webp']] - Allowed MIME types.
 * @param {number} [options.maxFiles=1] - Maximum number of files allowed.
 * @param {number} [options.maxSize=3145728] - Maximum file size in bytes.
 * @param {Object} [options.maxDimensions={width: 9999, height: 9999}] - Maximum image dimensions.
 * @returns {{
 *   fileInputElement: HTMLInputElement|null,
 *   allowedTypes: string[],
 *   maxFiles: number,
 *   maxSize: number,
 *   maxDimensions: {width: number, height: number},
 *   files: Array<{file: File, type: string}>,
 *   dragActive: boolean,
 *   errors: Object,
 *   dropFile(event: DragEvent): void,
 *   handleFiles(fileList: FileList): void,
 *   removeFile(index: number): void,
 *   clearFiles(): void,
 *   dragOver(): void,
 *   dragLeave(): void,
 *   validateAndAddFiles(newFiles: FileList|File[]): Promise<void>,
 *   updateFileInput(): void
 * }}
 * Store object for managing file uploads, validation, and drag-and-drop events.
 */
export const fileUploaderStore = (options = {}) => ({
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
});

/**
 * Creates a reusable Alpine.js store for managing a data table.
 * Usage:
 * On Element: $store.{manager}, direct access if you set it to x-data.
 * On Script: Alpine.store('{manager}')
 * @param {object} config - Configuration object for the data table.
 * @param {Array<object>} config.columns - Column definitions. E.g., [{ name: 'Name', class: 'w-64' }]
 * @param {object} config.sortables - Mapping of column display names to database fields. E.g., { 'Name': 'name' }
 * @param {object} config.routes - API endpoints for CRUD operations.
 * @param {string} config.routes.fetch - URL for fetching paginated data.
 * @param {string} config.routes.createUpdate - URL for creating/updating an item.
 * @param {string} config.routes.delete - URL for deleting a single item.
 * @param {string} config.routes.bulkDelete - URL for deleting multiple items.
 * @param {object} [config.selectors] - CSS selectors for modals.
 * @param {object} [config.initialSort] - Default sorting configuration.
 * @param {string} [config.itemName='item'] - Singular name for items (e.g., 'ingredient').
 * @param {string} [config.itemIdentifier='name'] - Property of the item to use in display messages.
 * @returns {object} An Alpine.js store object.
 */
export const dataTableStore = (config) => ({
    // Merge user config with defaults
    config: {
        selectors: {
            viewImage: '#view-image-modal',
            bulkDelete: '#bulk-delete-modal',
            confirm: '#confirm-modal',
            error: '#error-modal',
            createUpdate: '#create-update-modal',
            filters: '#filters-modal'
        },
        initialSort: {
            column: 'created_at',
            desc: true
        },
        initialFilters: {
            appliedFilters: [],
            availableFilters: [],
        },
        actions: {
            view: true,
            edit: true,
            delete: true
        },
        itemName: 'item',
        itemIdentifier: 'name',
        ...config
    },

    // Core State
    items: [],
    selectedIds: [],
    selectedItem: {},
    loading: true,
    checkAll: false,
    loadingRows: 3,

    // Sorting State
    sort: config.initialSort?.column || 'created_at',
    sortDesc: config.initialSort?.desc === true,

    // Search & Filtering State
    searchTerm: '',
    appliedFilters: config.initialFilters?.appliedFilters || [],
    availableFilters: config.initialFilters?.availableFilters || [],

    // Component Modules
    createUpdate: null,
    confirm: null,
    bulkDelete: null,
    error: null,
    nav: null,
    viewImage: null,
    filters: null,

    // Initialization
    init() {
        // Modal Handlers
        this.filters = this.createModalHandler(this.config.selectors.filters, {
            remove: (appliedFilter) => {
                this.appliedFilters = this.appliedFilters.filter(filter => !this.compareObjects(filter, appliedFilter));
                this.fetchData();
            },
            process: (event) => {

                const formData = new FormData(event.target);
                const obj = Object.fromEntries(formData.entries());

                // Group key and values
                const grouped = Object.entries(obj).reduce((acc, [fullKey, value]) => {
                    const [group, subKey] = fullKey.split('.');
                    if (!acc[group]) acc[group] = { column: group };

                    if (subKey) {
                        acc[group][subKey] = value;
                        acc[group].type = 'range';
                    } else {
                        acc[group].value = value;
                        acc[group].type = 'input';
                    }

                    return acc;
                }, {});

                // Build result with updated filter
                const result = Object.values(grouped)
                    .filter(f => {
                        if (f.type === 'input') return !(f.value === '' || f.value == null || String(f.value).toLowerCase() === 'all');
                        else if (f.type === 'range') return f.min != null && f.min !== '' || f.max != null && f.max !== '';
                        return true;
                    })
                    .map(f => {

                        const found = this.availableFilters.find(i => i.column === f.column) || {};
                        f.name = found.name ?? this.snakeToCapitalized(f.column);
                        f.accept = found.accept ?? 'string';

                        if (f.type === 'input' && f.accept === 'bool') {
                            f.displayedValue = Boolean(Number(f.value));
                        } else if (f.type === 'range') {
                            f.displayedValue = `${f.min ?? ''} - ${f.max ?? ''}`;
                        } else {
                            f.displayedValue = String(f.value);
                        }

                        return f;
                    });

                this.appliedFilters = result;

                if (result.length) this.fetchData();
                this.filters.hide();

            }
        });

        this.viewImage = this.createModalHandler(this.config.selectors.viewImage, {
            image: document.querySelector(`${this.config.selectors.viewImage} img`),
            setImage(source, title = "") {
                if (this.viewImage.image) {
                    this.viewImage.image.src = source;
                    this.viewImage.image.title = title;
                    this.viewImage.image.alt = title;
                }
            },
            open(source, title = "") {
                if (!source) return;
                this.viewImage.setImage(source, title);
                this.viewImage.show();
            }
        });

        this.bulkDelete = this.createModalHandler(this.config.selectors.bulkDelete, {
            onConfirm: null,
            handleConfirm: (e) => {
                e.preventDefault();
                if (this.bulkDelete.onConfirm && typeof this.bulkDelete.onConfirm === "function") {
                    if (!this.config.actions.delete) return;
                    this.bulkDelete.onConfirm(e);
                }
            },
            open: () => {
                if (!this.config.actions.delete) return;
                const listString = this.items
                    .filter(item => this.selectedIds.includes(item.id))
                    .map(item => `[${item.id}] ${item[this.config.itemIdentifier]}`)
                    .join(',\n');

                this.bulkDelete.setMessage(`Are you sure you want to delete:\n${listString}?\n\nThis action cannot be undone!`, '.bulk-delete-message');

                this.bulkDelete.onConfirm = () => this.handleApiCall(
                    axios.delete(this.config.routes.bulkDelete, { data: { ids: this.selectedIds } }),
                    (res) => {
                        this.updateItems(this.items.filter((item) => !this.selectedIds.includes(item.id)));
                        this.selectedIds = [];
                        this.fetchData();
                        this.bulkDelete.hide();
                    },
                    () => this.bulkDelete.hide()
                );

                this.bulkDelete.show();
            }
        });

        this.confirm = this.createModalHandler(this.config.selectors.confirm, {
            onConfirm: null,
            handleConfirm: (e) => {
                e.preventDefault();
                if (this.confirm.onConfirm && typeof this.confirm.onConfirm === "function") {
                    this.confirm.onConfirm(e);
                    this.confirm.onConfirm = null;
                }
            }
        });

        this.error = this.createModalHandler(this.config.selectors.error);

        this.createUpdate = this.createModalHandler(this.config.selectors.createUpdate, {
            hide: () => {
                modal(this.createUpdate.element, 'hide');
                this.selectedItem = {};
            },
            clearForm: () => {
                this.selectedItem = {};
                window.dispatchEvent(new CustomEvent("file-upload-clear"));
            },
            open: (data = {}) => {
                this.createUpdate.clearForm();
                Object.assign(this.selectedItem, data);
                this.createUpdate.show();
            },
            process: (e) => {
                if (!this.config.actions.create && !this.config.actions.edit) return;
                const formData = new FormData(e.target);
                this.handleApiCall(
                    axios.post(this.config.routes.createUpdate, formData),
                    (res) => {
                        this.createUpdate.hide();
                        const updatedItem = res.data.data;
                        const foundIndex = this.items.findIndex((item) => item.id === updatedItem.id);

                        if (foundIndex === -1) { // Item is new
                            const newItems = [updatedItem, ...this.items];
                            if (newItems.length > this.nav.perPage) newItems.pop();
                            this.updateItems(newItems);
                        } else { // Item exists
                            const newItems = [...this.items];
                            newItems[foundIndex] = updatedItem;
                            this.updateItems(newItems);
                        }
                    }
                );
            }
        });

        // Pagination
        this.nav = {
            currentPage: 1,
            pages: [],
            hasNextPage: false,
            hasPreviousPage: false,
            perPage: 0,
            total: 0,
            goTo: (page) => {
                if (this.nav.pages.includes(page)) {
                    this.nav.currentPage = page;
                    this.fetchData();
                }
            },
            prev: () => { this.nav.currentPage--; this.fetchData(); },
            next: () => { this.nav.currentPage++; this.fetchData(); },
            changePerPage: () => { this.nav.currentPage = 1; this.fetchData(); }
        };
    },

    // Getters
    get loadSkeleton() {
        return Array.from({ length: this.loadingRows }, () => this.config.columns);
    },

    // Methods
    updateItems(newItems = []) {
        this.loadingRows = this.items.length > 0 ? this.items.length : Math.max(this.nav.perPage, 1);
        this.items = newItems;
        this.checkAll = false;
    },

    onCheckAll() {
        this.selectedIds = this.checkAll ? this.items.map((item) => item.id) : [];
    },

    onCheckSingle(e) {
        const id = parseInt(e.target.id);
        if (e.target.checked) {
            this.selectedIds.push(id);
        } else {
            this.selectedIds = this.selectedIds.filter((selectedId) => selectedId !== id);
        }
        this.checkAll = this.selectedIds.length === this.items.length;
    },

    toggleSort(colName) {
        if (!this.config.sortables.hasOwnProperty(colName)) return;

        const newSortColumn = this.config.sortables[colName];
        if (this.sort === newSortColumn) {
            this.sortDesc = !this.sortDesc;
        } else {
            this.sort = newSortColumn;
            this.sortDesc = false;
        }
        this.fetchData();
    },

    fetchData() {
        this.loading = true;
        this.updateItems([]);
        axios.get(this.config.routes.fetch, {
            params: {
                page: this.nav.currentPage,
                per_page: this.nav.perPage || null,
                sort: this.sort,
                sort_desc: this.sortDesc,
                search: this.searchTerm,
                filters: this.appliedFilters
            }
        })
            .then(res => {
                const api = res.data.data;
                this.updateItems(api.data);
                Object.assign(this.nav, {
                    currentPage: api.current_page,
                    pages: api.pages,
                    total: api.total,
                    perPage: api.per_page,
                    hasNextPage: api.has_next_page,
                    hasPreviousPage: api.has_previous_page
                });
            })
            .catch(err => {
                this.error.show();
                const errorMessage = err?.response?.data?.message || err.message;
                this.error.setMessage(errorMessage, '.error-message');
            })
            .finally(() => {
                this.loading = false;
            });
    },

    deleteItem(id, name = '', column = null) {
        if (!this.config.actions.delete) return;

        const itemName = column || name || `this ${this.config.itemName}`;
        this.confirm.setMessage(`Are you sure you want to delete ${itemName}?`, '.confirm-message');

        this.confirm.onConfirm = () => this.handleApiCall(
            axios.delete(this.config.routes.delete, { data: { id, column } }),
            () => {
                if (column) {
                    const itemIndex = this.items.findIndex(i => i.id === id);
                    if (itemIndex > -1) this.items[itemIndex][column] = null;
                    this.selectedItem = this.items[itemIndex];
                } else {
                    this.updateItems(this.items.filter(item => item.id !== id));
                }
                this.confirm.hide();
            },
            () => this.confirm.hide()
        );

        this.confirm.show();
    },

    // Helpers
    compareObjects(obj1, obj2) {
        // Same reference or primitive equality
        if (obj1 === obj2) return true;

        // Check if both are objects
        if (typeof obj1 !== 'object' || obj1 === null ||
            typeof obj2 !== 'object' || obj2 === null) {
            return false;
        }

        const keys1 = Object.keys(obj1);
        const keys2 = Object.keys(obj2);

        // Different number of keys
        if (keys1.length !== keys2.length) return false;

        // Compare each key recursively
        return keys1.every(key => this.compareObjects(obj1[key], obj2[key]));
    },

    snakeToCapitalized: str => str.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()),

    createModalHandler(selector, customMethods = {}) {
        const element = document.querySelector(selector);
        if (!element) {
            console.warn(`Modal element with selector "${selector}" not found.`);
            // Return a mock object to prevent errors
            return Object.keys(customMethods).reduce((acc, key) => ({ ...acc, [key]: () => { } }), {
                show: () => { }, hide: () => { }, setMessage: () => { }, element: null
            });
        }
        const base = {
            element,
            show: () => modal(element, 'show'),
            hide: () => modal(element, 'hide'),
            setMessage: (message, msgSelector) => {
                const msgEl = element.querySelector(msgSelector);
                if (msgEl) msgEl.innerText = message;
            }
        };
        // Bind custom methods to the new object context
        const boundCustomMethods = Object.entries(customMethods).reduce((acc, [key, value]) => {
            acc[key] = typeof value === 'function' ? value.bind(this) : value;
            return acc;
        }, {});
        return { ...base, ...boundCustomMethods };
    },

    handleApiCall(promise, successCallback, errorCallback = null) {
        this.loading = true;
        promise
            .then(res => {
                const message = res?.data?.message || 'Success!';
                toast({ variant: 'success', title: 'Success', message });
                successCallback(res);
            })
            .catch(err => {
                const message = err?.response?.data?.message || err.message || 'An error occurred.';
                toast({ variant: 'danger', title: 'Oops...', message });
                if (errorCallback) errorCallback(err);
            })
            .finally(() => {
                this.loading = false;
            });
    }
});
