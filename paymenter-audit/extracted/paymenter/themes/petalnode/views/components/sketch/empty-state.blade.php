@props([
    'title' => 'Nothing sketched here yet',
    'message' => null,
    'stage' => 'seedling',
    'class' => '',
])
<div {{ $attributes->merge(['class' => "pn-card flex flex-col items-center justify-center px-6 py-12 text-center {$class}"]) }}>
    <x-sketch.sprout-mascot :stage="$stage" size="88" class="mb-3" />
    <h3 class="font-display text-xl font-bold text-[var(--pn-text-primary)]">{{ $title }}</h3>
    @if($message)
        <p class="mt-2 max-w-md text-sm leading-relaxed text-[var(--pn-text-secondary)]">{{ $message }}</p>
    @endif
    @if($slot->isNotEmpty())
        <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
