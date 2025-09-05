@use('App\Models\Order')

<x-admin.layout title="Manage Orders">

    <main x-data="$store.mg" class="flex flex-col px-6 md:px-12">
        {{-- Header Section --}}
        <div class="h-8 w-full"></div>
        <section id="header" class="relative flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl">Manage Orders</h1>
                <p class="opacity-90">Manage your orders here.</p>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <button x-on:click="createUpdate.open({})" x-show="false" x-cloak
                        class="rounded-radius bg-primary border-primary text-on-primary focus-visible:outline-primary dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark flex items-center gap-2 whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        type="button">
                    <svg class="size-5" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Insert
                </button>
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
                        <rect x="5" y="4" width="14" height="17" rx="2" stroke-width="2"></rect>
                        <path d="M9 9H15" stroke-width="2" stroke-linecap="round"></path>
                        <path d="M9 13H15" stroke-width="2" stroke-linecap="round"></path>
                        <path d="M9 17H13" stroke-width="2" stroke-linecap="round"></path>
                    </g>
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
                        <label class="w-fit pl-0.5 text-sm capitalize" for="invoice_number">Invoice Number</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input x-model="$store.mg.selectedItem.invoice_number" id="invoice_number" name="invoice_number"
                               class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                               type="text" autocomplete="off" required readonly />
                    </div>

                    <div class="col-span-12 border-t border-t-outline dark:border-t-outline-dark"></div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="customer_name">Customer Name</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input x-model="$store.mg.selectedItem.customer_name" id="customer_name" name="customer_name"
                               class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                               type="text" autocomplete="off" required readonly />
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="customer_email">Customer Email</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input x-model="$store.mg.selectedItem.customer_email" id="customer_email" name="customer_email"
                               class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                               type="email" autocomplete="off" required readonly />
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="customer_phone">Customer Phone</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input x-model="$store.mg.selectedItem.customer_phone" id="customer_phone" name="customer_phone"
                               class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                               type="tel" autocomplete="off" required readonly />
                    </div>

                    <div class="col-span-12 border-t border-t-outline dark:border-t-outline-dark"></div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="delivery_address">Delivery Address</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <textarea x-model="$store.mg.selectedItem.delivery_address" id="delivery_address" name="delivery_address"
                                  class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                  cols="4" autocomplete="off" required readonly></textarea>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="notes">Notes</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <textarea x-model="$store.mg.selectedItem.notes" id="notes" name="notes"
                                  class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                  cols="4" autocomplete="off" required readonly></textarea>
                    </div>

                    <div class="col-span-12 border-t border-t-outline dark:border-t-outline-dark"></div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="status">Status</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="flex flex-col gap-2">
                            @foreach (Order::STATUS as $i => $status)
                                <label class="flex w-fit min-w-52 items-center justify-start gap-2 rounded-radius border border-outline bg-surface-alt px-4 py-2 font-medium text-on-surface has-disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark cursor-pointer"
                                       for="status-{{ $status }}">
                                    <input x-model="$store.mg.selectedItem.status" id="status-{{ $status }}" name="status"
                                           class="before:content[''] relative h-4 w-4 appearance-none rounded-full border border-outline bg-surface before:invisible before:absolute before:left-1/2 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-x-1/2 before:-translate-y-1/2 before:rounded-full before:bg-on-primary checked:border-primary checked:bg-primary checked:before:visible focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary disabled:cursor-not-allowed dark:border-outline-dark dark:bg-surface-dark dark:before:bg-on-primary-dark dark:checked:border-primary-dark dark:checked:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark"
                                           type="radio" value="{{ $status }}" checked>
                                    <span class="text-sm">{{ ucfirst($status) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="total_amount">Total Amount</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <div class="pointer-events-none absolute right-4 top-0 bottom-0 inline-flex items-center">
                                <p x-text="$store.when.displayMoney(69).split(/\d/)[0]" class="text-sm"></p>
                            </div>
                            <input x-mask:dynamic="$money($input)" x-model="$store.mg.selectedItem.total_amount" id="total_amount" name="total_amount"
                                   class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border pl-3 pr-12 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                   type="text" autocomplete="off" required readonly />
                        </div>
                    </div>

                </div>
            </x-slot>

        </x-admin.ui.datatable.modals>

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
                        name: "Invoice",
                        class: "w-32",
                        data: "invoice_number",
                    },
                    {
                        name: "Name",
                        class: "w-32",
                        data: "customer_name",
                    },
                    {
                        name: "Total",
                        class: "w-48",
                        data: "total_amount",
                    },
                    {
                        name: "Status",
                        class: "w-24",
                        data: "status",
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
                    "Updated At": "created_at",
                },

                initialFilters: {
                    appliedFilters: [],
                    availableFilters: [{
                        name: 'Created At',
                        column: 'created_at',
                        type: 'date-range',
                    }],
                },

                routes: {
                    fetch: "{{ route('orders.dataTable') }}",
                    createUpdate: "{{ route('orders.createUpdate') }}",
                    delete: "{{ route('orders.delete') }}",
                    bulkDelete: "{{ route('orders.bulkDelete') }}",
                },

                listeners: {},

                itemName: "order",
                itemIdentifier: "invoice_number",
            };

            document.addEventListener("alpine:init", () => {
                const store = createDataTableStore(config);
                $.store('mg', store);

            });
        </script>
    </x-slot>
</x-admin.layout>
