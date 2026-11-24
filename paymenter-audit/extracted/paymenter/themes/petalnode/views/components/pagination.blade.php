<div class="flex justify-center mt-4">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-wrap gap-2 items-center">
            <span>
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center min-h-[40px] px-3.5 py-2 rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-paper-shade)]/60 text-sm font-semibold text-[var(--pn-text-secondary)] cursor-not-allowed">
                        {{ __('Previous') }}
                    </span>
                @else
                    <button type="button" wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
                        class="inline-flex items-center min-h-[40px] px-3.5 py-2 rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] text-sm font-semibold text-[var(--pn-text-primary)] hover:border-[var(--pn-ink)] hover:bg-[var(--pn-paper-shade)] transition cursor-pointer">
                        {{ __('Previous') }}
                    </button>
                @endif
            </span>

            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if (
                            $page == $paginator->currentPage() ||
                            $page <= 2 ||
                            $page > $paginator->lastPage() - 2 ||
                            abs($paginator->currentPage() - $page) <= 1
                        )
                            <button
                                type="button"
                                wire:click="gotoPage({{ $page }})"
                                wire:loading.attr="disabled"
                                aria-current="{{ $page === $paginator->currentPage() ? 'page' : 'false' }}"
                                class="inline-flex items-center justify-center min-w-[40px] min-h-[40px] px-3 py-2 rounded-[var(--pn-radius-md)] border text-sm font-bold tabular-nums transition cursor-pointer {{ $page === $paginator->currentPage() ? 'bg-[var(--pn-blossom-700)] text-white border-[var(--pn-ink)] shadow-[2px_2px_0_var(--pn-ink)]' : 'bg-[var(--pn-bg-surface)] text-[var(--pn-text-primary)] border-[var(--pn-border-subtle)] hover:border-[var(--pn-ink)]' }}"
                            >
                                {{ $page }}
                            </button>
                        @elseif($page == 3 || $page == $paginator->lastPage() - 3)
                            <span class="px-2 py-2 text-sm font-bold text-[var(--pn-text-secondary)]">...</span>
                        @endif
                    @endforeach
                @else
                    <span class="px-2 py-2 text-sm font-bold text-[var(--pn-text-secondary)]">...</span>
                @endif
            @endforeach

            <span>
                @if ($paginator->onLastPage())
                    <span class="inline-flex items-center min-h-[40px] px-3.5 py-2 rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-paper-shade)]/60 text-sm font-semibold text-[var(--pn-text-secondary)] cursor-not-allowed">
                        {{ __('Next') }}
                    </span>
                @else
                    <button type="button" wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                        class="inline-flex items-center min-h-[40px] px-3.5 py-2 rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] text-sm font-semibold text-[var(--pn-text-primary)] hover:border-[var(--pn-ink)] hover:bg-[var(--pn-paper-shade)] transition cursor-pointer">
                        {{ __('Next') }}
                    </button>
                @endif
            </span>
        </nav>
    @endif
</div>
