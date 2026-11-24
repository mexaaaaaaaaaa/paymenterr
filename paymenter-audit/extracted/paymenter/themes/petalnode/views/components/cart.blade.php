@if($cartCount > 0)
<div class="relative flex items-center gap-1" x-data>
    <button
        type="button"
        @click="$store.cartDrawer.toggle()"
        aria-label="{{ __('Open cart preview') }}"
        class="relative w-11 h-11 flex items-center justify-center rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] hover:bg-[var(--pn-blossom-50)] hover:border-[var(--pn-blossom-600)] transition cursor-pointer"
    >
        <x-ri-shopping-bag-4-fill class="size-5 text-[var(--pn-text-primary)]" aria-hidden="true" />
        <span class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center min-w-5 h-5 px-1 text-[11px] font-pixel font-bold text-white bg-[var(--pn-blossom-700)] border border-[var(--pn-ink)] rounded-full tabular-nums">
            {{ $cartCount }}
        </span>
    </button>

    {{-- Slide-Over Cart Preview Drawer --}}
    <template x-teleport="body">
        <div
            x-show="$store.cartDrawer.open"
            @keydown.window.escape="$store.cartDrawer.close()"
            x-cloak
            class="fixed inset-0 z-[100] flex justify-end"
            role="dialog"
            aria-modal="true"
            aria-label="{{ __('Shopping cart') }}"
        >
            <div
                x-show="$store.cartDrawer.open"
                x-transition.opacity.duration.200ms
                @click="$store.cartDrawer.close()"
                class="fixed inset-0 bg-[#2B2230]/40 backdrop-blur-[2px]"
            ></div>

            <div
                x-show="$store.cartDrawer.open"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="relative z-10 w-full max-w-md h-full bg-[var(--pn-bg-surface)] border-l-[1.5px] border-[var(--pn-ink)] shadow-[var(--pn-shadow-paper-lg)] flex flex-col"
            >
                <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--pn-border-subtle)]">
                    <div class="flex items-center gap-2">
                        <x-sketch.hand-icon name="receipt" size="20" class="text-[var(--pn-blossom-700)]" />
                        <h2 class="font-display text-lg font-bold text-[var(--pn-text-primary)]">
                            {{ __('Cart') }} ({{ $cartCount }})
                        </h2>
                    </div>
                    <button
                        type="button"
                        @click="$store.cartDrawer.close()"
                        aria-label="{{ __('Close cart preview') }}"
                        class="w-10 h-10 rounded-[var(--pn-radius-md)] flex items-center justify-center hover:bg-[var(--pn-paper-shade)] text-[var(--pn-text-primary)] cursor-pointer"
                    >
                        <x-ri-close-fill class="size-5" aria-hidden="true" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-3">
                    @foreach (Cart::items() as $item)
                        <div class="pn-card p-3.5 flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-display font-bold text-sm text-[var(--pn-text-primary)] truncate">{{ $item->product->name }}</p>
                                @if(!empty($item->config_options))
                                    <p class="mt-1 text-xs text-[var(--pn-text-secondary)]">
                                        @foreach ($item->config_options as $option)
                                            <span class="block">{{ $option['option_name'] }}: {{ $option['value_name'] }}</span>
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                            <span class="font-display font-bold text-sm text-[var(--pn-blossom-700)] tabular-nums shrink-0">
                                {{ $item->price->format($item->price->total * $item->quantity) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="p-5 border-t border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] space-y-2.5">
                    <a href="{{ route('cart') }}" wire:navigate @click="$store.cartDrawer.close()" class="block w-full">
                        <x-button.primary>
                            <span>{{ __('View Cart & Checkout') }}</span>
                            <x-ri-arrow-right-line class="size-4" aria-hidden="true" />
                        </x-button.primary>
                    </a>
                </div>
            </div>
        </div>
    </template>
</div>
@endif
