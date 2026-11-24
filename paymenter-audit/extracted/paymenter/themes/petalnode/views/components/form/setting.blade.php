<div class="flex flex-col gap-1.5">
    @switch($setting->type)
        @case('select')
            <x-form.select
                name="fields.{{ $key }}.{{ $setting->name }}"
                :label="__($setting->label ?? $setting->name)"
                :required="$setting->required ?? false"
                :options="$setting->options"
                :selected="config('settings.' . $setting->name)"
                :multiple="$setting->multiple ?? false"
                wire:model="fields.{{ $key }}.{{ $setting->name }}"
            />
        @break

        @case('text')
        @case('password')
        @case('email')
        @case('number')
        @case('color')
        @case('file')
            <x-form.input
                name="fields.{{ $key }}.{{ $setting->name }}"
                :type="$setting->type"
                :label="__($setting->label ?? $setting->name)"
                :placeholder="$setting->default ?? ''"
                :required="$setting->required ?? false"
                wire:model="fields.{{ $key }}.{{ $setting->name }}"
            />
        @break

        @case('checkbox')
            <x-form.checkbox
                name="fields.{{ $key }}.{{ $setting->name }}"
                :label="__($setting->label ?? $setting->name)"
                :required="$setting->required ?? false"
                :checked="config('settings.' . $setting->name) ? true : false"
                wire:model="fields.{{ $key }}.{{ $setting->name }}"
            />
        @break

        @default
    @endswitch

    @isset($setting->description)
        @isset($setting->link)
            <a href="{{ $setting->link }}" class="text-xs font-medium text-[var(--pn-blossom-700)] hover:underline group inline-flex items-center gap-1" target="_blank" rel="noopener noreferrer">
                <span>{{ $setting->description }}</span>
                <x-ri-arrow-right-long-line class="size-3 inline-block -rotate-45 group-hover:rotate-0 transition" aria-hidden="true" />
            </a>
        @else
            <p class="text-xs text-[var(--pn-text-secondary)]">{{ $setting->description }}</p>
        @endisset
    @endisset
</div>
