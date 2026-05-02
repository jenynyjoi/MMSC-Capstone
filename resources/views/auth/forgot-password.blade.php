<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password – Messiah School</title>
  @vite(['resources/css/login.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
</head>

<body class="font-poppins min-h-screen overflow-hidden bg-glass-gradient flex flex-col">

  <header class="relative z-10 h-[70px] sm:h-[85px] md:h-[95px] overflow-hidden flex-shrink-0">
    <div class="absolute inset-0 z-1" style="transform:skewX(-30deg);width:103%;height:45px;background:#5fb6e5;left:-5%;"></div>
    <div class="absolute z-0" style="top:45px;left:-5%;width:95%;height:100px;background:#0d4c8f;transform:skewX(-30deg);"></div>
    <a href="{{ url('/') }}" class="relative z-10 flex items-center gap-2 sm:gap-3 px-4 sm:px-6 md:px-8 h-full no-underline">
      <img src="{{ asset('images/messiah-logo.png') }}" alt="Logo" class="w-8 sm:w-10 md:w-12 h-auto drop-shadow"/>
    </a>
  </header>

  <main class="flex-1 flex items-center justify-center px-4" style="margin-top:-60px">
    <div class="relative w-[410px] rounded-none bg-white shadow-card border border-white/20 backdrop-blur-xl overflow-hidden animate-fadeUp">

      {{-- Error --}}
      @if($errors->has('email'))
        <div id="alert-error" class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100">
            <i class="fas fa-xmark text-red-600 text-xs"></i>
          </div>
          <div class="flex-1">
            <p class="text-xs font-semibold text-red-700">Error</p>
            <p class="text-xs text-red-500 mt-0.5">{{ $errors->first('email') }}</p>
          </div>
          <button onclick="document.getElementById('alert-error').remove()" class="text-red-300 hover:text-red-500 ml-1"><i class="fas fa-xmark text-xs"></i></button>
        </div>
      @endif

      <form method="POST" action="{{ route('password.send-otp') }}"
            class="relative z-8 flex flex-col gap-0 px-6 pb-11 pt-5" novalidate>
        @csrf

        <h1 class="text-3xl font-bold text-gray-800 mt-20 mb-5 leading-tight">Forgot Password</h1>
        <p class="text-xs text-gray-500 mb-8 leading-relaxed">
          Enter your email and we'll send you a one-time code to reset your password.
        </p>

        {{-- Email input --}}
        <div class="float-label relative border-b-2 border-gray-800 mb-6 @error('email') border-red-500 @enderror">
          <i class="fas fa-envelope absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input id="email" type="email" name="email" value="{{ old('email') }}"
            placeholder=" " required autofocus
            class="w-full h-11 bg-transparent border-none outline-none text-gray-900 text-sm"/>
          <label for="email">Enter your Email</label>
        </div>

        <button type="submit"
          class="w-full h-10 rounded-xl bg-mainBlue hover:bg-hoverBlue
                 text-white font-semibold text-sm tracking-wide
                 transition-colors duration-300 border-none cursor-pointer shadow-lg shadow-mainBlue/20 flex items-center justify-center gap-2">
          <i class="fas fa-envelope text-xs"></i>
          Send OTP via Email
        </button>

        <a href="{{ route('login') }}"
           class="mt-5 flex items-center justify-center gap-1.5 text-xs text-blue-500 hover:text-blue-700 transition-colors">
          <i class="fas fa-arrow-left text-[10px]"></i>
          Back to Login
        </a>

      </form>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const el = document.getElementById('alert-error');
      if (el) setTimeout(() => {
        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        el.style.opacity = '0'; el.style.transform = 'translateY(-8px)';
        setTimeout(() => el.remove(), 400);
      }, 5000);
    });
  </script>

</body>
</html>
