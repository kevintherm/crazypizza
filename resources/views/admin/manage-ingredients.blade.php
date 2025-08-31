@use('App\Models\Ingredient')

<x-admin.layout title="Manage Ingredients">

    <main x-data="$store.mg" class="flex flex-col px-6 md:px-12">

        {{-- Header Section --}}
        <div class="h-8 w-full"></div>
        <section id="header" class="relative flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl">Manage Ingredients</h1>
                <p class="opacity-90">Manage your ingredients here.</p>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <button
                    x-on:click="createUpdate.open({})"
                    class="rounded-radius bg-primary border-primary text-on-primary focus-visible:outline-primary dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark flex items-center gap-2 whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                    type="button"
                >
                    <svg
                        class="size-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15"
                        />
                    </svg>
                    Insert
                </button>
                <button
                    x-show="selectedIds.length > 0"
                    x-cloak
                    x-transition
                    x-on:click="bulkDelete.open()"
                    class="rounded-radius bg-danger border-danger dark:border-danger text-on-danger focus-visible:outline-danger dark:bg-danger dark:text-on-danger dark:focus-visible:outline-danger inline-flex items-center justify-center gap-2 whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                    type="button"
                >
                    <svg
                        class="size-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                        />
                    </svg>
                    Bulk Delete
                </button>
            </div>
            <div class="absolute right-0 md:left-0">
                <svg
                    class="stroke-primary size-24 opacity-20"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"
                    />
                </svg>
            </div>
        </section>

        <x-admin.ui.datatable.body id="data-table-container" />

        {{-- MORE CONTENTS --}}

    </main>

    <x-slot name="foot">

        <x-admin.ui.datatable.modals>

            <x-slot name="form">
                <div class="grid grid-cols-12 gap-2 md:gap-4">
                    <div class="col-span-12 md:col-span-3">
                        <p class="w-fit pb-1 pl-0.5 text-sm capitalize">Image</p>
                        <button
                            x-show="$store.mg.selectedItem.image"
                            x-transition
                            x-cloak
                            x-on:click="$store.mg.deleteItem($store.mg.selectedItem.id, null, 'image')"
                            class="rounded-radius border-danger text-danger focus-visible:outline-danger dark:border-danger dark:text-danger dark:focus-visible:outline-danger whitespace-nowrap border bg-transparent p-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                            type="button"
                        >
                            <svg
                                class="size-4"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                />
                            </svg>
                        </button>
                    </div>

                    <div class="col-span-12 md:col-span-9">
                        <img
                            x-show="$store.mg.selectedItem.image"
                            x-cloak
                            x-on:click="$store.mg.viewImage.open($store.mg.selectedItem.image, $store.mg.selectedItem.title)"
                            x-on:error="$store.when.imageError"
                            class="rounded-radius sm:max-w-1/3 w-full cursor-pointer"
                            :src="$store.mg.selectedItem.image"
                        >
                        <div x-show="!$store.mg.selectedItem.image" x-cloak>
                            <x-file-uploader
                                id="image"
                                :allowedTypes="['image/jpg', 'image/jpeg', 'image/png', 'image/webp']"
                                :maxSize="5 * MB_IN_BYTES"
                            />
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="name">Name</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input
                            x-model="$store.mg.selectedItem.name"
                            id="name"
                            name="name"
                            class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                            type="text"
                            placeholder="Name"
                            autocomplete="name"
                            required
                        />
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="description">Description</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <textarea
                            x-grow
                            x-model="$store.mg.selectedItem.description"
                            id="description"
                            name="description"
                            class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                            placeholder="Description"
                            autocomplete="off"
                            required
                        ></textarea>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="unit">Unit</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <svg
                                class="pointer-events-none absolute right-4 top-2 size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <select
                                x-model="$store.mg.selectedItem.unit"
                                id="unit"
                                name="unit"
                                class="rounded-radius border-outline bg-surface-alt focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full appearance-none border px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                placeholder="Unit"
                                autocomplete="off"
                                required
                            >
                                <option value="" hidden>Select option</option>
                                @foreach (Ingredient::UNITS as $abbr => $unit)
                                    <option value="{{ $abbr }}">{{ ucfirst($unit) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="calories_per_unit">Calories Per
                            Unit</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input
                            x-model="$store.mg.selectedItem.calories_per_unit"
                            id="calories_per_unit"
                            name="calories_per_unit"
                            class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                            type="number"
                            placeholder="Calories per unit"
                            autocomplete="off"
                            required
                        />
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="is_vegan">Is Vegan</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <svg
                                class="pointer-events-none absolute right-4 top-2 size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <select
                                x-model="$store.mg.selectedItem.is_vegan"
                                id="is_vegan"
                                name="is_vegan"
                                class="rounded-radius border-outline bg-surface-alt focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full appearance-none border px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                placeholder="Is Vegan"
                                autocomplete="off"
                                required
                            >
                                <option value="" hidden>Select option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="is_gluten_free">Is Gluten Free</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <svg
                                class="pointer-events-none absolute right-4 top-2 size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <select
                                x-model="$store.mg.selectedItem.is_gluten_free"
                                id="is_gluten_free"
                                name="is_gluten_free"
                                class="rounded-radius border-outline bg-surface-alt focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full appearance-none border px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                placeholder="Is Gluten Free"
                                autocomplete="off"
                                required
                            >
                                <option value="" hidden>Select option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="stock_quantity">Stock Quantity</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input
                            x-model="$store.mg.selectedItem.stock_quantity"
                            id="stock_quantity"
                            name="stock_quantity"
                            class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                            type="number"
                            placeholder="Stock Quantity"
                            autocomplete="off"
                            required
                        />
                    </div>
                </div>
            </x-slot>

        </x-admin.ui.datatable.modals>

        <script>
            const config = {
                columns: [{
                        name: 'CHECK_ALL',
                        class: 'w-12'
                    },
                    {
                        name: '#',
                        class: 'w-14'
                    },
                    {
                        name: 'Image',
                        class: 'w-20',
                        type: 'image',
                    },
                    {
                        name: 'Name',
                        class: ''
                    },
                    {
                        name: 'Stock',
                        class: 'w-32',
                        data: 'stock_quantity'
                    },
                    {
                        name: 'Unit',
                        class: 'w-24',
                    },
                    {
                        name: 'Updated At',
                        class: 'w-96'
                    },
                    {
                        name: 'Action',
                        class: 'w-32'
                    }
                ],

                sortables: {
                    '#': 'id',
                    'Name': 'name',
                    'Stock': 'stock_quantity',
                    'Updated At': 'created_at'
                },

                initialFilters: {
                    appliedFilters: [],
                    availableFilters: [{
                            name: 'Stock',
                            column: 'stock_quantity',
                            type: 'range',
                            accept: 'number'
                        },
                        {
                            name: 'Is Vegan',
                            column: 'is_vegan',
                            type: 'select',
                            accept: 'bool',
                            options: [{
                                    label: 'All',
                                    value: ''
                                },
                                {
                                    label: 'Yes',
                                    value: '1'
                                },
                                {
                                    label: 'No',
                                    value: '0'
                                },
                            ],
                            selectedOption: {
                                label: 'All',
                                value: ''
                            }
                        },
                        {
                            name: 'Is Gluten Free',
                            column: 'is_gluten_free',
                            type: 'select',
                            accept: 'bool',
                            options: [{
                                    label: 'All',
                                    value: ''
                                },
                                {
                                    label: 'Yes',
                                    value: '1'
                                },
                                {
                                    label: 'No',
                                    value: '0'
                                },
                            ],
                            selectedOption: {
                                label: 'All',
                                value: ''
                            }
                        },

                    ]
                },

                routes: {
                    fetch: "{{ route('ingredients.dataTable') }}",
                    createUpdate: "{{ route('ingredients.createUpdate') }}",
                    delete: "{{ route('ingredients.delete') }}",
                    bulkDelete: "{{ route('ingredients.bulkDelete') }}",
                },

                itemName: 'ingredient',
                itemIdentifier: 'name'
            };

            document.addEventListener('alpine:init', () => {
                const createDataTableManager = $.store('dataTable');
                $.store('mg', createDataTableManager(config));
            });
        </script>
    </x-slot>

</x-admin.layout>
