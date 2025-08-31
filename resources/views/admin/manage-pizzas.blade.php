@use('App\Models\Ingredient')

<x-admin.layout title="Manage Pizzas">
    <main class="flex flex-col px-6 md:px-12" x-data="$store.mg">
        {{-- Header Section --}}
        <div class="h-8 w-full"></div>
        <section
            id="header"
            class="relative flex flex-col justify-between gap-4 md:flex-row md:items-center"
        >
            <div>
                <h1 class="text-2xl">Manage Pizzas</h1>
                <p class="opacity-90">Manage your pizzas here.</p>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <button
                    type="button"
                    x-on:click="createUpdate.open({})"
                    class="rounded-radius bg-primary border-primary text-on-primary focus-visible:outline-primary dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark flex items-center gap-2 whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
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
                    type="button"
                    x-show="selectedIds.length > 0"
                    x-cloak
                    x-transition
                    x-on:click="bulkDelete.open()"
                    class="rounded-radius bg-danger border-danger dark:border-danger text-on-danger focus-visible:outline-danger dark:bg-danger dark:text-on-danger dark:focus-visible:outline-danger inline-flex items-center justify-center gap-2 whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
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
                <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                <svg
                    class="stroke-primary size-24 opacity-20"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g
                        id="SVGRepo_tracerCarrier"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M8.18092 2.56556C7.90392 3.05195 7.65396 3.65447 7.416 4.36507C5.57795 9.34447 2.73476 16.6246 1.36225 20.12C0.73894 21.7073 2.25721 23.2963 3.87117 22.7465C7.38796 21.5484 14.6626 19.0869 19.6353 17.5194L19.6504 17.5145C20.3639 17.277 20.9659 17.0333 21.4491 16.7641C21.9273 16.4977 22.3551 16.1704 22.6426 15.7347C23.2987 14.7406 22.9351 13.6998 22.5012 12.8954C19.7712 7.83439 16.3585 4.2775 12.0968 1.5703C11.6898 1.31179 11.2341 1.09226 10.7418 1.02286C10.2141 0.948472 9.69595 1.05467 9.22968 1.36307C8.79315 1.65181 8.45686 2.08103 8.18092 2.56556ZM15.0912 9.09151C13.5105 7.4048 11.7893 5.97947 9.55526 4.3325C9.6817 4.01505 9.80284 3.75901 9.91885 3.55532C10.1115 3.21703 10.2575 3.08115 10.333 3.03119C10.3788 3.0009 10.4025 2.99481 10.4626 3.00327C10.5579 3.01672 10.7358 3.07517 11.0244 3.25848C14.994 5.78016 18.1714 9.08132 20.741 13.8449C21.0989 14.5085 20.9833 14.6233 20.9739 14.6325L20.9734 14.6331C20.9318 14.696 20.8089 14.8313 20.4757 15.017C20.2861 15.1226 20.0491 15.2333 19.7558 15.3501C18.0975 12.7134 16.6772 10.7839 15.0912 9.09151ZM13.6318 10.4591C15.0211 11.9415 16.2981 13.6452 17.8022 16.0033C12.9009 17.5716 6.46194 19.751 3.22621 20.8533L3.22459 20.8538L3.22391 20.8531L3.22329 20.8525L3.22387 20.851C4.48689 17.6345 7.00299 11.1934 8.83498 6.28876C10.7878 7.75003 12.2738 9.00998 13.6318 10.4591ZM10 13C11.1046 13 12 12.1046 12 11C12 9.89545 11.1046 9.00002 10 9.00002C8.89543 9.00002 8 9.89545 8 11C8 12.1046 8.89543 13 10 13ZM10 16C10 17.1046 9.10457 18 8 18C6.89543 18 6 17.1046 6 16C6 14.8954 6.89543 14 8 14C9.10457 14 10 14.8954 10 16ZM13 17C14.1046 17 15 16.1046 15 15C15 13.8954 14.1046 13 13 13C11.8954 13 11 13.8954 11 15C11 16.1046 11.8954 17 13 17Z"
                            fill="#000000"
                        ></path>
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
                        <p class="w-fit pb-1 pl-0.5 text-sm capitalize">
                            Image
                        </p>
                        <button
                            type="button"
                            x-show="$store.mg.selectedItem.image"
                            x-transition
                            x-cloak
                            x-on:click="$store.mg.deleteItem($store.mg.selectedItem.id, null, 'image')"
                            class="rounded-radius border-danger text-danger focus-visible:outline-danger dark:border-danger dark:text-danger dark:focus-visible:outline-danger whitespace-nowrap border bg-transparent p-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-4"
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
                            :src="$store.mg.selectedItem.image"
                            x-on:error="$store.when.imageError"
                            class="rounded-radius sm:max-w-1/3 w-full cursor-pointer"
                        />
                        <div x-show="!$store.mg.selectedItem.image" x-cloak>
                            <x-file-uploader
                                id="image"
                                :allowedTypes="['image/jpg', 'image/jpeg', 'image/png', 'image/webp']"
                                :maxSize="5 * MB_IN_BYTES"
                            />
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label
                            for="name"
                            class="w-fit pl-0.5 text-sm capitalize"
                            >Name</label
                        >
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input
                            id="name"
                            type="text"
                            x-model="$store.mg.selectedItem.name"
                            class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                            name="name"
                            placeholder="Name"
                            autocomplete="name"
                            required
                        />
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label
                            for="description"
                            class="w-fit pl-0.5 text-sm capitalize"
                            >Description</label
                        >
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <textarea
                            x-grow
                            id="description"
                            x-model="$store.mg.selectedItem.description"
                            class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                            name="description"
                            placeholder="Description"
                            autocomplete="off"
                            required
                        ></textarea>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label
                            for="price"
                            class="w-fit pl-0.5 text-sm capitalize"
                            >Price</label
                        >
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <div
                                class="pointer-events-none absolute right-4 top-0 bottom-0 inline-flex items-center"
                            >
                                <p
                                    class="text-sm"
                                    x-text="$store.when.displayMoney(69).split(/\d/)[0]"
                                ></p>
                            </div>
                            <input
                                id="price"
                                type="text"
                                x-model="$store.mg.selectedItem.price"
                                x-mask:dynamic="$money($input)"
                                class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border pl-3 pr-12 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                name="price"
                                placeholder="Price"
                                autocomplete="off"
                                required
                            />
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label
                            for="is_available"
                            class="w-fit pl-0.5 text-sm capitalize"
                            >Is Available</label
                        >
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <label
                            for="is_available"
                            class="inline-flex items-center gap-3"
                        >
                            <input
                                id="is_available"
                                type="checkbox"
                                x-model="$store.mg.selectedItem.is_available"
                                class="peer sr-only"
                                role="switch"
                                checked
                            />
                            <div
                                class="relative h-6 w-11 after:h-5 after:w-5 peer-checked:after:translate-x-5 rounded-full border border-outline bg-surface-alt after:absolute after:bottom-0 after:left-[0.0625rem] after:top-0 after:my-auto after:rounded-full after:bg-on-surface after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:bg-on-primary peer-focus:outline-2 peer-focus:outline-offset-2 peer-focus:outline-outline-strong peer-focus:peer-checked:outline-primary peer-active:outline-offset-0 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:border-outline-dark dark:bg-surface-dark-alt dark:after:bg-on-surface-dark dark:peer-checked:bg-primary-dark dark:peer-checked:after:bg-on-primary-dark dark:peer-focus:outline-outline-dark-strong dark:peer-focus:peer-checked:outline-primary-dark"
                                aria-hidden="true"
                            ></div>
                        </label>
                    </div>
                </div>
            </x-slot>
        </x-admin.ui.datatable.modals>

        <script>
            const config = {
                columns: [
                    {
                        name: "CHECK_ALL",
                        class: "w-12",
                    },
                    {
                        name: "#",
                        class: "w-14",
                    },
                    {
                        name: "Image",
                        class: "w-20",
                        data: "image",
                        type: "image",
                    },
                    {
                        name: "Name",
                        class: "w-40",
                        data: "name",
                    },
                    {
                        name: "Description",
                        class: "w-64",
                        data: "description",
                    },
                    {
                        name: "Price",
                        class: "w-64",
                        data: "price",
                        type: "money",
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
                    "Updated At": "created_at",
                },

                initialFilters: {
                    appliedFilters: [],
                    availableFilters: [],
                },

                routes: {
                    fetch: "{{ route('pizzas.dataTable') }}",
                    createUpdate: "{{ route('pizzas.createUpdate') }}",
                    delete: "{{ route('pizzas.delete') }}",
                    bulkDelete: "{{ route('pizzas.bulkDelete') }}",
                },

                itemName: "ingredient",
                itemIdentifier: "name",
            };

            document.addEventListener("alpine:init", () => {
                const createDataTableManager = $.store("dataTable");
                $.store("mg", createDataTableManager(config));
            });
        </script>
    </x-slot>
</x-admin.layout>
