@php
    $planCategory = $category ?? $product->category;
    $priceObj = $product->price();
@endphp
<article
    class="pn-card pn-card-interactive flex flex-col p-5 bg-[var(--pn-bg-surface)]"
    data-pn-product-card
    data-name="{{ strtolower($product->name) }}"
    data-price="{{ is_numeric($priceObj->price ?? null) ? $priceObj->price : 0 }}"
    data-sort="{{ $product->sort ?? 0 }}"
>
    <div class="{{ theme('small_images', false) ? 'flex items-center gap-3.5' : 'flex flex-col gap-3' }}">
        @if ($product->image)
            <div class="overflow-hidden rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-paper-shade)] shrink-0 {{ theme('small_images', false) ? 'w-16 h-16' : 'aspect-[16/10] w-full' }}">
                <img
                    src="{{ Storage::url($product->image) }}"
                    alt="{{ $product->name }}"
                    class="h-full w-full object-cover object-center"
                />
            </div>
        @endif

        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)] leading-snug">
                    {{ $product->name }}
                </h2>
                @if ($product->stock === 0)
                    <x-sketch.stamp tone="blossom" rotate="rotate-2" class="shrink-0">
                        {{ __('Sold Out') }}
                    </x-sketch.stamp>
                @elseif ($product->stock > 0)
                    <x-sketch.stamp tone="moss" rotate="-rotate-2" class="shrink-0 tabular-nums">
                        {{ $product->stock }} {{ __('left') }}
                    </x-sketch.stamp>
                @endif
            </div>
        </div>
    </div>

    @if (theme('direct_checkout', false) && $product->description)
        <article class="prose dark:prose-invert prose-sm mt-3 text-[var(--pn-text-secondary)]">
            {!! $product->description !!}
        </article>
    @elseif ($product->description)
        <article class="prose dark:prose-invert prose-sm mt-3 text-[var(--pn-text-secondary)] line-clamp-3">
            {!! $product->description !!}
        </article>
    @endif

    <div class="mt-4 pt-4 border-t border-[var(--pn-border-subtle)] flex items-baseline justify-between gap-2">
        <span class="font-display text-2xl font-extrabold text-[var(--pn-text-primary)] tabular-nums">
            {{ $priceObj->formatted->price }}
        </span>
        @if ($priceObj->has_setup_fee ?? false)
            <span class="text-xs font-medium text-[var(--pn-text-secondary)] tabular-nums">
                + {{ $priceObj->formatted->setup_fee }} {{ __('product.setup_fee') }}
            </span>
        @endif
    </div>

    <div class="mt-auto pt-4 flex items-center gap-2">
        @if ($product->stock !== 0 && $priceObj->available && theme('direct_checkout', false))
            <a href="{{ route('products.checkout', ['category' => $planCategory, 'product' => $product->slug]) }}" wire:navigate class="flex-grow">
                <x-button.primary>
                    {{ __('product.add_to_cart') }}
                </x-button.primary>
            </a>
        @else
            <a href="{{ route('products.show', ['category' => $planCategory, 'product' => $product->slug]) }}" wire:navigate class="flex-grow">
                <x-button.primary>
                    {{ __('common.button.view') }}
                </x-button.primary>
            </a>
            @if ($product->stock !== 0 && $priceObj->available)
                <a
                    href="{{ route('products.checkout', ['category' => $planCategory, 'product' => $product->slug]) }}"
                    wire:navigate
                    aria-label="{{ __('Configure :name', ['name' => $product->name]) }}"
                >
                    <x-button.secondary class="!w-auto !px-3.5">
                        <x-ri-shopping-bag-4-fill class="size-5" aria-hidden="true" />
                    </x-button.secondary>
                </a>
            @endif
        @endif
    </div>
</article>
