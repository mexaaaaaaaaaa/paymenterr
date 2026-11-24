<x-dropdown>
    <x-slot:trigger>
        <div class="text-xs sm:text-sm text-[var(--pn-text-primary)] font-semibold text-nowrap flex items-center gap-1.5">
            @if(count($locales) > 1)
                <span>{{ config('app.available_locales')[app()->getLocale()] }}</span>
            @endif
            @if(count($locales) > 1 && count($this->currencies) > 1 && Cart::items()->isEmpty())
                <span class="text-[var(--pn-text-secondary)]/60">|</span>
            @endif
            @if(count($this->currencies) > 1 && Cart::items()->isEmpty())
                <span class="font-pixel">{{ collect($this->currencies)->firstWhere('value', $this->currentCurrency)['label'] ?? $this->currentCurrency }}</span>
            @endif
        </div>
    </x-slot:trigger>
    <x-slot:content>
        <div class="p-2 space-y-3">
            @if(count($locales) > 1)
                <div>
                    <strong class="block pb-1.5 text-[11px] font-display font-bold uppercase tracking-wider text-[var(--pn-text-secondary)]">{{ __('Language') }}</strong>
                    <x-select wire:model.live="currentLocale" :options="collect($locales)->map(fn($code) => [
                        'value' => $code,
                        'label' => config('app.available_locales')[$code]
                    ])->values()->toArray()" placeholder="{{ __('Select language') }}" />
                </div>
            @endif
            @if(count($this->currencies) > 1 && Cart::items()->isEmpty())
                <div>
                    <strong class="block pb-1.5 text-[11px] font-display font-bold uppercase tracking-wider text-[var(--pn-text-secondary)]">{{ __('Currency') }}</strong>
                    <x-select wire:model.live="currentCurrency" :options="$this->currencies" placeholder="{{ __('Select currency') }}" />
                </div>
            @endif
        </div>
    </x-slot:content>
</x-dropdown>
