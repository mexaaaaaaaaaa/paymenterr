<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-base-foreground tracking-tight">
                <x-ri-shopping-bag-line class="mr-2 size-5 text-primary" /> {{ __('smm::messages.smm_hub') }}
            </h1>
            <p class="text-muted-foreground mt-1">
                {{ __('smm::messages.choose_platform') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($categories as $category)
            <a href="{{ route('smm.category', ['slug' => $category->slug]) }}" class="zn-card-hover zn-card group relative overflow-hidden rounded-xl border border-neutral bg-background-secondary p-6 transition-all hover:shadow-xl hover:border-primary/50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary group-hover:text-primary-contrast transition-colors">
                        <x-ri-play-line class="size-5" />
                    </div>
                    <span class="ml-auto text-sm font-medium text-base-foreground">{{ $category->name }}</span>
                </div>
                <p class="text-sm text-muted-foreground line-clamp-2 mb-4">{{ $category->description ?? __('smm::messages.services_available') }}</p>
                <div class="mt-auto flex items-center justify-between">
                    <span class="text-xs font-semibold text-primary">{{ $category->services_count }} {{ __('smm::messages.services') }}</span>
                    <x-ri-arrow-right-line class="size-4 text-muted-foreground group-hover:text-primary transition-colors" />
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-muted-foreground">
                {{ __('smm::messages.no_categories') }}
            </div>
        @endforelse
    </div>
</div>
