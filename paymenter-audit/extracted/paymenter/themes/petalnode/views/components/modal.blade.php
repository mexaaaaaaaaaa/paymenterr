@props([
    'title' => '',
    'closable' => true,
    'closeTrigger' => '',
    'open' => false,
    'width' => 'max-w-3xl',
])
<div x-data="{ open: {{ $open ? 'true' : 'false' }} }">
    <template x-teleport="body">
        <div
            class="fixed inset-0 z-[100] flex items-center justify-center overflow-hidden bg-[#2B2230]/45 backdrop-blur-[2px] p-4"
            x-show="open"
            @keydown.escape.window="open = false"
            role="dialog"
            aria-modal="true"
            x-cloak
        >
            <div
                class="pn-card px-6 py-5 w-full text-left bg-[var(--pn-bg-surface)] border-[1.5px] border-[var(--pn-ink)] shadow-[var(--pn-shadow-paper-lg)] max-h-[calc(100vh-2rem)] overflow-y-auto {{ $width }}"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="flex justify-between items-center gap-3 pb-3 border-b border-[var(--pn-border-subtle)]">
                    <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)]">{{ $title }}</h2>
                    @if ($closable && !$closeTrigger)
                        <button type="button" @click="open = false" aria-label="{{ __('Close modal') }}" class="w-9 h-9 rounded-[var(--pn-radius-md)] flex items-center justify-center text-[var(--pn-text-secondary)] hover:bg-[var(--pn-paper-shade)] hover:text-[var(--pn-text-primary)] cursor-pointer">
                            <x-ri-close-fill class="size-5" aria-hidden="true" />
                        </button>
                    @elseif ($closable && $closeTrigger)
                        {{ $closeTrigger }}
                    @endif
                </div>
                <div class="mt-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>
</div>
