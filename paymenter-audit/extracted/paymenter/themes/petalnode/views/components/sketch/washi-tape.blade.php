@props([
    'tone' => 'blossom',
    'rotate' => '-rotate-3',
    'class' => '',
])
@php
    $bg = match ($tone) {
        'moss' => 'bg-[var(--pn-moss)]/35 border-[var(--pn-moss-700)]/20',
        'lantern' => 'bg-[var(--pn-lantern)]/55 border-[var(--pn-lantern-700)]/20',
        'bark' => 'bg-[var(--pn-bark)]/25 border-[var(--pn-bark)]/20',
        default => 'bg-[var(--pn-blossom-200)]/80 border-[var(--pn-blossom-600)]/20',
    };
@endphp
<span
    aria-hidden="true"
    {{ $attributes->merge(['class' => "pointer-events-none inline-block h-5 w-16 rounded-[2px] border {$bg} {$rotate} shadow-[0_1px_2px_rgba(43,34,48,0.08)] {$class}"]) }}
    style="clip-path: polygon(4% 0, 97% 3%, 100% 96%, 94% 100%, 2% 97%, 0 8%);"
></span>
