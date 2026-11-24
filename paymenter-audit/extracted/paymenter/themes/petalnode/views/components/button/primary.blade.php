<button
    {{ $attributes->merge(['class' => 'pn-btn-primary w-full']) }}
>
    @if (isset($type) && $type === 'submit')
        <div role="status" wire:loading>
            <x-ri-loader-5-fill aria-hidden="true" class="size-5 me-1.5 fill-current animate-spin" />
            <span class="sr-only">{{ __('Loading...') }}</span>
        </div>
        <div wire:loading.remove class="inline-flex items-center gap-2">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</button>
