@props([
    'items' => [],
    'defaultOpen' => 0,
    'class' => '',
])
<div
    x-data="{ openIdx: {{ $defaultOpen === null ? 'null' : (int) $defaultOpen }} }"
    {{ $attributes->merge(['class' => "divide-y divide-[var(--pn-border-subtle)] rounded-[var(--pn-radius-lg)] border-[1.5px] border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] shadow-[var(--pn-shadow-paper-sm)] {$class}"]) }}
>
    @foreach($items as $idx => $item)
    <div class="px-5 py-4">
        <button
            type="button"
            @click="openIdx = openIdx === {{ $idx }} ? null : {{ $idx }}"
            :aria-expanded="(openIdx === {{ $idx }}).toString()"
            class="flex w-full items-center justify-between gap-4 text-left font-display text-base font-bold text-[var(--pn-text-primary)] cursor-pointer min-h-[44px]"
        >
            <span>{{ $item['question'] ?? '' }}</span>
            <x-ri-arrow-down-s-line
                class="size-5 shrink-0 text-[var(--pn-text-secondary)] transition-transform duration-200"
                x-bind:class="{ 'rotate-180 text-[var(--pn-blossom-700)]': openIdx === {{ $idx }} }"
                aria-hidden="true"
            />
        </button>
        <div x-show="openIdx === {{ $idx }}" x-collapse x-cloak>
            <p class="pt-2 pb-1 text-sm leading-relaxed text-[var(--pn-text-secondary)]">
                {{ $item['answer'] ?? '' }}
            </p>
        </div>
    </div>
    @endforeach
</div>
