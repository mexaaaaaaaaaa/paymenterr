<div x-data="{ open: false }">
    <div
        x-anchor.offset.5="$refs.trigger"
        x-show="open"
        x-cloak
        class="absolute top-0 left-0 text-xs font-semibold text-[var(--pn-text-primary)] w-max max-w-xs px-2.5 py-1.5 rounded-[var(--pn-radius-sm)] bg-[var(--pn-bg-surface)] shadow-[var(--pn-shadow-paper-md)] z-40 border border-[var(--pn-ink)]/30"
        role="tooltip"
    >
        {{ $message }}
        <div class="arrow absolute w-2 h-2 rotate-45 bg-[var(--pn-bg-surface)] border-l border-t border-[var(--pn-ink)]/30 -top-1 left-1/2 -translate-x-1/2"></div>
    </div>
    <div
        class="underline decoration-dotted decoration-[var(--pn-blossom-600)]"
        x-ref="trigger"
        @mouseover="open = true"
        @mouseout="open = false"
        @focus="open = true"
        @blur="open = false"
    >
        {{ $slot }}
    </div>
</div>
