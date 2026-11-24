<nav aria-label="{{ __('Primary navigation') }}" class="w-full px-4 lg:px-8 bg-[var(--pn-bg-surface)]/95 backdrop-blur-sm border-b border-[var(--pn-border-subtle)] min-h-16 flex flex-col justify-center fixed top-0 z-30 shadow-[var(--pn-shadow-paper-sm)]">
    <div
        x-data="{
            slideOverOpen: false,
            hasAside: !!document.getElementById('main-aside')
        }"
        x-init="$watch('slideOverOpen', value => { document.documentElement.style.overflow = value ? 'hidden' : '' })"
        class="relative z-50 w-full"
    >
        <div class="flex flex-row items-center justify-between h-16 gap-3" :class="hasAside ? 'w-full' : 'container !px-0'">
            {{-- Zone 1: Brand Wordmark + Primary Links --}}
            <div class="flex flex-row items-center min-w-0">
                <a href="{{ route('home') }}" class="flex flex-row items-center h-11 gap-2.5 shrink-0 group" wire:navigate>
                    <x-logo class="h-8 w-auto transition-transform duration-200 group-hover:scale-105" />
                    @if(theme('logo_display', 'logo-and-name') != 'logo-only')
                        <span class="font-display text-lg sm:text-xl font-extrabold tracking-tight text-[var(--pn-text-primary)] leading-none">
                            {{ config('app.name') }}
                        </span>
                    @endif
                </a>

                <div class="md:flex hidden flex-row items-center gap-1 ml-6">
                    @foreach (\App\Classes\Navigation::getLinks() as $nav)
                        @if (isset($nav['children']) && count($nav['children']) > 0)
                            <div class="relative">
                                <x-dropdown>
                                    <x-slot:trigger>
                                        <span class="flex flex-row items-center px-2.5 py-2 text-sm font-semibold whitespace-nowrap text-[var(--pn-text-primary)] hover:text-[var(--pn-blossom-700)] transition-colors">
                                            {{ $nav['name'] }}
                                        </span>
                                    </x-slot:trigger>
                                    <x-slot:content>
                                        @foreach ($nav['children'] as $child)
                                            <x-navigation.link
                                                :href="$child['url']"
                                                :spa="$child['spa'] ?? true"
                                            >
                                                {{ $child['name'] }}
                                            </x-navigation.link>
                                        @endforeach
                                    </x-slot:content>
                                </x-dropdown>
                            </div>
                        @else
                            <x-navigation.link
                                :href="$nav['url']"
                                :spa="$nav['spa'] ?? true"
                                class="whitespace-nowrap"
                            >
                                {{ $nav['name'] }}
                            </x-navigation.link>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Zone 3: Cart, Locale/Currency, Theme & Auth --}}
            <div class="flex flex-row items-center gap-1.5 sm:gap-2 shrink-0">
                <livewire:components.cart />

                <div class="items-center hidden md:flex gap-1">
                    <livewire:components.locale-switch />
                    <x-theme-toggle />
                </div>

                @if(auth()->check())
                    <livewire:components.notifications />
                    <div class="hidden lg:flex">
                        <x-dropdown :showArrow="false">
                            <x-slot:trigger>
                                <span class="inline-flex items-center gap-2 rounded-full border-[1.5px] border-[var(--pn-ink)]/30 p-0.5 hover:border-[var(--pn-blossom-600)] transition">
                                    <img src="{{ auth()->user()->avatar }}" class="size-8 rounded-full bg-[var(--pn-paper-shade)] object-cover" alt="{{ auth()->user()->name }}" />
                                </span>
                            </x-slot:trigger>
                            <x-slot:content>
                                <div class="flex flex-col p-2.5 border-b border-[var(--pn-border-subtle)] mb-1">
                                    <span class="font-display text-sm font-bold text-[var(--pn-text-primary)] break-words">{{ auth()->user()->name }}</span>
                                    <span class="text-xs text-[var(--pn-text-secondary)] break-words">{{ auth()->user()->email }}</span>
                                </div>
                                @foreach (\App\Classes\Navigation::getAccountDropdownLinks() as $nav)
                                    <x-navigation.link :href="$nav['url']" :spa="$nav['spa'] ?? true">
                                        {{ $nav['name'] }}
                                    </x-navigation.link>
                                @endforeach
                                <div class="mt-1 pt-1 border-t border-[var(--pn-border-subtle)]">
                                    <livewire:auth.logout />
                                </div>
                            </x-slot:content>
                        </x-dropdown>
                    </div>
                @else
                    <div class="hidden lg:flex flex-row items-center gap-2.5 ml-1">
                        <a href="{{ route('login') }}" wire:navigate>
                            <x-button.secondary class="!w-auto !px-4 !py-2">
                                {{ __('navigation.login') }}
                            </x-button.secondary>
                        </a>
                        @if(!config('settings.registration_disabled', false))
                            <a href="{{ route('register') }}" wire:navigate>
                                <x-button.primary class="!w-auto !px-4 !py-2">
                                    {{ __('navigation.register') }}
                                </x-button.primary>
                            </a>
                        @endif
                    </div>
                @endif

                <button
                    type="button"
                    @click="slideOverOpen = !slideOverOpen"
                    :aria-expanded="slideOverOpen.toString()"
                    class="relative w-11 h-11 flex lg:hidden items-center justify-center rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] hover:bg-[var(--pn-paper-shade)] transition cursor-pointer"
                    aria-label="{{ __('Toggle Menu') }}"
                >
                    <span x-show="!slideOverOpen" class="flex items-center justify-center" aria-hidden="true">
                        <x-ri-menu-fill class="size-5 text-[var(--pn-text-primary)]" />
                    </span>
                    <span x-show="slideOverOpen" class="flex items-center justify-center" aria-hidden="true" x-cloak>
                        <x-ri-close-fill class="size-5 text-[var(--pn-text-primary)]" />
                    </span>
                </button>
            </div>
        </div>

        {{-- Mobile Slide-Over Navigation Drawer --}}
        <template x-teleport="body">
            <div
                x-show="slideOverOpen"
                @keydown.window.escape="slideOverOpen = false"
                x-cloak
                class="fixed left-0 right-0 top-16 w-full z-[99]"
                style="height: calc(100dvh - 4rem);"
                role="dialog"
                aria-modal="true"
                aria-label="{{ __('Mobile Navigation') }}"
                tabindex="-1"
            >
                <div
                    x-show="slideOverOpen"
                    @click.away="slideOverOpen = false"
                    x-transition.opacity.duration.200ms
                    class="absolute inset-0 bg-[var(--pn-bg-surface)] border-t border-[var(--pn-border-subtle)] shadow-[var(--pn-shadow-paper-lg)] overflow-y-auto flex flex-col"
                >
                    <div class="flex flex-col h-full p-4 sm:p-6">
                        <div class="flex-1 min-h-0 overflow-y-auto">
                            <x-navigation.sidebar-links />
                        </div>

                        <div class="mt-5 pt-4 border-t border-[var(--pn-border-subtle)]">
                            @if(auth()->check())
                                <div class="flex flex-col gap-3">
                                    <div class="flex gap-3 items-center">
                                        <img src="{{ auth()->user()->avatar }}" class="size-10 rounded-full border border-[var(--pn-border-subtle)] bg-[var(--pn-paper-shade)]" alt="{{ auth()->user()->name }}" />
                                        <div class="flex flex-col">
                                            <span class="font-display font-bold text-sm text-[var(--pn-text-primary)]">{{ auth()->user()->name }}</span>
                                            <span class="text-xs text-[var(--pn-text-secondary)]">{{ auth()->user()->email }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        @foreach (\App\Classes\Navigation::getAccountDropdownLinks() as $nav)
                                            <x-navigation.link :href="$nav['url']" :spa="$nav['spa'] ?? true" class="min-h-[44px]">
                                                {{ $nav['name'] }}
                                            </x-navigation.link>
                                        @endforeach
                                        <livewire:auth.logout />
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col gap-3">
                                    @if(!config('settings.registration_disabled', false))
                                        <a href="{{ route('register') }}" wire:navigate class="w-full">
                                            <x-button.primary>{{ __('navigation.register') }}</x-button.primary>
                                        </a>
                                    @endif
                                    <a href="{{ route('login') }}" wire:navigate class="w-full">
                                        <x-button.secondary>{{ __('navigation.login') }}</x-button.secondary>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</nav>
