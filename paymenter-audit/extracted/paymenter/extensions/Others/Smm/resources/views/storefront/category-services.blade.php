<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('smm.category') }}" class="text-sm text-primary hover:underline">{{ __('smm::messages.back_to_categories') }}</a>
            <h1 class="text-2xl font-bold text-base-foreground tracking-tight mt-1">{{ $category->name }}</h1>
            <p class="text-muted-foreground mt-1">{{ $category->description ?? __('smm::messages.browse_services') }}</p>
        </div>
    </div>

    @if($services->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($services as $service)
                <a href="{{ route('smm.service', $service->slug) }}" class="zn-card-hover zn-card group relative overflow-hidden rounded-xl border border-neutral bg-background-secondary p-5 transition-all hover:shadow-xl hover:border-primary/50 flex flex-col h-full">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-sm font-semibold text-base-foreground group-hover:text-primary transition-colors">{{ $service->name }}</span>
                        <span class="text-xs font-semibold bg-primary/10 text-primary rounded px-2.5 py-0.5 uppercase tracking-wider">
                            {{ $service->channel ?? __('smm::messages.platform') }}
                        </span>
                    </div>
                    @if($service->type)
                        <span class="text-xs text-muted-foreground mb-3 block">{{ $service->type }}</span>
                    @endif
                    <p class="text-sm text-muted-foreground line-clamp-2 flex-1 mb-3">{{ $service->description }}</p>
                    <div class="mt-auto border-t border-neutral/50 pt-3 flex items-center justify-between">
                        <span class="text-sm font-medium text-base-foreground">
                            {{ __('smm::messages.from') }} <span class="font-bold text-primary">{{ \Paymenter\Extensions\Others\Smm\Services\PricingEngine::calculate($service) }}</span>
                        </span>
                        <x-ri-arrow-right-line class="size-4 text-muted-foreground group-hover:text-primary transition-colors" />
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6 flex justify-center">
            {{ $services->links() }}
        </div>
    @else
        <div class="text-center py-16 text-muted-foreground">
            <p>{{ __('smm::messages.no_services_in_category') }}</p>
        </div>
    @endif
</div>
