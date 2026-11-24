@props([
    'target' => null,
])
<div role="status"
    @if(!$target) wire:loading @else wire:loading wire:target="{{ $target }}" @endif
>
    <x-ri-loader-5-fill aria-hidden="true" {{ $attributes->merge(['class' => 'size-5 me-1.5 fill-current animate-spin']) }} />
    <span class="sr-only">{{ __('Loading...') }}</span>
</div>
