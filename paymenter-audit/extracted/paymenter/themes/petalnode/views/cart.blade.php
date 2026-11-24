<div class="container py-10 sm:py-14">
    <x-navigation.breadcrumb />

    <div class="flex flex-col lg:grid lg:grid-cols-12 gap-8 mt-2">
        {{-- Left Column: Cart Ledger --}}
        <div class="lg:col-span-8 flex flex-col gap-4">
            <div class="flex items-center justify-between gap-3">
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[var(--pn-text-primary)]">
                    {{ __('Your Cart') }}
                </h1>
                @if (Cart::items()->count() > 0)
                    <span class="font-pixel text-xs font-bold uppercase text-[var(--pn-text-secondary)] tabular-nums">
                        {{ trans_choice(':count item|:count items', Cart::items()->count(), ['count' => Cart::items()->count()]) }}
                    </span>
                @endif
            </div>

            @if (Cart::items()->count() === 0)
                <x-sketch.empty-state
                    title="{{ __('product.empty_cart') }}"
                    message="{{ __('Browse our categories to add a plan and configure your options.') }}"
                    stage="seedling"
                >
                    <a href="{{ route('home') }}#catalog-categories" wire:navigate>
                        <x-button.primary class="!w-auto">
                            <span>{{ __('Explore Plans') }}</span>
                        </x-button.primary>
                    </a>
                </x-sketch.empty-state>
            @endif

            @foreach (Cart::items() as $item)
                <article class="pn-card p-5 flex flex-col sm:flex-row justify-between gap-5 bg-[var(--pn-bg-surface)]">
                    <div class="flex flex-col gap-1.5 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)]">
                                {{ $item->product->name }}
                            </h2>
                            @if (!empty($item->plan?->name))
                                <x-sketch.stamp tone="moss" rotate="rotate-0">{{ $item->plan->name }}</x-sketch.stamp>
                            @endif
                        </div>

                        @if (!empty($item->config_options))
                            <dl class="mt-2 space-y-1 text-xs text-[var(--pn-text-secondary)]">
                                @foreach ($item->config_options as $option)
                                    <div class="flex flex-wrap gap-1.5">
                                        <dt class="font-semibold text-[var(--pn-text-primary)]">{{ $option['option_name'] }}:</dt>
                                        <dd>{{ $option['value_name'] }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        @endif
                    </div>

                    <div class="flex flex-col sm:items-end justify-between gap-4 shrink-0">
                        <div class="sm:text-right">
                            <p class="font-display text-xl font-extrabold text-[var(--pn-blossom-700)] tabular-nums">
                                {{ $item->price->format($item->price->total * $item->quantity) }}
                            </p>
                            @if ($item->quantity > 1)
                                <p class="text-xs text-[var(--pn-text-secondary)] tabular-nums">
                                    ({{ $item->price }} {{ __('each') }})
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if ($item->product->allow_quantity == 'combined')
                                <div class="flex items-center gap-1.5 mr-1">
                                    <x-button.secondary
                                        type="button"
                                        wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                        aria-label="{{ __('Decrease quantity for :name', ['name' => $item->product->name]) }}"
                                        class="!w-10 !min-h-[40px] !px-0"
                                    >
                                        -
                                    </x-button.secondary>
                                    <span class="min-w-[2.25rem] text-center font-pixel text-sm font-bold text-[var(--pn-text-primary)] tabular-nums">
                                        {{ $item->quantity }}
                                    </span>
                                    <x-button.secondary
                                        type="button"
                                        wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                        aria-label="{{ __('Increase quantity for :name', ['name' => $item->product->name]) }}"
                                        class="!w-10 !min-h-[40px] !px-0"
                                    >
                                        +
                                    </x-button.secondary>
                                </div>
                            @endif

                            <a href="{{ route('products.checkout', [$item->product->category, $item->product, 'edit' => $item->id]) }}" wire:navigate>
                                <x-button.secondary class="!w-auto !min-h-[40px] !py-1.5 !px-3.5 text-xs">
                                    {{ __('product.edit') }}
                                </x-button.secondary>
                            </a>

                            <x-button.danger
                                type="button"
                                x-on:click="$store.confirmation.confirm({
                                    title: '{{ __('Remove item') }}',
                                    message: '{{ __('Remove :name from your cart?', ['name' => addslashes($item->product->name)]) }}',
                                    confirmText: '{{ __('product.remove') }}',
                                    cancelText: '{{ __('Cancel') }}',
                                    callback: () => $wire.removeProduct({{ $item->id }})
                                })"
                                class="!w-auto !min-h-[40px] !py-1.5 !px-3.5 text-xs"
                            >
                                <x-loading target="removeProduct({{ $item->id }})" />
                                <span wire:loading.remove wire:target="removeProduct({{ $item->id }})">
                                    {{ __('product.remove') }}
                                </span>
                            </x-button.danger>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Right Column: Order Summary, Coupon & Checkout --}}
        <aside class="lg:col-span-4">
            @if (Cart::items()->count() > 0)
                <div class="lg:sticky lg:top-24">
                    <x-sketch.rough-box surface="surface" seed="64" padding="p-5 sm:p-6">
                        <h2 class="font-display text-xl font-extrabold text-[var(--pn-text-primary)] pb-3 border-b border-[var(--pn-border-subtle)]">
                            {{ __('product.order_summary') }}
                        </h2>

                        {{-- Promo Coupon Form --}}
                        <div class="mt-4 pb-4 border-b border-[var(--pn-border-subtle)]">
                            @if (!$coupon)
                                <div class="flex items-end gap-2">
                                    <x-form.input wire:model="coupon" name="coupon" label="{{ __('Coupon') }}" placeholder="{{ __('Enter promo code') }}" />
                                    <x-button.secondary type="button" wire:click="applyCoupon" class="!w-auto shrink-0" wire:loading.attr="disabled">
                                        <x-loading target="applyCoupon" />
                                        <span wire:loading.remove wire:target="applyCoupon">{{ __('product.apply') }}</span>
                                    </x-button.secondary>
                                </div>
                            @else
                                <div class="flex items-center justify-between gap-2 rounded-[var(--pn-radius-md)] border border-[var(--pn-moss)] bg-[var(--pn-moss-100)] px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <x-sketch.hand-icon name="receipt" size="16" class="text-[var(--pn-moss-700)]" />
                                        <span class="font-pixel text-sm font-bold text-[var(--pn-moss-700)]">{{ $coupon->code }}</span>
                                    </div>
                                    <button type="button" wire:click="removeCoupon" class="text-xs font-bold text-[var(--pn-blossom-700)] hover:underline cursor-pointer">
                                        {{ __('product.remove') }}
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Server-Computed Totals --}}
                        <dl class="mt-4 space-y-2.5 text-sm">
                            <div class="flex justify-between gap-3">
                                <dt class="text-[var(--pn-text-secondary)]">{{ __('invoices.subtotal') }}</dt>
                                <dd class="font-semibold text-[var(--pn-text-primary)] tabular-nums">{{ $total->format($total->subtotal) }}</dd>
                            </div>

                            @if ($total->tax > 0)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-[var(--pn-text-secondary)]">{{ \App\Classes\Settings::tax()->name }} ({{ \App\Classes\Settings::tax()->rate }}%)</dt>
                                    <dd class="font-semibold text-[var(--pn-text-primary)] tabular-nums">{{ $total->format($total->tax) }}</dd>
                                </div>
                            @endif

                            <div class="pt-3 border-t border-[var(--pn-border-subtle)] flex justify-between items-baseline gap-3">
                                <dt class="font-display text-base font-bold text-[var(--pn-text-primary)]">{{ __('invoices.total') }}</dt>
                                <dd class="font-display text-2xl font-extrabold text-[var(--pn-blossom-700)] tabular-nums">{{ $total->format($total->total) }}</dd>
                            </div>
                        </dl>

                        <div class="mt-5 space-y-4">
                            @if (config('settings.tos'))
                                <x-form.checkbox wire:model="tos" name="tos">
                                    {{ __('product.tos') }}
                                    <a href="{{ config('settings.tos') }}" target="_blank" rel="noopener noreferrer" class="font-bold text-[var(--pn-blossom-700)] hover:underline">
                                        {{ __('product.tos_link') }}
                                    </a>
                                </x-form.checkbox>
                            @endif

                            <x-button.primary type="button" wire:click="checkout" wire:loading.attr="disabled">
                                <x-loading target="checkout" />
                                <span wire:loading.remove wire:target="checkout" class="inline-flex items-center gap-2">
                                    <span>{{ __('product.checkout') }}</span>
                                    <x-ri-arrow-right-line class="size-4" aria-hidden="true" />
                                </span>
                            </x-button.primary>
                        </div>
                    </x-sketch.rough-box>
                </div>
            @endif
        </aside>
    </div>
</div>
