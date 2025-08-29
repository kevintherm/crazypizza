@use('App\Models\Ingredient')

<x-admin.layout>

    <main class="flex flex-col px-6 md:px-12" x-data="$store.mg">

        <div class="w-full h-6"></div>

        <section id="header" class="flex items-center justify-between relative">
            <div>
                <h1 class="text-2xl">Manage Ingredients</h1>
                <p class="opacity-90">Manage your ingredients here.</p>
            </div>
            <div>
                <button type="button" x-on:click="createUpdate.open({})"
                    class="whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-2 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark flex items-center gap-2">
                    Insert
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </div>
            <div class="absolute left-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-24 opacity-20 stroke-primary">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </div>
        </section>

        <div class="w-full h-12"></div>

        <div x-init="fetchData" x-on:refresh.window="fetchData"
            class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark">
            <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                <thead
                    class="border-b border-outline bg-surface-alt text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                    <tr>
                        <th scope="col" class="p-4">
                            <label for="checkAll" class="flex items-center text-on-surface dark:text-on-surface-dark ">
                                <div class="relative flex items-center">
                                    <input type="checkbox" x-model="checkAll" id="checkAll"
                                        class="before:content[''] peer relative size-4 appearance-none overflow-hidden rounded border border-outline bg-surface before:absolute before:inset-0 checked:border-primary checked:before:bg-primary focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark" />
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                                        stroke="currentColor" fill="none" stroke-width="4"
                                        class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-on-primary peer-checked:visible dark:text-on-primary-dark">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                            </label>
                        </th>
                        <th scope="col" class="p-4">#</th>
                        <th scope="col" class="p-4">Image</th>
                        <th scope="col" class="p-4">Name</th>
                        <th scope="col" class="p-4">Stock</th>
                        <th scope="col" class="p-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    <template data-name="when loading" x-if="loading">
                        <template x-for="(row, rowIndex) in loadSkeleton" :key="rowIndex">
                            <tr>
                                <template x-for="(col, colIndex) in row" :key="colIndex">
                                    <td class="p-4">
                                        <div class="h-8 w-full animate-pulse rounded bg-gray-300 dark:bg-gray-600">
                                        </div>
                                    </td>
                                </template>
                            </tr>
                        </template>
                    </template>

                    <template data-name="when has data" x-for="(row, rowIndex) in items" :key="row.id + row.updated_at">
                        <tr>
                            <td class="p-4">
                                <label :for="row.id"
                                    class="flex items-center text-on-surface dark:text-on-surface-dark ">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" :id="row.id"
                                            class="before:content[''] peer relative size-4 appearance-none overflow-hidden rounded border border-outline bg-surface before:absolute before:inset-0 checked:border-primary checked:before:bg-primary focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark"
                                            :checked="checkAll" />
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                                            stroke="currentColor" fill="none" stroke-width="4"
                                            class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-on-primary peer-checked:visible dark:text-on-primary-dark">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                </label>
                            </td>
                            <td class="p-4" x-text="rowIndex + 1"></td>
                            <td class="p-4">
                                <img src="https://placehold.co/400x300" :alt="row.name"
                                    class="w-16 aspect-[4/3] rounded-radius object-cover cursor-pointer"
                                    draggable="false" />
                            </td>
                            <td class="p-4" x-text="row.name"></td>
                            <td class="p-4" x-text="`${row.stock_quantity} ${row.unit}`"></td>
                            <td class="p-4">
                                <div class="flex gap-3 items-center">
                                    <button type="button" x-on:click="createUpdate.open(row)"
                                        class="cursor-pointer whitespace-nowrap rounded-radius bg-transparent p-0.5 font-semibold text-primary outline-primary hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 dark:text-primary-dark dark:outline-primary-dark">Edit</button>
                                    <button type="button" x-on:click="deleteItem(row.id, row.name)"
                                        class="cursor-pointer whitespace-nowrap rounded-radius bg-transparent p-0.5 font-semibold text-danger outline-danger hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 dark:text-danger dark:outline-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template data-name="when empty" x-if="items.length < 1 && !loading">
                        <tr>
                            <td :colspan="cols" class="p-4">
                                <p class="text-center text-md font-semibold opacity-80">There's nothing to show.</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    </main>

    <x-modal id="create-update-modal" :static="true">
        <form id="create-update-form" x-on:submit.prevent="$store.mg.createUpdate.process($event)">
            <input type="hidden" name="id">

            <div class="p-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-lg font-semibold">Ingredient</h1>
                    <button type="button" x-on:click="$store.mg.createUpdate.hide()"
                        class="whitespace-nowrap rounded-radius bg-surface-alt border border-surface-alt px-4 py-2 text-sm font-medium tracking-wide text-on-surface-strong transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="w-full h-8"></div>

                <div class="grid grid-cols-12 gap-2 md:gap-4">
                    <div class="col-span-12 md:col-span-3">
                        <label for="name" class="w-fit pl-0.5 text-sm capitalize">Name</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input id="name" type="text"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-3 py-2 text-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            name="name" placeholder="Name" autocomplete="name" required />
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label for="description" class="w-fit pl-0.5 text-sm capitalize">Description</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <textarea id="description"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-3 py-2 text-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            name="description" placeholder="Description" autocomplete="off" required></textarea>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label for="unit" class="w-fit pl-0.5 text-sm capitalize">Unit</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="absolute pointer-events-none right-4 top-2 size-5">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <select id="unit"
                                class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                name="unit" placeholder="Unit" autocomplete="off" required>
                                <option value="" hidden>Select option</option>
                                @foreach (Ingredient::UNITS as $abbr => $unit)
                                    <option value="{{ $abbr }}">{{ ucfirst($unit) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label for="calories_per_unit" class="w-fit pl-0.5 text-sm capitalize">Calories Per
                            Unit</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input type="number" id="calories_per_unit"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-3 py-2 text-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            name="calories_per_unit" placeholder="Calories per unit" autocomplete="off" required />
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label for="is_vegan" class="w-fit pl-0.5 text-sm capitalize">Is Vegan</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="absolute pointer-events-none right-4 top-2 size-5">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <select id="is_vegan"
                                class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                name="is_vegan" placeholder="Is Vegan" autocomplete="off" required>
                                <option value="" hidden>Select option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label for="is_gluten_free" class="w-fit pl-0.5 text-sm capitalize">Is Gluten Free</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="absolute pointer-events-none right-4 top-2 size-5">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <select id="is_gluten_free"
                                class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                name="is_gluten_free" placeholder="Is Gluten Free" autocomplete="off" required>
                                <option value="" hidden>Select option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label for="is_gluten_free" class="w-fit pl-0.5 text-sm capitalize">Stock Quantity</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input type="number" id="is_gluten_free"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-3 py-2 text-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            name="stock_quantity" placeholder="Stock Quantity" autocomplete="off" required />
                    </div>
                </div>

                <div class="w-full h-8"></div>

                <div class="grid justify-end">
                    <button type="submit"
                        class="whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-2 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">Save</button>
                </div>
            </div>
        </form>
    </x-modal>

    <x-modal id="error-modal" :static="true">
        <div class="p-6">

            <div class="flex flex-col gap-4 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-24 stroke-danger">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <h2 class="text-xl font-semibold">Error</h2>
                <p class="text-md error-message">Something went wrong. Please try again later.</p>
                <p class="text-sm opacity-80">Please contact the administrator if this error keeps happening.</p>
            </div>

            <div class="w-full h-8"></div>

            <div class="w-full flex justify-center">
                <button type="button" x-on:click="window.location.reload()"
                    class="whitespace-nowrap rounded-radius bg-primary border border-primary px-6 py-2 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">Refresh</button>
            </div>

        </div>
    </x-modal>

    <x-modal id="confirm-modal">
        <div class="p-6">

            <div class="flex flex-col gap-4 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-24 stroke-primary">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                </svg>
                <h2 class="text-xl font-semibold">Are you sure?</h2>
                <p class="text-md confirm-message"></p>
            </div>

            <div class="w-full h-8"></div>

            <div class="w-full flex justify-center gap-6">
                <button type="button" x-on:click="$store.mg.confirm.hide()"
                    class="whitespace-nowrap rounded-radius bg-surface-alt border border-surface-alt px-6 py-2 text-sm font-medium tracking-wide text-on-surface-strong transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt">No</button>

                <button type="button" x-on:click="$store.mg.confirm.handleConfirm($event)"
                    class="whitespace-nowrap rounded-radius bg-primary border border-primary px-6 py-2 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">Yes</button>
            </div>

        </div>
    </x-modal>

    <x-toast />

    <script>
        document.addEventListener('alpine:init', () => {
            $.store('mg', {
                cols: 6,
                loadingRows: 3,
                loading: true,
                checkAll: false,
                items: [],
                createUpdate: null,
                confirm: null,
                error: null,

                init() {
                    this.confirm = {
                        onConfirm: null,
                        element: document.querySelector('#confirm-modal'),
                        show: () => $.notifier.modal(this.confirm.element, 'show'),
                        hide: () => $.notifier.modal(this.confirm.element, 'hide'),
                        setMessage: (message) => {
                            this.confirm.element.querySelector('.confirm-message').innerText =
                                message;
                        },
                        handleConfirm(e) {
                            e.preventDefault();
                            if (this.onConfirm && typeof this.onConfirm === "function") {
                                this.onConfirm(e);
                            }
                        },
                    };

                    this.error = {
                        element: document.querySelector('#error-modal'),
                        show: () => $.notifier.modal(this.error.element, 'show'),
                        hide: () => $.notifier.modal(this.error.element, 'hide'),
                        setMessage: (message) => {
                            this.error.element.querySelector('.error-message').innerText = message;
                        },
                    };

                    this.createUpdate = {
                        element: document.querySelector('#create-update-modal'),
                        show: () => $.notifier.modal(this.createUpdate.element, 'show'),
                        hide: () => $.notifier.modal(this.createUpdate.element, 'hide'),
                        clearForm: () => {
                            const inputs = this.createUpdate.element.querySelectorAll(
                                'input,select,textarea');
                            inputs.forEach(input => input.value = '');
                        },
                        open: (data = {}) => {
                            this.createUpdate.clearForm();
                            for (const key of Object.keys(data)) {
                                const el = this.createUpdate.element.querySelector(
                                    `[name='${key}']`);
                                if (el) el.value = data[key];
                            }
                            this.createUpdate.show();
                        },
                        process: (e) => {
                            const formData = new FormData(e.target);
                            axios.post(@js(route('ingredients.createUpdate')), formData)
                                .then(res => {
                                    const message = res?.data?.message || res.message ||
                                        'Successful';
                                    $.notifier.toast({
                                        variant: 'success',
                                        title: 'Success',
                                        message
                                    });

                                    this.createUpdate.hide();

                                    const updatedItem = res.data.data;
                                    this.updateItems(this.items.map((item) => (item.id === updatedItem.id ? updatedItem : item)));
                                })
                                .catch(err => {
                                    const message = err?.response?.data?.message || err.message;
                                    $.notifier.toast({
                                        variant: 'danger',
                                        title: 'Error',
                                        message
                                    });
                                });
                        }
                    };
                },

                fetchData() {
                    this.loading = true;
                    this.updateItems([]);
                    axios.get(@js(route('ingredients.dataTable')))
                        .then(res => {
                            this.updateItems(res.data.data);
                            this.loading = false;
                        })
                        .catch(err => {
                            this.loading = false;
                            this.error.show();
                            const errorMessage = err?.response?.data?.message || err.message;
                            this.error.setMessage(errorMessage);
                        });
                },

                deleteItem(id, name = '') {
                    this.confirm.setMessage(`Are you sure you want to delete ${name || 'this item'}?`);
                    this.confirm.onConfirm = () => {
                        axios.delete(@js(route('ingredients.delete')), {
                                data: {
                                    id
                                }
                            })
                            .then(res => {
                                this.updateItems(this.items.filter(item => item.id !== id));
                                const message = res?.data?.message || 'Deleted';
                                $.notifier.toast({
                                    variant: 'success',
                                    title: 'Success',
                                    message
                                });
                                this.confirm.hide();
                            })
                            .catch(err => {
                                this.confirm.hide();
                                const message = err?.response?.data?.message || err.message;
                                $.notifier.toast({
                                    variant: 'danger',
                                    title: 'Oops...',
                                    message
                                });
                            });
                    }

                    this.confirm.show();
                },

                get loadSkeleton() {
                    return Array.from({
                            length: this.loadingRows
                        }, () =>
                        Array.from({
                            length: this.cols
                        })
                    );
                },

                updateItems(newItems = []) {
                    this.items = newItems;
                    this.loadingRows = Math.max(this.loadingRows, newItems.length);
                },
            });
        });
    </script>

</x-admin.layout>
