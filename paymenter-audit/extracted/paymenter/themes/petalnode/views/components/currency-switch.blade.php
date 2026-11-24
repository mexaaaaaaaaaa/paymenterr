{{-- @deprecated, kept for Paymenter 1.5.x compatibility --}}
<div>
    <strong class="block p-2 text-xs font-display font-bold uppercase text-[var(--pn-text-secondary)]">{{ __('Currency') }}</strong>
    <x-select wire:model.live="currentCurrency" :options="$this->currencies" placeholder="{{ __('Select currency') }}" />
</div>
