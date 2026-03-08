<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password - MMSC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 to-blue-700 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">

        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/messiah-logo.png') }}" alt="MMSC" class="h-14 w-14 object-contain mb-3">
            <h1 class="text-xl font-bold text-slate-800">Forgot Password?</h1>
            <p class="text-sm text-slate-500 text-center mt-1">
                No worries! Enter your email and we'll send you a reset link.
            </p>
        </div>

        {{-- Success Message --}}
        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
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

            <button type="submit"
                class="w-full rounded-xl bg-blue-700 py-2.5 text-sm font-bold text-white
                       hover:bg-blue-600 transition-colors shadow-lg shadow-blue-700/30">
                Send Reset Link
            </button>

            <a href="{{ route('login') }}"
                class="block text-center mt-4 text-sm text-blue-600 hover:text-blue-800">
                ← Back to Login
            </a>
        </form>

    </div>

</body>
</html>
