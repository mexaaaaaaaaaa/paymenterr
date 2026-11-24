<div class="container py-10 sm:py-14 space-y-12">
    <x-navigation.breadcrumb />

    <div class="flex flex-col gap-3">
        <span class="font-hand text-sm font-semibold text-[var(--pn-blossom-700)]">{{ __('Free Hosting Rewards') }}</span>
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-[var(--pn-text-primary)]">
            {{ __('Earn community-backed hosting rewards') }}
        </h1>
        <p class="text-sm sm:text-base text-[var(--pn-text-secondary)] max-w-2xl">
            {{ __('Earn free community servers by inviting friends or boosting our Discord, backed by NVMe storage.') }}
        </p>
    </div>

    {{-- Invite Rewards --}}
    <section class="space-y-5">
        <div class="flex items-center gap-3">
            <span class="flex size-10 items-center justify-center rounded-[var(--pn-radius-md)] bg-[var(--pn-blossom-50)] text-[var(--pn-blossom-700)] border border-[var(--pn-blossom-200)]">
                <x-sketch.hand-icon name="blossom" size="20" />
            </span>
            <div>
                <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)]">{{ __('Invite Rewards') }}</h2>
                <p class="text-xs sm:text-sm text-[var(--pn-text-secondary)]">{{ __('Invite friends to Discord to unlock qualifying tiers') }}</p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($inviteTiers as $tier)
                <article class="pn-card p-5 flex flex-col {{ $tier['invites'] >= 12 ? 'border-[var(--pn-blossom-600)] bg-[var(--pn-blossom-50)]/45' : 'bg-[var(--pn-bg-surface)]' }}">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-display text-lg font-bold text-[var(--pn-text-primary)]">{{ $tier['name'] }}</h3>
                        @if ($tier['invites'] >= 12)
                            <x-sketch.stamp tone="blossom" rotate="-rotate-2">{{ __('Popular') }}</x-sketch.stamp>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-[var(--pn-text-secondary)]">{{ __('Free while active') }}</p>
                    <p class="mt-3 font-display text-2xl font-extrabold text-[var(--pn-blossom-700)] tabular-nums">
                        {{ $tier['ram'] }} <span class="text-sm font-semibold text-[var(--pn-text-secondary)]">RAM</span>
                    </p>
                    <ul class="mt-4 space-y-1.5 text-sm text-[var(--pn-text-secondary)] flex-1">
                        <li>• {{ $tier['invites'] }} {{ __('qualifying invites') }}</li>
                        <li>• {{ __('NVMe storage') }}</li>
                        <li>• {{ __('Java + Bedrock support') }}</li>
                        <li>• {{ __('Setup after review') }}</li>
                    </ul>
                    <a href="https://discord.gg/zypernodes" class="pn-btn-primary mt-5" target="_blank" rel="noopener noreferrer">
                        {{ __('Join Discord') }}
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Boost Rewards --}}
    <section class="space-y-5">
        <div class="flex items-center gap-3">
            <span class="flex size-10 items-center justify-center rounded-[var(--pn-radius-md)] bg-[var(--pn-moss-100)] text-[var(--pn-moss-700)] border border-[var(--pn-moss)]">
                <x-sketch.hand-icon name="leaf" size="20" />
            </span>
            <div>
                <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)]">{{ __('Boost Rewards') }}</h2>
                <p class="text-xs sm:text-sm text-[var(--pn-text-secondary)]">{{ __('Boost Discord for active reward tiers') }}</p>
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($boostTiers as $tier)
                <article class="pn-card p-5 flex flex-col bg-[var(--pn-bg-surface)]">
                    <h3 class="font-display text-lg font-bold text-[var(--pn-text-primary)]">{{ $tier['name'] }}</h3>
                    <p class="mt-1 text-xs text-[var(--pn-text-secondary)]">{{ __('While boost is active') }}</p>
                    <p class="mt-3 font-display text-2xl font-extrabold text-[var(--pn-moss-700)] tabular-nums">
                        {{ $tier['ram'] }} <span class="text-sm font-semibold text-[var(--pn-text-secondary)]">RAM</span>
                    </p>
                    <ul class="mt-4 space-y-1.5 text-sm text-[var(--pn-text-secondary)] flex-1">
                        <li>• {{ $tier['boosts'] }} {{ __('active') }} {{ $tier['boosts'] > 1 ? __('boosts') : __('boost') }}</li>
                        <li>• {{ __('NVMe storage') }}</li>
                        <li>• {{ __('Java + Bedrock support') }}</li>
                    </ul>
                    <a href="https://discord.gg/zypernodes" class="pn-btn-secondary mt-5" target="_blank" rel="noopener noreferrer">
                        {{ __('Boost Server') }}
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    {{-- How to Claim & Rules --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <x-sketch.rough-box surface="surface" seed="49" padding="p-6">
            <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)] mb-4">{{ __('How to claim') }}</h2>
            <ol class="space-y-2.5 text-sm text-[var(--pn-text-secondary)] list-decimal list-inside">
                <li><strong class="text-[var(--pn-text-primary)]">{{ __('Join Discord') }}</strong> — <a href="https://discord.gg/zypernodes" target="_blank" rel="noopener noreferrer" class="text-[var(--pn-blossom-700)] underline">discord.gg/zypernodes</a></li>
                <li><strong class="text-[var(--pn-text-primary)]">{{ __('Track progress') }}</strong> — <code>!invitecheck</code> / <code>!boostcheck</code></li>
                <li><strong class="text-[var(--pn-text-primary)]">{{ __('Open a Discord ticket') }}</strong> {{ __('with your eligibility screenshot') }}</li>
                <li><strong class="text-[var(--pn-text-primary)]">{{ __('Owner verification') }}</strong> {{ __('before provisioning') }}</li>
            </ol>
        </x-sketch.rough-box>

        <x-sketch.sketch-card surface="shade" padding="p-6">
            <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)] mb-4">{{ __('Program rules') }}</h2>
            <ul class="space-y-2 text-sm text-[var(--pn-text-secondary)]">
                <li>• {{ __('Member leave') }}: <strong class="text-[var(--pn-text-primary)]">−2</strong> {{ __('qualifying invites') }}</li>
                <li>• {{ __('Rejoin') }}: <strong class="text-[var(--pn-text-primary)]">−1</strong> {{ __('qualifying invite') }}</li>
                <li>• {{ __('Alt or fake accounts') }}: <strong class="text-[var(--pn-text-primary)]">−2</strong> {{ __('invites, subject to review') }}</li>
                <li>• {{ __('One free server per user per campaign') }}</li>
                <li>• {{ __('All reward claims require review') }}</li>
            </ul>
        </x-sketch.sketch-card>
    </div>
</div>
