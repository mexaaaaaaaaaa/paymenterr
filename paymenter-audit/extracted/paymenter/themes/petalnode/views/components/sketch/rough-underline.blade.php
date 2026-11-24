@props([
    'color' => 'var(--pn-blossom-600)',
    'seed' => 17,
    'strokeWidth' => '2.4',
    'class' => '',
])
<span {{ $attributes->merge(['class' => "relative inline-block pb-1 {$class}"]) }}>
    <span class="relative z-10">{{ $slot }}</span>
    <span
        class="pointer-events-none absolute -bottom-1 left-0 right-0 h-3"
        aria-hidden="true"
        data-pn-rough="underline"
        data-pn-seed="{{ $seed }}"
        data-pn-stroke="{{ $color }}"
        data-pn-stroke-width="{{ $strokeWidth }}"
    ></span>
</span>
