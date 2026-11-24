<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('smm.customer.orders') }}" class="text-sm text-primary hover:underline">{{ __('smm::messages.back_to_orders') }}</a>
            <h1 class="text-2xl font-bold text-base-foreground mt-1">{{ $order->order_code }}</h1>
            <p class="text-muted-foreground">{{ $order->items->first()?->service?->name ?? __('smm::messages.smm_order') }}</p>
        </div>
        <div class="flex gap-2">
            @if($order->provider_order_id && !$order->isTerminal())
                <button wire:click="syncStatus" wire:loading.attr="disabled" class="btn-sm btn-outline">
                    <x-ri-refresh-line class="mr-2 size-4" /> {{ __('smm::messages.sync_status') }}
                </button>
            @endif
            @if($order->items->first()?->service?->refill_eligible && $order->status === 'completed')
                <button wire:click="requestRefill" wire:loading.attr="disabled" class="btn-sm btn-outline">
                    <x-ri-refresh-line class="mr-2 size-4" /> {{ __('smm::messages.request_refill') }}
                </button>
            @endif
            @if($order->items->first()?->service?->cancel_eligible && in_array($order->status, ['processing','in_progress','queued']))
                <button wire:click="requestCancellation" wire:loading.attr="disabled" class="btn-sm btn-outline">
                    <x-ri-close-circle-line class="mr-2 size-4" /> {{ __('smm::messages.request_cancel') }}
                </button>
            @endif
            @if($order->status === 'paid' || $order->status === 'pending_payment')
                <a href="{{ route('invoices.show', $order->invoice) }}" class="btn-sm btn-primary">{{ __('smm::messages.view_invoice') }}</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="rounded-xl border border-neutral bg-background-secondary p-5">
            <div class="text-xs text-muted-foreground uppercase tracking-wider">{{ __('smm::messages.status') }}</div>
            <div class="text-base-foreground font-semibold mt-1">
                @switch($order->status)
                    @case('completed') <span class="text-green-400">{{ __('smm::messages.completed') }}</span> @break
                    @case('in_progress') <span class="text-blue-400">{{ __('smm::messages.in_progress') }}</span> @break
                    @case('processing') <span class="text-blue-400">{{ __('smm::messages.processing') }}</span> @break
                    @case('queued') <span class="text-yellow-400">{{ __('smm::messages.queued') }}</span> @break
                    @case('pending_payment') <span class="text-neutral">{{ __('smm::messages.pending_payment') }}</span> @break
                    @case('paid') <span class="text-blue-400">{{ __('smm::messages.paid') }}</span> @break
                    @case('failed') <span class="text-red-400">{{ __('smm::messages.failed') }}</span> @break
                    @case('canceled') <span class="text-neutral">{{ __('smm::messages.canceled') }}</span> @break
                    @default <span class="text-neutral">{{ $order->status }}</span>
                @endswitch
            </div>
            @if($order->provider_order_id)
                <div class="text-xs text-muted-foreground mt-2">{{ __('smm::messages.provider_id') }}: <span class="font-mono">{{ $order->provider_order_id }}</span></div>
            @endif
            @if($order->completed_at)
                <div class="text-xs text-muted-foreground mt-2">{{ __('smm::messages.completed_at') }}: {{ $order->completed_at->format('M d, Y H:i') }}</div>
            @endif
        </div>
        <div class="rounded-xl border border-neutral bg-background-secondary p-5">
            <div class="text-xs text-muted-foreground uppercase tracking-wider">{{ __('smm::messages.order_details') }}</div>
            <div class="mt-1 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-muted-foreground">{{ __('smm::messages.target_url') }}</span><span class="text-base-foreground max-w-xs truncate">{{ $order->target_url }}</span></div>
                <div class="flex justify-between"><span class="text-muted-foreground">{{ __('smm::messages.quantity') }}</span><span class="text-base-foreground">{{ $order->quantity }}</span></div>
                <div class="flex justify-between"><span class="text-muted-foreground">{{ __('smm::messages.started') }}</span><span class="text-base-foreground">{{ $order->quantity_started }}</span></div>
                <div class="flex justify-between"><span class="text-muted-foreground">{{ __('smm::messages.remaining') }}</span><span class="text-base-foreground">{{ $order->quantity_remaining }}</span></div>
                <div class="flex justify-between"><span class="text-muted-foreground">{{ __('smm::messages.amount') }}</span><span class="text-base-foreground font-bold">{{ $order->amount }} {{ $order->currency_code }}</span></div>
            </div>
        </div>
        <div class="rounded-xl border border-neutral bg-background-secondary p-5">
            <div class="text-xs text-muted-foreground uppercase tracking-wider">{{ __('smm::messages.timeline') }}</div>
            <div class="mt-1 space-y-3">
                @foreach($order->statusHistory as $h)
                    <div class="flex items-start gap-3">
                        <div class="size-2 rounded-full bg-primary mt-0.5"></div>
                        <div>
                            <div class="text-sm text-base-foreground">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $h->to_status)) }}</div>
                            <div class="text-xs text-muted-foreground">{{ $h->created_at->format('M d, Y H:i') }}</div>
                            @if($h->note) <div class="text-xs text-muted-foreground mt-0.5">{{ $h->note }}</div> @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-neutral bg-background-secondary p-6 mb-8">
        <h3 class="text-sm font-semibold text-base-foreground mb-4">{{ __('smm::messages.ordered_items') }}</h3>
        <table class="w-full text-left border-collapse divide-y divide-neutral/50">
            <thead>
                <tr class="border-b border-neutral text-sm text-muted-foreground">
                    <th class="pb-3 font-medium">{{ __('smm::messages.service') }}</th>
                    <th class="pb-3 text-right font-medium">{{ __('smm::messages.quantity') }}</th>
                    <th class="pb-3 text-right font-medium">{{ __('smm::messages.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td class="py-4">{{ $item->service?->name ?? __('smm::messages.unknown') }}</td>
                        <td class="py-4 text-right">{{ $item->quantity }}</td>
                        <td class="py-4 text-right">{{ $item->amount }} {{ $item->currency_code }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($order->refills->count())
        <div class="rounded-xl border border-neutral bg-background-secondary p-6">
            <h3 class="text-sm font-semibold text-base-foreground mb-4">{{ __('smm::messages.refills') }}</h3>
            <table class="w-full text-left border-collapse divide-y divide-neutral/50">
                <thead>
                    <tr class="border-b border-neutral text-sm text-muted-foreground">
                        <th class="pb-3 font-medium">{{ __('smm::messages.status') }}</th>
                        <th class="pb-3 text-right font-medium">{{ __('smm::messages.quantity') }}</th>
                        <th class="pb-3 font-medium">{{ __('smm::messages.created_at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->refills as $refill)
                        <tr>
                            <td class="py-4">{{ \Illuminate\Support\Str::title($refill->status) }}</td>
                            <td class="py-4 text-right">{{ $refill->quantity }}</td>
                            <td class="py-4">{{ $refill->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
