<div
    x-data="{
        selectOpen: false,
        selectedValue: @entangle($attributes->wire('model')),
        selectableItems: {{ json_encode($options) }},
        selectableItemActive: null,
        selectId: $id('select'),
        selectDropdownPosition: 'bottom',

        init() {
            this.$watch('selectOpen', () => {
                if (this.selectOpen) {
                    this.selectableItemActive = this.selectableItems.find(item => item.value === this.selectedValue)
                        || this.selectableItems[0];
                    this.$nextTick(() => this.selectScrollToActiveItem());
                }
                this.selectPositionUpdate();
            });

            window.addEventListener('resize', this.resizeHandler = () => this.selectPositionUpdate());
            this.$el.addEventListener('alpine:destroyed', () => window.removeEventListener('resize', this.resizeHandler));
        },

        selectableItemIsActive(item) {
            return this.selectableItemActive && this.selectableItemActive.value === item.value;
        },

        selectableItemActiveNext() {
            let index = this.selectableItems.indexOf(this.selectableItemActive);
            if (index < this.selectableItems.length - 1) {
                this.selectableItemActive = this.selectableItems[index + 1];
                this.selectScrollToActiveItem();
            }
        },

        selectableItemActivePrevious() {
            let index = this.selectableItems.indexOf(this.selectableItemActive);
            if (index > 0) {
                this.selectableItemActive = this.selectableItems[index - 1];
                this.selectScrollToActiveItem();
            }
        },

        selectScrollToActiveItem() {
            if (this.selectableItemActive) {
                const activeElement = document.getElementById(this.selectableItemActive.value + '-' + this.selectId);
                if (activeElement) {
                    activeElement.scrollIntoView({ block: 'nearest' });
                }
            }
        },

        selectPositionUpdate() {
            if (!this.$refs.selectButton || !this.$refs.selectableItemsList) return;
            const selectDropdownBottomPos = this.$refs.selectButton.getBoundingClientRect().top +
                this.$refs.selectButton.offsetHeight +
                this.$refs.selectableItemsList.offsetHeight;
            this.selectDropdownPosition = window.innerHeight < selectDropdownBottomPos ? 'top' : 'bottom';
        }
    }"
    @keydown.escape="selectOpen = false"
    @keydown.down.prevent="if(selectOpen) { selectableItemActiveNext() } else { selectOpen = true }"
    @keydown.up.prevent="if(selectOpen) { selectableItemActivePrevious() } else { selectOpen = true }"
    @keydown.enter.prevent="if(selectableItemActive) { selectedValue = selectableItemActive.value; } selectOpen = false"
    class="relative w-full"
>
    <button
        x-ref="selectButton"
        @click="selectOpen = !selectOpen"
        :class="{ 'ring-2 ring-[var(--pn-blossom-200)] border-[var(--pn-blossom-600)]': selectOpen }"
        class="relative w-full min-h-[40px] py-2 pl-3 pr-9 text-left bg-[var(--pn-bg-surface)] border border-[var(--pn-border-subtle)] rounded-[var(--pn-radius-md)] shadow-[var(--pn-shadow-paper-sm)] cursor-pointer text-sm text-[var(--pn-text-primary)] focus:outline-none"
        type="button"
    >
        <span x-text="selectableItems.find(item => item.value === selectedValue)?.label ?? '{{ $placeholder ?? 'Select option' }}'" class="block truncate font-medium"></span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-[var(--pn-text-secondary)]">
            <x-ri-expand-up-down-line class="size-4" aria-hidden="true" />
        </span>
    </button>

    <ul
        x-show="selectOpen"
        x-ref="selectableItemsList"
        @click.away="selectOpen = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        :class="{ 'bottom-full mb-1': selectDropdownPosition === 'top', 'top-full mt-1': selectDropdownPosition === 'bottom' }"
        class="absolute z-50 w-full py-1 overflow-auto bg-[var(--pn-bg-surface)] border-[1.5px] border-[var(--pn-ink)]/30 rounded-[var(--pn-radius-md)] shadow-[var(--pn-shadow-paper-md)] max-h-60 focus:outline-none text-sm"
        x-cloak
    >
        <template x-for="item in selectableItems" :key="item.value">
            <li
                :id="item.value + '-' + selectId"
                @click="selectedValue = item.value; selectOpen = false"
                @mousemove="selectableItemActive = item"
                :class="{ 'bg-[var(--pn-blossom-50)] text-[var(--pn-ink)]': selectableItemIsActive(item) }"
                class="relative py-2 pl-8 pr-4 cursor-pointer select-none text-[var(--pn-text-primary)] hover:bg-[var(--pn-blossom-50)]"
            >
                <span
                    class="block truncate"
                    :class="{ 'font-bold text-[var(--pn-blossom-700)]': item.value === selectedValue }"
                    x-text="item.label"
                ></span>
                <span
                    x-show="item.value === selectedValue"
                    class="absolute inset-y-0 left-0 flex items-center pl-2 text-[var(--pn-blossom-700)]"
                >
                    <x-ri-check-fill class="size-4" aria-hidden="true" />
                </span>
            </li>
        </template>
    </ul>
</div>
