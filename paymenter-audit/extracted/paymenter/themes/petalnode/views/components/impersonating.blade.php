@if(session()->has('impersonating'))
    <div class="fixed bottom-0 left-0 right-0 z-50 flex flex-wrap gap-3 justify-center items-center bg-[var(--pn-lantern-100)] text-[var(--pn-ink)] shadow-[var(--pn-shadow-paper-md)] px-4 py-3 border-t-[1.5px] border-[var(--pn-ink)]">
        <p class="text-sm font-semibold">
            {{ __('You are currently impersonating') }}:
            <strong class="font-display">{{ auth()->user()->name }}</strong>
        </p>
        <a href="/admin/users/{{ auth()->user()->id }}/edit">
            <x-button.primary class="!w-auto !py-1.5 !px-3.5 !min-h-[36px] text-xs">
                {{ __('Leave') }}
            </x-button.primary>
        </a>
    </div>
@endif
