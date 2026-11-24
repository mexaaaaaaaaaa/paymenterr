@props([
    'tone' => 'info',
    'title' => null,
    'class' => '',
])
@php
    $styles = match ($tone) {
        'success' => 'bg-[var(--pn-status-success-bg)] border-[var(--pn-moss)] text-[var(--pn-status-success)]',
        'warning' => 'bg-[var(--pn-status-warning-bg)] border-[var(--pn-lantern)] text-[var(--pn-status-warning)]',
        'danger' => 'bg-[var(--pn-status-danger-bg)] border-[var(--pn-blossom-600)] text-[var(--pn-status-danger)]',
        default => 'bg-[var(--pn-status-info-bg)] border-[var(--pn-bark)]/45 text-[var(--pn-text-primary)]',
    };
    $icon = match ($tone) {
        'success' => 'shield',
        'warning' => 'lantern',
        'danger' => 'quill',
        default => 'compass',
    };
@endphp
<div role="alert" {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-[var(--pn-radius-md)] border-[1.5px] px-4 py-3.5 text-sm {$styles} {$class}"]) }}>
    <x-sketch.hand-icon :name="$icon" size="20" class="mt-0.5 shrink-0" />
    <div class="min-w-0 flex-1 space-y-1">
        @if($title)
            <p class="font-display font-bold text-[var(--pn-text-primary)]">{{ $title }}</p>
        @endif
        <div class="text-[var(--pn-text-secondary)]">
            {{ $slot }}
        </div>
    </div>
</div>
