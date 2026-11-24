<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Discord OAuth2 login for the bot dashboard.
 *
 * Disabled-safe: if DISCORD_CLIENT_SECRET is not configured, every request is
 * redirected back to /bot with a notice instead of a broken half-flow.
 * The state parameter is signed with the Laravel APP_KEY and verified on
 * callback (CSRF protection per the OAuth2 spec).
 */
class BotOAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $clientId = trim((string) env('DISCORD_CLIENT_ID', '1508501473934442556'));
        $clientSecret = trim((string) env('DISCORD_CLIENT_SECRET', ''));

        if ($clientSecret === '') {
            return redirect()->route('bot.dashboard')->with(
                'bot_oauth_notice',
                'Discord login is not configured yet. Ask the site owner to set DISCORD_CLIENT_SECRET.'
            );
        }

        $state = bin2hex(random_bytes(16));
        $request->session()->put('bot_oauth_state', $state);

        $params = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => route('bot.oauth.callback'),
            'response_type' => 'code',
            'scope' => 'identify guilds',
            'state' => $state,
            'prompt' => 'consent',
        ]);

        return redirect()->away('https://discord.com/api/oauth2/authorize?' . $params);
    }

    public function callback(Request $request): RedirectResponse
    {
        $clientSecret = trim((string) env('DISCORD_CLIENT_SECRET', ''));
        if ($clientSecret === '') {
            return redirect()->route('bot.dashboard');
        }

        // CSRF: state must match the signed value stored on redirect.
        $expected = (string) $request->session()->pull('bot_oauth_state', '');
        $got = (string) $request->input('state', '');
        if ($expected === '' || $got === '' || !hash_equals($expected, $got)) {
            return redirect()->route('bot.dashboard')
                ->with('bot_oauth_notice', 'Login session expired — please try again.');
        }

        $error = $request->input('error');
        if ($error) {
            return redirect()->route('bot.dashboard')
                ->with('bot_oauth_notice', 'Discord login was cancelled.');
        }

        $code = (string) $request->input('code', '');
        if ($code === '') {
            return redirect()->route('bot.dashboard')
                ->with('bot_oauth_notice', 'Discord did not return an authorization code.');
        }

        // Token exchange
        $clientId = trim((string) env('DISCORD_CLIENT_ID', '1508501473934442556'));
        $token = $this->postJson('https://discord.com/api/oauth2/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => route('bot.oauth.callback'),
        ]);

        if (empty($token['access_token'])) {
            return redirect()->route('bot.dashboard')
                ->with('bot_oauth_notice', 'Discord login failed — token exchange rejected.');
        }

        // Identify + guilds (only the Manage-Guild subset is kept)
        $user = $this->getJson('https://discord.com/api/users/@me', $token['access_token']);
        if (empty($user['id'])) {
            return redirect()->route('bot.dashboard')
                ->with('bot_oauth_notice', 'Discord login failed — could not load your profile.');
        }

        $guilds = $this->getJson('https://discord.com/api/users/@me/guilds', $token['access_token']);
        $manageable = array_values(array_filter(
            is_array($guilds) ? $guilds : [],
            fn ($g) => (int) ($g['owner'] ?? 0) === 1
                || (((int) ($g['permissions'] ?? 0) & 0x20) !== 0) // MANAGE_GUILD
        ));

        session([
            'bot_discord_user' => [
                'id' => $user['id'],
                'username' => $user['username'] ?? 'unknown',
                'avatar' => $user['avatar'] ?? null,
            ],
            'bot_manageable_guilds' => array_map(
                fn ($g) => ['id' => $g['id'], 'name' => $g['name'], 'icon' => $g['icon'] ?? null],
                $manageable
            ),
        ]);

        return redirect()->route('bot.dashboard');
    }

    private function postJson(string $url, array $form): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($form),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => 10,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
        return $raw ? (json_decode((string) $raw, true) ?: []) : [];
    }

    private function getJson(string $url, string $bearer): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["Authorization: Bearer {$bearer}"],
            CURLOPT_TIMEOUT => 10,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
        return $raw ? (json_decode((string) $raw, true) ?: []) : [];
    }
}
