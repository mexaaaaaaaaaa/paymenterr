@props([
    'direction' => 'right',
    'color' => 'currentColor',
    'animate' => false,
    'class' => 'w-12 h-7',
])
@php
    $rotation = match ($direction) {
        'left' => 'rotate-180',
        'down' => 'rotate-90',
        'up' => '-rotate-90',
        default => '',
    };
@endphp
<svg
    viewBox="0 0 64 32"
    fill="none"
    aria-hidden="true"
    {{ $attributes->merge(['class' => "inline-block shrink-0 {$rotation} {$class}"]) }}
>
    <path
        d="M4 18 C18 8, 38 10, 56 17"
        stroke="{{ $color }}"
        stroke-width="2.2"
        stroke-linecap="round"
        @if($animate) data-pn-draw @endif
    />
    <path
        d="M47 10 L58 17 L48 24"
        stroke="{{ $color }}"
        stroke-width="2.2"
        stroke-linecap="round"
        stroke-linejoin="round"
        @if($animate) data-pn-draw @endif
    />
</svg>
