<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - My Messiah School of Cavite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">

        {{-- Logo --}}
        <div class="flex flex-col items-center mb-8">
            <img src="{{ asset('images/messiah-logo.png') }}" alt="MMSC Logo" class="h-16 w-16 object-contain mb-3">
            <h1 class="text-xl font-bold text-slate-800">My Messiah School of Cavite</h1>
            <p class="text-sm text-slate-500 mt-1">Sign in to your account</p>
        </div>

        {{-- Session Status (for password reset success msg) --}}
        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('status') }}
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm
                           text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500
                           @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm
                               text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500
                               @error('password') border-red-400 @enderror">
                    {{-- Show/Hide Password --}}
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                   9.542 7-1.274 4.057-5.064 7-9.542 7-4.477
                                   0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me + Forgot Password --}}
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember"
                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Forgot password?
                    </a>
                @endif
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full rounded-xl bg-blue-700 py-2.5 text-sm font-bold text-white
                       hover:bg-blue-600 transition-colors shadow-lg shadow-blue-700/30">
                Sign In
            </button>

        </form>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>