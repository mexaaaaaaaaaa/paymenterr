@php
    $lightLogo = config('settings.logo');
    $darkLogo = config('settings.logo_dark');
@endphp

@if ($lightLogo && $darkLogo)
    <img src="{{ Storage::url($lightLogo) }}" alt="{{ config('app.name') }}" {{ $attributes->merge(['class' => 'w-auto inline-block dark:hidden']) }}>
    <img src="{{ Storage::url($darkLogo) }}" alt="{{ config('app.name') }}" {{ $attributes->merge(['class' => 'w-auto hidden dark:inline-block']) }}>
@elseif ($lightLogo)
    <img src="{{ Storage::url($lightLogo) }}" alt="{{ config('app.name') }}" {{ $attributes->merge(['class' => 'w-auto inline-block']) }}>
@elseif ($darkLogo)
    <img src="{{ Storage::url($darkLogo) }}" alt="{{ config('app.name') }}" {{ $attributes->merge(['class' => 'w-auto inline-block']) }}>
@else
    <svg viewBox="0 0 40 40" fill="none" aria-label="{{ config('app.name', 'Petalnode') }}" {{ $attributes->merge(['class' => 'size-8 shrink-0']) }}>
        <rect x="3" y="5" width="34" height="31" rx="8" fill="#FFFDF9" stroke="#2B2230" stroke-width="2.2" />
        <path d="M12 19L20 14.5L28 19L20 23.5L12 19Z" fill="#FFF1F4" stroke="#2B2230" stroke-width="1.8" stroke-linejoin="round" />
        <path d="M12 19L20 23.5V31.5L12 27V19Z" fill="#FBF6EE" stroke="#2B2230" stroke-width="1.8" stroke-linejoin="round" />
        <path d="M28 19L20 23.5V31.5L28 27V19Z" fill="#F1E7D6" stroke="#2B2230" stroke-width="1.8" stroke-linejoin="round" />
        <circle cx="24" cy="10" r="3" fill="#FF8FAB" stroke="#2B2230" stroke-width="1.6" />
        <path d="M20 14.5C16.5 13 15 10.5 17 8.8C18.8 7.5 21 9.8 20 14.5Z" fill="#6FAF5B" stroke="#2B2230" stroke-width="1.6" stroke-linejoin="round" />
    </svg>
@endif
