<x-admin.layout title="Dashboard">

    <x-slot name="foot">
        <x-admin.ui.datatable.modals></x-admin.ui.datatable.modals>

        <script>
            document.addEventListener('alpine:init', () => {
                $.data('dashboard', () => ({
                    state: {
                        totalRevenue: '$0',
                        orderCount: 0,
                        pizzaCount: 0,
                        avgRating: 0,
                        orderChart: null,
                        revenueChart: null,
                    },

                    loading: {
                        totalRevenue: false,
                        orderCount: false,
                        pizzaCount: false,
                        avgRating: false,
                        orderChart: false,
                        revenueChart: false,
                    },

                    handleApiCall(promise, stateKey, successCallback, errorCallback = null, toastOnSuccess =
                        false) {
                        this.loading[stateKey] = true;
                        promise
                            .then(res => {
                                if (toastOnSuccess) {
                                    $.store('notifiers').toast({
                                        variant: 'success',
                                        title: 'Success',
                                        message: res?.data?.message || 'Data refreshed successfully!'
                                    });
                                }

                                this.loading[stateKey] = false;

                                if (successCallback) successCallback(res);
                            })
                            .catch(err => {
                                $.store('notifiers').toast({
                                    variant: 'danger',
                                    title: 'Oops...',
                                    message: err?.response?.data?.message || err.message
                                });

                                this.loading[stateKey] = false;

                                if (errorCallback) errorCallback(err);
                            });
                    },

                    initChart(chartId, chartData) {
                        this.loading[chartId] = true;

                        if (this.state[chartId]) {
                            this.state[chartId]?.destroy();
                            this.state[chartId] = null;
                        }

                        this.state[chartId] = new Chart(this.$refs[chartId], {
                            ...chartData,
                            options: {
                                ...chartData.options,
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: {
                                    duration: 1000,
                                    onComplete: (context) => {
                                        if (context.initial) {
                                            this.loading[chartId] = false
                                        }
                                    }
                                },
                            }
                        });
                    },

                    fetchTotalRevenue() {
                        this.handleApiCall(axios.get(@js(route('dashboard.totalRevenue'))), 'totalRevenue', ({
                            data
                        }) => (this.state.totalRevenue = data.data));
                    },
                    fetchOrderCount() {
                        this.handleApiCall(axios.get(@js(route('dashboard.orderCount'))), 'orderCount', ({
                            data
                        }) => (this.state.orderCount = data.data));
                    },
                    fetchPizzaCount() {
                        this.handleApiCall(axios.get(@js(route('dashboard.pizzaCount'))), 'pizzaCount', ({
                            data
                        }) => (this.state.pizzaCount = data.data));
                    },
                    fetchAvgRating() {
                        this.handleApiCall(axios.get(@js(route('dashboard.avgRating'))), 'avgRating', ({
                            data
                        }) => (this.state.avgRating = data.data));
                    },
                    fetchOrderChart() {
                        this.handleApiCall(axios.get(@js(route('dashboard.orderChart'))), 'orderChart', ({
                            data
                        }) => (this.initChart('orderChart', data.data)));
                    },
                    fetchRevenueChart() {
                        this.handleApiCall(axios.get(@js(route('dashboard.revenueChart'))), 'revenueChart', ({
                            data
                        }) => (this.initChart('revenueChart', data.data)));
                    },

                    fetchAllData() {
                        this.fetchTotalRevenue();
                        this.fetchOrderCount();
                        this.fetchPizzaCount();
                        this.fetchAvgRating();
                        this.fetchOrderChart();
                        this.fetchRevenueChart();
                    },

                    init() {
                        this.fetchAllData();
                    },
                }));

                const store = createDataTableStore({
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
                            }
                        },
                        {
                            name: "Rating",
                            class: "w-16",
                            data: (row, index) => `${row.rating} &#9733;`,
                        },
                        {
                            name: "Comment",
                            class: "w-40",
                            data: 'comment'
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
                });
                $.store('mg', store);
            });
        </script>
    </x-slot>

    <main x-data="dashboard()" class="flex flex-col px-6 md:px-12">
        {{-- Header Section --}}
        <div class="h-8 w-full"></div>
        <section id="header" class="relative flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl">Dashboard</h1>
                <p class="opacity-90">Welcome, {{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <button class="flex items-center gap-2 whitespace-nowrap rounded-radius bg-secondary border border-secondary px-4 py-2 text-sm font-medium tracking-wide text-on-secondary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-secondary-dark dark:border-secondary-dark dark:text-on-secondary-dark dark:focus-visible:outline-secondary-dark"
                        type="button">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />

                    </svg>
                    Filters
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

        <div class="h-12 w-full"></div>

        <div class="grid grid-cols-12 place-items-stretch gap-4">
            <div class="col-span-12 md:col-span-6 lg:col-span-3">
                <div class="p-4 border bg-white dark:bg-zinc-900 border-outline dark:border-outline-dark rounded-radius relative">
                    <p x-text="state.totalRevenue" class="text-3xl font-bold leading-tight"></p>
                    <p class="text-md text-on-surface dark:text-on-surface-dark">Revenue</p>
                    <button class="absolute top-0 right-0 m-4" @click="fetchTotalRevenue()" type="button" :disabled="loading.totalRevenue">
                        <svg class="size-4" :class="{ 'animate-spin': loading.totalRevenue }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />

                        </svg>
                    </button>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-3">
                <div class="p-4 border bg-white dark:bg-zinc-900 border-outline dark:border-outline-dark rounded-radius relative">
                    <p x-text="state.orderCount" class="text-3xl font-bold leading-tight"></p>
                    <p class="text-md text-on-surface dark:text-on-surface-dark">Orders</p>
                    <button class="absolute top-0 right-0 m-4" @click="fetchOrderCount()" type="button" :disabled="loading.orderCount">
                        <svg class="size-4" :class="{ 'animate-spin': loading.orderCount }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />

                        </svg>
                    </button>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-3">
                <div class="p-4 border bg-white dark:bg-zinc-900 border-outline dark:border-outline-dark rounded-radius relative">
                    <p x-text="state.pizzaCount" class="text-3xl font-bold leading-tight"></p>
                    <p class="text-md text-on-surface dark:text-on-surface-dark">Pizzas</p>
                    <button class="absolute top-0 right-0 m-4" @click="fetchPizzaCount()" type="button" :disabled="loading.pizzaCount">
                        <svg class="size-4" :class="{ 'animate-spin': loading.pizzaCount }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />

                        </svg>
                    </button>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-3">
                <div class="p-4 border bg-white dark:bg-zinc-900 border-outline dark:border-outline-dark rounded-radius relative">
                    <p x-text="state.avgRating" class="text-3xl font-bold leading-tight"></p>
                    <p class="text-md text-on-surface dark:text-on-surface-dark">Average Rating</p>
                    <button class="absolute top-0 right-0 m-4" @click="fetchAvgRating()" type="button" :disabled="loading.avgRating">
                        <svg class="size-4" :class="{ 'animate-spin': loading.avgRating }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />

                        </svg>
                    </button>
                </div>
            </div>

            <div class="col-span-12 h-8"></div>

            <div class="col-span-12 lg:col-span-6">
                <div class="p-4 border bg-white dark:bg-zinc-900 border-outline dark:border-outline-dark rounded-radius relative">
                    <p class="text-lg font-semibold leading-tight">Success Orders</p>
                    <canvas x-ref="orderChart" class="min-h-[250px] max-h-[250px]"></canvas>
                    <button class="absolute top-0 right-0 m-4" @click="fetchOrderChart()" type="button" :disabled="loading.orderChart">
                        <svg class="size-4" :class="{ 'animate-spin': loading.orderChart }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />

                        </svg>
                    </button>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="p-4 border bg-white dark:bg-zinc-900 border-outline dark:border-outline-dark rounded-radius relative">
                    <p class="text-lg font-semibold leading-tight">Revenue</p>
                    <canvas x-ref="revenueChart" class="min-h-[250px] max-h-[250px]"></canvas>
                    <button class="absolute top-0 right-0 m-4" @click="fetchRevenueChart()" type="button" :disabled="loading.revenueChart">
                        <svg class="size-4" :class="{ 'animate-spin': loading.revenueChart }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />

                        </svg>
                    </button>
                </div>
            </div>
            <div class="col-span-12">
                <div class="p-4 border bg-white dark:bg-zinc-900 border-outline dark:border-outline-dark rounded-radius relative">
                    <p class="text-lg font-semibold leading-tight">Reviews</p>
                    <x-admin.ui.datatable.body x-data="$store.mg" id="data-table-container" />
                </div>
            </div>
        </div>

    </main>

</x-admin.layout>
