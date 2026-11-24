<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-base-foreground">{{ __('smm::messages.smm_orders') }}</h1>
            <p class="text-muted-foreground">{{ __('smm::messages.track_orders') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        @foreach([
            ['key' => 'total', 'label' => __('smm::messages.total_orders'), 'icon' => 'ri-file-line'],
            ['key' => 'active', 'label' => __('smm::messages.active_orders'), 'icon' => 'ri-time-line'],
            ['key' => 'completed', 'label' => __('smm::messages.completed_orders'), 'icon' => 'ri-check-line'],
            ['key' => 'pending', 'label' => __('smm::messages.pending_orders'), 'icon' => 'ri-hourglass-line'],
            ['key' => 'failed', 'label' => __('smm::messages.failed_orders'), 'icon' => 'ri-error-warning-line'],
        ] as $stat)
            <div class="rounded-xl border border-neutral bg-background-secondary p-5">
                <div class="text-xs text-muted-foreground uppercase tracking-wider">{{ $stat['label'] }}</div>
                <div class="text-2xl font-bold text-base-foreground mt-1">{{ $stats[$stat['key']] ?? 0 }}</div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="relative flex-1 sm:w-80">
            <x-ri-search-line class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
            <input wire:model.debounce.300ms="search" type="search" placeholder="{{ __('smm::messages.search_orders') }}"
                class="w-full pl-9 pr-4 py-2 rounded-md border border-neutral input-shadow focus:border-primary focus:ring-1 focus:ring-primary text-sm" />
        </div>
        <select wire:model="status" class="w-full sm:w-48 py-2 rounded-md border border-neutral input-shadow text-sm focus:border-primary focus:ring-1 focus:ring-primary">
            <option value="">{{ __('smm::messages.all_statuses') }}</option>
            @foreach(SmmOrder::$statuses as $status => $label)
                <option value="{{ $status }}">{{ $label }}</option>
            @endforeach
        </select>
        <a href="{{ route('smm.category') }}" class="btn-ghost ml-auto">{{ __('smm::messages.new_order') }}</a>
    </div>

    @if($orders->count())
        <div class="rounded-xl border border-neutral bg-background-secondary overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-neutral text-sm text-muted-foreground">
                        <th class="pb-4 pl-6 font-medium">{{ __('smm::messages.order_id') }}</th>
                        <th class="pb-4 font-medium">{{ __('smm::messages.service') }}</th>
                        <th class="pb-4 text-right font-medium">{{ __('smm::messages.target_url') }}</th>
                        <th class="pb-4 text-right font-medium">{{ __('smm::messages.quantity') }}</th>
                        <th class="pb-4 text-right font-medium">{{ __('smm::messages.amount') }}</th>
                        <th class="pb-4 font-medium">{{ __('smm::messages.status') }}</th>
                        <th class="pb-4 text-right font-medium">{{ __('smm::messages.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral/50">
                    @foreach($orders as $order)
                        <tr class="group">
                            <td class="py-4 pl-6">
                                <div class="font-medium text-base-foreground">{{ $order->order_code }}</div>
                                <div class="text-xs text-muted-foreground">{{ $order->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="py-4">
                                <div class="text-sm text-base-foreground">{{ $order->items->first()?->service?->name ?? __('smm::messages.unknown') }}</div>
                            </td>
                            <td class="py-4 text-right text-sm text-muted-foreground max-w-xs truncate">{{ $order->target_url }}</td>
                            <td class="py-4 text-right text-sm">{{ $order->quantity }}</td>
                            <td class="py-4 text-right text-sm">{{ $order->amount }} {{ $order->currency_code }}</td>
                            <td class="py-4">
                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold
                                    @switch($order->status)
                                        @case('completed') bg-green-500/10 text-green-400 @break
                                        @case('in_progress') bg-blue-500/10 text-blue-400 @break
                                        @case('processing') bg-blue-500/10 text-blue-400 @break
                                        @case('queued') bg-yellow-500/10 text-yellow-400 @break
                                        @case('pending_payment') bg-neutral/10 text-neutral @break
                                        @case('failed') bg-red-500/10 text-red-400 @break
                                        @case('canceled') bg-neutral/10 text-neutral @break
                                        @default bg-neutral/10 text-neutral
                                    @endswitch">
                                    {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                <a href="{{ route('smm.customer.orders.show', $order) }}" class="text-sm text-primary hover:underline">{{ __('smm::messages.view') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $orders->links() }}</div>
        </div>
    @else
        <div class="text-center py-16 text-muted-foreground">
            <p>{{ __('smm::messages.no_orders') }}</p>
        </div>
    @endif
</div>
