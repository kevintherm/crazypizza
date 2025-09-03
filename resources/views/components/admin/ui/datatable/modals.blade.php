<div class="data-table-modals">
    <x-modal id="create-update-modal">
        <form x-on:submit.prevent="$store.mg.createUpdate.process($event)" x-on:keydown.enter="$event.preventDefault()" id="create-update-form">
            <input x-model="$store.mg.selectedItem.id" name="id" type="hidden">

            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-lg font-semibold">Ingredient</h1>
                    <button x-on:click="$store.mg.createUpdate.hide()"
                            class="rounded-radius bg-surface-alt border-surface-alt text-on-surface-strong focus-visible:outline-surface-alt dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                            type="button">
                        <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="h-8 w-full"></div>

                {{ $form }}

                <div class="h-8 w-full"></div>

                <div class="flex justify-end gap-4">
                    <button x-on:click="$store.mg.createUpdate.hide()"
                            class="rounded-radius bg-surface-alt border-surface-alt text-on-surface-strong focus-visible:outline-surface-alt dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt whitespace-nowrap border px-4 py-2 text-center text-xs font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                            type="button">Cancel</button>
                    <button class="rounded-radius bg-primary border-primary text-on-primary focus-visible:outline-primary dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark whitespace-nowrap border px-4 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                            type="submit">Save</button>
                </div>
            </div>
        </form>
    </x-modal>

    <x-modal id="error-modal" :static="true">
        <div class="p-6">

            <div class="flex flex-col items-center gap-4">
                <svg class="stroke-danger size-24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <h2 class="text-xl font-semibold">Error</h2>
                <p class="text-md error-message">Something went wrong. Please try again later.</p>
                <p class="text-sm opacity-80">Please contact the administrator if this error keeps happening.</p>
            </div>

            <div class="h-8 w-full"></div>

            <div class="flex w-full justify-center gap-4">
                <button x-on:click="window.location.reload()"
                        class="rounded-radius bg-primary border-primary text-on-primary focus-visible:outline-primary dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark whitespace-nowrap border px-6 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        type="button">Refresh</button>
                <button x-on:click="$store.mg.error.hide()"
                        class="rounded-radius bg-surface-dark border-surface-dark text-on-surface-dark focus-visible:outline-surface-dark dark:bg-surface dark:border-surface dark:text-on-surface dark:focus-visible:outline-surface whitespace-nowrap border px-4 py-2 text-center text-xs font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        type="button">Try
                    Again</button>

            </div>

        </div>
    </x-modal>

    <x-modal id="confirm-modal">
        <div class="p-6">

            <div class="flex flex-col items-center gap-4">
                <svg class="stroke-primary size-24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                </svg>
                <h2 class="text-xl font-semibold">Are you sure?</h2>
                <p class="text-md confirm-message"></p>
            </div>

            <div class="h-8 w-full"></div>

            <div class="flex w-full justify-center gap-6">
                <button x-on:click="$store.mg.confirm.hide()"
                        class="rounded-radius bg-surface-alt border-surface-alt text-on-surface-strong focus-visible:outline-surface-alt dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt whitespace-nowrap border px-6 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        type="button">No</button>

                <button x-on:click="$store.mg.confirm.handleConfirm($event)"
                        class="rounded-radius bg-primary border-primary text-on-primary focus-visible:outline-primary dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark whitespace-nowrap border px-6 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        type="button">Yes</button>
            </div>

        </div>
    </x-modal>

    <x-modal id="bulk-delete-modal">
        <div class="p-6">

            <div class="flex flex-col items-center gap-4">
                <svg class="stroke-danger size-24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                </svg>
                <h2 class="text-xl font-semibold">Warning!</h2>
                <p class="text-md bulk-delete-message"></p>
            </div>

            <div class="h-8 w-full"></div>

            <div class="flex w-full justify-center gap-6">
                <button x-on:click="$store.mg.bulkDelete.hide()"
                        class="rounded-radius bg-surface-alt border-surface-alt text-on-surface-strong focus-visible:outline-surface-alt dark:bg-surface-dark-alt dark:border-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt whitespace-nowrap border px-6 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        type="button">No</button>

                <button x-on:click="$store.mg.bulkDelete.handleConfirm($event)"
                        class="rounded-radius bg-danger border-danger text-on-danger focus-visible:outline-danger dark:bg-danger-dark dark:border-danger-dark dark:text-on-danger-dark dark:focus-visible:outline-danger-dark whitespace-nowrap border px-6 py-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                        type="button">Yes</button>
            </div>

        </div>
    </x-modal>

    <x-modal id="view-image-modal">
        <div class="rounded-radius bg-transparent">
            <img x-on:error="$store.when.imageError" x-on:load="$refs.info.innerText = `${$el.naturalWidth}x${$el.naturalHeight}`" class="image w-full max-w-2xl" src="" alt="" />
            <p x-ref="info" class="my-2 text-center"></p>
        </div>
    </x-modal>

    <x-modal id="filters-modal">
        <form x-on:submit.prevent="$store.mg.filters.process">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-lg font-semibold">Filters</h1>
                    <button x-on:click="$store.mg.filters.hide()"
                            class="rounded-radius border border-surface-alt bg-surface-alt text-on-surface-strong transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75 dark:border-surface-dark-alt dark:bg-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt whitespace-nowrap px-4 py-2 text-center text-sm font-medium tracking-wide"
                            type="button">
                        <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="h-8 w-full"></div>

                {{-- TODO --}}

                <div class="flex flex-col gap-2 md:gap-4">
                    <template x-for="(filter, index) in $store.mg.availableFilters" :key="filter.name">
                        <div>
                            <label x-text="filter.name" class="mb-2 w-fit text-sm" :for="filter.column"></label>

                            <template x-if="filter.type == 'number' || filter.type == 'text'">
                                <input x-mask:dynamic="filter.mask" x-on:input="filter.type == 'number' ? $store.when.inputNumber($event) : null"
                                       class="w-full rounded-radius border border-outline bg-surface-alt px-3 py-2 text-sm text-md transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                       :id="filter.column" :type="filter.type" :min="filter.min" :max="filter.max" :step="filter.step" :minlength="filter.minLength" :maxlength="filter.maxLength" :name="filter.column"
                                       :placeholder="filter.name" autocomplete="off">
                            </template>

                            <template x-if="filter.type == 'range'">
                                <div class="flex items-center gap-2">
                                    <input x-on:input="$store.when.inputNumber"
                                           class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                           type="text" :name="`${filter.column}.min`" placeholder="Min" autocomplete="off">
                                    <span>-</span>
                                    <input x-on:input="$store.when.inputNumber"
                                           class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                           type="text" :name="`${filter.column}.max`" placeholder="Max" autocomplete="off">
                                </div>
                            </template>

                            <template x-if="filter.type == 'select'">
                                <div x-data="combobox({ allOptions: filter.options, selectedOption: filter.selectedOption })" x-on:keydown="handleKeydownOnOptions($event)" x-on:keydown.esc.window="isOpen = false, openedWithKeyboard = false" class="w-full">
                                    <div class="relative">
                                        <button x-ref="comboboxTrigger" x-on:click="isOpen = ! isOpen" x-on:keydown.down.prevent="openedWithKeyboard = true" x-on:keydown.enter.prevent="openedWithKeyboard = true"
                                                x-on:keydown.space.prevent="openedWithKeyboard = true" x-bind:aria-expanded="isOpen || openedWithKeyboard" x-bind:aria-label="selectedOption ? selectedOption.label : 'Please Select'"
                                                class="inline-flex w-full items-center justify-between gap-2 rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                                                type="button" role="combobox" :aria-controls="filter.name" aria-haspopup="listbox">
                                            <span x-text="selectedOption ? selectedOption.label : 'Please Select'" class="text-sm font-normal"></span>
                                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd">
                                                </path>
                                            </svg>
                                        </button>

                                        <input x-ref="hiddenTextField" :id="filter.column" :name="filter.column" hidden="">

                                        <div x-show="isOpen || openedWithKeyboard" x-on:click.outside="isOpen = false, openedWithKeyboard = false" x-on:keydown.down.prevent="$focus.wrap().next()"
                                             x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition x-trap.noscroll="openedWithKeyboard" x-anchor.bottom-start.offset.4="$refs.comboboxTrigger"
                                             class="z-40 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt" :id="filter.name" role="listbox"
                                             :aria-label="filter.name + 'list'">

                                            <div class="relative">
                                                <svg class="absolute left-4 top-1/2 size-5 -translate-y-1/2 text-on-surface/50 dark:text-on-surface-dark/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                                     fill="none" stroke-width="1.5" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z">
                                                    </path>
                                                </svg>
                                                <input x-ref="searchField" x-on:input="getFilteredOptions($el.value)"
                                                       class="w-full border-b border-outline bg-surface-alt py-2.5 pl-11 pr-4 text-sm text-on-surface transition focus:outline-none focus-visible:border-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:border-primary-dark"
                                                       type="text" aria-label="Search" placeholder="Search">
                                            </div>

                                            <ul class="flex max-h-44 flex-col overflow-y-auto">
                                                <li x-ref="noResultsMessage" class="hidden px-4 py-2 text-sm text-on-surface dark:text-on-surface-dark">
                                                    <span>No matches found</span>
                                                </li>
                                                <template x-for="(item, index) in options" x-bind:key="item.value">
                                                    <li x-on:click="setSelectedOption(item)" x-on:keydown.enter="setSelectedOption(item)" x-bind:id="'option-' + index"
                                                        class="inline-flex cursor-pointer justify-between gap-6 bg-neutral-50 px-4 py-2 text-sm text-on-surface transition hover:bg-surface-dark-alt/5 hover:text-on-surface-strong focus-visible:bg-surface-dark-alt/5 focus-visible:text-on-surface-strong focus-visible:outline-none dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:bg-surface-alt/5 dark:hover:text-on-surface-dark-strong dark:focus-visible:bg-surface-alt/10 dark:focus-visible:text-on-surface-dark-strong"
                                                        role="option" tabindex="0">
                                                        <span x-bind:class="selectedOption == item ? 'font-bold' : ''" x-text="item.label"></span>
                                                        <span x-text="selectedOption == item ? 'selected' : ''" class="sr-only"></span>
                                                        <svg x-cloak x-show="selectedOption == item" class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2"
                                                             aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path>
                                                        </svg>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <div class="h-8 w-full"></div>

                <div class="flex justify-end gap-4">
                    <button x-on:click="$store.mg.filters.hide()"
                            class="rounded-radius border border-surface-alt bg-surface-alt px-4 py-2 text-center text-xs font-medium tracking-wide text-on-surface-strong transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75 dark:border-surface-dark-alt dark:bg-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt whitespace-nowrap"
                            type="button">Cancel</button>
                    <button class="rounded-radius border border-primary bg-primary px-4 py-2 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75 dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark whitespace-nowrap"
                            type="submit">Apply</button>
                </div>
            </div>
        </form>
    </x-modal>

    <x-toast />
</div>
