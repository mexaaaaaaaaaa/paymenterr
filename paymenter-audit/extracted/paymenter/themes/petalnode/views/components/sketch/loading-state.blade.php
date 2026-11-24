@props([
    'title' => 'Sketching your view...',
    'message' => null,
    'class' => '',
])
<div role="status" aria-live="polite" {{ $attributes->merge(['class' => "pn-card flex flex-col items-center justify-center px-6 py-10 text-center {$class}"]) }}>
    <x-sketch.nodey-mascot mood="working" size="84" class="mb-2" />
    <p class="font-display text-lg font-bold text-[var(--pn-text-primary)]">{{ $title }}</p>
    @if($message)
        <p class="mt-1.5 max-w-sm text-sm text-[var(--pn-text-secondary)]">{{ $message }}</p>
    @endif
    <div class="mt-4 h-2.5 w-48 pn-skeleton"></div>
</div>
