@props([
    'surface' => 'surface',
    'seed' => 42,
    'stroke' => null,
    'fill' => null,
    'strokeWidth' => '1.6',
    'roughness' => '1.15',
    'padding' => 'p-5 sm:p-6',
    'interactive' => false,
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
    $resolvedStroke = $stroke ?? 'var(--pn-ink)';
@endphp
<div {{ $attributes->merge(['class' => "relative rounded-[var(--pn-radius-lg)] {$bgClass} {$padding} shadow-[var(--pn-shadow-paper-sm)] " . ($interactive ? 'pn-card-interactive ' : '') . $class]) }}>
    <div
        class="pointer-events-none absolute inset-0 overflow-visible"
        aria-hidden="true"
        data-pn-rough="box"
        data-pn-seed="{{ $seed }}"
        data-pn-stroke="{{ $resolvedStroke }}"
        @if($fill) data-pn-fill="{{ $fill }}" @endif
        data-pn-stroke-width="{{ $strokeWidth }}"
        data-pn-roughness="{{ $roughness }}"
    ></div>
    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>
