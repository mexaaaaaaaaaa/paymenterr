@props([
    'direction' => 'right',
    'class' => '',
])
<div {{ $attributes->merge(['class' => "inline-flex items-center gap-2 font-hand text-sm text-[var(--pn-ink-soft)] {$class}"]) }}>
    @if($direction === 'left')
        <x-sketch.ink-arrow direction="left" color="var(--pn-blossom-600)" class="w-9 h-5" />
    @endif
    <span>{{ $slot }}</span>
    @if($direction !== 'left')
        <x-sketch.ink-arrow :direction="$direction" color="var(--pn-blossom-600)" class="w-9 h-5" />
    @endif
</div>
