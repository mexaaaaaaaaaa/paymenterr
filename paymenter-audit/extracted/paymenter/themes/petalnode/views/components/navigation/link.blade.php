@props(['href', 'spa' => true])
@php
    $isActive = $href === request()->url() || $href === request()->livewireUrl();
@endphp
<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'flex flex-row items-center px-3 py-2 min-h-[40px] gap-2 text-sm font-semibold rounded-[var(--pn-radius-md)] transition-colors duration-150 ' . ($isActive
            ? 'text-[var(--pn-blossom-700)] bg-[var(--pn-blossom-50)]'
            : 'text-[var(--pn-text-primary)] hover:text-[var(--pn-blossom-700)] hover:bg-[var(--pn-paper-shade)]/60')
    ]) }}
    @if($spa) wire:navigate @endif
>
    {{ $slot }}
</a>
