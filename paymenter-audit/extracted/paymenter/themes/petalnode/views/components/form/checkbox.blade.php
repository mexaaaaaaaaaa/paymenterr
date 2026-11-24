@props([
    'name',
    'label' => null,
    'id' => null,
    'divClass' => null,
    'class' => null,
    'required' => false,
])
<div class="flex flex-col gap-1 {{ $divClass ?? '' }}">
    <div class="flex items-start gap-2.5">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            {{ $attributes->except(['label', 'name', 'id', 'class', 'divClass', 'required']) }}
            @required($required)
            class="form-checkbox mt-0.5 size-5 rounded-[var(--pn-radius-xs)] border-[1.5px] border-[var(--pn-ink)]/45 bg-[var(--pn-bg-surface)] text-[var(--pn-blossom-700)] focus:ring-2 focus:ring-[var(--pn-blossom-200)] focus:ring-offset-0 cursor-pointer {{ $class ?? '' }}"
        />
        <label class="text-sm font-medium text-[var(--pn-text-primary)] cursor-pointer select-none leading-snug" for="{{ $id ?? $name }}">
            @if(isset($label))
                {{ $label }}
            @else
                {{ $slot }}
            @endif
        </label>
    </div>

    @error($name)
        <p class="text-xs font-semibold text-[var(--pn-status-danger)]">{{ $message }}</p>
    @enderror
</div>
