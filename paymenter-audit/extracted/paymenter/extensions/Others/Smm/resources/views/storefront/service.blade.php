<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">
        <div class="lg:w-1/2 space-y-4">
            <div class="flex flex-col sm:flex-row gap-4 items-end">
                <h1 class="text-2xl font-bold text-base-foreground">{{ $service->name }}</h1>
                <span class="text-xs font-semibold bg-primary/10 text-primary rounded px-2.5 py-0.5 uppercase tracking-wider">
                    {{ $service->channel ?? __('smm::messages.platform') }}
                </span>
            </div>
            @if($service->type)
                <p class="text-sm text-muted-foreground">{{ $service->type }}</p>
            @endif
            <div class="rounded-lg border border-neutral bg-background-secondary p-4">
                <p class="text-sm text-muted-foreground mb-2">{{ __('smm::messages.description') }}</p>
                <p class="text-base-foreground whitespace-pre-wrap line-clamp-3">{{ $service->description }}</p>
            </div>
            <div class="flex gap-2 text-sm">
                <span class="flex items-center gap-1"><x-ri-information-line class="size-4" /> {{ $service->refill_eligible ? __('smm::messages.refill_eligible') : __('smm::messages.refill_not_eligible') }}</span>
                <span class="flex items-center gap-1"><x-ri-close-circle-line class="size-4" /> {{ $service->cancel_eligible ? __('smm::messages.cancel_eligible') : __('smm::messages.cancel_not_eligible') }}</span>
            </div>
        </div>

        <div class="lg:w-1/2 space-y-4">
            <form wire:submit.prevent="addToCart" class="space-y-4 rounded-xl border border-neutral bg-background-secondary p-6">
                <div>
                    <label class="block text-sm font-medium text-base-foreground mb-1">
                        {{ __('smm::messages.target_url') }}
                    </label>
                    <input wire:model="targetUrl" type="text" placeholder="https://example.com/page"
                        class="w-full rounded-md border border-neutral input-shadow focus:border-primary focus:ring-1 focus:ring-primary text-sm" />
                    <p class="text-xs text-muted-foreground mt-1">{{ __('smm::messages.url_help') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-base-foreground mb-1 flex justify-between">
                        {{ __('smm::messages.quantity') }}
                        <span class="text-xs text-muted-foreground normal-case">
                            {{ __('smm::messages.min_max', ['min' => $service->min_quantity ?? 1, 'max' => $service->max_quantity ?? '∞']) }}
                        </span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input wire:model="quantity" wire:change="recalculatePrice" type="number" min="{{ $service->min_quantity ?? 1 }}" max="{{ $service->max_quantity ?? 999999 }}"
                            class="w-24 rounded-md border border-neutral input-shadow focus:border-primary focus:ring-1 focus:ring-primary text-sm" />
                    </div>
                </div>

                @if($service->drip_feed)
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <input wire:model="dripFeed" type="checkbox" class="rounded border-neutral" />
                            <label class="text-sm font-medium text-base-foreground">{{ __('smm::messages.drip_feed') }}</label>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <label class="block text-xs text-muted-foreground mb-1">{{ __('smm::messages.runs') }}</label>
                                <input wire:model="runs" type="number" min="1" class="w-full rounded-md border border-neutral input-shadow focus:border-primary focus:ring-1 focus:ring-primary" />
                            </div>
                            <div>
                                <label class="block text-xs text-muted-foreground mb-1">{{ __('smm::messages.interval_min') }}</label>
                                <input wire:model="interval" type="number" min="1" class="w-full rounded-md border border-neutral input-shadow focus:border-primary focus:ring-1 focus:ring-primary" />
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-base-foreground mb-1">{{ __('smm::messages.comment') }}</label>
                    <textarea wire:model="comment" rows="2" placeholder="{{ __('smm::messages.comment_placeholder') }}"
                        class="w-full rounded-md border border-neutral input-shadow focus:border-primary focus:ring-1 focus:ring-primary text-sm"></textarea>
                </div>

                <div class="pt-4 border-t border-neutral/50">
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-sm text-muted-foreground">{{ __('smm::messages.unit_price') }}:</span>
                        <span class="text-lg font-bold text-base-foreground">{{ $unitPrice }} {{ $currency }}</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-sm text-muted-foreground">{{ __('smm::messages.total') }}:</span>
                        <span class="text-xl font-bold text-primary">{{ $total }} {{ $currency }}</span>
                    </div>
                </div>

                <button wire:loading.attr="disabled" class="w-full btn-primary">
                    @if($added)
                        <x-ri-check-line class="mr-2 size-4" /> {{ __('smm::messages.added') }}
                    @else
                        <x-ri-shopping-cart-line class="mr-2 size-4" /> {{ __('smm::messages.add_to_cart') }}
                    @endif
                </button>
            </form>
        </div>
    </div>
</div>
