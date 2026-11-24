<div
    class="container py-10 sm:py-14"
    x-data="{
        search: '',
        sortBy: 'sort',
        applySortAndFilter() {
            const grid = this.$refs.productGrid;
            if (!grid) return;
            const cards = Array.from(grid.querySelectorAll('[data-pn-product-card]'));
            const q = this.search.trim().toLowerCase();

            cards.sort((a, b) => {
                if (this.sortBy === 'price-asc') {
                    return parseFloat(a.dataset.price || 0) - parseFloat(b.dataset.price || 0);
                }
                if (this.sortBy === 'price-desc') {
                    return parseFloat(b.dataset.price || 0) - parseFloat(a.dataset.price || 0);
                }
                if (this.sortBy === 'name') {
                    return (a.dataset.name || '').localeCompare(b.dataset.name || '');
                }
                return parseInt(a.dataset.sort || 0, 10) - parseInt(b.dataset.sort || 0, 10);
            });

            cards.forEach((card) => {
                const name = card.dataset.name || '';
                const matches = !q || name.includes(q);
                card.style.display = matches ? '' : 'none';
                grid.appendChild(card);
            });
        }
    }"
>
    <x-navigation.breadcrumb />

    <div class="flex flex-col lg:grid lg:grid-cols-12 gap-8 mt-2">
        {{-- Sidebar: Category Header & Tree --}}
        <aside class="lg:col-span-3 flex flex-col gap-5">
            <x-sketch.rough-box surface="surface" seed="31" padding="p-5">
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[var(--pn-text-primary)]">
                    {{ $category->name }}
                </h1>
                @if (filled($category->description))
                    <article class="prose dark:prose-invert prose-sm mt-2 text-[var(--pn-text-secondary)]">
                        {!! $category->description !!}
                    </article>
                @endif
            </x-sketch.rough-box>

            <div class="pn-card p-3 flex flex-col gap-1" aria-label="{{ __('Product categories') }}">
                <p class="px-3 py-1.5 font-display text-xs font-bold uppercase tracking-wider text-[var(--pn-text-secondary)]">
                    {{ __('Categories') }}
                </p>
                @foreach ($categories as $ccategory)
                    <a
                        href="{{ route('category.show', ['category' => $ccategory->slug]) }}"
                        wire:navigate
                        class="flex items-center justify-between px-3 py-2.5 min-h-[40px] rounded-[var(--pn-radius-md)] text-sm transition {{ $category->id == $ccategory->id ? 'bg-[var(--pn-blossom-50)] text-[var(--pn-blossom-700)] font-bold border border-[var(--pn-blossom-200)]' : 'font-semibold text-[var(--pn-text-primary)] hover:bg-[var(--pn-paper-shade)]/60' }}"
                    >
                        <span>{{ $ccategory->name }}</span>
                        @if ($category->id == $ccategory->id)
                            <x-sketch.hand-icon name="blossom" size="15" />
                        @endif
                    </a>
                @endforeach
            </div>
        </aside>

        {{-- Main Catalog Column --}}
        <div class="lg:col-span-9 flex flex-col gap-8">
            @if (count($childCategories) >= 1)
                <section>
                    <h2 class="font-display text-lg font-bold text-[var(--pn-text-primary)] mb-3">
                        {{ __('Subcategories') }}
                    </h2>
                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($childCategories as $childCategory)
                            <article class="pn-card pn-card-interactive flex flex-col p-4 bg-[var(--pn-bg-surface)]">
                                <div class="{{ theme('small_images', false) ? 'flex gap-x-3 items-center' : 'flex flex-col gap-3' }}">
                                    @if ($childCategory->image)
                                        <img
                                            src="{{ Storage::url($childCategory->image) }}"
                                            alt="{{ $childCategory->name }}"
                                            class="rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] {{ theme('small_images', false) ? 'w-14 h-14 object-cover' : 'aspect-[16/10] w-full object-cover object-center' }}"
                                        >
                                    @endif
                                    <h3 class="font-display text-lg font-bold text-[var(--pn-text-primary)]">{{ $childCategory->name }}</h3>
                                </div>
                                @if (theme('show_category_description', true) && filled($childCategory->description))
                                    <article class="mt-2 prose dark:prose-invert prose-sm text-[var(--pn-text-secondary)] line-clamp-2">
                                        {!! $childCategory->description !!}
                                    </article>
                                @endif
                                <a href="{{ route('category.show', ['category' => $childCategory->slug]) }}" wire:navigate class="mt-auto pt-3">
                                    <x-button.secondary>
                                        {{ __('common.button.view') }}
                                    </x-button.secondary>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($products->isNotEmpty())
                <div class="pn-card p-3.5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <label for="pn-catalog-search" class="sr-only">{{ __('Search plans') }}</label>
                        <input
                            id="pn-catalog-search"
                            type="search"
                            x-model="search"
                            @input="applySortAndFilter()"
                            placeholder="{{ __('Filter plans in :category...', ['category' => $category->name]) }}"
                            class="w-full min-h-[40px] rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] px-3.5 py-2 text-sm text-[var(--pn-text-primary)] placeholder:text-[var(--pn-text-secondary)] focus:border-[var(--pn-blossom-600)] focus:outline-none"
                        />
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <label for="pn-catalog-sort" class="text-xs font-semibold text-[var(--pn-text-secondary)] whitespace-nowrap">{{ __('Sort by') }}</label>
                        <select
                            id="pn-catalog-sort"
                            x-model="sortBy"
                            @change="applySortAndFilter()"
                            class="min-h-[40px] rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] px-3 py-2 text-sm font-semibold text-[var(--pn-text-primary)] focus:border-[var(--pn-blossom-600)] focus:outline-none"
                        >
                            <option value="sort">{{ __('Featured order') }}</option>
                            <option value="price-asc">{{ __('Price: Low to High') }}</option>
                            <option value="price-desc">{{ __('Price: High to Low') }}</option>
                            <option value="name">{{ __('Name: A to Z') }}</option>
                        </select>
                    </div>
                </div>

                <div x-ref="productGrid" class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach ($products as $product)
                        @include('products.partials.plan-card', ['product' => $product, 'category' => $category])
                    @endforeach
                </div>
            @elseif (count($childCategories) === 0)
                <x-sketch.empty-state
                    title="{{ __('No plans available in this category') }}"
                    message="{{ __('Check another category on the left or return to the homepage.') }}"
                    stage="seedling"
                >
                    <a href="{{ route('home') }}" wire:navigate>
                        <x-button.primary class="!w-auto">{{ __('Back to Home') }}</x-button.primary>
                    </a>
                </x-sketch.empty-state>
            @endif
        </div>
    </div>
</div>
