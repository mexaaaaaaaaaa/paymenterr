@props(['class' => ''])
<svg
    aria-hidden="true"
    viewBox="0 0 420 280"
    fill="none"
    {{ $attributes->merge(['class' => "pointer-events-none absolute inset-0 h-full w-full opacity-80 {$class}"]) }}
>
    <g class="pn-animate-float">
        <path d="M44 48C50 40 58 43 56 52C52 57 44 55 44 48Z" fill="#FFC9D6" stroke="#2B2230" stroke-width="1.2" />
        <path d="M360 38C366 30 374 34 371 42C367 47 360 44 360 38Z" fill="#FF8FAB" stroke="#2B2230" stroke-width="1.2" />
        <path d="M342 214C348 206 356 210 353 218C349 223 342 220 342 214Z" fill="#FFC9D6" stroke="#2B2230" stroke-width="1.2" />
    </g>
    <g class="pn-animate-sway" style="transform-origin: 210px 140px;">
        <path d="M28 192C34 184 42 188 39 196C35 201 28 198 28 192Z" fill="#FF8FAB" stroke="#2B2230" stroke-width="1.2" />
        <path d="M214 24C220 17 228 21 225 28C221 33 214 30 214 24Z" fill="#FFC9D6" stroke="#2B2230" stroke-width="1.2" />
    </g>
</svg>
