@props([
    'title' => 'Something went off the page',
    'message' => null,
    'class' => '',
])
<div role="alert" {{ $attributes->merge(['class' => "pn-card bg-[var(--pn-blossom-50)] border-[var(--pn-blossom-600)] flex flex-col items-center justify-center px-6 py-10 text-center {$class}"]) }}>
    <x-sketch.nodey-mascot mood="alert" size="84" class="mb-2" />
    <h3 class="font-display text-lg font-bold text-[var(--pn-text-primary)]">{{ $title }}</h3>
    @if($message)
        <p class="mt-1.5 max-w-md text-sm text-[var(--pn-text-secondary)]">{{ $message }}</p>
    @endif
    @if($slot->isNotEmpty())
        <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
