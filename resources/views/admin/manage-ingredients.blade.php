@use('App\Models\Ingredient')

<x-admin.layout>

    <main class="flex flex-col px-6 md:px-12" x-data="$store.mg">

        {{-- Header Section --}}
        <div class="w-full h-6"></div>
        <section id="header" class="flex items-center justify-between relative">
            <div>
                <h1 class="text-2xl">Manage Ingredients</h1>
                <p class="opacity-90">Manage your ingredients here.</p>
            </div>
            <div class="flex gap-4 items-center">
                <button type="button" x-on:click="createUpdate.open({})"
                    class="whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-2 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Insert
                </button>
                <button type="button" x-show="selected.length > 0" x-cloak x-transition x-on:click="bulkDelete.open()"
                    class="inline-flex justify-center items-center gap-2 whitespace-nowrap rounded-radius bg-danger border border-danger dark:border-danger px-4 py-2 text-sm font-medium tracking-wide text-on-danger transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-danger active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-danger dark:text-on-danger dark:focus-visible:outline-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Bulk Delete
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

        {{-- Table Controls --}}
        <div class="w-full h-12"></div>
        <div aria-label="table-info" class="w-full flex justify-between">
            <div class="relative flex w-full max-w-36 flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    class="absolute pointer-events-none right-4 top-2 size-5">
                    <path fill-rule="evenodd"
                        d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                        clip-rule="evenodd" />
                </svg>
                <select id="perpage" name="perpage" x-model="nav.perPage" x-on:change="nav.changePerPage"
                    :disabled="loading"
                    class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark">
                    <option value="" selected>Per Page</option>
                    <option value="8">8</option>
                    @foreach (range(0, 100, 25) as $i)
                        <option value="{{ max(1, $i) }}">{{ max(1, $i) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" x-on:click.debounce="fetchData" :disabled="loading" aria-label="Refresh Table"
                title="Refresh Table"
                class="whitespace-nowrap bg-transparent rounded-radius border border-outline px-4 py-2 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-outline active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-outline-dark dark:text-on-surface-dark dark:focus-visible:outline-outline-dark"><svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg></button>
        </div>

        {{-- Table --}}
        <div class="w-full h-6"></div>
        <div x-init="fetchData" x-on:refresh.window="fetchData"
            class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark">
            <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark table-fixed">
                <thead
                    class="border-b border-outline bg-surface-alt text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                    <tr>
                        <template x-for="col in cols" :key="col.name">
                            <th scope="col" class="p-4" :class="col.class">
                                <template x-if="col.name === 'CHECK_ALL'">
                                    <label for="checkAll"
                                        class="flex items-center text-on-surface dark:text-on-surface-dark">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" id="checkAll" x-model="checkAll"
                                                @change="onCheckAll"
                                                class="before:content[''] peer relative size-4 appearance-none overflow-hidden rounded border border-outline bg-surface before:absolute before:inset-0 checked:border-primary checked:before:bg-primary focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark" />
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4"
                                                class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-on-primary peer-checked:visible dark:text-on-primary-dark">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </label>
                                </template>

                                <template x-if="col.name !== 'CHECK_ALL'">
                                    <button type="button" x-on:click="toggleSort(col.name)"
                                        :disabled="!sortables.hasOwnProperty(col.name)"
                                        class="flex items-center gap-2">
                                        <span x-text="col.name"></span>
                                        <svg x-show="sortables.hasOwnProperty(col.name) && sort === sortables[col.name]"
                                            x-cloak :class="sortDesc ? 'size-4 rotate-180' : 'size-4'"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </template>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    <template data-name="when loading" x-if="loading">
                        <template x-for="(row, rowIndex) in loadSkeleton" :key="rowIndex">
                            <tr>
                                <template x-for="col in cols" :key="col.name">
                                    <td class="p-4">
                                        <div
                                            class="h-6 w-full animate-pulse rounded-radius bg-gray-300 dark:bg-gray-600">
                                        </div>
                                    </td>
                                </template>
                            </tr>
                        </template>
                    </template>

                    <template data-name="when has data" x-for="(row, rowIndex) in items"
                        :key="row.id + row.updated_at">
                        <tr>
                            <td class="p-4">
                                <label :for="row.id"
                                    class="flex items-center text-on-surface dark:text-on-surface-dark ">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" :id="row.id" x-on:change="onCheckSingle"
                                            class="before:content[''] peer relative size-4 appearance-none overflow-hidden rounded border border-outline bg-surface before:absolute before:inset-0 checked:border-primary checked:before:bg-primary focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark"
                                            :checked="checkAll" />
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4"
                                            class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-on-primary peer-checked:visible dark:text-on-primary-dark">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                </label>
                            </td>
                            <td class="p-4" x-text="rowIndex + 1"></td>
                            <td class="p-2">
                                <img src="https://placehold.co/400x300" :alt="row.name"
                                    class="w-12 aspect-[4/3] rounded-radius object-cover cursor-pointer"
                                    draggable="false" />
                            </td>
                            <td class="p-4 truncate" x-text="row.name"></td>
                            <td class="p-4" x-text="`${row.stock_quantity} ${row.unit}`"></td>
                            <td class="p-4">
                                <div class="flex flex-col gap-1 justify-start">
                                    <p x-text="row.updated_at"></p>
                                    <p class="text-xs opacity-80" x-text="`Created ${row.created_at}`"></p>
                                </div>
                            </td>
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
                            <td :colspan="cols.length" class="p-4">
                                <p class="text-center text-md font-semibold opacity-80">There's nothing to show.</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="w-full h-6"></div>
        <div aria-label="table-info" class="flex flex-col md:flex-row justify-between items-center gap-2">
            <p class="text-sm leading-tight">Showing <span x-text="items.length"></span> of <span
                    x-text="nav.total"></span>
                Items </p>
            <nav aria-label="pagination">
                <ul class="flex shrink-0 items-center gap-2 text-sm font-medium">
                    <li>
                        <button type="button" x-on:click="nav.prev()" :disabled="!nav.hasPreviousPage || loading"
                            :inert="!nav.hasPreviousPage"
                            class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-60"
                            aria-label="previous page">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true" class="size-6">
                                <path fill-rule="evenodd"
                                    d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                            Previous
                        </button>
                    </li>

                    <template x-for="page in nav.pages" :key="page">
                        <li>
                            <button type="button" x-on:click="nav.goTo(page)"
                                :disabled="nav.currentPage === page || loading"
                                :class="nav.currentPage === page ?
                                    'flex size-6 items-center justify-center rounded-radius bg-primary p-1 font-bold text-on-primary dark:bg-primary-dark dark:text-on-primary-dark' :
                                    'flex size-6 items-center justify-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark'"
                                :aria-label="`page ${page}`" x-text="page">
                            </button>
                        </li>
                    </template>

                    <li>
                        <button type="button" x-on:click="nav.next()" :disabled="!nav.hasNextPage || loading"
                            :inert="!nav.hasNextPage"
                            class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-60"
                            aria-label="next page">
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true" class="size-6">
                                <path fill-rule="evenodd"
                                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </li>
                </ul>
            </nav>
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

                <div class="flex justify-end gap-4">
                    <button type="button" x-on:click="$store.mg.createUpdate.hide()"
                        class="whitespace-nowrap rounded-radius bg-surface-alt border border-surface-alt px-4 py-2 text-xs font-medium tracking-wide text-on-surface-strong transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt">Cancel</button>
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

            <div class="w-full flex justify-center gap-4">
                <button type="button" x-on:click="window.location.reload()"
                    class="whitespace-nowrap rounded-radius bg-primary border border-primary px-6 py-2 text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">Refresh</button>
                <button type="button" x-on:click="$store.mg.error.hide()"
                    class="whitespace-nowrap rounded-radius bg-surface-dark border border-surface-dark px-4 py-2 text-xs font-medium tracking-wide text-on-surface-dark transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-dark active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface dark:border-surface dark:text-on-surface dark:focus-visible:outline-surface">Try
                    Again</button>

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

    <x-modal id="bulk-delete-modal">
        <div class="p-6">

            <div class="flex flex-col gap-4 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-24 stroke-danger">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                </svg>
                <h2 class="text-xl font-semibold">Warning!</h2>
                <p class="text-md bulk-delete-message"></p>
            </div>

            <div class="w-full h-8"></div>

            <div class="w-full flex justify-center gap-6">
                <button type="button" x-on:click="$store.mg.bulkDelete.hide()"
                    class="whitespace-nowrap rounded-radius bg-surface-alt border border-surface-alt px-6 py-2 text-sm font-medium tracking-wide text-on-surface-strong transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt">No</button>

                <button type="button" x-on:click="$store.mg.bulkDelete.handleConfirm($event)"
                    class="whitespace-nowrap rounded-radius bg-danger border border-danger px-6 py-2 text-sm font-medium tracking-wide text-on-danger transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-danger active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-danger-dark dark:border-danger-dark dark:text-on-danger-dark dark:focus-visible:outline-danger-dark">Yes</button>
            </div>

        </div>
    </x-modal>

    <x-toast />

    <script>
        document.addEventListener('alpine:init', () => {
            $.store('mg', {
                cols: [{
                        name: 'CHECK_ALL',
                        class: 'w-12'
                    }, {
                        name: '#',
                        class: 'w-14'
                    }, {
                        name: 'Image',
                        class: 'w-20'
                    }, {
                        name: 'Name',
                        class: ''
                    },
                    {
                        name: 'Stock',
                        class: 'w-32'
                    }, {
                        name: 'Updated At',
                        class: 'w-96'
                    }, {
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

                loadingRows: 3,

                loading: true,

                checkAll: false,
                selected: [],
                items: [],

                sort: 'created_at',
                sortDesc: true,

                createUpdate: null,
                confirm: null,
                bulkDelete: null,
                error: null,
                nav: null,

                init() {
                    this.bulkDelete = {
                        onConfirm: null,
                        element: document.querySelector('#bulk-delete-modal'),
                        show: () => $.notifier.modal(this.bulkDelete.element, 'show'),
                        hide: () => $.notifier.modal(this.bulkDelete.element, 'hide'),
                        setMessage: (message) => {
                            this.bulkDelete.element.querySelector('.bulk-delete-message')
                                .innerText = message;
                        },
                        handleConfirm(e) {
                            e.preventDefault();
                            if (this.onConfirm && typeof this.onConfirm === "function") {
                                this.onConfirm(e);
                            }
                        },
                        open: () => {

                            const listString = this.items
                                .filter(item => this.selected.includes(item.id))
                                .map(item => `[${item.id}] ${item.name}`)
                                .join(',\n');

                            this.bulkDelete.setMessage(
                                `Are you sure you want to delete:\n${listString}?\n\nThis action cannot be undone!`
                            );

                            this.bulkDelete.onConfirm = () => {
                                axios.delete(@js(route('ingredients.bulkDelete')), {
                                        data: {
                                            ids: this.selected
                                        }
                                    }).then(res => {
                                        this.updateItems(this.items.filter((item) => !this
                                            .selected.includes(item.id)));
                                        this.selected = [];
                                        this.fetchData();

                                        const message = res?.data?.message || 'Deleted';
                                        $.notifier.toast({
                                            variant: 'success',
                                            title: 'Success',
                                            message
                                        });
                                        this.bulkDelete.hide();
                                    })
                                    .catch(err => {
                                        this.bulkDelete.hide();
                                        const message = err?.response?.data?.message || err
                                            .message;
                                        $.notifier.toast({
                                            variant: 'danger',
                                            title: 'Oops...',
                                            message
                                        });
                                    });
                            };

                            this.bulkDelete.show();
                        }
                    };

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
                        prev: () => {
                            this.nav.currentPage--;
                            this.fetchData();
                        },
                        next: () => {
                            this.nav.currentPage++;
                            this.fetchData();
                        },
                        changePerPage: () => {
                            this.nav.currentPage = 1;
                            this.fetchData();
                        }
                    };

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
                                'input,select,textarea'
                            );
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

                                    const found = this.items.findIndex((item) => item.id ===
                                        updatedItem.id);

                                    if (found === -1) {

                                        if (this.items.length >= this.nav.perPage)
                                            this.updateItems([updatedItem, ...this.items
                                                .slice(
                                                    0, -1)
                                            ]);

                                        else
                                            this.updateItems([updatedItem, ...this.items]);

                                    } else {
                                        const newItems = [...this.items];
                                        newItems[found] = updatedItem;

                                        this.updateItems(newItems);
                                    }

                                })
                                .catch(err => {
                                    const message = err?.response?.data?.message || err
                                        .message;
                                    $.notifier.toast({
                                        variant: 'danger',
                                        title: 'Error',
                                        message
                                    });
                                });
                        }
                    };
                },

                get loadSkeleton() {
                    return Array.from({
                        length: this.loadingRows
                    }, () => this.cols);
                },

                updateItems(newItems = []) {
                    this.items = newItems;
                    this.checkAll = false;
                    this.loadingRows = Math.max(this.nav.perPage, this.items.length, 1);
                },

                onCheckAll() {
                    if (this.checkAll) {
                        this.selected = this.items.map((item) => item.id);
                    } else {
                        this.selected = [];
                    }
                },

                onCheckSingle(e) {
                    if (!e.target.checked) this.selected = this.selected.filter((id) =>
                        id !== parseInt(e.target.id));
                    else this.selected = [parseInt(e.target.id), ...this.selected];

                    if (this.selected.length < 1) this.checkAll = false;
                },

                toggleSort(colName) {
                    if (!this.sortables.hasOwnProperty(colName)) return;

                    const newSortColumn = this.sortables[colName];
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
                    axios.get(@js(route('ingredients.dataTable')), {
                            params: {
                                page: this.nav.currentPage,
                                per_page: this.nav.perPage || null,
                                sort: this.sort,
                                sort_desc: this.sortDesc
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
            });
        });
    </script>

</x-admin.layout>
