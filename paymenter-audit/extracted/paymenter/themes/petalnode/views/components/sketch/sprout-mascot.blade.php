@props([
    'stage' => 'blooming',
    'size' => 96,
    'label' => 'Sprout mascot',
    'class' => '',
])
<svg
    role="img"
    aria-label="{{ $label }}"
    width="{{ $size }}"
    height="{{ $size }}"
    viewBox="0 0 110 110"
    fill="none"
    {{ $attributes->merge(['class' => "select-none {$class}"]) }}
>
    <ellipse cx="55" cy="96" rx="28" ry="5" fill="#E4D6C1" />
    <path d="M35 68H75L70 93H40L35 68Z" fill="#F1E7D6" stroke="#2B2230" stroke-width="2.4" stroke-linejoin="round" />
    <rect x="31" y="61" width="48" height="9" rx="3" fill="#FFC9D6" stroke="#2B2230" stroke-width="2.4" />
    @if($stage === 'resting')
        <path d="M44 79C46 81 48 81 50 79" stroke="#2B2230" stroke-width="2" stroke-linecap="round" />
        <path d="M60 79C62 81 64 81 66 79" stroke="#2B2230" stroke-width="2" stroke-linecap="round" />
    @else
        <circle cx="47" cy="79" r="2.2" fill="#2B2230" />
        <circle cx="63" cy="79" r="2.2" fill="#2B2230" />
        <path d="M51 82.5C53 84.8 57 84.8 59 82.5" stroke="#2B2230" stroke-width="2" stroke-linecap="round" />
    @endif
    <path
        d="{{ $stage === 'seedling' ? 'M55 61V45' : 'M55 61C55 50 54 39 55 30' }}"
        stroke="#6B4A3A"
        stroke-width="3"
        stroke-linecap="round"
    />
    <g class="pn-animate-sway" style="transform-origin: 55px 52px;">
        <path d="M55 48C43 47 36 39 40 34C45 30 53 38 55 48Z" fill="#6FAF5B" stroke="#2B2230" stroke-width="2.2" stroke-linejoin="round" />
        @if($stage !== 'seedling')
            <path d="M55 44C66 43 74 35 70 30C65 26 57 34 55 44Z" fill="#6FAF5B" stroke="#2B2230" stroke-width="2.2" stroke-linejoin="round" />
        @endif
    </g>
    @if($stage === 'blooming')
        <g transform="translate(55, 24)">
            <circle cx="0" cy="-7" r="6" fill="#FFC9D6" stroke="#2B2230" stroke-width="2" />
            <circle cx="7" cy="-1" r="6" fill="#FF8FAB" stroke="#2B2230" stroke-width="2" />
            <circle cx="-7" cy="-1" r="6" fill="#FFC9D6" stroke="#2B2230" stroke-width="2" />
            <circle cx="4" cy="6" r="6" fill="#FFC9D6" stroke="#2B2230" stroke-width="2" />
            <circle cx="-4" cy="6" r="6" fill="#FF8FAB" stroke="#2B2230" stroke-width="2" />
            <circle cx="0" cy="0" r="4.5" fill="#FFC75A" stroke="#2B2230" stroke-width="2" />
        </g>
    @elseif($stage === 'growing')
        <circle cx="55" cy="27" r="6.5" fill="#FFC9D6" stroke="#2B2230" stroke-width="2.2" />
    @endif
</svg>
