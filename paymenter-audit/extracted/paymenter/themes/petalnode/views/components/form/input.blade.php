@props([
    'name',
    'label' => null,
    'required' => false,
    'divClass' => null,
    'class' => null,
    'placeholder' => null,
    'id' => null,
    'type' => null,
    'hideRequiredIndicator' => false,
    'dirty' => false,
])
<fieldset class="flex flex-col w-full {{ $divClass ?? '' }}">
    @if ($label)
        <label for="{{ $id ?? $name }}" class="mb-1.5 text-sm font-semibold text-[var(--pn-text-primary)]">
            {{ $label }}
            @if ($required && !$hideRequiredIndicator)
                <span class="text-[var(--pn-status-danger)]" aria-hidden="true">*</span>
            @endif
        </label>
    @endif
    <input
        type="{{ $type ?? 'text' }}"
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        class="block w-full min-h-[44px] rounded-[var(--pn-radius-md)] border-[1.5px] border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] text-sm text-[var(--pn-text-primary)] placeholder:text-[var(--pn-text-secondary)]/70 shadow-[var(--pn-shadow-paper-sm)] transition-colors duration-200 focus:border-[var(--pn-blossom-600)] focus:outline-none focus:ring-2 focus:ring-[var(--pn-blossom-200)] disabled:bg-[var(--pn-paper-shade)]/60 disabled:cursor-not-allowed {{ $class ?? '' }} @if ($type !== 'color') px-3.5 py-2.5 @else p-1 @endif"
        placeholder="{{ $placeholder ?? ($label ?? '') }}"
        @if ($dirty && isset($attributes['wire:model'])) wire:dirty.class="!border-[var(--pn-lantern-700)]" @endif
        {{ $attributes->except(['placeholder', 'label', 'id', 'name', 'type', 'class', 'divClass', 'required', 'hideRequiredIndicator', 'dirty']) }}
        @required($required)
    />
    @error($name)
        <p class="mt-1.5 text-xs font-semibold text-[var(--pn-status-danger)]">{{ $message }}</p>
    @enderror
</fieldset>
