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
 * Creates a reactive data table store for Alpine.js.
 *
 * @param {object} userConfig - User-defined configuration to override defaults.
 * @param {object} userConfig.routes - API endpoints for fetch, create/update, delete, etc.
 * @param {object[]} userConfig.columns - Column definitions for the table.
 * @param {object} [userConfig.listeners] - Callbacks for lifecycle events.
 * @param {function} [userConfig.listeners.onBeforeFetch] - Fires before data is fetched.
 * @param {function} [userConfig.listeners.onAfterFetch] - Fires after data is successfully fetched.
 * @param {function} [userConfig.listeners.onFetchError] - Fires when a fetch error occurs.
 * @param {function} [userConfig.listeners.onBeforeCreate] - Fires before a new item is created. Return false to cancel.
 * @param {function} [userConfig.listeners.onAfterCreate] - Fires after a new item is successfully created.
 * @param {function} [userConfig.listeners.onBeforeUpdate] - Fires before an item is updated. Return false to cancel.
 * @param {function} [userConfig.listeners.onAfterUpdate] - Fires after an item is successfully updated.
 * @param {function} [userConfig.listeners.onBeforeDelete] - Fires before an item is deleted. Return false to cancel.
 * @param {function} [userConfig.listeners.onAfterDelete] - Fires after an item is successfully deleted.
 * @param {function} [userConfig.listeners.onBeforeBulkDelete] - Fires before bulk deletion. Return false to cancel.
 * @param {function} [userConfig.listeners.onAfterBulkDelete] - Fires after successful bulk deletion.
 * @param {function} [userConfig.listeners.onSortChange] - Fires after the sort column or direction changes.
 * @param {function} [userConfig.listeners.onFilterChange] - Fires after filters are applied.
 * @param {function} [userConfig.listeners.onSelectionChange] - Fires when the set of selected IDs changes.
 * @returns {object} The Alpine.js store object.
 */
