<div class="lg:px-4 lg:py-6 flex flex-col gap-2">
    <div class="flex flex-col gap-1.5 md:hidden">
        @foreach (\App\Classes\Navigation::getLinks() as $nav)
            @if (!empty($nav['children']))
                <div x-data="{ activeAccordion: {{ ($nav['active'] ?? false) ? 'true' : 'false' }} }"
                    class="relative w-full mx-auto overflow-hidden text-sm font-normal rounded-[var(--pn-radius-md)]">
                    <button
                        type="button"
                        @click="activeAccordion = !activeAccordion"
                        :aria-expanded="activeAccordion.toString()"
                        class="flex items-center justify-between w-full min-h-[44px] px-3 py-2.5 text-sm font-semibold rounded-[var(--pn-radius-md)] text-[var(--pn-text-primary)] hover:bg-[var(--pn-paper-shade)]/70 cursor-pointer"
                    >
                        <div class="flex flex-row items-center gap-2.5">
                            @isset($nav['icon'])
                                <x-dynamic-component :component="$nav['icon']" class="size-5 {{ ($nav['active'] ?? false) ? 'text-[var(--pn-blossom-700)]' : 'text-[var(--pn-text-secondary)]' }}" />
                            @endisset
                            <span>{{ $nav['name'] }}</span>
                        </div>
                        <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }" class="size-4 text-[var(--pn-text-secondary)] ease-out duration-200" aria-hidden="true" />
                    </button>
                    <div x-show="activeAccordion" x-collapse x-cloak>
                        <div class="pl-4 pr-2 pb-2 flex flex-col gap-1">
                            @foreach ($nav['children'] as $child)
                                <x-navigation.link
                                    :href="$child['url']"
                                    :spa="$child['spa'] ?? true"
                                    class="{{ ($child['active'] ?? false) ? 'text-[var(--pn-blossom-700)] font-bold' : '' }}"
                                >
                                    {{ $child['name'] }}
                                </x-navigation.link>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <x-navigation.link
                    :href="$nav['url']"
                    :spa="$nav['spa'] ?? true"
                    class="w-full min-h-[44px]"
                >
                    @isset($nav['icon'])
                        <x-dynamic-component :component="$nav['icon']" class="size-5 {{ ($nav['active'] ?? false) ? 'text-[var(--pn-blossom-700)]' : 'text-[var(--pn-text-secondary)]' }}" />
                    @endisset
                    <span>{{ $nav['name'] }}</span>
                </x-navigation.link>
            @endif
            @isset($nav['separator'])
                <div class="h-px w-full bg-[var(--pn-border-subtle)] my-1"></div>
            @endisset
        @endforeach
    </div>

    <div class="flex flex-col gap-1.5">
        @foreach (\App\Classes\Navigation::getDashboardLinks() as $nav)
            @if (!empty($nav['children']))
                <div x-data="{ activeAccordion: {{ ($nav['active'] ?? false) ? 'true' : 'false' }} }"
                    class="relative w-full mx-auto overflow-hidden text-sm font-normal rounded-[var(--pn-radius-md)]">
                    <button
                        type="button"
                        @click="activeAccordion = !activeAccordion"
                        :aria-expanded="activeAccordion.toString()"
                        class="flex items-center justify-between w-full min-h-[44px] px-3 py-2.5 text-sm font-semibold rounded-[var(--pn-radius-md)] text-[var(--pn-text-primary)] hover:bg-[var(--pn-paper-shade)]/70 cursor-pointer"
                    >
                        <div class="flex flex-row items-center gap-2.5">
                            @isset($nav['icon'])
                                <x-dynamic-component :component="$nav['icon']" class="size-5 {{ ($nav['active'] ?? false) ? 'text-[var(--pn-blossom-700)]' : 'text-[var(--pn-text-secondary)]' }}" />
                            @endisset
                            <span>{{ $nav['name'] }}</span>
                        </div>
                        <x-ri-arrow-down-s-line x-bind:class="{ 'rotate-180': activeAccordion }" class="size-4 text-[var(--pn-text-secondary)] ease-out duration-200" aria-hidden="true" />
                    </button>
                    <div x-show="activeAccordion" x-collapse x-cloak>
                        <div class="pl-4 pr-2 pb-2 flex flex-col gap-1">
                            @foreach ($nav['children'] as $child)
                                @if ($child['condition'] ?? true)
                                    <x-navigation.link
                                        :href="$child['url']"
                                        :spa="$child['spa'] ?? true"
                                        class="{{ ($child['active'] ?? false) ? 'text-[var(--pn-blossom-700)] font-bold' : '' }}"
                                    >
                                        {{ $child['name'] }}
                                    </x-navigation.link>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <x-navigation.link
                    :href="$nav['url']"
                    :spa="$nav['spa'] ?? true"
                    class="w-full min-h-[44px]"
                >
                    @isset($nav['icon'])
                        <x-dynamic-component :component="$nav['icon']" class="size-5 {{ ($nav['active'] ?? false) ? 'text-[var(--pn-blossom-700)]' : 'text-[var(--pn-text-secondary)]' }}" />
                    @endisset
                    <span>{{ $nav['name'] }}</span>
                </x-navigation.link>
            @endif
            @isset($nav['separator'])
                <div class="h-px w-full bg-[var(--pn-border-subtle)] my-1"></div>
            @endisset
        @endforeach

        <div class="flex flex-row items-center mt-4 pt-3 border-t border-[var(--pn-border-subtle)] justify-between md:hidden">
            <livewire:components.locale-switch />
            <x-theme-toggle />
        </div>
    </div>
</div>
