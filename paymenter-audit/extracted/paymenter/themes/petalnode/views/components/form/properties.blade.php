@props(['properties', 'custom_properties' => []])

@foreach ($custom_properties as $property)
    @switch($property->type)
        @case('date')
        @case('string')
        @case('number')
            <x-form.input
                :type="$property->type === 'string' ? 'text' : $property->type"
                name="properties.{{ $property->key }}"
                :label="$property->name"
                :required="$property->required"
                wire:model="properties.{{ $property->key }}"
                :value="$properties[$property->key] ?? ''"
                :disabled="$property->non_editable && isset($properties[$property->key])"
            />
        @break

        @case('checkbox')
            <x-form.checkbox
                name="properties.{{ $property->key }}"
                :label="$property->name"
                :required="$property->required"
                wire:model="properties.{{ $property->key }}"
                :checked="$properties[$property->key] ?? false"
                :disabled="$property->non_editable && isset($properties[$property->key])"
            />
        @break

        @case('radio')
            <fieldset class="flex flex-col gap-2">
                <legend class="mb-1 text-sm font-semibold text-[var(--pn-text-primary)]">{{ $property->name }}</legend>
                @foreach ($property->allowed_values as $value)
                    <div class="flex items-center gap-2.5">
                        <input
                            type="radio"
                            id="properties_{{ $property->key }}_{{ $loop->index }}"
                            value="{{ $value }}"
                            name="properties.{{ $property->key }}"
                            @checked(($properties[$property->key] ?? null) === $value)
                            @required($property->required)
                            wire:model="properties.{{ $property->key }}"
                            @disabled($property->non_editable && isset($properties[$property->key]))
                            class="form-radio size-4 border-[var(--pn-ink)]/45 text-[var(--pn-blossom-700)] focus:ring-[var(--pn-blossom-200)] bg-[var(--pn-bg-surface)]"
                        />
                        <label class="text-sm text-[var(--pn-text-primary)] cursor-pointer" for="properties_{{ $property->key }}_{{ $loop->index }}">
                            {{ $value }}
                        </label>
                    </div>
                @endforeach
            </fieldset>
        @break

        @case('select')
            <x-form.select
                name="properties.{{ $property->key }}"
                :label="$property->name"
                class="has-[option:disabled:checked]:text-[var(--pn-text-secondary)]"
                wire:model="properties.{{ $property->key }}"
                :required="$property->required"
                :disabled="$property->non_editable && isset($properties[$property->key]) && !empty($properties[$property->key])"
            >
                <option disabled value="">{{ __('common.select.placeholder', ['name' => $property->name]) }}</option>
                @foreach ($property->allowed_values as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </x-form.select>
        @break

        @case('text')
            <x-form.textarea
                :type="$property->type"
                name="properties.{{ $property->key }}"
                :label="$property->name"
                :required="$property->required"
                wire:model="properties.{{ $property->key }}"
                :disabled="$property->non_editable && isset($properties[$property->key])"
            >{{ $properties[$property->key] ?? '' }}</x-form.textarea>
        @break

        @default
    @endswitch
@endforeach
