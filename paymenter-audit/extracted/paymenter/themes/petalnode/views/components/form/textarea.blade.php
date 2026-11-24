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
    <textarea
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        rows="{{ $attributes->get('rows', 4) }}"
        class="block w-full rounded-[var(--pn-radius-md)] border-[1.5px] border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] px-3.5 py-2.5 text-sm text-[var(--pn-text-primary)] placeholder:text-[var(--pn-text-secondary)]/70 shadow-[var(--pn-shadow-paper-sm)] outline-none transition-colors duration-200 focus:border-[var(--pn-blossom-600)] focus:ring-2 focus:ring-[var(--pn-blossom-200)] disabled:bg-[var(--pn-paper-shade)]/60 disabled:cursor-not-allowed {{ $class ?? '' }}"
        placeholder="{{ $placeholder ?? ($label ?? '') }}"
        @if ($dirty && isset($attributes['wire:model'])) wire:dirty.class="!border-[var(--pn-lantern-700)]" @endif
        {{ $attributes->except(['placeholder', 'label', 'id', 'name', 'type', 'class', 'divClass', 'required', 'hideRequiredIndicator', 'dirty']) }}
        @required($required)
    >{{ $slot }}</textarea>
    @error($name)
        <p class="mt-1.5 text-xs font-semibold text-[var(--pn-status-danger)]">{{ $message }}</p>
    @enderror
</fieldset>
