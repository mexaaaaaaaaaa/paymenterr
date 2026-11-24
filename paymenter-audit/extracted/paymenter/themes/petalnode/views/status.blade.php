<div class="container py-10 sm:py-14">
    <x-navigation.breadcrumb />

    <div class="flex flex-col gap-3 mt-2 mb-8">
        <span class="font-hand text-sm font-semibold text-[var(--pn-blossom-700)]">{{ __('System status') }}</span>
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-[var(--pn-text-primary)]">
            {{ config('app.name', 'Petalnode') }} {{ __('Status') }}
        </h1>
        <p class="text-sm sm:text-base text-[var(--pn-text-secondary)] max-w-2xl">
            {{ __('Live health of our website, billing, game panel and infrastructure — checked automatically.') }}
        </p>
        <p class="font-pixel text-xs text-[var(--pn-text-secondary)] tabular-nums">
            {{ __('Last checked') }}: {{ collect($live)->max('checked_at') ?? '—' }}
        </p>
    </div>

    {{-- Overall Banner --}}
    @php
        $overallMeta = [
            'operational' => ['All systems operational', 'moss', 'bg-[var(--pn-moss-700)]'],
            'partial' => ['Partial outage', 'lantern', 'bg-[var(--pn-lantern-700)]'],
            'major' => ['Major outage', 'blossom', 'bg-[#B42343]'],
            'maintenance' => ['Maintenance in progress', 'lantern', 'bg-[var(--pn-blossom-700)]'],
        ][$overall] ?? ['Status check pending', 'shade', 'bg-[var(--pn-text-secondary)]'];
    @endphp
    <x-sketch.rough-box :surface="$overallMeta[1]" seed="41" padding="p-5 sm:p-6" class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <span class="flex size-3.5 rounded-full {{ $overallMeta[2] }} shrink-0" aria-hidden="true"></span>
            <div class="flex-1">
                <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)]">{{ __($overallMeta[0]) }}</h2>
                <p class="text-sm text-[var(--pn-text-secondary)] tabular-nums">
                    {{ trans_choice('Checking :count service in real time|Checking :count services in real time', count($services), ['count' => count($services)]) }}
                </p>
            </div>
        </div>
    </x-sketch.rough-box>

    {{-- Service Health Cards --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($services as $key => $svc)
            @php
                $result = $live[$key] ?? ['status' => 'down', 'latency_ms' => null, 'details' => 'not checked yet', 'checked_at' => null];
                $hist = $history[$key] ?? [];
                $stampTone = match ($result['status']) {
                    'operational' => 'moss',
                    'degraded' => 'lantern',
                    default => 'blossom',
                };
                $label = match ($result['status']) {
                    'operational' => 'Operational',
                    'degraded' => 'Degraded',
                    default => 'Down',
                };
            @endphp
            <article class="pn-card p-5 flex flex-col gap-3.5 bg-[var(--pn-bg-surface)]">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <h3 class="font-display font-bold text-base text-[var(--pn-text-primary)]">{{ $svc['name'] }}</h3>
                        <p class="text-xs text-[var(--pn-text-secondary)] mt-0.5">{{ $svc['description'] }}</p>
                    </div>
                    <x-sketch.stamp :tone="$stampTone" rotate="rotate-0" class="shrink-0">
                        {{ __($label) }}
                    </x-sketch.stamp>
                </div>

                <div class="grid grid-cols-3 gap-2 text-center border-y border-[var(--pn-border-subtle)] py-3">
                    <div>
                        <div class="font-display text-sm font-bold text-[var(--pn-text-primary)] tabular-nums">
                            {{ isset($hist['uptime']) && $hist['uptime'] !== null ? $hist['uptime'] . '%' : '—' }}
                        </div>
                        <div class="text-[11px] text-[var(--pn-text-secondary)]">{{ __('30-day uptime') }}</div>
                    </div>
                    <div>
                        <div class="font-display text-sm font-bold text-[var(--pn-text-primary)] tabular-nums">
                            {{ $result['latency_ms'] !== null ? $result['latency_ms'] . 'ms' : '—' }}
                        </div>
                        <div class="text-[11px] text-[var(--pn-text-secondary)]">{{ __('Response') }}</div>
                    </div>
                    <div>
                        <div class="font-display text-sm font-bold text-[var(--pn-text-primary)] tabular-nums">
                            {{ isset($hist['avg_latency']) && $hist['avg_latency'] !== null ? $hist['avg_latency'] . 'ms' : '—' }}
                        </div>
                        <div class="text-[11px] text-[var(--pn-text-secondary)]">{{ __('Avg 30d') }}</div>
                    </div>
                </div>

                <p class="text-xs text-[var(--pn-text-secondary)] leading-relaxed">{{ $result['details'] }}</p>
                @if (empty($hist['has_data']))
                    <p class="font-pixel text-[11px] text-[var(--pn-text-secondary)]">
                        {{ __('Collecting history') }} — {{ \Carbon\Carbon::now()->format('d M Y') }}
                    </p>
                @endif
            </article>
        @endforeach
    </div>

    {{-- Incidents & Maintenance --}}
    <section class="mt-12 flex flex-col gap-4">
        <h2 class="font-display text-2xl font-bold text-[var(--pn-text-primary)]">{{ __('Incidents & maintenance') }}</h2>
        @if ($incidents->isEmpty())
            <x-sketch.empty-state
                title="{{ __('No incidents reported') }}"
                message="{{ __('When something happens, verified timeline details will appear right here.') }}"
                stage="blooming"
            />
        @else
            <div class="flex flex-col gap-3">
                @foreach ($incidents as $incident)
                    @php
                        $stateTone = match ($incident->state) {
                            'resolved' => 'moss',
                            'identified', 'monitoring', 'maintenance' => 'lantern',
                            default => 'blossom',
                        };
                    @endphp
                    <article class="pn-card p-5 flex flex-col gap-2.5 bg-[var(--pn-bg-surface)]">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="font-display font-bold text-base text-[var(--pn-text-primary)]">{{ $incident->title }}</h3>
                            <x-sketch.stamp :tone="$stateTone" rotate="rotate-0">{{ ucfirst($incident->state) }}</x-sketch.stamp>
                            <span class="font-pixel text-xs text-[var(--pn-text-secondary)] ml-auto tabular-nums">{{ $incident->started_at->format('d M Y, H:i') }} UTC</span>
                        </div>
                        @if ($incident->affected_services)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($incident->affected_services as $sKey)
                                    @if (isset($services[$sKey]))
                                        <span class="text-xs px-2 py-0.5 rounded-[var(--pn-radius-xs)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] text-[var(--pn-text-secondary)]">
                                            {{ $services[$sKey]['name'] }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if ($incident->body)
                            <p class="text-sm text-[var(--pn-text-secondary)] leading-relaxed whitespace-pre-line">{!! strip_tags($incident->body, '<b><strong><i><em><a><code>') !!}</p>
                        @endif
                        @if ($incident->resolved_at)
                            <p class="text-xs font-bold text-[var(--pn-moss-700)] tabular-nums">{{ __('Resolved') }} {{ $incident->resolved_at->format('d M Y, H:i') }} UTC</p>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
