{{-- @deprecated, kept for Paymenter 1.5.x compatibility --}}
<x-select
    wire:model.live="currentLocale"
    :options="collect($locales)->map(fn($locale, $code) => [
        'value' => $code,
        'label' => $locale
    ])->values()->toArray()"
    placeholder="{{ __('Select language') }}"
/>
