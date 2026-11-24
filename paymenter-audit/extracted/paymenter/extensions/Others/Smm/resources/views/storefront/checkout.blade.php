<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-2 mb-6">
        <x-ri-file-invoice-line class="size-5 text-primary" />
        <h1 class="text-2xl font-bold text-base-foreground">{{ __('smm::messages.checkout') }}</h1>
    </div>

    <div class="rounded-xl border border-neutral bg-background-secondary p-6">
        <p class="text-sm text-muted-foreground mb-4">{{ __('smm::messages.review_order') }}</p>

        <table class="w-full text-left border-collapse divide-y divide-neutral/50 mb-6">
            <thead>
                <tr class="border-b border-neutral text-sm text-muted-foreground">
                    <th class="pb-3 font-medium">{{ __('smm::messages.service') }}</th>
                    <th class="pb-3 text-right font-medium">{{ __('smm::messages.quantity') }}</th>
                    <th class="pb-3 text-right font-medium">{{ __('smm::messages.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr class="group">
                        <td class="py-4">
                            <div class="font-medium text-base-foreground">{{ $item['name'] }}</div>
                            <div class="text-xs text-muted-foreground">{{ $item['target_url'] }}</div>
                        </td>
                        <td class="py-4 text-right text-sm">{{ $item['quantity'] }}</td>
                        <td class="py-4 text-right text-sm font-semibold">{{ $item['total'] }} {{ $item['currency'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-between items-baseline mb-6">
            <span class="text-sm text-muted-foreground">{{ __('smm::messages.order_total') }}:</span>
            <span class="text-xl font-bold text-base-foreground">{{ $total }} {{ $currency }}</span>
        </div>

        @if(config('settings.tos'))
            <div class="flex items-start gap-3 mb-6">
                <input id="tos" wire:model="tos" type="checkbox" class="mt-0.5 rounded border-neutral" />
                <label for="tos" class="text-sm leading-6">
                    {{ __('smm::messages.tos_agree', ['link' => '<a href="'.route('home').'" class="underline text-primary">'.__('smm::messages.terms').'</a>']) }}
                </label>
            </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('smm.cart') }}" class="btn-ghost">{{ __('smm::messages.back_to_cart') }}</a>
            <button wire:click="placeOrder" wire:loading.attr="disabled" class="btn-primary">
                {{ __('smm::messages.pay_now') }}
            </button>
        </div>
    </div>

    <div class="mt-6 rounded-lg border border-neutral bg-neutral/5 p-4 text-sm">
        <p class="font-medium text-base-foreground mb-2">{{ __('smm::messages.how_it_works') }}</p>
        <ul class="list-disc list-inside text-muted-foreground space-y-1 text-sm">
            <li>{{ __('smm::messages.how_pay') }}</li>
            <li>{{ __('smm::messages.how_verify') }}</li>
            <li>{{ __('smm::messages.how_deliver') }}</li>
            <li>{{ __('smm::messages.how_track') }}</li>
        </ul>
    </div>
</div>
