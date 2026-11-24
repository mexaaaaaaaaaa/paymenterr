<?php

namespace Paymenter\Extensions\Others\MagicLink;

use App\Attributes\ExtensionMeta;
use App\Classes\Extension\Extension;
use App\Helpers\ExtensionHelper;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Carbon\Carbon;

#[ExtensionMeta(
    name: 'Magic Link Login',
    description: 'Passwordless login via email magic links',
    version: '1.0.0',
    author: 'NeonNextGenerationPTY',
    url: 'https://neonnextgeneration.com',
    icon: 'ri-mail-send-line'
)]
class MagicLink extends Extension
{
    public function boot()
    {
        require __DIR__ . '/routes/web.php';
        
        // Register views namespace
        View::addNamespace('magiclink', __DIR__ . '/resources/views');
        
        Event::listen('auth.login', function () {
            return [
                'view' => view('magiclink::login-button'),
                'priority' => 10,
            ];
        });
    }

    public function enabled(\App\Models\Extension $extension)
    {
        // Set default config values when enabled
        if (!config('settings::magiclink_enabled')) {
            Config::set('settings::magiclink_enabled', true);
        }
    }

    public function disabled(\App\Models\Extension $extension)
    {
        Config::set('settings::magiclink_enabled', false);
    }

    public function installed()
    {
        ExtensionHelper::runMigrations('extensions/Others/MagicLink/migrations');
    }

    public function uninstalled()
    {
        ExtensionHelper::rollbackMigrations('extensions/Others/MagicLink/migrations');
    }

    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'token_expiry',
                'label' => 'Token Expiry (minutes)',
                'type' => 'number',
                'description' => 'How long magic links remain valid',
                'default' => 15,
                'required' => true,
            ],
            [
                'name' => 'max_attempts',
                'label' => 'Max Login Attempts',
                'type' => 'number',
                'description' => 'Maximum login attempts per email before rate limiting',
                'default' => 5,
                'required' => true,
            ],
            [
                'name' => 'rate_limit_minutes',
                'label' => 'Rate Limit Window (minutes)',
                'type' => 'number',
                'description' => 'Time window for rate limiting',
                'default' => 60,
                'required' => true,
            ],
        ];
    }

    public static function generateToken(string $email): ?string
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return null;
        }

        $token = Str::random(64);
        $expiryMinutes = config('extensions.magic_link.token_expiry', 15);

        // Store token in database
        \DB::table('magic_link_tokens')->insert([
            'user_id' => $user->id,
            'token' => hash('sha256', $token),
            'email' => $email,
            'created_at' => now(),
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
        ]);

        return $token;
    }

    public static function validateToken(string $token): ?User
    {
        // Trim and URL decode the token
        $token = urldecode(trim($token));

        $hashedToken = hash('sha256', $token);

        $tokenRecord = \DB::table('magic_link_tokens')
            ->where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->first();

        if (!$tokenRecord) {
            return null;
        }

        // Delete used token
        \DB::table('magic_link_tokens')
            ->where('id', $tokenRecord->id)
            ->delete();

        $user = User::find($tokenRecord->user_id);

        // Log the attempt for debugging
        \Log::info('Magic Link: User logged in', [
            'user_id' => $user?->id,
            'email' => $user?->email,
        ]);

        return $user;
    }

    public static function checkRateLimit(string $email): bool
    {
        $maxAttempts = config('extensions.magic_link.max_attempts', 5);
        $rateLimitMinutes = config('extensions.magic_link.rate_limit_minutes', 60);

        $attempts = \DB::table('magic_link_tokens')
            ->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subMinutes($rateLimitMinutes))
            ->count();

        return $attempts < $maxAttempts;
    }

    public static function sendMagicLink(string $email): bool
    {
        if (!static::checkRateLimit($email)) {
            return false;
        }

        $token = static::generateToken($email);
        
        if (!$token) {
            return false;
        }

        $user = User::where('email', $email)->first();
        $loginUrl = route('magiclink.login', ['token' => $token]);

        Mail::to($email)->send(new MagicLinkMail($user, $loginUrl));

        return true;
    }
}
