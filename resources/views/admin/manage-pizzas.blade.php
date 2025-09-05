@use('App\Models\Ingredient')

<x-admin.layout title="Manage Pizzas">

    <main x-data="$store.mg" class="flex flex-col px-6 md:px-12">
        {{-- Header Section --}}
        <div class="h-8 w-full"></div>
        <section id="header" class="relative flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl">Manage Pizzas</h1>
                <p class="opacity-90">Manage your pizzas here.</p>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <button x-on:click="createUpdate.open({})"
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
        <x-admin.ui.datatable.modals>
            <x-slot name="form">
                <div class="grid grid-cols-12 gap-2 md:gap-4">
                    <div class="col-span-12 md:col-span-3">
                        <p class="w-fit pb-1 pl-0.5 text-sm capitalize">
                            Image
                        </p>
                        <button x-cloak x-on:click="$store.mg.deleteItem($store.mg.selectedItem.id, null, 'image')" x-show="$store.mg.selectedItem.image" x-transition
                                class="rounded-radius border-danger text-danger focus-visible:outline-danger dark:border-danger dark:text-danger dark:focus-visible:outline-danger whitespace-nowrap border bg-transparent p-2 text-center text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 active:opacity-100 active:outline-offset-0 disabled:cursor-not-allowed disabled:opacity-75"
                                type="button">
                            <svg class="size-4" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                      stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    <div class="col-span-12 md:col-span-9">
                        <img x-cloak x-on:click="$store.mg.viewImage.open($store.mg.selectedItem.image, $store.mg.selectedItem.title)" x-on:error="$store.when.imageError" x-show="$store.mg.selectedItem.image"
                             class="rounded-radius sm:max-w-1/3 w-full cursor-pointer" :src="$store.mg.selectedItem.image" />
                        <div x-cloak x-show="!$store.mg.selectedItem.image">
                            <x-file-uploader id="image" :allowedTypes="['image/jpg', 'image/jpeg', 'image/png', 'image/webp']" :maxSize="5 * MB_IN_BYTES" />
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="name">Name</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <input x-model="$store.mg.selectedItem.name" id="name" name="name"
                               class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                               type="text" autocomplete="name" placeholder="Name" required />
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="description">Description</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <textarea x-grow x-model="$store.mg.selectedItem.description" id="description" name="description"
                                  class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border px-3 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                  autocomplete="off" placeholder="Description" required></textarea>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="price">Price</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div class="relative">
                            <div class="pointer-events-none absolute right-4 top-0 bottom-0 inline-flex items-center">
                                <p x-text="$store.when.displayMoney(69).split(/\d/)[0]" class="text-sm"></p>
                            </div>
                            <input x-mask:dynamic="$money($input)" x-model="$store.mg.selectedItem.price" id="price" name="price"
                                   class="rounded-radius border-outline bg-surface-alt text-md focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark w-full border pl-3 pr-12 py-2 focus-visible:outline-2 focus-visible:outline-offset-2 disabled:cursor-not-allowed disabled:opacity-75"
                                   type="text" autocomplete="off" placeholder="Price" required />
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize" for="is_available">Is Available</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <label class="inline-flex items-center gap-3" for="is_available">
                            <input x-model="$store.mg.selectedItem.is_available" id="is_available" class="peer sr-only" :checked="$store.mg.selectedItem.is_available" type="checkbox" role="switch" />
                            <div class="relative h-6 w-11 after:h-5 after:w-5 peer-checked:after:translate-x-5 rounded-full border border-outline bg-surface-alt after:absolute after:bottom-0 after:left-[0.0625rem] after:top-0 after:my-auto after:rounded-full after:bg-on-surface after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:bg-on-primary peer-focus:outline-2 peer-focus:outline-offset-2 peer-focus:outline-outline-strong peer-focus:peer-checked:outline-primary peer-active:outline-offset-0 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:border-outline-dark dark:bg-surface-dark-alt dark:after:bg-on-surface-dark dark:peer-checked:bg-primary-dark dark:peer-checked:after:bg-on-primary-dark dark:peer-focus:outline-outline-dark-strong dark:peer-focus:peer-checked:outline-primary-dark"
                                 aria-hidden="true">
                            </div>
                        </label>
                    </div>

                    <div class="col-span-12 border-t border-t-outline dark:border-t-outline-dark"></div>

                    <div class="col-span-12 md:col-span-3">
                        <label class="w-fit pl-0.5 text-sm capitalize">Ingredients</label>
                    </div>
                    <div class="col-span-12 md:col-span-9">
                        <div x-data="selectIngredients" x-effect="selectedOptionsChanged($store.mg.selectedItem.ingredients);">
                            <div class="relative">
                                <button x-ref="comboboxTrigger"
                                        class="inline-flex w-full items-center justify-between gap-2 whitespace-nowrap border-outline bg-surface-alt px-4 py-2 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark rounded-radius border"
                                        type="button" role="combobox" aria-haspopup="listbox" :aria-controls="name + 'List'" @click="isOpen = !isOpen" @keydown.down.prevent="openedWithKeyboard = true"
                                        @keydown.enter.prevent="openedWithKeyboard = true" @keydown.space.prevent="openedWithKeyboard = true" :aria-expanded="isOpen || openedWithKeyboard" :aria-label="getButtonLabel()">
                                    <span x-text="getButtonLabel()" class="text-sm w-full font-normal text-start overflow-hidden text-ellipsis whitespace-nowrap"></span>
                                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <input x-ref="hiddenInput" type="text" hidden />

                                <div x-cloak x-show="isOpen || openedWithKeyboard" x-anchor.bottom-start.offset.4.width="$refs.comboboxTrigger" x-transition x-trap.noscroll="openedWithKeyboard"
                                     class="z-10 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt" :id="name + 'List'" role="listbox"
                                     aria-label="options list" @click.outside="closeDropdown()" @keydown.escape.window="closeDropdown()" @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()"
                                     @keydown="handleListKeydown($event)">
                                    <template x-if="searchable">
                                        <div class="relative border-b border-outline dark:border-outline-dark">
                                            <svg class="absolute left-4 top-1/2 size-5 -translate-y-1/2 text-on-surface/50 dark:text-on-surface-dark/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                                 fill="none" stroke-width="1.5" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                            </svg>
                                            <input x-ref="searchField" x-model.debounce.200ms="searchQuery"
                                                   class="w-full bg-transparent py-2.5 pl-11 pr-4 text-sm text-on-surface focus:outline-none disabled:cursor-not-allowed disabled:opacity-75 dark:text-on-surface-dark" type="text"
                                                   placeholder="Search..." aria-label="Search" />
                                        </div>
                                    </template>

                                    <ul x-ref="listbox" class="flex max-h-44 flex-col overflow-y-auto py-1.5">
                                        <li x-show="filteredOptions.length === 0" class="px-4 py-2 text-sm text-center text-on-surface/70 dark:text-on-surface-dark/70">
                                            <span>No matches found</span>
                                        </li>
                                        <template x-for="(option, index) in filteredOptions" :key="option.value">
                                            <li class="combobox-option text-sm focus-visible:outline-none" :id="name + '-option-' + index" role="option" :aria-selected="isSelected(option)" tabindex="0" @click="selectOption(option)"
                                                @keydown.enter.prevent="selectOption(option)" @keydown.space.prevent="selectOption(option)">
                                                <template x-if="multiple">
                                                    <div class="flex items-center gap-3 px-4 py-2 cursor-pointer text-on-surface dark:text-on-surface-dark hover:bg-surface-dark-alt/5 dark:hover:bg-surface-alt/5 focus-visible:bg-surface-dark-alt/5 dark:focus-visible:bg-surface-alt/10"
                                                         :class="{ 'text-on-surface-strong dark:text-on-surface-dark-strong': isSelected(option) }">
                                                        <div class="relative flex items-center">
                                                            <input class="pointer-events-none before:content[''] peer relative size-4 appearance-none overflow-hidden border border-outline bg-surface-alt before:absolute before:inset-0 checked:border-primary checked:before:bg-primary focus:outline-none checked:focus:outline-none dark:border-outline-dark rounded-sm dark:bg-surface-dark-alt dark:checked:border-primary-dark dark:checked:before:bg-primary-dark"
                                                                   type="checkbox" :checked="isSelected(option)" :name="name" :value="option.value" readonly tabindex="-1" />
                                                            <svg class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-on-primary peer-checked:visible dark:text-on-primary-dark"
                                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="4" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                            </svg>
                                                        </div>
                                                        <template x-if="withAvatar">
                                                            <div class="flex items-center gap-2">
                                                                <img x-on:error="$store.when.imageError" class="size-8 rounded-full object-cover" :src="option.image || 'a'" alt="" aria-hidden="true" />
                                                                <div class="flex flex-col">
                                                                    <span x-text="option.label" :class="{ 'font-bold': isSelected(option) }"></span>
                                                                    <span x-text="option.description" class="text-xs"></span>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <template x-if="!withAvatar">
                                                            <span x-text="option.label"></span>
                                                        </template>
                                                    </div>
                                                </template>

                                                <template x-if="!multiple">
                                                    <div
                                                         class="inline-flex w-full justify-between items-center gap-6 px-4 py-2 cursor-pointer text-on-surface dark:text-on-surface-dark hover:bg-surface-dark-alt/5 dark:hover:bg-surface-alt/5 focus-visible:bg-surface-dark-alt/5 dark:focus-visible:bg-surface-alt/10">
                                                        <template x-if="withAvatar">
                                                            <div class="flex items-center gap-2">
                                                                <img x-on:error="$store.when.imageError" class="size-8 rounded-full" :src="option.image || 'a'" alt="" aria-hidden="true" />
                                                                <div class="flex flex-col text-left">
                                                                    <span x-text="option.label" :class="{ 'font-bold': isSelected(option) }"></span>
                                                                    <span x-text="option.description" class="text-xs"></span>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <template x-if="!withAvatar">
                                                            <span x-text="option.label" :class="{ 'font-bold': isSelected(option) }"></span>
                                                        </template>
                                                        <svg x-cloak x-show="isSelected(option)" class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2"
                                                             aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                        </svg>
                                                    </div>
                                                </template>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-4">
                                <template x-for="ing in selectedOptions" :key="`${$store.mg.selectedItem.id}-${ing.value}`">
                                    <div class="w-full grid grid-cols-12 gap-2 md:gap-4 mb-2">
                                        <div class="col-span-12 md:col-span-3">
                                            <label x-text="ing.label" class="w-fit pl-0.5 text-sm capitalize" :for="`qty-${ing.value}`"></label>
                                            <span class="w-fit pl-0.5 text-sm">Qty <span x-text="`(${ing.unit})`"></span></span>
                                        </div>
                                        <div x-data="{ currentVal: (ing.pivot && ing.pivot.quantity) || 1, minVal: 0, maxVal: ing.stock_quantity, incrementAmount: 1 }" class="col-span-12 md:col-span-9">
                                            <div x-on:dblclick.prevent class="flex items-center">
                                                <button x-on:click="currentVal = Math.max(minVal, currentVal - incrementAmount)"
                                                        class="flex h-10 items-center justify-center rounded-l-radius border border-outline bg-surface-alt px-4 py-2 text-on-surface hover:opacity-75 focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                                                        type="button" aria-label="subtract">
                                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                                                    </svg>
                                                </button>
                                                <input x-model="currentVal"
                                                       class="border-x-none h-10 max-w-24 w-full rounded-none border-y border-outline bg-surface-alt/50 text-center text-on-surface-strong focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark"
                                                       :id="`qty-${ing.value}`" :name="`ingredients[${ing.value}]`" type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                                <button x-on:click="currentVal = Math.min(maxVal, currentVal + incrementAmount)"
                                                        class="flex h-10 items-center justify-center rounded-r-radius border border-outline bg-surface-alt px-4 py-2 text-on-surface hover:opacity-75 focus-visible:z-10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:focus-visible:outline-primary-dark"
                                                        type="button" aria-label="add">
                                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
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
                        class: "w-32",
                        data: "price",
                        type: "money",
                    },
                    {
                        name: "Available",
                        class: "w-32",
                        data: (row, index) => {
                            return row.is_available ? 'Yes' : 'No';
                        },
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
                    availableFilters: [
                        {
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
                            min: new Date(),
                            max: new Date('2030-01-31 00:00:00')
                        }
                    ],
                },

                routes: {
                    fetch: "{{ route('pizzas.dataTable') }}",
                    createUpdate: "{{ route('pizzas.createUpdate') }}",
                    delete: "{{ route('pizzas.delete') }}",
                    bulkDelete: "{{ route('pizzas.bulkDelete') }}",
                },

                listeners: {
                    onBeforeUpdate: (formData, model) => {
                        formData.set('is_available', model.is_available ? 1 : 0);
                    },
                    onBeforeCreate: (formData, model) => {
                        formData.set('is_available', model.is_available ? 1 : 0);
                    },
                },

                itemName: "pizza",
                itemIdentifier: "name",
            };

            const mapOptions = (ing) => {
                return {
                    label: ing.name,
                    value: ing.id,
                    image: ing.image,
                    description: ing.description,
                    unit: ing.unit,
                    stock_quantity: ing.stock_quantity,
                    pivot: ing.pivot,
                }
            };

            const ingredientOptions = @js(Ingredient::query()->take(100)->get())
                .map(mapOptions);

            document.addEventListener("alpine:init", () => {
                const store = createDataTableStore(config);
                $.store('mg', store);

                $.data('selectIngredients', () => ({
                    name: '_ingredients',
                    placeholder: 'Select ingredients',
                    allOptions: ingredientOptions,
                    selectedOptions: [],
                    filteredOptions: [],
                    isOpen: false,
                    openedWithKeyboard: false,
                    searchQuery: '',
                    selectedOption: null,
                    multiple: true,
                    searchable: true,
                    withAvatar: true,

                    init() {
                        this.filteredOptions = [...this.allOptions];

                        this.$watch('isOpen', isOpen => {
                            if (isOpen && this.searchable) {
                                this.$nextTick(() => this.$refs.searchField.focus());
                            }
                        });
                        this.$watch('searchQuery', () => {
                            this.filterOptions();
                        });
                    },

                    getButtonLabel() {
                        if (this.multiple) {
                            if (this.selectedOptions.length === 0) return this.placeholder;
                            return this.selectedOptions
                                .map(value => value.label)
                                .join(', ');
                        }
                        return this.selectedOption ? this.selectedOption.label : this.placeholder;
                    },

                    isSelected(option) {
                        if (this.multiple) {
                            return this.selectedOptions.some(selected => selected.value === option.value);
                        }
                        return this.selectedOption ? this.selectedOption.value === option.value : false;
                    },

                    closeDropdown() {
                        this.isOpen = false;
                        this.openedWithKeyboard = false;
                    },

                    selectOption(option) {
                        if (this.multiple) {
                            const index = this.selectedOptions.findIndex(selected => selected.value === option.value);

                            if (index > -1) {
                                this.selectedOptions.splice(index, 1);
                            } else {
                                this.selectedOptions.push(option);
                            }

                            this.$refs.hiddenInput.value = this.selectedOptions.map(opt => opt.value).join(',');
                        } else {
                            this.selectedOption = option;
                            this.$refs.hiddenInput.value = option.value;
                            this.closeDropdown();
                        }
                    },

                    filterOptions() {
                        if (!this.searchQuery) {
                            this.filteredOptions = [...this.allOptions];
                            return;
                        }
                        this.filteredOptions = this.allOptions.filter(option =>
                            option.label.toLowerCase().includes(this.searchQuery.toLowerCase())
                        );
                    },

                    selectedOptionsChanged(ingredientsFromServer) {
                        const serverIngredients = ingredientsFromServer || [];
                        this.selectedOptions = serverIngredients.map(mapOptions);
                    },

                    handleListKeydown(event) {
                        if (!this.searchable && /^[a-zA-Z0-9]$/.test(event.key)) {
                            event.preventDefault();
                            const firstMatch = this.filteredOptions.find(opt =>
                                opt.label.toLowerCase().startsWith(event.key.toLowerCase())
                            );
                            if (firstMatch) {
                                const index = this.filteredOptions.indexOf(firstMatch);
                                this.$refs.listbox.children[index + 1]?.focus();
                            }
                        }
                    },
                }));

            });
        </script>
    </x-slot>
</x-admin.layout>
