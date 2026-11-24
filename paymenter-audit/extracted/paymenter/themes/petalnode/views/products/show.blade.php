<div class="container py-10 sm:py-14">
    <x-navigation.breadcrumb />

    <div class="pn-card p-6 sm:p-8 bg-[var(--pn-bg-surface)] mt-2">
        <div class="flex flex-col {{ $product->image ? 'lg:grid lg:grid-cols-12 gap-8 lg:gap-10 items-start' : 'gap-6' }}">
            @if ($product->image)
                <div class="lg:col-span-5 w-full">
                    <x-sketch.polaroid-frame caption="{{ $product->name }}" tapeTone="blossom" tilt="-rotate-1">
                        <img
                            src="{{ Storage::url($product->image) }}"
                            alt="{{ $product->name }}"
                            class="w-full max-h-80 object-contain object-center bg-[var(--pn-paper-shade)]/40 p-2"
                        />
                    </x-sketch.polaroid-frame>
                </div>
            @endif

            <div class="{{ $product->image ? 'lg:col-span-7' : 'w-full' }} flex flex-col gap-5">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="font-pixel text-xs font-semibold uppercase tracking-wider text-[var(--pn-text-secondary)]">
                        {{ $category->name }}
                    </span>
                    @if ($product->stock === 0)
                        <x-sketch.stamp tone="blossom" rotate="rotate-0">
                            {{ __('product.out_of_stock', ['product' => $product->name]) }}
                        </x-sketch.stamp>
                    @elseif ($product->stock > 0)
                        <x-sketch.stamp tone="moss" rotate="rotate-0">
                            {{ __('product.in_stock') }} ({{ $product->stock }})
                        </x-sketch.stamp>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-[var(--pn-text-primary)]">
                            {{ $product->name }}
                        </h1>
                        @if ($product->price()->has_setup_fee)
                            <p class="mt-1.5 text-sm font-medium text-[var(--pn-text-secondary)] tabular-nums">
                                + {{ $product->price()->formatted->setup_fee }} {{ __('product.setup_fee') }}
                            </p>
                        @endif
                    </div>

                    <x-sketch.rough-circle color="var(--pn-blossom-600)" seed="77" class="shrink-0">
                        <span class="font-display text-2xl font-extrabold text-[var(--pn-text-primary)] tabular-nums">
                            {{ $product->price()->formatted->price }}
                        </span>
                    </x-sketch.rough-circle>
                </div>

                @if (filled($product->description))
                    <article class="prose dark:prose-invert max-w-none text-[var(--pn-text-secondary)] border-y border-[var(--pn-border-subtle)] py-5">
                        {!! $product->description !!}
                    </article>
                @endif

                @if ($product->availablePlans()->count() > 1)
                    <div class="space-y-2">
                        <p class="font-display text-sm font-bold text-[var(--pn-text-primary)]">
                            {{ __('Available billing cycles') }}
                        </p>
                        <div class="grid gap-2.5 sm:grid-cols-2">
                            @foreach ($product->availablePlans() as $availablePlan)
                                <a
                                    href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug, 'plan' => $availablePlan->id]) }}"
                                    wire:navigate
                                    class="pn-card p-3 flex items-center justify-between gap-2 hover:border-[var(--pn-blossom-600)] transition"
                                >
                                    <span class="font-semibold text-sm text-[var(--pn-text-primary)]">{{ $availablePlan->name }}</span>
                                    <span class="font-display font-bold text-sm text-[var(--pn-blossom-700)] tabular-nums">{{ $availablePlan->price()->formatted->price }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="pt-2 flex flex-wrap items-center gap-3">
                    @if ($product->stock !== 0 && $product->price()->available)
                        <a href="{{ route('products.checkout', ['category' => $category, 'product' => $product->slug]) }}" wire:navigate class="sm:w-auto w-full">
                            <x-button.primary class="sm:!w-auto !px-7">
                                <x-ri-shopping-bag-4-fill class="size-5" aria-hidden="true" />
                                <span>{{ __('product.add_to_cart') }}</span>
                            </x-button.primary>
                        </a>
                    @else
                        <x-sketch.sketch-alert tone="warning" title="{{ __('Currently Unavailable') }}">
                            {{ __('This product cannot be ordered right now.') }}
                        </x-sketch.sketch-alert>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
