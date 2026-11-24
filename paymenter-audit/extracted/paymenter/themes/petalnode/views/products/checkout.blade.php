<div class="container py-10 sm:py-14">
    <x-navigation.breadcrumb />

    <div class="flex flex-col lg:grid lg:grid-cols-12 gap-8 mt-2">
        {{-- Left Column: Product Header & Dynamic Configuration --}}
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="pn-card p-5 sm:p-6 flex flex-col sm:flex-row gap-5 items-start">
                @if ($product->image)
                    <img
                        src="{{ Storage::url($product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-full sm:w-36 aspect-square object-cover rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-paper-shade)] shrink-0"
                    />
                @endif
                <div class="flex-1 min-w-0">
                    <span class="font-pixel text-xs font-bold uppercase tracking-wider text-[var(--pn-blossom-700)]">
                        {{ __('Configure Plan') }}
                    </span>
                    <h1 class="mt-1 font-display text-2xl sm:text-3xl font-extrabold text-[var(--pn-text-primary)]">
                        {{ $product->name }}
                    </h1>
                    @if (filled($product->description))
                        <div class="mt-2 max-h-36 overflow-y-auto pr-1">
                            <article class="prose dark:prose-invert prose-sm text-[var(--pn-text-secondary)]">
                                {!! $product->description !!}
                            </article>
                        </div>
                    @endif
                </div>
            </div>

            @if ($product->availablePlans()->count() > 1)
                <div class="pn-card p-5">
                    <x-form.select wire:model.live="plan_id" name="plan_id" label="{{ __('Select a billing plan') }}">
                        @foreach ($product->availablePlans() as $availablePlan)
                            <option value="{{ $availablePlan->id }}">
                                {{ $availablePlan->name }} — {{ $availablePlan->price()->formatted->price }}
                                @if ($availablePlan->price()->has_setup_fee)
                                    + {{ $availablePlan->price()->formatted->setup_fee }} {{ __('product.setup_fee') }}
                                @endif
                            </option>
                        @endforeach
                    </x-form.select>
                </div>
            @endif

            @if ($product->configOptions->isNotEmpty() || !empty($this->getCheckoutConfig()))
                <div class="pn-card p-5 sm:p-6 flex flex-col gap-5">
                    <h2 class="font-display text-lg font-bold text-[var(--pn-text-primary)]">
                        {{ __('Server & Option Configuration') }}
                    </h2>

                    @foreach ($product->configOptions as $configOption)
                        @php
                            $showPriceTag = $configOption->children->filter(fn ($value) => !$value->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->is_free)->count() > 0;
                        @endphp
                        <x-form.configoption :config="$configOption" :name="'configOptions.' . $configOption->id" :showPriceTag="$showPriceTag" :plan="$plan">
                            @if ($configOption->type == 'select')
                                @foreach ($configOption->children as $configOptionValue)
                                    <option value="{{ $configOptionValue->id }}">
                                        {{ $configOptionValue->name }}
                                        {{ ($showPriceTag && $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? ' — ' . $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '' }}
                                    </option>
                                @endforeach
                            @elseif($configOption->type == 'radio')
                                @foreach ($configOption->children as $configOptionValue)
                                    <div class="flex items-center gap-2.5 min-h-[32px]">
                                        <input
                                            type="radio"
                                            id="cfg_{{ $configOptionValue->id }}"
                                            name="config_{{ $configOption->id }}"
                                            wire:model.live="configOptions.{{ $configOption->id }}"
                                            value="{{ $configOptionValue->id }}"
                                            class="form-radio size-4 border-[var(--pn-ink)]/45 text-[var(--pn-blossom-700)] focus:ring-[var(--pn-blossom-200)]"
                                        />
                                        <label for="cfg_{{ $configOptionValue->id }}" class="text-sm font-medium text-[var(--pn-text-primary)] cursor-pointer">
                                            {{ $configOptionValue->name }}
                                            {{ ($showPriceTag && $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit)->available) ? ' — ' . $configOptionValue->price(billing_period: $plan->billing_period, billing_unit: $plan->billing_unit) : '' }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </x-form.configoption>
                    @endforeach

                    @foreach ($this->getCheckoutConfig() as $configOption)
                        @php $configOption = (object) $configOption; @endphp
                        <x-form.configoption :config="$configOption" :name="'checkoutConfig.' . $configOption->name">
                            @if ($configOption->type == 'select')
                                @foreach ($configOption->options as $configOptionValue => $configOptionValueName)
                                    <option value="{{ $configOptionValue }}">
                                        {{ $configOptionValueName }}
                                    </option>
                                @endforeach
                            @elseif($configOption->type == 'radio')
                                @foreach ($configOption->options as $configOptionValue => $configOptionValueName)
                                    <div class="flex items-center gap-2.5 min-h-[32px]">
                                        <input
                                            type="radio"
                                            id="chk_{{ $configOption->name }}_{{ $configOptionValue }}"
                                            name="chk_{{ $configOption->name }}"
                                            wire:model.live="checkoutConfig.{{ $configOption->name }}"
                                            value="{{ $configOptionValue }}"
                                            class="form-radio size-4 border-[var(--pn-ink)]/45 text-[var(--pn-blossom-700)] focus:ring-[var(--pn-blossom-200)]"
                                        />
                                        <label for="chk_{{ $configOption->name }}_{{ $configOptionValue }}" class="text-sm font-medium text-[var(--pn-text-primary)] cursor-pointer">
                                            {{ $configOptionValueName }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </x-form.configoption>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right Column: Sticky Order Summary RoughBox --}}
        <aside class="lg:col-span-4">
            <div class="lg:sticky lg:top-24">
                <x-sketch.rough-box surface="surface" seed="55" padding="p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-[var(--pn-border-subtle)]">
                        <h2 class="font-display text-xl font-extrabold text-[var(--pn-text-primary)]">
                            {{ __('product.order_summary') }}
                        </h2>
                        <x-sketch.hand-icon name="receipt" size="20" class="text-[var(--pn-blossom-700)]" />
                    </div>

                    <dl class="mt-4 space-y-2.5 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-[var(--pn-text-secondary)]">{{ __('Plan') }}</dt>
                            <dd class="font-semibold text-[var(--pn-text-primary)] text-right">{{ $plan->name }}</dd>
                        </div>

                        @if ($total->total_tax > 0)
                            <div class="flex justify-between gap-3">
                                <dt class="text-[var(--pn-text-secondary)]">{{ __('invoices.subtotal') }}</dt>
                                <dd class="font-semibold text-[var(--pn-text-primary)] tabular-nums">{{ $total->format($total->subtotal) }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-[var(--pn-text-secondary)]">{{ \App\Classes\Settings::tax()->name }} ({{ \App\Classes\Settings::tax()->rate }}%)</dt>
                                <dd class="font-semibold text-[var(--pn-text-primary)] tabular-nums">{{ $total->formatted->total_tax }}</dd>
                            </div>
                        @endif

                        <div class="pt-3 border-t border-[var(--pn-border-subtle)] flex justify-between items-baseline gap-3">
                            <dt class="font-display font-bold text-base text-[var(--pn-text-primary)]">{{ __('product.total_today') }}</dt>
                            <dd class="font-display font-extrabold text-2xl text-[var(--pn-blossom-700)] tabular-nums">{{ $total }}</dd>
                        </div>

                        @if ($total->setup_fee > 0 && $plan->type == 'recurring')
                            <div class="flex justify-between gap-3 text-xs text-[var(--pn-text-secondary)]">
                                <dt>{{ __('product.then_after_x', ['time' => $plan->billing_period . ' ' . trans_choice(__('services.billing_cycles.' . $plan->billing_unit), $plan->billing_period)]) }}</dt>
                                <dd class="font-bold text-[var(--pn-text-primary)] tabular-nums">{{ $total->format($total->price) }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if ($product->stock !== 0 && $product->price()->available)
                        <div class="mt-5">
                            <x-button.primary wire:click="checkout" wire:loading.attr="disabled">
                                <x-loading target="checkout" />
                                <span wire:loading.remove wire:target="checkout" class="inline-flex items-center gap-2">
                                    <span>{{ __('product.checkout') }}</span>
                                    <x-ri-arrow-right-line class="size-4" aria-hidden="true" />
                                </span>
                            </x-button.primary>
                        </div>
                    @endif
                </x-sketch.rough-box>
            </div>
        </aside>
    </div>
</div>
