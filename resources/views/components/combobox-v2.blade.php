<div {{ $attributes->merge(['class' => 'w-full max-w-xs flex flex-col gap-1']) }}>
    <label x-bind:for="name" x-text="name.replace('_', ' ')" class="w-fit pl-0.5 text-sm text-on-surface dark:text-on-surface-dark capitalize"></label>

    <div class="relative">
        <button x-ref="comboboxTrigger"
                class="inline-flex w-full items-center justify-between gap-2 whitespace-nowrap border-outline bg-surface-alt px-4 py-2 text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark rounded-radius border"
                type="button" role="combobox" aria-haspopup="listbox" :aria-controls="name + 'List'" @click="isOpen = !isOpen" @keydown.down.prevent="openedWithKeyboard = true" @keydown.enter.prevent="openedWithKeyboard = true"
                @keydown.space.prevent="openedWithKeyboard = true" :aria-expanded="isOpen || openedWithKeyboard" :aria-label="getButtonLabel()">
            <span x-text="getButtonLabel()" class="text-sm w-full font-normal text-start overflow-hidden text-ellipsis whitespace-nowrap"></span>
            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>

        <input x-ref="hiddenInput" type="text" :name="name" hidden />

        <div x-cloak x-show="isOpen || openedWithKeyboard" x-anchor.bottom-start.offset.4.width="$refs.comboboxTrigger" x-transition x-trap.noscroll="openedWithKeyboard"
             class="z-10 flex flex-col overflow-hidden rounded-radius border border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt" :id="name + 'List'" role="listbox" aria-label="options list"
             @click.outside="closeDropdown()" @keydown.escape.window="closeDropdown()" @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()" @keydown="handleListKeydown($event)">
            <template x-if="searchable">
                <div class="relative border-b border-outline dark:border-outline-dark">
                    <svg class="absolute left-4 top-1/2 size-5 -translate-y-1/2 text-on-surface/50 dark:text-on-surface-dark/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5"
                         aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input x-ref="searchField" x-model.debounce.200ms="searchQuery"
                           class="w-full bg-transparent py-2.5 pl-11 pr-4 text-sm text-on-surface focus:outline-none disabled:cursor-not-allowed disabled:opacity-75 dark:text-on-surface-dark" type="text" placeholder="Search..."
                           aria-label="Search" />
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
                                           type="checkbox" :checked="isSelected(option)" readonly tabindex="-1" />
                                    <svg class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-on-primary peer-checked:visible dark:text-on-primary-dark"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                                <template x-if="withAvatar">
                                    <div class="flex items-center gap-2">
                                        <img class="size-8 rounded-full" :src="option.image" alt="" aria-hidden="true" />
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
                                        <img class="size-8 rounded-full" :src="option.image" alt="" aria-hidden="true" />
                                        <div class="flex flex-col text-left">
                                            <span x-text="option.label" :class="{ 'font-bold': isSelected(option) }"></span>
                                            <span x-text="option.description" class="text-xs"></span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!withAvatar">
                                    <span x-text="option.label" :class="{ 'font-bold': isSelected(option) }"></span>
                                </template>
                                <svg x-cloak x-show="isSelected(option)" class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </div>
                        </template>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
