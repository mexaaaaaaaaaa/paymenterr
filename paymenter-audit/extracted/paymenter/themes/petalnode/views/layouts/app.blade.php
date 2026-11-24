<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(in_array(app()->getLocale(), config('app.rtl_locales', []))) dir="rtl" @endif>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ config('app.name', 'Paymenter') }}
        @isset($title)
        — {{ $title }}
        @endisset
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;1,9..144,500&family=Nunito:wght@400;500;600;700;800&family=Pixelify+Sans:wght@500;600;700&family=Shantell+Sans:ital,wght@0,400..600;1,400..600&display=swap" rel="stylesheet">

    @livewireStyles
    @vite(['themes/' . config('settings.theme') . '/js/app.js', 'themes/' . config('settings.theme') . '/css/app.css'], config('settings.theme'))
    @include('layouts.colors')
    @stack('styles')

    @if (config('settings.favicon'))
    <link rel="icon" href="{{ Storage::url(config('settings.favicon')) }}">
    @endif
    @isset($title)
    <meta content="{{ config('app.name', 'Paymenter') . ' - ' . $title }}" property="og:title">
    <meta content="{{ config('app.name', 'Paymenter') . ' - ' . $title }}" name="title">
    @endisset
    @isset($description)
    <meta content="{{ $description }}" property="og:description">
    <meta content="{{ $description }}" name="description">
    @endisset
    @isset($image)
    <meta content="{{ $image }}" property="og:image">
    <meta content="{{ $image }}" name="image">
    @endisset

    <meta name="theme-color" content="{{ theme('primary', '#B8375B') }}">

    {!! hook('head') !!}
</head>

<body class="pn-paper-grain w-full bg-[var(--pn-bg-canvas)] text-[var(--pn-text-primary)] min-h-screen flex flex-col antialiased selection:bg-[var(--pn-blossom-200)] selection:text-[var(--pn-ink)]"
    x-cloak
    x-data="{
        theme: $persist('light').as('theme_mode'),
        paperPreset: $persist('grain').as('pn_paper_preset'),
        systemDark: window.matchMedia('(prefers-color-scheme: dark)').matches,
        showBackToTop: false,
        init() {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                this.systemDark = e.matches;
            });
            window.addEventListener('scroll', () => {
                this.showBackToTop = window.scrollY > 420;
            }, { passive: true });
        },
        get isDark() {
            return this.theme === 'dark' || (this.theme === 'system' && this.systemDark);
        }
    }"
    :class="{
        'dark': isDark,
        'pn-paper-grain': paperPreset === 'grain',
        'pn-sketch-grid': paperPreset === 'grid',
        'pn-notebook-lines': paperPreset === 'notebook'
    }"
>
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[120] focus:px-4 focus:py-2 focus:rounded-lg focus:bg-[var(--pn-blossom-700)] focus:text-white focus:font-display focus:font-bold focus:shadow-[var(--pn-shadow-ink-hard)]">
        {{ __('Skip to main content') }}
    </a>

    <div class="pn-scroll-progress" aria-hidden="true"></div>

    {!! hook('body') !!}
    <x-navigation />

    <div class="w-full flex flex-grow relative">
        @if (isset($sidebar) && $sidebar)
        <x-navigation.sidebar :title="$title ?? ''" />
        @endif
        <div class="{{ (isset($sidebar) && $sidebar) ? 'md:ml-64 rtl:ml-0 rtl:md:mr-64' : '' }} flex flex-col flex-grow min-w-0">
            <main id="main-content" class="mt-16 grow pn-page-enter">
                {{ $slot }}
            </main>
            <x-notification />
            <x-confirmation />
            <div class="flex">
                <x-navigation.footer />
            </div>
        </div>
        <x-impersonating />
    </div>

    <button
        type="button"
        x-show="showBackToTop"
        x-transition.opacity.duration.200ms
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        data-pn-no-print
        aria-label="{{ __('Back to top') }}"
        class="fixed bottom-5 right-5 z-40 w-11 h-11 rounded-full bg-[var(--pn-bg-surface)] border-[1.5px] border-[var(--pn-ink)] text-[var(--pn-ink)] shadow-[var(--pn-shadow-ink-hard)] flex items-center justify-center hover:bg-[var(--pn-blossom-50)] hover:-translate-y-0.5 transition cursor-pointer"
        x-cloak
    >
        <x-ri-arrow-up-line class="size-5" aria-hidden="true" />
    </button>

    @livewireScriptConfig
    @stack('scripts')
    {!! hook('footer') !!}
</body>

</html>
