@props([
    'tone' => 'moss',
    'rotate' => '-rotate-3',
    'class' => '',
])
@php
    $toneClass = match ($tone) {
        'blossom' => 'border-[var(--pn-blossom-700)] text-[var(--pn-blossom-700)] bg-[var(--pn-blossom-50)]',
        'lantern' => 'border-[var(--pn-lantern-700)] text-[var(--pn-lantern-700)] bg-[var(--pn-lantern-100)]',
        'bark' => 'border-[var(--pn-bark)] text-[var(--pn-bark)] bg-[var(--pn-bark-100)]',
        default => 'border-[var(--pn-moss-700)] text-[var(--pn-moss-700)] bg-[var(--pn-moss-100)]',
    };
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 rounded-[var(--pn-radius-xs)] border-2 border-dashed px-2.5 py-0.5 font-pixel text-xs font-bold uppercase tracking-wider {$toneClass} {$rotate} {$class}"]) }}>
    {{ $slot }}
</span>
