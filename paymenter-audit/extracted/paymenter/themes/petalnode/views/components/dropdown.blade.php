@props([
    'width' => null,
    'content' => null,
    'trigger' => null,
    'showArrow' => true,
])
<div
    class="relative"
    x-data="{
        open: false,
        adjustWidth: 0
    }"
    x-init="$watch('open', value => {
        if (value) {
            adjustWidth = 0;
            $nextTick(() => {
                let dropdown = $refs.dropdown;
                if (!dropdown) return;
                let rect = dropdown.getBoundingClientRect();
                let windowWidth = window.innerWidth;
                adjustWidth = rect.right > windowWidth ? rect.width - 40 : 0;
            });
        }
    })"
    @keydown.escape.window="open = false"
>
    <button
        type="button"
        class="flex flex-row items-center gap-1 px-2.5 py-1.5 min-h-[40px] rounded-[var(--pn-radius-md)] text-sm font-semibold whitespace-nowrap text-[var(--pn-text-primary)] hover:bg-[var(--pn-paper-shade)]/60 transition cursor-pointer"
        x-on:click="open = !open"
        :aria-expanded="open.toString()"
    >
        {{ $trigger }}
        @if($showArrow)
            <x-ri-arrow-down-s-line x-bind:class="{ '-rotate-180': open }" class="md:block hidden size-4 text-[var(--pn-text-secondary)] ease-out duration-200" aria-hidden="true" />
        @endif
    </button>

    <div
        x-ref="dropdown"
        class="absolute mt-2 {{ $width ?? 'w-52' }} p-1.5 bg-[var(--pn-bg-surface)] rounded-[var(--pn-radius-lg)] shadow-[var(--pn-shadow-paper-md)] z-40 border-[1.5px] border-[var(--pn-ink)]/25"
        x-bind:style="{ left: `-${adjustWidth}px` }"
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-on:click.outside="open = false"
        x-cloak
    >
        {{ $content }}
    </div>
</div>
