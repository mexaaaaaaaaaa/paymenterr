@props([
    'color' => 'var(--pn-blossom-600)',
    'seed' => 91,
    'strokeWidth' => '1.8',
    'class' => '',
])
<span {{ $attributes->merge(['class' => "relative inline-flex items-center justify-center px-3 py-1 {$class}"]) }}>
    <span
        class="pointer-events-none absolute inset-[-4px]"
        aria-hidden="true"
        data-pn-rough="circle"
        data-pn-seed="{{ $seed }}"
        data-pn-stroke="{{ $color }}"
        data-pn-stroke-width="{{ $strokeWidth }}"
    ></span>
    <span class="relative z-10">{{ $slot }}</span>
</span>
