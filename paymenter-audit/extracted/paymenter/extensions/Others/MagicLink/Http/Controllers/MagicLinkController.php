<?php

namespace Paymenter\Extensions\Others\MagicLink\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use App\Events\Auth\Login as LoginEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Paymenter\Extensions\Others\MagicLink\MagicLink;

class MagicLinkController extends Controller
{
    public function showForm()
    {
        return view('magiclink::form');
    }

    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $success = MagicLink::sendMagicLink($request->email);

        if ($success) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Magic link sent to your email!'
                ]);
            }
            return redirect()->route('magiclink.form')
                ->with('success', 'Magic link sent to your email!');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to send magic link. Please try again later.'
            ]);
        }
        return redirect()->route('magiclink.form')
            ->with('error', 'Unable to send magic link. Please try again later.');
    }

    public function login(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')
                ->with('error', 'Invalid magic link.');
        }

        $user = MagicLink::validateToken($token);

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired magic link.');
        }

        // Use Paymenter's login flow
        Session::regenerate();

        $userSession = UserSession::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 512),
            'last_activity' => Carbon::now(),
            'expires_at' => null,
        ]);

        Session::put('user_session', $userSession->ulid);

        Auth::login($user);

        event(new LoginEvent($user));

        return redirect()->intended(route('home'));
    }
}
