@props(['id'])

{{-- Requires DataTableStore! --}}

<section id="{{ $id }}" aria-label="Data Table">

    {{-- Search and Filtering --}}
    <div class="h-12 w-full"></div>
    <div class="flex flex-col justify-between md:flex-row md:items-center gap-4">
        <div class="text-on-surface dark:text-on-surface-dark relative flex w-full flex-col gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                aria-hidden="true"
                class="text-on-surface/50 dark:text-on-surface-dark/50 absolute left-2.5 top-1/2 size-5 -translate-y-1/2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" x-model="searchTerm" x-on:input.debounce.500ms="fetchData"
                x-on:keydown.enter.debounce.500ms="fetchData"
                class="rounded-radius border-outline bg-surface-alt focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border py-2 pl-10 pr-28 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                name="search" placeholder="Search" aria-label="search" autocomplete="off" />
            <button type="button" x-cloak x-show="searchTerm" x-on:click="searchTerm = ''"
                class="absolute bottom-0 right-0 top-0 flex items-center gap-2 rounded-radius border-primary text-primary focus-visible:outline-primary dark:border-primary-dark dark:text-primary-dark dark:focus-visible:outline-primary-dark whitespace-nowrap border bg-transparent px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                Clear
            </button>
        </div>
        <button type="button" x-show="availableFilters.length > 0" x-on:click="filters.show()"
            class="flex items-center gap-2 whitespace-nowrap rounded-radius bg-secondary border border-secondary px-4 py-2 text-sm font-medium tracking-wide text-on-secondary transition hover:opacity-75 text-center focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-secondary-dark dark:border-secondary-dark dark:text-on-secondary-dark dark:focus-visible:outline-secondary-dark">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
            </svg>
            Filters
        </button>
    </div>

    <div class="h-2 w-full"></div>
    <div class="flex flex-wrap gap-2">
        <template x-for="(filter, index) in appliedFilters" :key="filter.column">
            <button type="button" x-on:click="$store.mg.filters.remove(filter)"
                class="rounded-radius w-fit border border-outline bg-surface-alt px-2 py-1 text-xs font-medium text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark flex items-center gap-0.5">
                <div>
                    <span x-text="filter.name"></span>: <span x-text="filter.displayedValue"></span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-3 ms-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </template>
    </div>

    {{-- Table Controls --}}
    <div class="h-4 w-full"></div>
    <div aria-label="table-info" class="flex w-full justify-between">
        <div class="text-on-surface dark:text-on-surface-dark relative flex w-full max-w-36 flex-col gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                class="pointer-events-none absolute right-4 top-2 size-5">
                <path fill-rule="evenodd"
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
            <select id="perpage" name="perpage" x-model="nav.perPage" x-on:change="nav.changePerPage"
                :disabled="loading"
                class="rounded-radius border-outline bg-surface-alt focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full appearance-none border px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75">
                <option value="" selected>Per Page</option>
                <option value="8">8</option>
                @foreach (range(0, 100, 25) as $i)
                    <option value="{{ max(1, $i) }}">{{ max(1, $i) }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" x-on:click.debounce="fetchData" :disabled="loading" aria-label="Refresh Table"
            title="Refresh Table"
            class="rounded-radius border-outline text-on-surface focus-visible:outline-outline dark:border-outline-dark dark:text-on-surface-dark dark:focus-visible:outline-outline-dark whitespace-nowrap border bg-transparent px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"><svg
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg></button>
    </div>

    {{-- Table --}}
    <div class="h-4 w-full"></div>
    <div x-init="fetchData" x-on:refresh.window="fetchData"
        class="rounded-radius border-outline dark:border-outline-dark w-full overflow-hidden overflow-x-auto border">
        <table class="text-on-surface dark:text-on-surface-dark w-full table-fixed text-left text-sm">
            <thead
                class="border-outline bg-surface-alt text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong border-b">
                <tr>
                    <template x-for="col in config.columns" :key="col.name">
                        <th scope="col" class="p-4" :class="col.class">
                            <template x-if="col.name === 'CHECK_ALL'">
                                <label for="checkAll"
                                    class="text-on-surface dark:text-on-surface-dark flex items-center">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="checkAll" x-model="checkAll"
                                            @change="onCheckAll"
                                            class="before:content[''] border-outline bg-surface checked:border-primary checked:before:bg-primary focus:outline-outline-strong checked:focus:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark peer relative size-4 appearance-none overflow-hidden rounded border before:absolute before:inset-0 focus:outline-2 focus:outline-offset-2 active:outline-offset-0" />
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4"
                                            class="text-on-primary dark:text-on-primary-dark pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 peer-checked:visible">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                </label>
                            </template>

                            <template x-if="col.name !== 'CHECK_ALL'">
                                <button type="button" x-on:click="toggleSort(col.name)"
                                    :disabled="!config.sortables.hasOwnProperty(col.name)"
                                    class="flex items-center gap-2">
                                    <span x-text="col.name"></span>
                                    <svg x-show="config.sortables.hasOwnProperty(col.name) && sort === config.sortables[col.name]"
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
            <tbody class="divide-outline dark:divide-outline-dark divide-y">
                <template data-name="when loading" x-if="loading && items.length < 1">
                    <template x-for="(row, rowIndex) in loadSkeleton" :key="rowIndex">
                        <tr>
                            <template x-for="col in config.columns" :key="col.name">
                                <td class="p-4">
                                    <div class="rounded-radius h-6 w-full animate-pulse bg-gray-300 dark:bg-gray-600">
                                    </div>
                                </td>
                            </template>
                        </tr>
                    </template>
                </template>

                <template data-name="when has data" x-for="(row, rowIndex) in items" :key="row.id + row.updated_at">
                    <tr>
                        <template x-for="(col, colIndex) in config.columns" :key="colIndex">

                            <td>
                                <template x-if="col.name == 'CHECK_ALL'">
                                    <div class="p-4">
                                        <label :for="row.id"
                                            class="text-on-surface dark:text-on-surface-dark flex items-center">
                                            <div class="relative flex items-center">
                                                <input type="checkbox" :id="row.id"
                                                    x-on:change="onCheckSingle"
                                                    class="before:content[''] border-outline bg-surface checked:border-primary checked:before:bg-primary focus:outline-outline-strong checked:focus:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark peer relative size-4 appearance-none overflow-hidden rounded border before:absolute before:inset-0 focus:outline-2 focus:outline-offset-2 active:outline-offset-0"
                                                    :checked="checkAll" />
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    aria-hidden="true" stroke="currentColor" fill="none"
                                                    stroke-width="4"
                                                    class="text-on-primary dark:text-on-primary-dark pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 peer-checked:visible">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                </template>

                                <template x-if="String(col.name).replace(' ', '_').toLowerCase() == 'updated_at'">
                                    <div class="p-4 flex flex-col justify-start gap-1">
                                        <p x-text="row.updated_at"></p>
                                        <p class="text-xs opacity-80" x-text="`Created ${row.created_at}`"></p>
                                    </div>
                                </template>

                                <template x-if="col.type == 'image'">
                                    <div class="p-2">
                                        <img :src="row.image || window.IMG_NOT_FOUND" :alt="row.name"
                                            x-on:click="viewImage.open(row.image, row.name)"
                                            x-on:error="$store.when.imageError"
                                            :class="row.image ? 'w-12 aspect-[4/3] rounded-radius object-cover cursor-pointer' :
                                                'w-12 aspect-[4/3] rounded-radius object-cover'"
                                            draggable="false" />
                                    </div>
                                </template>

                                <template x-if="['action', 'actions'].includes(String(col.name).toLowerCase())">
                                    <div class="p-4 flex items-center gap-3">
                                        <button type="button"
                                            x-show="$store.mg.config.actions.update || $store.mg.actions.view"
                                            x-text="$store.mg.config.actions.update ? 'Edit' : 'View'"
                                            x-on:click="createUpdate.open(row)"
                                            class="rounded-radius text-primary outline-primary dark:text-primary-dark dark:outline-primary-dark cursor-pointer whitespace-nowrap bg-transparent p-0.5 font-semibold hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0">Update</button>
                                        <button type="button" x-show="$store.mg.config.actions.delete"
                                            x-on:click="deleteItem(row.id, row.name)"
                                            class="rounded-radius text-danger outline-danger dark:text-danger dark:outline-danger cursor-pointer whitespace-nowrap bg-transparent p-0.5 font-semibold hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0">Delete</button>
                                    </div>
                                </template>

                                <template x-if="col.name == '#'">
                                    <p class="p-4" x-text="rowIndex + 1"></p>
                                </template>

                                <template x-if="col.type == 'money'">
                                    <p class="p-4 wrap-break-word truncate cursor-pointer"
                                        x-on:click="$el.classList.toggle('truncate')"
                                        x-text="$store.when.displayMoney(row[col.data])">
                                    </p>
                                </template>

                                <template
                                    x-if="col.type != 'money' && col.name !== 'CHECK_ALL' && String(col.name).replace(' ', '_').toLowerCase() !== 'updated_at' && col.name !== '#' && col.type !== 'image' && !['action', 'actions'].includes(String(col.name).toLowerCase())">
                                    <p class="p-4 wrap-break-word truncate cursor-pointer"
                                        x-on:click="$el.classList.toggle('truncate')"
                                        x-text="col.data ? row[col.data] : row[col.name.toLowerCase().replaceAll(' ', '')]">
                                    </p>
                                </template>
                            </td>

                        </template>
                    </tr>
                </template>

                <template data-name="when empty" x-if="items.length < 1 && !loading">
                    <tr>
                        <td :colspan="config.columns.length" class="p-4">
                            <p class="text-md text-center font-semibold opacity-80">There's nothing to show.</p>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="h-6 w-full"></div>
    <div aria-label="table-info" class="flex flex-col items-center justify-between gap-2 md:flex-row">
        <p class="text-sm leading-tight">Showing <span x-text="items.length"></span> of <span
                x-text="nav.total"></span>
            Items </p>
        <nav aria-label="pagination">
            <ul class="flex shrink-0 items-center gap-2 text-sm font-medium">
                <li>
                    <button type="button" x-on:click="nav.prev()" :disabled="!nav.hasPreviousPage || loading"
                        :inert="!nav.hasPreviousPage"
                        class="rounded-radius text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark flex items-center p-1 disabled:opacity-60"
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
                        class="rounded-radius text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark flex items-center p-1 disabled:opacity-60"
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
</section>
