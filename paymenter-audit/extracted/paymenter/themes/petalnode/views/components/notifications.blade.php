<div>
    <x-dropdown width="w-80 sm:w-96" :showArrow="false">
        <x-slot:trigger>
            <div
                class="relative w-10 h-10 flex items-center justify-center rounded-[var(--pn-radius-md)] cursor-pointer transition"
                x-data="{ hasNew: false }"
                x-on:new-notification.window="hasNew = true"
                @click="hasNew = false"
                aria-label="{{ __('Notifications') }}"
            >
                <x-ri-notification-3-fill class="size-4 text-[var(--pn-text-primary)]" ::class="{ 'animate-wiggle': hasNew }" aria-hidden="true" />
                @if($this->notifications->where('read_at', null)->count() > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-4 h-4 px-1 inline-flex items-center justify-center text-[10px] font-pixel font-bold leading-none text-white bg-[var(--pn-blossom-700)] border border-[var(--pn-ink)] rounded-full tabular-nums">
                        {{ $this->notifications->where('read_at', null)->count() }}
                    </span>
                @endif
            </div>
        </x-slot:trigger>
        <x-slot:content>
            <div class="w-full max-h-96 overflow-y-auto divide-y divide-[var(--pn-border-subtle)]">
                @if ($this->notifications->isEmpty())
                    <div class="p-6 text-center text-sm text-[var(--pn-text-secondary)]">
                        {{ __('No new notifications') }}
                    </div>
                @else
                    @foreach ($this->notifications ?? collect() as $notification)
                        <div wire:click="goToNotification({{ $notification->id }})" class="block px-3.5 py-3 hover:bg-[var(--pn-paper-shade)]/50 cursor-pointer rounded-[var(--pn-radius-sm)] transition">
                            <div class="flex items-start gap-3">
                                <x-ri-notification-3-fill class="size-4 mt-1 shrink-0 {{ $notification && $notification->read_at ? 'text-[var(--pn-text-secondary)]' : 'text-[var(--pn-blossom-700)]' }}" aria-hidden="true" />
                                <div class="flex flex-col min-w-0 flex-1">
                                    <span class="font-display font-bold text-sm text-[var(--pn-text-primary)]">{{ $notification->title }}</span>
                                    <span class="text-xs text-[var(--pn-text-secondary)]">{{ $notification->body }}</span>
                                    <div class="flex flex-row justify-between items-center mt-1.5 text-[11px] text-[var(--pn-text-secondary)]">
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        <button wire:click.stop="markAsRead({{ $notification->id }})" class="font-semibold text-[var(--pn-blossom-700)] hover:underline cursor-pointer" type="button">
                                            {{ __('Mark as read') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </x-slot:content>
    </x-dropdown>
</div>
