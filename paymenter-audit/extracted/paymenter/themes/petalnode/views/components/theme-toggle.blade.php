<button
    @click="theme = theme === 'light' ? 'dark' : (theme === 'dark' ? 'system' : 'light')"
    type="button"
    class="w-10 h-10 flex items-center justify-center rounded-[var(--pn-radius-md)] border border-[var(--pn-border-subtle)] bg-[var(--pn-bg-canvas)] hover:bg-[var(--pn-paper-shade)] transition text-[var(--pn-text-primary)] cursor-pointer"
    :title="theme === 'system' ? 'System' : (theme === 'dark' ? 'Dark' : 'Light')"
    :aria-label="theme === 'system' ? 'Theme: System' : (theme === 'dark' ? 'Theme: Dark' : 'Theme: Light')"
>
    <template x-if="theme === 'light'">
        <x-ri-sun-fill class="size-4 text-[var(--pn-lantern-700)]" aria-hidden="true" />
    </template>

    <template x-if="theme === 'dark'">
        <x-ri-moon-fill class="size-4 text-[var(--pn-blossom-400)]" aria-hidden="true" />
    </template>

    <template x-if="theme === 'system'">
        <x-ri-computer-line class="size-4 text-[var(--pn-text-secondary)]" aria-hidden="true" />
    </template>
</button>
