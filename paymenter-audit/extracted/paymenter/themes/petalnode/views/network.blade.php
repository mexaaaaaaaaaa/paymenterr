<div class="container py-10 sm:py-14">
    <x-navigation.breadcrumb />

    <div class="flex flex-col gap-2 mt-2 mb-8">
        <span class="font-hand text-sm font-semibold text-[var(--pn-blossom-700)]">{{ __('network.header.eyebrow') }}</span>
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-[var(--pn-text-primary)]">{{ __('network.header.title') }}</h1>
        <p class="text-sm sm:text-base text-[var(--pn-text-secondary)] max-w-2xl">{{ __('network.header.description') }}</p>
    </div>

    {{-- Controls --}}
    <div class="pn-card p-4 flex flex-wrap items-center gap-3 mb-6">
        <input
            type="search"
            wire:model.live.debounce.300ms="query"
            placeholder="{{ __('Search nodes, regions...') }}"
            aria-label="{{ __('Search nodes') }}"
            class="min-h-[42px] flex-1 min-w-[200px] rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] px-3.5 py-2 text-sm text-[var(--pn-text-primary)] placeholder:text-[var(--pn-text-secondary)] focus:border-[var(--pn-blossom-600)] focus:outline-none"
        />
        <select
            wire:model.live="statusFilter"
            aria-label="{{ __('Filter by status') }}"
            class="min-h-[42px] rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] px-3 py-2 text-sm font-semibold text-[var(--pn-text-primary)] focus:border-[var(--pn-blossom-600)] focus:outline-none"
        >
            <option value="all">{{ __('network.filters.all_statuses') }}</option>
            <option value="online">{{ __('network.filters.online') }}</option>
            <option value="offline">{{ __('network.filters.offline') }}</option>
        </select>
        <x-button.secondary type="button" wire:click="refresh" class="!w-auto" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="refresh">{{ __('network.buttons.refresh_status') }}</span>
            <span wire:loading wire:target="refresh">{{ __('network.buttons.checking') }}</span>
        </x-button.secondary>
    </div>

    <div class="grid lg:grid-cols-12 gap-6 items-start">
        {{-- Sketch Map & Leaflet Host --}}
        <div class="lg:col-span-8">
            <x-sketch.rough-box surface="surface" seed="92" padding="p-4">
                <div class="relative rounded-[var(--pn-radius-md)] overflow-hidden border border-[var(--pn-border-subtle)] bg-[var(--pn-paper-shade)]/40" wire:loading.class="opacity-60">
                    <div id="node-map" class="w-full h-[380px] relative flex items-center justify-center">
                        <img
                            src="/petalnode/images/network-globe.jpg"
                            alt="{{ __('Global node regions map illustration') }}"
                            class="h-full w-full object-cover opacity-85"
                            onerror="this.classList.add('hidden');"
                        />
                    </div>
                    @if (count($filtered) === 0)
                        <div class="absolute inset-0 flex items-center justify-center bg-[var(--pn-bg-surface)]/80 backdrop-blur-[1px] p-4">
                            <span class="font-display font-bold text-sm text-[var(--pn-text-secondary)]">
                                {{ __('network.messages.no_nodes_match_filters') }}
                            </span>
                        </div>
                    @endif
                </div>
            </x-sketch.rough-box>
        </div>

        {{-- Node List & Selected Node Inspector --}}
        <div class="lg:col-span-4 space-y-4">
            @if ($selectedNode)
                <article class="pn-card p-5 border-[var(--pn-blossom-600)] bg-[var(--pn-blossom-50)]/45" role="region" aria-label="{{ __('Selected node details') }}">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <h2 class="font-display text-lg font-bold text-[var(--pn-text-primary)]">{{ $selectedNode['name'] }}</h2>
                        <x-sketch.stamp :tone="$selectedNode['online'] ? 'moss' : 'blossom'" rotate="rotate-0">
                            {{ $selectedNode['status'] }}
                        </x-sketch.stamp>
                    </div>
                    <dl class="text-sm space-y-2 text-[var(--pn-text-secondary)]">
                        <div class="flex justify-between gap-2"><dt>{{ __('network.details.region') }}</dt><dd class="font-semibold text-[var(--pn-text-primary)]">{{ $selectedNode['region'] }}</dd></div>
                        <div class="flex justify-between gap-2"><dt>{{ __('network.details.location') }}</dt><dd class="font-semibold text-[var(--pn-text-primary)]">{{ $selectedNode['location'] }}</dd></div>
                        @if (!empty($selectedNode['wings']))
                            <div class="flex justify-between gap-2"><dt>{{ __('network.details.wings') }}</dt><dd class="font-semibold text-[var(--pn-text-primary)]">{{ $selectedNode['wings'] }}</dd></div>
                        @endif
                        <div class="flex justify-between gap-2"><dt>{{ __('network.details.products') }}</dt><dd class="font-semibold text-[var(--pn-text-primary)]">{{ $selectedNode['products'] }}</dd></div>
                        <div class="flex justify-between gap-2"><dt>{{ __('network.details.checked') }}</dt><dd class="font-pixel text-xs text-[var(--pn-text-primary)] tabular-nums">{{ $selectedNode['checked_at'] }} UTC</dd></div>
                    </dl>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('home') }}#catalog-categories" wire:navigate class="pn-btn-primary !w-auto !py-2 !px-3 text-xs">
                            {{ __('network.node_buttons.view_plans') }}
                        </a>
                        @if (Route::has('status'))
                            <a href="{{ route('status') }}" wire:navigate class="pn-btn-secondary !w-auto !py-2 !px-3 text-xs">
                                {{ __('network.node_buttons.status') }}
                            </a>
                        @endif
                    </div>
                </article>
            @endif

            <div class="space-y-2.5" role="list">
                @forelse ($filtered as $n)
                    <button
                        type="button"
                        role="listitem"
                        class="w-full text-left pn-card p-3.5 cursor-pointer transition {{ $selected === $n['uuid'] ? 'border-[var(--pn-blossom-600)] bg-[var(--pn-blossom-50)]/55' : 'hover:border-[var(--pn-ink)]' }}"
                        wire:click="select('{{ $n['uuid'] }}')"
                        aria-label="{{ __('Focus node :name', ['name' => $n['name']]) }}"
                    >
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <div class="font-display font-bold text-sm text-[var(--pn-text-primary)] truncate">{{ $n['name'] }}</div>
                                <div class="text-xs text-[var(--pn-text-secondary)]">{{ $n['region'] }}</div>
                            </div>
                            <x-sketch.stamp :tone="$n['online'] ? 'moss' : 'blossom'" rotate="rotate-0">
                                {{ $n['status'] }}
                            </x-sketch.stamp>
                        </div>
                    </button>
                @empty
                    <div class="pn-card p-4 text-sm text-[var(--pn-text-secondary)]">
                        {{ __('network.messages.no_locations_found') }}
                    </div>
                @endforelse
            </div>

            @if (count($nodes) === 0)
                <x-sketch.empty-state
                    title="{{ __('network.messages.node_data_unavailable') }}"
                    stage="resting"
                >
                    <x-button.secondary type="button" wire:click="refresh" class="!w-auto">
                        {{ __('network.buttons.retry') }}
                    </x-button.secondary>
                </x-sketch.empty-state>
            @endif
        </div>
    </div>
</div>
