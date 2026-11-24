<template x-teleport="body">
    <div
        class="fixed inset-0 z-[105] flex items-center justify-center overflow-hidden bg-[#2B2230]/45 backdrop-blur-[2px] p-4"
        x-show="$store.confirmation.show"
        x-on:keydown.escape.window="!$store.confirmation.loading && $store.confirmation.close()"
        role="dialog"
        aria-modal="true"
        x-cloak
    >
        <div
            class="pn-card w-full max-w-lg px-6 py-5 text-left bg-[var(--pn-bg-surface)] border-[1.5px] border-[var(--pn-ink)] shadow-[var(--pn-shadow-paper-lg)] max-h-[90vh] overflow-y-auto"
            @click.away="!$store.confirmation.loading && $store.confirmation.close()"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="flex justify-between items-start gap-3">
                <h2 class="font-display text-xl font-bold text-[var(--pn-text-primary)]" x-text="$store.confirmation.title"></h2>
                <button
                    type="button"
                    @click="!$store.confirmation.loading && $store.confirmation.close()"
                    aria-label="{{ __('Close dialog') }}"
                    class="w-9 h-9 rounded-[var(--pn-radius-md)] flex items-center justify-center text-[var(--pn-text-secondary)] hover:bg-[var(--pn-paper-shade)] hover:text-[var(--pn-text-primary)] cursor-pointer transition"
                    :class="{ 'opacity-50 cursor-not-allowed': $store.confirmation.loading }"
                >
                    <x-ri-close-fill class="size-5" aria-hidden="true" />
                </button>
            </div>
            <div class="mt-3 text-sm leading-relaxed text-[var(--pn-text-secondary)]" x-text="$store.confirmation.message"></div>
            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5">
                <x-button.secondary
                    type="button"
                    class="sm:!w-auto"
                    x-text="$store.confirmation.cancelText"
                    x-on:click="!$store.confirmation.loading && $store.confirmation.close()"
                    ::disabled="$store.confirmation.loading"
                ></x-button.secondary>

                <x-button.danger
                    type="button"
                    class="sm:!w-auto"
                    x-on:click="$store.confirmation.execute()"
                    ::disabled="$store.confirmation.loading"
                >
                    <template x-if="$store.confirmation.loading">
                        <x-ri-loader-5-fill class="size-4 animate-spin" aria-hidden="true" />
                    </template>
                    <span x-text="$store.confirmation.loading ? '{{ __('Loading...') }}' : $store.confirmation.confirmText"></span>
                </x-button.danger>
            </div>
        </div>
    </div>
</template>
