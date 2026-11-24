@props([
    'caption' => null,
    'tapeTone' => 'blossom',
    'tilt' => '-rotate-1',
    'class' => '',
])
<figure {{ $attributes->merge(['class' => "group relative rounded-[var(--pn-radius-md)] border-[1.5px] border-[var(--pn-ink)] bg-[#FFFDF9] dark:bg-[var(--pn-bg-surface)] p-3 pb-4 shadow-[var(--pn-shadow-paper-md)] transition-transform duration-300 {$tilt} hover:rotate-0 {$class}"]) }}>
    <x-sketch.washi-tape :tone="$tapeTone" rotate="-rotate-2" class="absolute -top-2.5 left-1/2 -translate-x-1/2 z-20" />
    <div class="overflow-hidden rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]">
        {{ $slot }}
    </div>
    @if($caption)
        <figcaption class="mt-3 px-1 text-center font-hand text-sm text-[var(--pn-ink-soft)]">
            {{ $caption }}
        </figcaption>
    @endif
</figure>
