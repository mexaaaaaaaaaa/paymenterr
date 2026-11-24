@props([
    'mood' => 'idle',
    'size' => 112,
    'label' => 'Nodey mascot',
    'class' => '',
])
@php
    $cheekColor = $mood === 'alert' ? '#FFC75A' : '#FF8FAB';
    $leafFill = $mood === 'alert' ? '#FFC75A' : '#6FAF5B';
@endphp
<svg
    role="img"
    aria-label="{{ $label }}"
    width="{{ $size }}"
    height="{{ $size }}"
    viewBox="0 0 120 120"
    fill="none"
    {{ $attributes->merge(['class' => "pn-animate-float select-none {$class}"]) }}
>
    <ellipse cx="60" cy="107" rx="34" ry="6" fill="#E4D6C1" />
    <path d="M60 28C60 20 62 15 68 12" stroke="#6B4A3A" stroke-width="3" stroke-linecap="round" />
    <path d="M59 22C50 21 46 15 51 11C56 9 60 14 59 22Z" fill="{{ $leafFill }}" stroke="#2B2230" stroke-width="2.2" stroke-linejoin="round" />
    <circle cx="71" cy="11" r="5" fill="#FFC9D6" stroke="#2B2230" stroke-width="2.2" />
    <path d="M26 42L60 27L94 42L60 57L26 42Z" fill="#FFFDF9" stroke="#2B2230" stroke-width="2.5" stroke-linejoin="round" />
    <path d="M26 42L60 57V97L26 82V42Z" fill="#FBF6EE" stroke="#2B2230" stroke-width="2.5" stroke-linejoin="round" />
    <path d="M94 42L60 57V97L94 82V42Z" fill="#F1E7D6" stroke="#2B2230" stroke-width="2.5" stroke-linejoin="round" />
    <rect x="71" y="63" width="13" height="13" rx="3" transform="skewY(-22)" fill="#FFF1F4" stroke="#E2557B" stroke-width="1.6" />
    <g transform="translate(0, 2)">
        @if($mood === 'happy')
            <path d="M35 61C37 58 40 58 42 61" stroke="#2B2230" stroke-width="2.4" stroke-linecap="round" />
            <path d="M48 67C50 64 53 64 55 67" stroke="#2B2230" stroke-width="2.4" stroke-linecap="round" />
        @elseif($mood === 'working')
            <path d="M35 59L42 62" stroke="#2B2230" stroke-width="2.4" stroke-linecap="round" />
            <path d="M48 65L55 68" stroke="#2B2230" stroke-width="2.4" stroke-linecap="round" />
        @else
            <circle cx="38.5" cy="60" r="2.7" fill="#2B2230" />
            <circle cx="51.5" cy="66" r="2.7" fill="#2B2230" />
        @endif
        <ellipse cx="34.5" cy="65" rx="3" ry="1.8" fill="{{ $cheekColor }}" opacity="0.85" />
        <ellipse cx="54.5" cy="73.5" rx="3" ry="1.8" fill="{{ $cheekColor }}" opacity="0.85" />
        @if($mood === 'alert')
            <circle cx="45" cy="70.5" r="2.3" stroke="#2B2230" stroke-width="2.1" fill="#FFFDF9" />
        @else
            <path d="M41.5 68.5C43.2 71 46.2 72 48.2 70.5" stroke="#2B2230" stroke-width="2.2" stroke-linecap="round" />
        @endif
    </g>
</svg>
