@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'multiple' => false,
    'required' => false,
    'divClass' => null,
    'id' => null,
    'hideRequiredIndicator' => false,
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

    <select
        id="{{ $id ?? $name }}"
        {{ $multiple ? 'multiple' : '' }}
        {{ $attributes->except(['options', 'id', 'name', 'multiple', 'selected'])->merge([
            'class' => 'block w-full min-h-[44px] rounded-[var(--pn-radius-md)] border-[1.5px] border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] px-3.5 py-2.5 text-sm text-[var(--pn-text-primary)] shadow-[var(--pn-shadow-paper-sm)] outline-none transition-colors duration-200 focus:border-[var(--pn-blossom-600)] focus:ring-2 focus:ring-[var(--pn-blossom-200)] form-select disabled:bg-[var(--pn-paper-shade)]/60 disabled:cursor-not-allowed'
        ]) }}
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    >
        @if (count($options) == 0 && $slot)
            {{ $slot }}
        @else
            @foreach ($options as $key => $option)
                <option value="{{ gettype($options) == 'array' ? $option : $key }}" {{ ($multiple && $selected ? in_array($key, $selected) : $selected == $option) ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        @endif
    </select>

    @if ($multiple)
        <p class="mt-1 text-xs text-[var(--pn-text-secondary)]">
            {{ __('Pro tip: Hold down the Ctrl (Windows) / Command (Mac) button to select multiple options.') }}
        </p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs font-semibold text-[var(--pn-status-danger)]">{{ $message }}</p>
    @enderror
</fieldset>
