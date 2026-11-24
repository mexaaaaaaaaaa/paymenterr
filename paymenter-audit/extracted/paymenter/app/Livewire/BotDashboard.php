<?php

namespace App\Livewire;

use Livewire\Component;

class BotDashboard extends Component
{
    public string $search = '';
    public string $filter = 'all'; // all | slash | prefix
    public string $category = 'all';
    public array $slashCommands = [];
    public array $prefixCommands = [];
    public string $inviteUrl = '';
    public bool $oauthConfigured = false;
    public string $loginUrl = '';

    /** @var array<int, array{id: string, name: string, icon: ?string}> */
    public array $manageableGuilds = [];

    public string $selectedGuildId = '';

    public function mount(): void
    {
        // Real command directory — generated from the actual bot source.
        $dump = storage_path('app/bot_commands.json');
        if (is_file($dump)) {
            $data = json_decode((string) file_get_contents($dump), true) ?: [];
            $this->slashCommands = array_values($data['slash'] ?? []);
            $this->prefixCommands = array_values($data['prefix'] ?? []);
        }

        // Bot invite URL — only real, minimal-ish required permissions.
        $clientId = config('services.discord.client_id')
            ?? trim((string) env('DISCORD_CLIENT_ID', '1508501473934442556'));
        $perms = 1024 + 2048 + 16384 + 32768 + 64 + 65536 + 8192
               + 16 + 268435456 + 2 + 4 + 1099511627776
               + 1048576 + 2097152 + 1073741824 + 134217728;
        $this->inviteUrl = sprintf(
            'https://discord.com/oauth2/authorize?client_id=%s&permissions=%d&scope=bot%%20applications.commands',
            $clientId,
            $perms
        );

        // OAuth login is only offered when the secret is actually configured.
        $this->oauthConfigured = (bool) trim((string) env('DISCORD_CLIENT_SECRET', ''));
        if ($this->oauthConfigured) {
            $this->loginUrl = route('bot.oauth.redirect');
        }

        $guilds = session('bot_manageable_guilds', []);
        if (is_array($guilds)) {
            $this->manageableGuilds = array_values(array_filter(
                array_map(static function ($guild): ?array {
                    if (!is_array($guild) || !isset($guild['id'], $guild['name'])) {
                        return null;
                    }

                    return [
                        'id' => (string) $guild['id'],
                        'name' => (string) $guild['name'],
                        'icon' => isset($guild['icon']) ? (string) $guild['icon'] : null,
                    ];
                }, $guilds),
                static fn (?array $guild): bool => $guild !== null
            ));
        }

        $this->selectedGuildId = (string) ($this->manageableGuilds[0]['id'] ?? '');
    }

    public function updatedSelectedGuildId(string $value): void
    {
        foreach ($this->manageableGuilds as $guild) {
            if ($guild['id'] === $value) {
                return;
            }
        }

        $this->selectedGuildId = (string) ($this->manageableGuilds[0]['id'] ?? '');
    }

    /** @return array{id: string, name: string, icon: ?string}|array{} */
    public function getSelectedGuildProperty(): array
    {
        foreach ($this->manageableGuilds as $guild) {
            if ($guild['id'] === $this->selectedGuildId) {
                return $guild;
            }
        }

        return [];
    }

    public function updatedSearch(): void
    {
        // Livewire re-renders automatically.
    }

    public function getFilteredCommandsProperty(): array
    {
        $q = mb_strtolower(trim($this->search));
        $out = [];

        if ($this->filter !== 'prefix') {
            foreach ($this->slashCommands as $c) {
                if ($q !== '' && !str_contains(mb_strtolower($c['name'] . ' ' . ($c['description'] ?? '')), $q)) {
                    continue;
                }
                $c['type'] = 'slash';
                $out[] = $c;
            }
        }
        if ($this->filter !== 'slash') {
            foreach ($this->prefixCommands as $c) {
                if ($q !== '' && !str_contains(mb_strtolower($c['name'] . ' ' . ($c['description'] ?? '')), $q)) {
                    continue;
                }
                $c['type'] = 'prefix';
                $out[] = $c;
            }
        }
        return array_slice($out, 0, 400);
    }

    public function render()
    {
        return view('bot.dashboard', [
            'filteredCommands' => $this->filteredCommands,
            'totalSlash' => count($this->slashCommands),
            'totalPrefix' => count($this->prefixCommands),
            'manageableGuilds' => $this->manageableGuilds,
            'selectedGuild' => $this->selectedGuild,
        ]);
    }
}
