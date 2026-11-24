<div class="flex flex-col gap-1.5">
    @switch($config->type)
        @case('select')
            <x-form.select
                name="{{ $name }}"
                :label="__($config->label ?? $config->name)"
                :required="$config->required ?? false"
                :selected="config('configs.' . $config->name)"
                :multiple="$config->multiple ?? false"
                wire:model.live="{{ $name }}"
                :placeholder="$config->placeholder ?? ''"
            >
                {{ $slot }}
            </x-form.select>
        @break

        @case('slider')
            <div x-data="{
                options: @js($config->children->map(fn($child) => ['option' => $child->name, 'value' => $child->id, 'price' => ($showPriceTag && $child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? (string)$child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : ''])),
                showPriceTag: @js($showPriceTag),
                selectedOption: 0,
                backendOption: $wire.entangle('{{ $name }}').live,
                progressOption: '0%',
                segmentsWidthOption: '0%',

                init() {
                    const initialValue = this.$wire.get('{{ $name }}');
                    const foundIndex = this.options.findIndex(opt => opt.value == initialValue);
                    if (foundIndex !== -1) {
                        this.selectedOption = foundIndex;
                    }
                    this.updateSliderVisuals();
                    $watch('selectedOption', Alpine.debounce(() => {
                        if (this.options[this.selectedOption]) {
                            this.backendOption = this.options[this.selectedOption].value;
                        }
                    }, 250));
                },

                updateSliderVisuals() {
                    const denom = Math.max(1, this.options.length - 1);
                    this.progressOption = `${(this.selectedOption / denom) * 100}%`;
                    this.segmentsWidthOption = `${100 / denom}%`;
                },

                setOptionValue(index) {
                    this.selectedOption = parseInt(index, 10);
                    this.updateSliderVisuals();
                }
            }" class="pn-card bg-[var(--pn-bg-surface)] p-4 flex flex-col gap-3 relative">
                <div class="flex items-center justify-between gap-3">
                    <label for="{{ $name }}" class="text-sm font-display font-bold text-[var(--pn-text-primary)]">
                        {{ $config->label ?? $config->name }}
                    </label>
                    <span class="font-pixel text-xs font-bold text-[var(--pn-blossom-700)] bg-[var(--pn-blossom-50)] border border-[var(--pn-blossom-200)] rounded-md px-2 py-0.5 tabular-nums"
                        x-text="options[selectedOption] ? options[selectedOption].option + (options[selectedOption].price ? ' · ' + options[selectedOption].price : '') : ''">
                    </span>
                </div>

                <div class="relative flex items-center py-1" :style="`--progress:${progressOption};--segments-width:${segmentsWidthOption}`" wire:ignore>
                    <div class="absolute left-2.5 right-2.5 h-2 bg-[var(--pn-paper-shade)] border border-[var(--pn-border-subtle)] rounded-full overflow-hidden before:absolute before:inset-0 before:bg-[var(--pn-blossom-600)] before:[mask-image:_linear-gradient(to_right,theme(colors.white),theme(colors.white)_var(--progress),transparent_var(--progress))] [&[x-cloak]]:hidden" aria-hidden="true" x-cloak></div>
                    <input
                        class="relative appearance-none cursor-pointer w-full bg-transparent focus:outline-none [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-[#FFFDF9] [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-[var(--pn-ink)] [&::-webkit-slider-thumb]:shadow-[2px_2px_0_var(--pn-ink)] [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-[#FFFDF9] [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-[var(--pn-ink)]"
                        type="range"
                        min="0"
                        :max="Math.max(0, options.length - 1)"
                        x-model="selectedOption"
                        @input="setOptionValue(selectedOption)"
                        aria-label="{{ $config->label ?? $config->name }}"
                        name="{{ $name }}"
                        id="{{ $name }}"
                    />
                </div>

                <ul class="flex flex-wrap justify-between gap-2 text-xs font-medium text-[var(--pn-text-secondary)]">
                    @foreach($config->children as $child)
                        <li>
                            <button
                                type="button"
                                @click="setOptionValue({{ $loop->index }})"
                                :class="{ 'bg-[var(--pn-blossom-50)] border-[var(--pn-blossom-600)] text-[var(--pn-ink)] font-bold': selectedOption === {{ $loop->index }}, 'border-[var(--pn-border-subtle)] hover:border-[var(--pn-ink)]/50': selectedOption !== {{ $loop->index }} }"
                                class="px-2.5 py-1 rounded-[var(--pn-radius-sm)] border text-left transition cursor-pointer"
                            >
                                <span class="block text-xs">{{ $child->name }}</span>
                                @if($showPriceTag && $child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available)
                                    <span class="hidden sm:inline text-[11px] font-pixel tabular-nums text-[var(--pn-blossom-700)]">
                                        {{ $child->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) }}
                                    </span>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        @break

        @case('text')
        @case('password')
        @case('email')
        @case('number')
        @case('color')
        @case('file')
            <x-form.input
                name="{{ $name }}"
                :type="$config->type"
                :label="__($config->label ?? $config->name)"
                :required="$config->required ?? false"
                wire:model.live="{{ $name }}"
                :placeholder="$config->placeholder ?? ($config->default ?? '')"
            />
        @break

        @case('checkbox')
            @php
                $cbPrice = ($showPriceTag && $config->children->first()?->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available)
                    ? ' — ' . $config->children->first()->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)
                    : '';
            @endphp
            <div class="pn-card px-4 py-3">
                <x-form.checkbox
                    name="{{ $name }}"
                    :label="__($config->label ?? $config->name) . $cbPrice"
                    :required="$config->required ?? false"
                    wire:model.live="{{ $name }}"
                />
            </div>
        @break

        @case('radio')
            <x-form.radio
                name="{{ $name }}"
                :label="__($config->label ?? $config->name)"
                :required="$config->required ?? false"
                wire:model.live="{{ $name }}"
            >
                {{ $slot }}
            </x-form.radio>
        @break

        @default
    @endswitch

    @isset($config->description)
        @isset($config->link)
            <a href="{{ $config->link }}" class="text-xs font-medium text-[var(--pn-blossom-700)] hover:underline group inline-flex items-center gap-1" target="_blank" rel="noopener noreferrer">
                <span>{{ $config->description }}</span>
                <x-ri-arrow-right-long-line class="size-3 inline-block -rotate-45 group-hover:rotate-0 transition" aria-hidden="true" />
            </a>
        @else
            <p class="text-xs text-[var(--pn-text-secondary)]">{{ $config->description }}</p>
        @endisset
    @endisset
</div>
