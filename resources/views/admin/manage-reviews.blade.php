@use('App\Models\{Review}')

<x-admin.layout title="Manage Reviews">

    <main x-data="$store.mg" class="flex flex-col px-6 md:px-12">
        {{-- Header Section --}}
        <div class="h-8 w-full"></div>
        <section id="header" class="relative flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl">Manage Reviews</h1>
                <p class="opacity-90">Manage your reviews here.</p>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <button x-cloak x-on:click="bulkDelete.open()" x-show="selectedIds.length > 0" x-transition
                        class="rounded-radius bg-danger border-danger dark:border-danger text-on-danger focus-visible:outline-danger dark:bg-danger dark:text-on-danger dark:focus-visible:outline-danger inline-flex items-center justify-center gap-2 whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        type="button">
                    <svg class="size-5" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                              stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Bulk Delete
                </button>
            </div>
            <div class="absolute right-0 md:left-0">
                <svg class="stroke-primary size-24 opacity-20" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path clip-rule="evenodd"
                              d="M8.18092 2.56556C7.90392 3.05195 7.65396 3.65447 7.416 4.36507C5.57795 9.34447 2.73476 16.6246 1.36225 20.12C0.73894 21.7073 2.25721 23.2963 3.87117 22.7465C7.38796 21.5484 14.6626 19.0869 19.6353 17.5194L19.6504 17.5145C20.3639 17.277 20.9659 17.0333 21.4491 16.7641C21.9273 16.4977 22.3551 16.1704 22.6426 15.7347C23.2987 14.7406 22.9351 13.6998 22.5012 12.8954C19.7712 7.83439 16.3585 4.2775 12.0968 1.5703C11.6898 1.31179 11.2341 1.09226 10.7418 1.02286C10.2141 0.948472 9.69595 1.05467 9.22968 1.36307C8.79315 1.65181 8.45686 2.08103 8.18092 2.56556ZM15.0912 9.09151C13.5105 7.4048 11.7893 5.97947 9.55526 4.3325C9.6817 4.01505 9.80284 3.75901 9.91885 3.55532C10.1115 3.21703 10.2575 3.08115 10.333 3.03119C10.3788 3.0009 10.4025 2.99481 10.4626 3.00327C10.5579 3.01672 10.7358 3.07517 11.0244 3.25848C14.994 5.78016 18.1714 9.08132 20.741 13.8449C21.0989 14.5085 20.9833 14.6233 20.9739 14.6325L20.9734 14.6331C20.9318 14.696 20.8089 14.8313 20.4757 15.017C20.2861 15.1226 20.0491 15.2333 19.7558 15.3501C18.0975 12.7134 16.6772 10.7839 15.0912 9.09151ZM13.6318 10.4591C15.0211 11.9415 16.2981 13.6452 17.8022 16.0033C12.9009 17.5716 6.46194 19.751 3.22621 20.8533L3.22459 20.8538L3.22391 20.8531L3.22329 20.8525L3.22387 20.851C4.48689 17.6345 7.00299 11.1934 8.83498 6.28876C10.7878 7.75003 12.2738 9.00998 13.6318 10.4591ZM10 13C11.1046 13 12 12.1046 12 11C12 9.89545 11.1046 9.00002 10 9.00002C8.89543 9.00002 8 9.89545 8 11C8 12.1046 8.89543 13 10 13ZM10 16C10 17.1046 9.10457 18 8 18C6.89543 18 6 17.1046 6 16C6 14.8954 6.89543 14 8 14C9.10457 14 10 14.8954 10 16ZM13 17C14.1046 17 15 16.1046 15 15C15 13.8954 14.1046 13 13 13C11.8954 13 11 13.8954 11 15C11 16.1046 11.8954 17 13 17Z"
                              fill-rule="evenodd" fill="#000000"></path>
                    </g>
                </svg>
            </div>
        </section>

        <x-admin.ui.datatable.body id="data-table-container" />

        {{-- MORE CONTENTS --}}

    </main>

    <x-slot name="foot">
        <x-admin.ui.datatable.modals></x-admin.ui.datatable.modals>

        <script>
            const config = {
                columns: [{
                        name: "CHECK_ALL",
                        class: "w-12",
                    },
                    {
                        name: "#",
                        class: "w-14",
                    },
                    {
                        name: "Pizza",
                        class: "w-32",
                        data: (row, index) => {
                            return row.pizza.name;
                        }
                    },
                    {
                        name: "Order",
                        class: "w-32",
                        data: (row, index) => {
                            return row.order.invoice_number;
                        },
                        type: 'copyable'
                    },
                    {
                        name: "Rating",
                        class: "w-16",
                        data: (row, index) => `${row.rating} &#9733;`,
                    },
                    {
                        name: "Comment",
                        class: "w-40",
                        data: 'comment',
                        type: 'copyable'
                    },
                    {
                        name: "Updated At",
                        class: "w-64",
                    },
                    {
                        name: "Action",
                        class: "w-32",
                    },
                ],

                sortables: {
                    "#": "id",
                    Name: "name",
                    Price: "price",
                    Available: 'is_available',
                    "Updated At": "created_at",
                },

                selectors: {
                    viewImage: '#view-image-modal'
                },

                initialFilters: {
                    appliedFilters: [],
                    availableFilters: [{
                            name: 'Available',
                            column: 'is_available',
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
                            name: 'Created At',
                            column: 'created_at',
                            type: 'date-range',
                        }
                    ],
                },

                routes: {
                    fetch: "{{ route('reviews.dataTable') }}",
                    createUpdate: "",
                    delete: "{{ route('reviews.delete') }}",
                    bulkDelete: "{{ route('reviews.bulkDelete') }}",
                },

                listeners: {},

                actions: {
                    view: false,
                    update: false,
                    delete: true,
                    create: false
                },

                itemName: "review",
                itemIdentifier: "id",
            };

            document.addEventListener("alpine:init", () => {
                const store = createDataTableStore(config);
                $.store('mg', store);
            });
        </script>
    </x-slot>
</x-admin.layout>