export const createDataTableStore = (userConfig) => {
    // --- PRIVATE HELPERS ---

    /** Triggers a configured listener if it exists. */
    const _trigger = function (eventName, ...args) {
        if (typeof this.config.listeners[eventName] === 'function') {
            return this.config.listeners[eventName].apply(this, args);
        }
        return true; // Default to allow action
    };

    /** Merges user config with defaults. */
    const _mergeConfig = (config) => {
        const defaults = {
            selectors: {
                viewImage: '#view-image-modal',
                bulkDelete: '#bulk-delete-modal',
                confirm: '#confirm-modal',
                error: '#error-modal',
                createUpdate: '#create-update-modal',
                filters: '#filters-modal'
            },
            initialSort: { column: 'created_at', desc: true },
            initialFilters: { appliedFilters: [], availableFilters: [] },
            actions: { view: true, update: true, delete: true, create: true },
            itemName: 'item',
            itemIdentifier: 'name',
            listeners: {} // All listeners are opt-in
        };
        // A deep merge could be used here for more complex configs
        return { ...defaults, ...config, selectors: { ...defaults.selectors, ...config.selectors } };
    };

    // --- STORE DEFINITION ---

    return {
        // --- CONFIG & STATE ---
        config: _mergeConfig(userConfig),
        items: [],
        selectedIds: [],
        selectedItem: {},
        loading: true,
        checkAll: false,
        loadingRows: 5,
        sort: null,
        sortDesc: null,
        searchTerm: '',
        appliedFilters: [],
        availableFilters: [],

        // Component Modules (will be initialized in init)
        createUpdate: null,
        confirm: null,
        bulkDelete: null,
        error: null,
        nav: null,
        viewImage: null,
        filters: null,

        // --- INITIALIZATION ---
        init() {
            // Set initial state from config
            this.sort = this.config.initialSort?.column || 'created_at';
            this.sortDesc = this.config.initialSort?.desc === true;
            this.appliedFilters = this.config.initialFilters?.appliedFilters || [];
            this.availableFilters = this.config.initialFilters?.availableFilters || [];

            // Initialize modules
            this.initNav();
            this.initModalHandlers();
        },

        // --- GETTERS ---
        get loadSkeleton() {
            return Array.from({ length: this.loadingRows }, () => this.config.columns);
        },

        // --- CORE METHODS ---
        fetchData() {
            if (_trigger.call(this, 'onBeforeFetch') === false) return;
            this.loading = true;
            this.updateItems([]);

            const params = {
                page: this.nav.currentPage,
                per_page: this.nav.perPage || null,
                sort: this.sort,
                sort_desc: this.sortDesc,
                search: this.searchTerm,
                filters: this.appliedFilters
            };

            axios.get(this.config.routes.fetch, { params })
                .then(res => {
                    const api = res.data.data;
                    this.updateItems(api.data);
                    this.checkAll = false;
                    Object.assign(this.nav, {
                        currentPage: api.current_page,
                        pages: api.pages,
                        total: api.total,
                        perPage: api.per_page,
                        hasNextPage: api.has_next_page,
                        hasPreviousPage: api.has_previous_page
                    });
                    _trigger.call(this, 'onAfterFetch', api);
                })
                .catch(err => {
                    _trigger.call(this, 'onFetchError', err);
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
            this.confirm.show();

            this.confirm.onConfirm = () => {
                const deletePayload = { id, column };
                if (_trigger.call(this, 'onBeforeDelete', deletePayload) === false) {
                    this.confirm.hide();
                    return;
                }

                this.handleApiCall(
                    axios.delete(this.config.routes.delete, { data: deletePayload }),
                    () => {
                        if (column) {
                            const itemIndex = this.items.findIndex(i => i.id === id);
                            if (itemIndex > -1) this.items[itemIndex][column] = null;
                            this.selectedItem = this.items[itemIndex];
                        } else {
                            this.updateItems(this.items.filter(item => item.id !== id));
                        }
                        _trigger.call(this, 'onAfterDelete', deletePayload);
                        this.confirm.hide();
                    },
                    () => this.confirm.hide()
                );
            };
        },

        // --- UI EVENT HANDLERS ---
        onCheckAll() {
            this.selectedIds = this.checkAll ? this.items.map((item) => item.id) : [];
        },

        onCheckSingle(e) {
            const id = parseInt(e.target.id);

            _trigger.call(this, 'onSelectionChange', id);

            if (e.target.checked) {
                this.selectedIds.push(id);
            } else {
                this.selectedIds = this.selectedIds.filter((selectedId) => selectedId !== id);
            }
        },

        toggleSort(colName) {
            if (!this.config.sortables?.hasOwnProperty(colName)) return;

            const newSortColumn = this.config.sortables[colName];
            if (this.sort === newSortColumn) {
                this.sortDesc = !this.sortDesc;
            } else {
                this.sort = newSortColumn;
                this.sortDesc = false;
            }
            _trigger.call(this, 'onSortChange', { sort: this.sort, sortDesc: this.sortDesc });
            this.fetchData();
        },

        // --- MODULE INITIALIZERS & HELPERS ---
        initNav() {
            this.nav = {
                currentPage: 1,
                pages: [],
                hasNextPage: false,
                hasPreviousPage: false,
                perPage: userConfig.perPage || 10,
                total: 0,
                goTo: (page) => {
                    if (this.nav.pages.includes(page)) {
                        this.nav.currentPage = page;
                        this.fetchData();
                    }
                },
                prev: () => { if (this.nav.hasPreviousPage) { this.nav.currentPage--; this.fetchData(); } },
                next: () => { if (this.nav.hasNextPage) { this.nav.currentPage++; this.fetchData(); } },
                changePerPage: () => { this.nav.currentPage = 1; this.fetchData(); }
            };
        },

        initModalHandlers() {
            this.error = this.createModalHandler(this.config.selectors.error);
            this.viewImage = this.createModalHandler(this.config.selectors.viewImage, this.getViewImageMethods());
            this.confirm = this.createModalHandler(this.config.selectors.confirm, this.getConfirmMethods());
            this.bulkDelete = this.createModalHandler(this.config.selectors.bulkDelete, this.getBulkDeleteMethods());
            this.createUpdate = this.createModalHandler(this.config.selectors.createUpdate, this.getCreateUpdateMethods());
            this.filters = this.createModalHandler(this.config.selectors.filters, this.getFiltersMethods());
        },

        getCreateUpdateMethods() {
            return {
                open: (data = {}) => {
                    _trigger.call(this, 'onOpenCreateUpdate', data);
                    this.selectedItem = {};
                    window.dispatchEvent(new CustomEvent("form-clear"));
                    Object.assign(this.selectedItem, data);
                    this.createUpdate.show();
                },
                process: (e) => {
                    const isUpdate = !!this.selectedItem.id;
                    const eventPrefix = isUpdate ? 'Update' : 'Create';
                    if (!this.config.actions[eventPrefix.toLowerCase()]) return;

                    const formData = new FormData(e.target);
                    if (_trigger.call(this, `onBefore${eventPrefix}`, formData, this.selectedItem) === false) return;
                    this.handleApiCall(
                        axios.post(this.config.routes.createUpdate, formData),
                        (res) => {
                            this.createUpdate.hide();
                            const updatedItem = res.data.data;
                            const foundIndex = this.items.findIndex((item) => item.id === updatedItem.id);

                            if (foundIndex === -1) {
                                this.updateItems([updatedItem, ...this.items]);
                            } else {
                                this.items.splice(foundIndex, 1, updatedItem);
                            }
                            _trigger.call(this, `onAfter${eventPrefix}`, updatedItem);
                        }
                    );
                }
            };
        },

        getBulkDeleteMethods() {
            return {
                open: () => {
                    if (!this.config.actions.delete || this.selectedIds.length === 0) return;
                    const listString = this.items
                        .filter(item => this.selectedIds.includes(item.id))
                        .map(item => `[${item.id}] ${item[this.config.itemIdentifier]}`)
                        .join(',\n');
                    this.bulkDelete.setMessage(`Delete the following items?\n${listString}`, '.bulk-delete-message');
                    this.bulkDelete.show();
                },
                handleConfirm: (e) => {
                    e.preventDefault();
                    if (_trigger.call(this, 'onBeforeBulkDelete', this.selectedIds) === false) {
                        this.bulkDelete.hide();
                        return;
                    }
                    this.handleApiCall(
                        axios.delete(this.config.routes.bulkDelete, { data: { ids: this.selectedIds } }),
                        () => {
                            this.updateItems(this.items.filter((item) => !this.selectedIds.includes(item.id)));
                            const deletedIds = [...this.selectedIds];
                            this.selectedIds = [];
                            _trigger.call(this, 'onAfterBulkDelete', deletedIds);
                            this.bulkDelete.hide();
                        },
                        () => this.bulkDelete.hide()
                    );
                }
            };
        },

        getConfirmMethods() {
            return {
                onConfirm: null,
                handleConfirm: (e) => {
                    e.preventDefault();
                    if (typeof this.confirm.onConfirm === "function") {
                        this.confirm.onConfirm(e);
                        this.confirm.onConfirm = null; // Prevent re-triggering
                    }
                }
            };
        },

        getViewImageMethods() {
            return {
                open(source, title = "") {
                    const image = document.querySelector(`${this.config.selectors.viewImage} img`);
                    if (!source || !image) return;
                    image.src = source;
                    image.alt = image.title = title;
                    this.viewImage.show();
                }
            };
        },

        getFiltersMethods() {
            return {
                process: (e) => {
                    const formData = new FormData(e.target);
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

                    this.filters.hide();
                    _trigger.call(this, 'onFilterChange', result);
                    if (result.length) this.fetchData();
                    this.filters.hide();
                },
                remove: (filterToRemove) => {
                    this.appliedFilters = this.appliedFilters.filter(f => f.column !== filterToRemove.column);
                    _trigger.call(this, 'onFilterChange', this.appliedFilters);
                    this.fetchData();
                }
            };
        },


        // --- UTILITY METHODS ---

        updateItems(newItems = []) {
            this.loadingRows = this.items.length > 0 ? this.items.length : Math.max(this.nav.perPage, 1);
            this.items = newItems;
        },

        createModalHandler(selector, customMethods = {}) {
            const element = document.querySelector(selector);
            if (!element) {
                console.warn(`Modal element with selector "${selector}" not found.`);
                return { show: () => { }, hide: () => { }, setMessage: () => { } };
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
            const boundCustomMethods = Object.entries(customMethods).reduce((acc, [key, value]) => {
                acc[key] = typeof value === 'function' ? value.bind(this) : value;
                return acc;
            }, {});
            return { ...base, ...boundCustomMethods };
        },

        handleApiCall(promise, successCallback, errorCallback = null, toastOnSuccess = true) {
            this.loading = true;
            promise
                .then(res => {
                    if (toastOnSuccess) {
                        toast({ variant: 'success', title: 'Success', message: res?.data?.message || 'Success!' });
                    }
                    if (successCallback) successCallback(res);
                })
                .catch(err => {
                    toast({ variant: 'danger', title: 'Oops...', message: err?.response?.data?.message || err.message });
                    if (errorCallback) errorCallback(err);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    };
};
