export const combobox = (comboboxData = {
    allOptions: [], selectedOption: null
},) => ({
    options: comboboxData.allOptions,
    isOpen: false,
    openedWithKeyboard: false,
    selectedOption: comboboxData.selectedOption,
    setSelectedOption(option) {
        this.selectedOption = option
        this.isOpen = false
        this.openedWithKeyboard = false
        this.$refs.hiddenTextField.value = option.value
    },
    getFilteredOptions(query) {
        this.options = comboboxData.allOptions.filter((option) =>
            option.label.toLowerCase().includes(query.toLowerCase()),
        )
        if (this.options.length === 0) {
            this.$refs.noResultsMessage.classList.remove('hidden')
        } else {
            this.$refs.noResultsMessage.classList.add('hidden')
        }
    },
    // if the user presses backspace or the alpha-numeric keys, focus on the search field
    handleKeydownOnOptions(event) {
        if ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 48 && event
            .keyCode <= 57) || event.keyCode === 8) {
            this.$refs.searchField.focus()
        }
    },
});

export const comboboxV2 = (config = {}) => ({
    // --- CONFIGURATION ---
    allOptions: config.options || [],
    multiple: config.multiple || false,
    searchable: config.searchable || false,
    withAvatar: config.withAvatar || false,
    name: config.name || 'combobox',
    placeholder: config.placeholder || 'Please Select',

    // --- STATE ---
    filteredOptions: [],
    isOpen: false,
    openedWithKeyboard: false,
    searchQuery: '',
    selectedOption: null, // For single-select (stores the full option object)
    selectedOptions: [], // For multi-select (stores an array of values)

    // --- LIFECYCLE ---
    init() {
        this.filteredOptions = [...this.allOptions];
        if (this.multiple) {
            this.selectedOptions = Array.isArray(config.initialValue) ? config.initialValue : [];
            this.$refs.hiddenInput.value = this.selectedOptions.join(',');
        } else {
            this.selectedOption = this.allOptions.find(opt => opt.value === config.initialValue) || null;
            this.$refs.hiddenInput.value = this.selectedOption?.value || '';
        }
        this.$watch('isOpen', isOpen => {
            if (isOpen && this.searchable) {
                this.$nextTick(() => this.$refs.searchField.focus());
            }
        });
        this.$watch('searchQuery', () => {
            this.filterOptions();
        });
    },

    // --- GETTERS ---
    getButtonLabel() {
        if (this.multiple) {
            if (this.selectedOptions.length === 0) return this.placeholder;
            return this.selectedOptions
                .map(value => this.allOptions.find(opt => opt.value === value)?.label)
                .join(', ');
        }
        return this.selectedOption ? this.selectedOption.label : this.placeholder;
    },

    isSelected(option) {
        if (this.multiple) {
            return this.selectedOptions.includes(option.value);
        }
        return this.selectedOption?.value === option.value;
    },

    // --- METHODS ---
    closeDropdown() {
        this.isOpen = false;
        this.openedWithKeyboard = false;
    },

    selectOption(option) {
        if (this.multiple) {
            this.toggleMultiSelectOption(option.value);
        } else {
            this.selectedOption = option;
            this.$refs.hiddenInput.value = option.value;
            this.closeDropdown();
        }
    },

    toggleMultiSelectOption(optionValue) {
        const index = this.selectedOptions.indexOf(optionValue);
        if (index > -1) {
            this.selectedOptions.splice(index, 1);
        } else {
            this.selectedOptions.push(optionValue);
        }
        this.$refs.hiddenInput.value = this.selectedOptions.join(',');
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

    handleListKeydown(event) {
        // If not searchable, jump to the first matching option
        if (!this.searchable && /^[a-zA-Z0-9]$/.test(event.key)) {
            event.preventDefault();
            const firstMatch = this.filteredOptions.find(opt =>
                opt.label.toLowerCase().startsWith(event.key.toLowerCase())
            );
            if (firstMatch) {
                const index = this.filteredOptions.indexOf(firstMatch);
                this.$refs.listbox.children[index + 1]?.focus(); // +1 to account for the hidden `li`
            }
        }
    },
});
