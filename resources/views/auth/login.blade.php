<!-- LOGIN FORM UI WITHOUT  BACKEND LOGIC-->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login – Messiah School</title>
  @vite(['resources/css/login.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,500;1,8..60,400&display=swap" rel="stylesheet"/>
</head>

<body class="font-poppins min-h-screen overflow-hidden bg-glass-gradient flex flex-col">

  <!-- ── Page Header with skewed background behind logo ── -->
  <header class="relative z-10 h-[70px] sm:h-[85px] md:h-[95px] overflow-hidden flex-shrink-0">
    <!-- Light blue skew layer -->
    <div class="absolute inset-0 z-1" style="
      transform:skewX(-30deg);
      width: 103%; height:45px;
      background:#5fb6e5; left: -5%;
    "></div>
    <!-- Dark blue skew overlay -->
    <div class="absolute z-0" style="
      top:45px; left:-5%;
      width:95%; height:100px;
      background:#0d4c8f;
      transform:skewX(-30deg);
    "></div>
    <!-- Logo sits on top (z-10) -->
    <a href="{{ url('/') }}" class="relative z-10 flex items-center gap-2 sm:gap-3 px-4 sm:px-6 md:px-8 h-full no-underline">
      <img src="{{ asset('images/messiah logo.png') }}" alt="Logo" class="w-8 sm:w-10 md:w-12 h-auto drop-shadow"/>
    </a>
  </header>

  <!-- Centered login card -->
  <main class="flex-1 flex items-center justify-center px-4" style="margin-top:-60px">

    <div
      class="relative w-[410px] rounded-none bg-white shadow-card border border-white/20
             backdrop-blur-xl overflow-hidden animate-fadeUp"
    >

      {{-- ── Inline Error Message (wrong credentials) ── --}}
      @error('email')
        <div id="alert-error"
             class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100">
            <i class="fas fa-xmark text-red-600 text-xs"></i>
          </div>
          <div class="flex-1">
            <p class="text-xs font-semibold text-red-700">Login Failed</p>
            <p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>
          </div>
          <button onclick="document.getElementById('alert-error').remove()"
                  class="text-red-300 hover:text-red-500 transition-colors ml-1">
            <i class="fas fa-xmark text-xs"></i>
          </button>
        </div>
      @enderror

      {{-- ── Inline Status Message (e.g. password reset link sent) ── --}}
      @if (session('status'))
        <div id="alert-status"
             class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100">
            <i class="fas fa-info text-blue-600 text-xs"></i>
          </div>
          <div class="flex-1">
            <p class="text-xs font-semibold text-blue-700">Notice</p>
            <p class="text-xs text-blue-500 mt-0.5">{{ session('status') }}</p>
          </div>
          <button onclick="document.getElementById('alert-status').remove()"
                  class="text-blue-300 hover:text-blue-500 transition-colors ml-1">
            <i class="fas fa-xmark text-xs"></i>
          </button>
        </div>
      @endif

      {{-- ── Inline Success Message ── --}}
      @if (session('success'))
        <div id="alert-success"
             class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100">
            <i class="fas fa-check text-green-600 text-xs"></i>
          </div>
          <div class="flex-1">
            <p class="text-xs font-semibold text-green-700">Success</p>
            <p class="text-xs text-green-500 mt-0.5">{{ session('success') }}</p>
          </div>
          <button onclick="document.getElementById('alert-success').remove()"
                  class="text-green-300 hover:text-green-500 transition-colors ml-1">
            <i class="fas fa-xmark text-xs"></i>
          </button>
        </div>
      @endif

      <!-- Form -->
      <form method="POST" action="{{ route('login') }}" class="relative z-8 flex flex-col gap-0 px-6 pb-11 pt-5" novalidate>
        @csrf

        <h1 class="text-4xl font-bold text-gray-800 mt-20 mb-10 leading-tight">Login</h1>

        <!-- Email -->
        <div class="float-label relative border-b-2 border-gray-800 mb-4 @error('email') border-red-500 @enderror">
          <i class="fas fa-envelope absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder=" "
            required
            autofocus
            class="w-full h-11 bg-transparent border-none outline-none text-gray-900 text-sm"
          />
          <label for="email">Enter your Email</label>
        </div>

        <!-- Password -->
        <div class="float-label relative border-b-2 border-gray-800 mb-1 @error('password') border-red-500 @enderror">
          <i class="fas fa-lock absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input
            id="password"
            type="password"
            name="password"
            placeholder=" "
            required
            class="w-full h-11 bg-transparent border-none outline-none text-gray-900 text-sm"
          />
          <label for="password">Enter your Password</label>
          <i
            id="togglePassword"
            class="fas fa-eye toggle-password absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer text-gray-800 text-sm"
          ></i>
        </div>
        @error('password')
          <p class="text-xs text-red-500 mt-1 mb-1">{{ $message }}</p>
        @enderror

        <!-- Remember Me + Forgot Password -->
        <div class="flex items-center justify-between mt-2 mb-8">
          <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer select-none">
            <input
              type="checkbox"
              name="remember"
              class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            />
            Remember me
          </label>

          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-xs text-blue-500 hover:underline">
              Forgot Password?
            </a>
          @endif
        </div>

        <!-- Submit -->
        <button
          type="submit"
          class="w-full h-10 rounded-xl bg-mainBlue hover:bg-hoverBlue
                 text-white font-semibold text-sm tracking-wide
                 transition-colors duration-300 border-none cursor-pointer shadow-lg shadow-mainBlue/20"
        >
          LOGIN
        </button>

      </form>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {

      // ── Toggle Password Visibility ──
      const toggleBtn = document.getElementById('togglePassword');
      const pwInput   = document.getElementById('password');
      if (toggleBtn && pwInput) {
        toggleBtn.addEventListener('click', () => {
          const isPassword = pwInput.type === 'password';
          pwInput.type = isPassword ? 'text' : 'password';
          toggleBtn.classList.toggle('fa-eye',       !isPassword);
          toggleBtn.classList.toggle('fa-eye-slash',  isPassword);
        });
      }

      // ── Auto-dismiss alerts after 5 seconds ──
      ['alert-error', 'alert-status', 'alert-success'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
          setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            el.style.opacity    = '0';
            el.style.transform  = 'translateY(-8px)';
            setTimeout(() => el.remove(), 400);
          }, 5000);
        }
      });

    });
  </script>

</body>
</html>