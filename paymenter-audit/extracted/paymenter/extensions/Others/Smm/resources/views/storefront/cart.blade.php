<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-2 mb-6">
        <x-ri-shopping-cart-line class="size-5 text-primary" />
        <h1 class="text-2xl font-bold text-base-foreground">{{ __('smm::messages.smm_cart') }}</h1>
    </div>

    @if(count($items) === 0)
        <div class="text-center py-20 text-muted-foreground">
            <div class="mb-4 opacity-25"><x-ri-shopping-cart-line class="size-16" /></div>
            <p class="text-lg">{{ __('smm::messages.cart_is_empty') }}</p>
            <a href="{{ route('smm.category') }}" class="mt-4 inline-flex items-center gap-2 btn-primary">
                <x-ri-store-line class="size-4" /> {{ __('smm::messages.browse_services') }}
            </a>
        </div>
    @else
        <div class="rounded-xl border border-neutral bg-background-secondary p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral text-sm text-muted-foreground">
                        <th class="pb-3 font-medium">{{ __('smm::messages.service') }}</th>
                        <th class="pb-3 text-right font-medium w-24">{{ __('smm::messages.target_url') }}</th>
                        <th class="pb-3 text-right font-medium w-24">{{ __('smm::messages.quantity') }}</th>
                        <th class="pb-3 text-right font-medium w-24">{{ __('smm::messages.unit_price') }}</th>
                        <th class="pb-3 text-right font-medium w-32">{{ __('smm::messages.total') }}</th>
                        <th class="pb-3 text-right font-medium w-20">{{ __('smm::messages.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral/50">
                    @foreach($items as $key => $item)
                        <tr class="group">
                            <td class="py-4">
                                <div class="font-medium text-base-foreground">{{ $item['name'] }}</div>
                                <div class="text-xs text-muted-foreground">{{ $item['channel'] ?? '' }}</div>
                            </td>
                            <td class="py-4 text-right text-sm text-muted-foreground max-w-xs truncate">{{ $item['target_url'] }}</td>
                            <td class="py-4 text-right">
                                <input wire:model="items.{{ $key }}.quantity" wire:change="updateQuantity('{{ $key }}', $event.target.value)" type="number" min="1"
                                    class="w-20 rounded-md border border-neutral input-shadow text-right text-sm focus:border-primary focus:ring-1 focus:ring-primary" />
                            </td>
                            <td class="py-4 text-right text-sm">{{ $item['unit_price'] }} {{ $item['currency'] }}</td>
                            <td class="py-4 text-right text-sm font-semibold">{{ $item['total'] }} {{ $item['currency'] }}</td>
                            <td class="py-4 text-right">
                                <button wire:click="remove('{{ $key }}')" class="text-xs text-error hover:underline underline-offset-4">{{ __('smm::messages.remove') }}</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="text-sm text-muted-foreground">{{ __('smm::messages.items_count', ['count' => $count]) }}</div>
                <div class="text-xl font-bold text-base-foreground">{{ $total }} {{ $currency }}</div>
            </div>
            <div class="flex gap-3">
                <button wire:click="clear" class="text-sm text-muted-foreground hover:text-error underline-offset-4 hover:underline">{{ __('smm::messages.clear_cart') }}</button>
                <a href="{{ route('smm.checkout') }}" class="btn-primary">{{ __('smm::messages.proceed_to_checkout') }}</a>
            </div>
        </div>
    @endif
</div>
