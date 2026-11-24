@props([
    'surface' => 'surface',
    'interactive' => false,
    'tilt' => false,
    'padding' => 'p-5',
    'class' => '',
])
@php
    $bgClass = match ($surface) {
        'shade' => 'bg-[var(--pn-paper-shade)]',
        'blossom' => 'bg-[var(--pn-blossom-50)]',
        'moss' => 'bg-[var(--pn-moss-100)]',
        'lantern' => 'bg-[var(--pn-lantern-100)]',
        default => 'bg-[var(--pn-bg-surface)]',
    };
@endphp
<div
    @if($tilt) data-pn-tilt @endif
    {{ $attributes->merge(['class' => "pn-card {$bgClass} {$padding} " . ($interactive ? 'pn-card-interactive ' : '') . $class]) }}
>
    {{ $slot }}
</div>
