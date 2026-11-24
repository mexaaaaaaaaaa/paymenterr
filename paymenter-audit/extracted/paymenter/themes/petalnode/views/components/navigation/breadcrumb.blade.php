@php
    $currentRoute = request()->livewireUrl();

    $navigation = [
        \App\Classes\Navigation::getLinks(),
        \App\Classes\Navigation::getAccountDropdownLinks(),
        \App\Classes\Navigation::getDashboardLinks(),
    ];

    if (!function_exists('pnFindBreadcrumb')) {
        function pnFindBreadcrumb($items, $currentRoute) {
            foreach ($items as $item) {
                if (isset($item['url']) && $item['url'] === $currentRoute) {
                    return [$item];
                }

                if (!empty($item['children'])) {
                    $childTrail = pnFindBreadcrumb($item['children'], $currentRoute);
                    if (!empty($childTrail)) {
                        return array_merge([$item], $childTrail);
                    }
                }
            }

            return [];
        }
    }

    $breadcrumbs = [];
    foreach ($navigation as $group) {
        $breadcrumbs = pnFindBreadcrumb($group, $currentRoute);
        if (!empty($breadcrumbs)) {
            break;
        }
    }
@endphp

<nav aria-label="{{ __('Breadcrumb') }}" class="flex flex-row flex-wrap items-center gap-1.5 pb-4 text-sm">
    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-1.5 font-semibold text-[var(--pn-text-secondary)] hover:text-[var(--pn-blossom-700)] transition-colors">
        <x-sketch.hand-icon name="blossom" size="15" class="text-[var(--pn-blossom-600)]" />
        <span>{{ __('navigation.home') }}</span>
    </a>

    @if (!empty($breadcrumbs))
        @foreach ($breadcrumbs as $index => $breadcrumb)
            <x-ri-arrow-right-s-line class="size-4 text-[var(--pn-text-secondary)]/60" aria-hidden="true" />
            @if ($index === count($breadcrumbs) - 1)
                <span class="font-display font-bold text-[var(--pn-text-primary)]" aria-current="page">
                    {{ $breadcrumb['name'] ?? '' }}
                </span>
            @else
                <a href="{{ isset($breadcrumb['route']) ? route($breadcrumb['route'], $breadcrumb['params'] ?? []) : ($breadcrumb['url'] ?? '#') }}"
                   wire:navigate
                   class="font-semibold text-[var(--pn-text-secondary)] hover:text-[var(--pn-blossom-700)] transition-colors">
                    {{ $breadcrumb['name'] ?? '' }}
                </a>
            @endif
        @endforeach
    @endif
</nav>
