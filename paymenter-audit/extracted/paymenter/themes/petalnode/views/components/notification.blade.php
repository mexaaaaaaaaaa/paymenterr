<div x-data aria-live="polite" aria-atomic="true">
    <template x-for="(notification, index) in $store.notifications.notifications" :key="notification.id">
        <div
            x-show="notification.show"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click="$store.notifications.removeNotification(notification.id)"
            :class="notification.type === 'success'
                ? 'bg-[var(--pn-moss-100)] border-[var(--pn-moss-700)] text-[var(--pn-ink)]'
                : 'bg-[var(--pn-blossom-50)] border-[var(--pn-blossom-700)] text-[var(--pn-ink)]'"
            class="fixed z-[110] flex items-center gap-3 rounded-[var(--pn-radius-md)] border-[1.5px] px-4 py-3 shadow-[var(--pn-shadow-paper-md)] cursor-pointer max-w-md w-[calc(100%-2rem)]"
            :style="'top: ' + (20 + index * 64) + 'px; left: 50%; transform: translateX(-50%);'"
        >
            <span
                class="size-2.5 rounded-full shrink-0"
                :class="notification.type === 'success' ? 'bg-[var(--pn-moss-700)]' : 'bg-[var(--pn-blossom-700)]'"
                aria-hidden="true"
            ></span>
            <p class="text-sm font-semibold flex-1" x-text="notification.message"></p>
            <x-ri-close-line class="size-4 text-[var(--pn-ink-soft)] shrink-0" aria-hidden="true" />
        </div>
    </template>
</div>
