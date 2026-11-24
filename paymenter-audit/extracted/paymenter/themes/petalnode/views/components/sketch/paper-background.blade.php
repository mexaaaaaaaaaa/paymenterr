@props([
    'variant' => 'grain',
    'class' => '',
])
@php
    $surfaceClass = match ($variant) {
        'grid' => 'pn-sketch-grid bg-[var(--pn-bg-canvas)]',
        'notebook' => 'pn-notebook-lines bg-[var(--pn-bg-canvas)]',
        default => 'pn-paper-grain bg-[var(--pn-bg-canvas)]',
    };
@endphp
<div {{ $attributes->merge(['class' => "relative {$surfaceClass} {$class}"]) }}>
    {{ $slot }}
</div>
