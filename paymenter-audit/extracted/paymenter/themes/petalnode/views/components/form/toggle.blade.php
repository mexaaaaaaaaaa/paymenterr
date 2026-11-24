@props([
    'label' => null,
    'id' => 'toggle-' . \Illuminate\Support\Str::random(8),
    'disabled' => false,
])
<label for="{{ $id }}" class="inline-flex items-center gap-3 min-h-[44px] {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
    <div class="relative shrink-0">
        <input
            id="{{ $id }}"
            type="checkbox"
            class="sr-only peer"
            {{ $attributes->except('disabled') }}
            {{ $disabled ? 'disabled' : '' }}
        >
        <div class="w-12 h-7 rounded-full border-[1.5px] border-[var(--pn-ink)]/40 bg-[var(--pn-paper-shade)] peer-checked:bg-[var(--pn-blossom-700)] peer-checked:border-[var(--pn-ink)] transition-colors duration-200"></div>
        <div class="absolute left-[3px] top-[3px] h-5 w-5 rounded-full border border-[var(--pn-ink)]/60 bg-white shadow-sm peer-checked:translate-x-5 transition-transform duration-200"></div>
    </div>
    @isset($label)
        <span class="text-sm font-semibold text-[var(--pn-text-primary)] select-none">{{ $label }}</span>
    @endisset
</label>
