@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'multiple' => false,
    'required' => false,
    'divClass' => null,
    'hideRequiredIndicator' => false,
])
<fieldset class="flex flex-col w-full {{ $divClass ?? '' }}" name="{{ $name }}">
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 text-sm font-semibold text-[var(--pn-text-primary)]">
            {{ $label }}
            @if ($required && !$hideRequiredIndicator)
                <span class="text-[var(--pn-status-danger)]" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="flex flex-col gap-2 w-full rounded-[var(--pn-radius-md)] border-[1.5px] border-[var(--pn-border-subtle)] bg-[var(--pn-bg-surface)] px-3.5 py-3 text-sm text-[var(--pn-text-primary)] shadow-[var(--pn-shadow-paper-sm)]">
        @if (count($options) == 0 && $slot)
            {{ $slot }}
        @else
            @foreach ($options as $key => $option)
                <div class="flex items-center gap-2.5 min-h-[32px]">
                    <input
                        type="radio"
                        id="{{ $name }}_{{ $key }}"
                        name="{{ $name }}"
                        class="form-radio size-4 border-[var(--pn-ink)]/45 text-[var(--pn-blossom-700)] focus:ring-[var(--pn-blossom-200)]"
                        value="{{ gettype($options) == 'array' ? $option : $key }}"
                        {{ ($multiple && $selected ? in_array($key, $selected) : $selected == $option) ? 'checked' : '' }}
                    />
                    <label for="{{ $name }}_{{ $key }}" class="cursor-pointer font-medium">
                        {{ $option }}
                    </label>
                </div>
            @endforeach
        @endif
    </div>

    @error($name)
        <p class="mt-1.5 text-xs font-semibold text-[var(--pn-status-danger)]">{{ $message }}</p>
    @enderror
</fieldset>
