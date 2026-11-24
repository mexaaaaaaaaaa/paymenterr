<div class="flex flex-row items-center gap-3">
    <x-button.primary {{ $attributes }}>
        {{ $slot }}
    </x-button.primary>

    <div
        class="hidden items-center justify-center rounded-[var(--pn-radius-xs)] border border-[var(--pn-moss)] bg-[var(--pn-moss-100)] px-2.5 py-1 font-pixel text-xs font-bold uppercase text-[var(--pn-moss-700)] opacity-0 transition-opacity ease-in-out duration-300"
        id="saved"
    >
        {{ __('Saved!') }}
    </div>
    @script
        <script>
            $wire.on('saved', function () {
                const el = document.getElementById('saved');
                if (!el) return;
                setTimeout(() => {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                }, 40);
                setTimeout(() => {
                    el.classList.remove('opacity-0');
                    el.classList.add('opacity-100');
                }, 70);
                setTimeout(() => {
                    el.classList.remove('opacity-100');
                    el.classList.add('opacity-0');
                }, 2500);
            });
        </script>
    @endscript
</div>
