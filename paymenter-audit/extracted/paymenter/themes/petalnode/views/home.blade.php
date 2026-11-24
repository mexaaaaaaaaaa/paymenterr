<div>
    {{-- 1. Animated Sketchbook Hero --}}
    <section class="relative overflow-hidden border-b border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] py-12 sm:py-16 lg:py-20">
        <div class="container relative">
            <div class="grid gap-10 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-7 flex flex-col items-start gap-5">
                    <span class="inline-flex items-center gap-2 rounded-[var(--pn-radius-md)] border border-[var(--pn-blossom-200)] bg-[var(--pn-blossom-50)] px-3 py-1 font-hand text-xs sm:text-sm font-semibold text-[var(--pn-blossom-700)]">
                        <x-sketch.hand-icon name="blossom" size="16" class="text-[var(--pn-blossom-600)]" />
                        <span>{{ config('app.name', 'Petalnode') }} · {{ __('Sketchbook Hosting Storefront') }}</span>
                    </span>

                    <h1 class="font-display text-4xl sm:text-5xl lg:text-[3.5rem] font-extrabold tracking-tight leading-[1.08] text-[var(--pn-text-primary)]">
                        {{ __('Cozy worlds built on') }}
                        <x-sketch.rough-underline color="var(--pn-blossom-600)" seed="23">
                            {{ __('dependable nodes.') }}
                        </x-sketch.rough-underline>
                    </h1>

                    @if (filled(theme('home_page_text')))
                        <article class="prose dark:prose-invert max-w-xl text-[var(--pn-text-secondary)]">
                            {!! Str::markdown(theme('home_page_text'), [
                                'allow_unsafe_links' => false,
                                'renderer' => ['soft_break' => '<br>'],
                            ]) !!}
                        </article>
                    @else
                        <p class="max-w-xl text-base sm:text-lg leading-relaxed text-[var(--pn-text-secondary)]">
                            {{ __('Browse real categories, configurable plans, and instant checkout powered directly by your Paymenter billing storefront.') }}
                        </p>
                    @endif

                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        @if ($categories->isNotEmpty())
                            <a href="#catalog-categories" class="pn-btn-primary">
                                <span>{{ __('Explore Hosting Catalog') }}</span>
                                <x-ri-arrow-right-line class="size-4" aria-hidden="true" />
                            </a>
                        @elseif(Route::has('products.index'))
                            <a href="{{ route('home') }}#catalog-categories" class="pn-btn-primary">
                                <span>{{ __('Browse Catalog') }}</span>
                            </a>
                        @endif

                        @if (Route::has('status'))
                            <a href="{{ route('status') }}" wire:navigate class="pn-btn-secondary">
                                <x-sketch.hand-icon name="shield" size="18" class="text-[var(--pn-moss-700)]" />
                                <span>{{ __('Service Status') }}</span>
                            </a>
                        @endif

                        @if (Route::has('free-plans'))
                            <a href="{{ route('free-plans') }}" wire:navigate class="pn-btn-secondary">
                                <x-sketch.hand-icon name="leaf" size="18" class="text-[var(--pn-moss-700)]" />
                                <span>{{ __('Free Rewards') }}</span>
                            </a>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-5 pt-2">
                        <x-sketch.annotation direction="right">
                            {{ __('Meet Nodey & Sprout') }}
                        </x-sketch.annotation>
                        <div class="flex items-center gap-2">
                            <x-sketch.nodey-mascot mood="happy" size="58" />
                            <x-sketch.sprout-mascot stage="blooming" size="52" />
                        </div>
                    </div>
                </div>

                {{-- Hero Polaroid Showcase --}}
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-md">
                        <x-sketch.floating-petals />
                        <x-sketch.polaroid-frame
                            caption="{{ config('app.name', 'Petalnode') }} · {{ __('Freshly sketched & ready to deploy') }}"
                            tapeTone="blossom"
                            tilt="-rotate-1"
                        >
                            <div class="relative aspect-[4/3] w-full overflow-hidden bg-[var(--pn-paper-shade)]">
                                <img
                                    src="/petalnode/images/hero-island.jpg"
                                    alt="{{ __('Hand-painted floating voxel blossom island illustration') }}"
                                    class="h-full w-full object-cover"
                                    onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                />
                                <div class="hidden h-full w-full flex flex-col items-center justify-center gap-3 p-6 text-center">
                                    <div class="flex items-center gap-3">
                                        <x-sketch.nodey-mascot mood="happy" size="72" />
                                        <x-sketch.sprout-mascot stage="blooming" size="64" />
                                    </div>
                                    <p class="font-hand text-xs text-[var(--pn-text-secondary)]">
                                        {{ __('Blossom island sketchbook scene') }}
                                    </p>
                                </div>
                            </div>
                        </x-sketch.polaroid-frame>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Extension Home Hook --}}
    {!! hook('pages.home') !!}

    {{-- 3. Live Paymenter Categories Grid --}}
    <section id="catalog-categories" class="container py-14 sm:py-16" data-pn-reveal-group>
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="font-hand text-sm font-semibold text-[var(--pn-blossom-700)]">{{ __('01. Live Storefront Categories') }}</span>
                <h2 class="mt-1 font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-[var(--pn-text-primary)]">
                    {{ __('Services & Hosting Categories') }}
                </h2>
            </div>
            @if ($categories->isNotEmpty())
                <span class="font-pixel text-xs font-bold uppercase tracking-wider text-[var(--pn-text-secondary)] tabular-nums">
                    {{ trans_choice(':count category available|:count categories available', $categories->count(), ['count' => $categories->count()]) }}
                </span>
            @endif
        </div>

        @if ($categories->isEmpty())
            <x-sketch.empty-state
                title="{{ __('No visible categories published yet') }}"
                message="{{ __('Categories created in the Paymenter admin panel will automatically appear here as soon as they have visible plans.') }}"
                stage="growing"
            />
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($categories as $category)
                    <article data-pn-reveal data-pn-tilt class="pn-card pn-card-interactive flex flex-col p-5 bg-[var(--pn-bg-surface)]">
                        <div class="{{ theme('small_images', false) ? 'flex items-center gap-3.5' : 'flex flex-col gap-3' }}">
                            @if ($category->image)
                                <div class="overflow-hidden rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-paper-shade)] shrink-0 {{ theme('small_images', false) ? 'w-16 h-16' : 'aspect-[16/10] w-full' }}">
                                    <img
                                        src="{{ Storage::url($category->image) }}"
                                        alt="{{ $category->name }}"
                                        class="h-full w-full object-cover object-center"
                                    />
                                </div>
                            @else
                                <div class="flex items-center justify-center rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-blossom-50)] text-[var(--pn-blossom-700)] shrink-0 {{ theme('small_images', false) ? 'w-14 h-14' : 'h-24 w-full' }}">
                                    <x-sketch.hand-icon name="cube" size="28" />
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-display text-xl font-bold text-[var(--pn-text-primary)] truncate">
                                        {{ $category->name }}
                                    </h3>
                                </div>
                                @if (isset($category->products_count) || isset($category->children_count))
                                    <p class="mt-1 font-pixel text-xs text-[var(--pn-text-secondary)] tabular-nums">
                                        @if (($category->children_count ?? 0) > 0)
                                            {{ trans_choice(':count subcategory|:count subcategories', $category->children_count, ['count' => $category->children_count]) }}
                                        @endif
                                        @if (($category->children_count ?? 0) > 0 && ($category->products_count ?? 0) > 0)
                                            ·
                                        @endif
                                        @if (($category->products_count ?? 0) > 0)
                                            {{ trans_choice(':count plan|:count plans', $category->products_count, ['count' => $category->products_count]) }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if (theme('show_category_description', true) && filled($category->description))
                            <article class="prose dark:prose-invert prose-sm mt-3 text-[var(--pn-text-secondary)] line-clamp-3">
                                {!! $category->description !!}
                            </article>
                        @endif

                        <div class="mt-auto pt-5">
                            <a href="{{ route('category.show', ['category' => $category->slug]) }}" wire:navigate class="block w-full">
                                <x-button.primary>
                                    <span>{{ __('common.button.view_all') }}</span>
                                    <x-ri-arrow-right-line class="size-4" aria-hidden="true" />
                                </x-button.primary>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- 4. Featured Real Products Spotlight (renders only if Paymenter returns matching records) --}}
    @php
        $featuredProducts = collect([
            $minecraftProduct ?? null,
            $gameHostingProduct ?? null,
            $botHostingProduct ?? null,
        ])->filter()->unique('id');
    @endphp
    @if ($featuredProducts->isNotEmpty())
        <section class="border-y border-[var(--pn-border-subtle)] bg-[var(--pn-paper-shade)]/45 py-14">
            <div class="container">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                    <div>
                        <span class="font-hand text-sm font-semibold text-[var(--pn-moss-700)]">{{ __('02. Direct from Catalog') }}</span>
                        <h2 class="mt-1 font-display text-2xl sm:text-3xl font-extrabold text-[var(--pn-text-primary)]">
                            {{ __('Spotlight Plans') }}
                        </h2>
                    </div>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredProducts as $featuredProduct)
                        @include('products.partials.plan-card', ['product' => $featuredProduct, 'category' => $featuredProduct->category])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 5. Craft & Live Infrastructure Story (zero fabricated specs or uptime) --}}
    <section class="container py-14 sm:py-16">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-5">
                <x-sketch.polaroid-frame
                    caption="{{ __('Sketchbook engineering & live Paymenter hooks') }}"
                    tapeTone="moss"
                    tilt="rotate-1"
                >
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-[var(--pn-paper-shade)]">
                        <img
                            src="/petalnode/images/server-craft.jpg"
                            alt="{{ __('Hand-painted cozy server workbench illustration') }}"
                            class="h-full w-full object-cover"
                            onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                        />
                        <div class="hidden h-full w-full flex flex-col items-center justify-center p-6 text-center">
                            <x-sketch.nodey-mascot mood="working" size="76" />
                        </div>
                    </div>
                </x-sketch.polaroid-frame>
            </div>

            <div class="lg:col-span-7 space-y-5">
                <span class="font-hand text-sm font-semibold text-[var(--pn-blossom-700)]">{{ __('Why Petalnode') }}</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[var(--pn-text-primary)]">
                    {{ __('Every button, option, and price comes straight from Paymenter.') }}
                </h2>
                <p class="text-sm sm:text-base leading-relaxed text-[var(--pn-text-secondary)]">
                    {{ __('Your server extensions, configurable options, multi-currency pricing, coupons, and payment gateways stay intact inside a warm paper storefront.') }}
                </p>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-sketch.sketch-card padding="p-4">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-[var(--pn-radius-md)] bg-[var(--pn-blossom-50)] text-[var(--pn-blossom-700)]">
                                <x-sketch.hand-icon name="shield" size="20" />
                            </span>
                            <div>
                                <h3 class="font-display text-base font-bold text-[var(--pn-text-primary)]">{{ __('Authentic Billing Flow') }}</h3>
                                <p class="mt-1 text-xs leading-relaxed text-[var(--pn-text-secondary)]">{{ __('Taxes, discounts, stock limits, and invoices are always computed server-side.') }}</p>
                            </div>
                        </div>
                    </x-sketch.sketch-card>

                    <x-sketch.sketch-card padding="p-4">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-[var(--pn-radius-md)] bg-[var(--pn-moss-100)] text-[var(--pn-moss-700)]">
                                <x-sketch.hand-icon name="compass" size="20" />
                            </span>
                            <div>
                                <h3 class="font-display text-base font-bold text-[var(--pn-text-primary)]">{{ __('Accessible by Default') }}</h3>
                                <p class="mt-1 text-xs leading-relaxed text-[var(--pn-text-secondary)]">{{ __('Keyboard navigation, AA ink contrast, and reduced-motion safeguards built in.') }}</p>
                            </div>
                        </div>
                    </x-sketch.sketch-card>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. Real Preview Nodes (only rendered if backend returns real nodes) --}}
    @if (!empty($previewNodes))
        <section class="border-t border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] py-14">
            <div class="container">
                <div class="grid gap-8 lg:grid-cols-12 lg:items-center">
                    <div class="lg:col-span-4">
                        <x-sketch.polaroid-frame
                            caption="{{ __('Configured infrastructure nodes') }}"
                            tapeTone="lantern"
                            tilt="-rotate-1"
                        >
                            <div class="relative aspect-[4/3] w-full overflow-hidden bg-[var(--pn-paper-shade)]">
                                <img
                                    src="/petalnode/images/network-globe.jpg"
                                    alt="{{ __('Sketchbook cartography globe illustration') }}"
                                    class="h-full w-full object-cover"
                                    onerror="this.classList.add('hidden');"
                                />
                            </div>
                        </x-sketch.polaroid-frame>
                    </div>
                    <div class="lg:col-span-8 space-y-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <span class="font-hand text-sm font-semibold text-[var(--pn-moss-700)]">{{ __('Live Panel Telemetry') }}</span>
                                <h2 class="font-display text-2xl font-extrabold text-[var(--pn-text-primary)]">
                                    {{ __('Infrastructure Nodes') }}
                                </h2>
                            </div>
                            @if (Route::has('network'))
                                <a href="{{ route('network') }}" wire:navigate class="pn-btn-secondary !w-auto">
                                    <span>{{ __('Open Node Map') }}</span>
                                    <x-ri-arrow-right-line class="size-4" aria-hidden="true" />
                                </a>
                            @endif
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            @foreach ($previewNodes as $node)
                                <x-sketch.sketch-card padding="p-4">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="font-display font-bold text-base text-[var(--pn-text-primary)] truncate">{{ $node['name'] }}</h3>
                                        <x-sketch.stamp :tone="$node['online'] ? 'moss' : 'blossom'" rotate="rotate-0">
                                            {{ $node['online'] ? __('Online') : __('Offline') }}
                                        </x-sketch.stamp>
                                    </div>
                                    <p class="mt-2 text-xs text-[var(--pn-text-secondary)]">{{ $node['region'] }}</p>
                                    @if (isset($node['mem_pct']) && $node['mem_pct'] !== null)
                                        <p class="mt-2 font-pixel text-xs text-[var(--pn-text-primary)] tabular-nums">
                                            {{ __('Memory') }}: {{ $node['mem_pct'] }}%
                                        </p>
                                    @endif
                                </x-sketch.sketch-card>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 7. FAQ & Call to Action --}}
    <section class="container py-14 space-y-12">
        <div class="grid gap-8 lg:grid-cols-12 items-start">
            <div class="lg:col-span-5 space-y-3">
                <span class="font-hand text-sm font-semibold text-[var(--pn-blossom-700)]">{{ __('Questions & Answers') }}</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[var(--pn-text-primary)]">
                    {{ __('How ordering & billing works') }}
                </h2>
                <p class="text-sm leading-relaxed text-[var(--pn-text-secondary)]">
                    {{ __('Everything you need to know about selecting a plan, configuring options, and checking out.') }}
                </p>
            </div>
            <div class="lg:col-span-7">
                <x-sketch.sketch-accordion :items="[
                    [
                        'question' => __('How are prices and taxes calculated?'),
                        'answer' => __('All plan prices, setup fees, promo discounts, and regional taxes are calculated directly by Paymenter in your selected currency during checkout.'),
                    ],
                    [
                        'question' => __('Can I customize plan resources before adding to cart?'),
                        'answer' => __('Yes. Products with configurable options or billing cycles let you choose every available option and preview the updated server-calculated total before adding to your cart.'),
                    ],
                    [
                        'question' => __('What happens after I place an order?'),
                        'answer' => __('Paid orders generate your invoice and trigger automated provisioning via the configured Paymenter server extension, with live status visible inside your client area.'),
                    ],
                ]" />
            </div>
        </div>

        <x-sketch.rough-box surface="blossom" seed="88" padding="p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <x-sketch.nodey-mascot mood="happy" size="72" class="shrink-0 hidden sm:block" />
                    <div>
                        <h2 class="font-display text-2xl font-extrabold text-[var(--pn-text-primary)]">
                            {{ __('Ready to start your next world?') }}
                        </h2>
                        <p class="mt-1 text-sm text-[var(--pn-text-secondary)]">
                            {{ __('Pick a category above or open your cart to complete your order.') }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="#catalog-categories" class="pn-btn-primary !w-auto">
                        <span>{{ __('View Categories') }}</span>
                    </a>
                    @if (Cart::items()->count() > 0)
                        <a href="{{ route('cart') }}" wire:navigate class="pn-btn-secondary !w-auto">
                            <span>{{ __('Go to Cart') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </x-sketch.rough-box>
    </section>
</div>
