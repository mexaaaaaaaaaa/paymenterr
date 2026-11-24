<div class="container py-10 sm:py-14 space-y-10">
    <x-navigation.breadcrumb />

    <div class="flex flex-col gap-3">
        <span class="font-hand text-sm font-semibold text-[var(--pn-blossom-700)]">{{ __('Discord Bot Companion') }}</span>
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-[var(--pn-text-primary)]">
            {{ __('One bot, everything managed') }}
        </h1>
        <p class="text-sm sm:text-base text-[var(--pn-text-secondary)] max-w-2xl">
            {{ __('Moderation, tickets, giveaways, rewards, and hosting automation — invite the bot and inspect commands directly.') }}
        </p>

        <div class="flex flex-wrap gap-3 pt-2">
            <a href="{{ $inviteUrl }}" target="_blank" rel="noopener noreferrer" class="pn-btn-primary !w-auto">
                <span>{{ __('Add to Discord') }}</span>
                <x-ri-external-link-line class="size-4" aria-hidden="true" />
            </a>
            @if (Route::has('status'))
                <a href="{{ route('status') }}" wire:navigate class="pn-btn-secondary !w-auto">
                    {{ __('Service Status') }}
                </a>
            @endif
        </div>
    </div>

    {{-- OAuth Server Management Notice --}}
    @if ($oauthConfigured)
        <x-sketch.rough-box surface="surface" seed="71" padding="p-6">
            <h2 class="font-display text-lg font-bold text-[var(--pn-text-primary)] mb-2">{{ __('Manage your servers') }}</h2>
            <p class="text-sm text-[var(--pn-text-secondary)] mb-4">{{ __('Sign in with Discord to view servers you manage.') }}</p>
            <a href="{{ $loginUrl }}" class="pn-btn-primary !w-auto">
                {{ __('Login with Discord') }}
            </a>
        </x-sketch.rough-box>

        @if (!empty($manageableGuilds))
            <div class="pn-card p-6 space-y-4">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="font-display text-lg font-bold text-[var(--pn-text-primary)]">{{ __('Your managed servers') }}</h2>
                        <p class="text-sm text-[var(--pn-text-secondary)]">
                            {{ __('Read-only guild list loaded via Discord OAuth.') }}
                        </p>
                    </div>
                    <div class="min-w-[220px]">
                        <label class="sr-only" for="bot-guild-selector">{{ __('Choose a managed Discord server') }}</label>
                        <select
                            id="bot-guild-selector"
                            wire:model.live="selectedGuildId"
                            class="w-full min-h-[42px] rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] px-3 py-2 text-sm text-[var(--pn-text-primary)] focus:border-[var(--pn-blossom-600)] focus:outline-none"
                        >
                            @foreach ($manageableGuilds as $guild)
                                <option value="{{ $guild['id'] }}">{{ $guild['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if (!empty($selectedGuild))
                    <p class="text-xs text-[var(--pn-text-secondary)]" aria-live="polite">
                        {{ __('Selected server') }}: <strong class="text-[var(--pn-text-primary)]">{{ $selectedGuild['name'] }}</strong> ({{ $selectedGuild['id'] }})
                    </p>
                @endif
            </div>
        @endif
    @else
        <x-sketch.sketch-alert tone="info" title="{{ __('Server management login') }}">
            {{ __('Discord OAuth login is not configured yet. Configure the bot inside Discord using !setupserver or /applypanel.') }}
        </x-sketch.sketch-alert>
    @endif

    {{-- Command Directory --}}
    <section class="space-y-5">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)]">{{ __('Command directory') }}</h2>
                <p class="font-pixel text-xs text-[var(--pn-text-secondary)] mt-1 tabular-nums" aria-live="polite">
                    {{ $totalSlash }} {{ __('slash commands') }} · {{ $totalPrefix }} {{ __('prefix commands') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <label class="sr-only" for="bot-command-search">{{ __('Search bot commands') }}</label>
                <input
                    id="bot-command-search"
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="{{ __('Search commands…') }}"
                    class="min-h-[42px] rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] px-3.5 py-2 text-sm text-[var(--pn-text-primary)] placeholder:text-[var(--pn-text-secondary)] w-56 focus:border-[var(--pn-blossom-600)] focus:outline-none"
                />
                <label class="sr-only" for="bot-command-filter">{{ __('Filter bot commands') }}</label>
                <select
                    id="bot-command-filter"
                    wire:model.live="filter"
                    class="min-h-[42px] rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] px-3 py-2 text-sm font-semibold text-[var(--pn-text-primary)] focus:border-[var(--pn-blossom-600)] focus:outline-none"
                >
                    <option value="all">{{ __('All') }}</option>
                    <option value="slash">{{ __('Slash') }}</option>
                    <option value="prefix">{{ __('Prefix') }}</option>
                </select>
            </div>
        </div>

        <div class="grid gap-3">
            @forelse ($filteredCommands as $c)
                <article class="pn-card p-4 bg-[var(--pn-bg-surface)]">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-pixel text-sm font-bold px-2.5 py-0.5 rounded-[var(--pn-radius-xs)] border {{ $c['type'] === 'slash' ? 'bg-[var(--pn-blossom-50)] text-[var(--pn-blossom-700)] border-[var(--pn-blossom-200)]' : 'bg-[var(--pn-moss-100)] text-[var(--pn-moss-700)] border-[var(--pn-moss)]' }}">
                            {{ $c['type'] === 'slash' ? '/' : '!' }}{{ $c['name'] }}
                        </span>
                        @if (!empty($c['aliases']))
                            <span class="text-xs text-[var(--pn-text-secondary)]">
                                {{ __('aliases') }}: {{ implode(', ', $c['aliases']) }}
                            </span>
                        @endif
                    </div>
                    @if (!empty($c['description']))
                        <p class="text-sm text-[var(--pn-text-secondary)] mt-2">{{ $c['description'] }}</p>
                    @endif
                    @if (!empty($c['params']))
                        <div class="flex flex-wrap gap-2 mt-2.5">
                            @foreach ($c['params'] as $p)
                                <span class="text-xs px-2 py-0.5 rounded-[var(--pn-radius-xs)] bg-[var(--pn-bg-canvas)] border border-[var(--pn-border-subtle)] text-[var(--pn-text-primary)]">
                                    {{ $p['name'] }}{{ empty($p['required']) ? '?' : '' }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </article>
            @empty
                <x-sketch.empty-state
                    title="{{ __('No matching commands') }}"
                    message="{{ __('No commands match') }} “{{ $search }}”."
                    stage="resting"
                />
            @endforelse
        </div>
    </section>
</div>
