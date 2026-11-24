<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Magic Link Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        surface: {
                            DEFAULT: '#0f172a',
                            light: '#1e293b',
                            lighter: '#334155',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-surface flex">

    {{-- Left side --}}
    <div class="hidden lg:flex lg:w-1/2 bg-surface-light relative flex-col justify-center px-16">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-transparent"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-bold text-white mb-4">Join Our Community!</h1>
            <p class="text-gray-400 text-lg mb-8">Sign in to access your account and services.</p>
            <div class="bg-surface-lighter/50 backdrop-blur rounded-xl p-6 border border-white/5">
                <p class="text-gray-300 italic">"Magic link login makes signing in effortless — no password needed."</p>
                <p class="text-gray-500 text-sm mt-3">— Happy User</p>
            </div>
        </div>
    </div>

    {{-- Right side - form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">

            @if(session('success'))
                <div class="bg-surface-light rounded-xl p-8 border border-white/5">
                    <div class="text-center">
                        <div class="mx-auto w-16 h-16 rounded-full bg-green-500/10 flex items-center justify-center mb-5">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-white mb-2">Check your email</h1>
                        <p class="text-gray-400 mb-2">{{ session('success') }}</p>
                        <p class="text-sm text-gray-500 mb-6">The link expires in {{ config('extensions.magic_link.token_expiry', 15) }} minutes.</p>
                        <a href="{{ route('login') }}" class="inline-block w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium text-center transition">
                            Back to Login
                        </a>
                    </div>
                </div>

            @else
                <div class="bg-surface-light rounded-xl p-8 border border-white/5">
                    <h1 class="text-2xl font-bold text-white mb-1">Magic Link Login</h1>
                    <p class="text-gray-400 text-sm mb-6">Enter your email and we'll send you a login link.</p>

                    @if(session('error'))
                        <div class="mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('magiclink.send') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label for="email" class="block text-sm font-medium text-gray-200 mb-2">Email</label>
                            <input type="email" name="email" id="email" required
                                class="w-full px-4 py-3 rounded-xl bg-surface border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                                placeholder="Your email address">
                        </div>

                        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                            Send Magic Link
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition">
                            Back to Login
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

</body>
</html>
