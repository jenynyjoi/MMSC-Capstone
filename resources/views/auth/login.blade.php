
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
  <style>
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes fadeInScale {
      from { opacity:0; transform:scale(0.94); }
      to   { opacity:1; transform:scale(1); }
    }
    #captcha-modal-box { animation: fadeInScale 0.22s ease; }
    #robot-spinner-el  { animation: spin 0.65s linear infinite; }
  </style>
</head>

<body class="font-poppins min-h-screen overflow-hidden bg-glass-gradient flex flex-col">

  {{-- ══════════════════════════════════════════════════════════
       CAPTCHA MODAL OVERLAY
  ══════════════════════════════════════════════════════════ --}}
  <div id="captcha-overlay"
       style="display:none; position:fixed; inset:0; z-index:9999;
              background:rgba(10,20,40,0.65); backdrop-filter:blur(6px);
              align-items:center; justify-content:center;">

    <div id="captcha-modal-box"
         style="background:#fff; border-radius:18px; padding:30px 28px 24px;
                width:340px; max-width:92vw;
                box-shadow:0 24px 64px rgba(0,0,0,0.28);">

      {{-- Header --}}
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:22px;">
        <div style="width:40px; height:40px; border-radius:10px; flex-shrink:0;
                    background:linear-gradient(135deg,#0d4c8f,#0891b2);
                    display:flex; align-items:center; justify-content:center;">
          <i class="fas fa-shield-halved" style="color:#fff; font-size:17px;"></i>
        </div>
        <div>
          <p style="font-size:15px; font-weight:700; color:#0f172a; margin:0; line-height:1.2;">Security Verification</p>
          <p style="font-size:11px; color:#94a3b8; margin:0;">Confirm you're human to continue</p>
        </div>
      </div>

      {{-- Captcha error --}}
      @if($errors->has('captcha'))
      <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px;
                  padding:10px 14px; margin-bottom:16px; display:flex; gap:8px; align-items:center;">
        <i class="fas fa-xmark" style="color:#ef4444; font-size:11px; flex-shrink:0;"></i>
        <p style="font-size:12px; color:#dc2626; margin:0;">{{ $errors->first('captcha') }}</p>
      </div>
      @endif

      {{-- "I'm not a robot" row --}}
      <div id="robot-row"
           onclick="handleRobotCheck()"
           style="display:flex; align-items:center; justify-content:space-between;
                  border:2px solid #e2e8f0; border-radius:12px; padding:14px 16px;
                  cursor:pointer; margin-bottom:18px; background:#f8fafc;
                  transition:border-color 0.2s, background 0.2s; user-select:none;">
        <div style="display:flex; align-items:center; gap:12px;">
          {{-- Checkbox box --}}
          <div id="robot-checkbox-box"
               style="width:26px; height:26px; border:2px solid #cbd5e1; border-radius:5px;
                      display:flex; align-items:center; justify-content:center;
                      flex-shrink:0; transition:all 0.2s; background:#fff;">
            {{-- spinner --}}
            <div id="robot-spinner-el"
                 style="width:15px; height:15px; border:2.5px solid #cbd5e1;
                        border-top-color:#0d4c8f; border-radius:50%; display:none;"></div>
            {{-- checkmark --}}
            <i id="robot-check-el" class="fas fa-check"
               style="color:#22c55e; font-size:13px; display:none;"></i>
          </div>
          <span style="font-size:14px; font-weight:500; color:#374151;">I'm not a robot</span>
        </div>
        {{-- Branding badge --}}
        <div style="text-align:center; flex-shrink:0; opacity:0.45;">
          <i class="fas fa-shield" style="color:#475569; font-size:18px; display:block;"></i>
          <span style="font-size:8px; color:#64748b; letter-spacing:0.5px; display:block; margin-top:1px;">MMSC<br>reCAPTCHA</span>
        </div>
      </div>

      {{-- Image CAPTCHA section (hidden until checkbox clicked) --}}
      <div id="image-captcha-section" style="display:none;">

        <p style="font-size:12px; color:#475569; margin:0 0 8px;">
          Type the characters shown in the image:
        </p>

        {{-- Image row + refresh --}}
        <div style="display:flex; align-items:stretch; gap:8px; margin-bottom:10px;">
          <img id="captcha-img"
               src="{{ route('captcha.image') }}"
               style="flex:1; height:60px; border:1.5px solid #e2e8f0;
                      border-radius:10px; object-fit:fill;"
               alt="CAPTCHA image"/>
          <button type="button" onclick="refreshCaptcha()"
                  title="Get a new image"
                  style="width:40px; height:60px; border:1.5px solid #e2e8f0;
                         border-radius:10px; background:#f8fafc; cursor:pointer;
                         color:#64748b; flex-shrink:0; transition:background 0.15s;"
                  onmouseover="this.style.background='#f1f5f9'"
                  onmouseout="this.style.background='#f8fafc'">
            <i class="fas fa-rotate-right" style="font-size:14px;"></i>
          </button>
        </div>

        {{-- Text input --}}
        <form method="POST" action="{{ route('captcha.verify') }}" id="captcha-form">
          @csrf
          <input type="text" name="captcha_input" id="captcha-input"
                 maxlength="5" autocomplete="off" spellcheck="false"
                 placeholder="Enter characters above"
                 oninput="this.value=this.value.toUpperCase()"
                 style="width:100%; padding:11px 14px;
                        border:2px solid #e2e8f0; border-radius:10px;
                        font-size:18px; letter-spacing:6px; text-transform:uppercase;
                        font-family:monospace; font-weight:700; color:#0f172a;
                        box-sizing:border-box; outline:none; margin-bottom:12px;
                        transition:border-color 0.2s;"
                 onfocus="this.style.borderColor='#0d4c8f'"
                 onblur="this.style.borderColor='#e2e8f0'"/>

          <button type="submit"
                  style="width:100%; padding:11px; background:#0d4c8f; color:#fff;
                         border:none; border-radius:10px; font-size:13px;
                         font-weight:700; cursor:pointer; letter-spacing:0.5px;
                         font-family:inherit; transition:background 0.2s;"
                  onmouseover="this.style.background='#0a3d73'"
                  onmouseout="this.style.background='#0d4c8f'">
            VERIFY
          </button>
        </form>

      </div>

      {{-- Cancel link --}}
      <p style="text-align:center; margin:16px 0 0; font-size:11px; color:#94a3b8;">
        <a href="{{ route('login') }}"
           style="color:#94a3b8; text-decoration:none;"
           onmouseover="this.style.color='#64748b'"
           onmouseout="this.style.color='#94a3b8'">
          Cancel — go back to login
        </a>
      </p>

    </div>
  </div>
  {{-- end captcha modal --}}

  <!-- ── Page Header ── -->
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
      @error('email')
        <div id="alert-error" class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
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

      {{-- Status --}}
      @if(session('status'))
        <div id="alert-status" class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
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

      {{-- Success --}}
      @if(session('success'))
        <div id="alert-success" class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
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

      <!-- Login Form -->
      <form method="POST" action="{{ route('login') }}"
            class="relative z-8 flex flex-col gap-0 px-6 pb-11 pt-5" novalidate>
        @csrf

        <h1 class="text-4xl font-bold text-gray-800 mt-20 mb-10 leading-tight">Login</h1>

        <!-- Email -->
        <div class="float-label relative border-b-2 border-gray-800 mb-4 @error('email') border-red-500 @enderror">
          <i class="fas fa-envelope absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input id="email" type="email" name="email" value="{{ old('email') }}"
                 placeholder=" " required autofocus
                 class="w-full h-11 bg-transparent border-none outline-none text-gray-900 text-sm"/>
          <label for="email">Enter your Email</label>
        </div>

        <!-- Password -->
        <div class="float-label relative border-b-2 border-gray-800 mb-1 @error('password') border-red-500 @enderror">
          <i class="fas fa-lock absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input id="password" type="password" name="password"
                 placeholder=" " required
                 class="w-full h-11 bg-transparent border-none outline-none text-gray-900 text-sm"/>
          <label for="password">Enter your Password</label>
          <i id="togglePassword"
             class="fas fa-eye toggle-password absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer text-gray-800 text-sm"></i>
        </div>
        @error('password')
          <p class="text-xs text-red-500 mt-1 mb-1">{{ $message }}</p>
        @enderror

        <!-- Remember Me + Forgot Password -->
        <div class="flex items-center justify-between mt-2 mb-8">
          <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer select-none">
            <input type="checkbox" name="remember"
                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
            Remember me
          </label>
          @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-xs text-blue-500 hover:underline">
              Forgot Password?
            </a>
          @endif
        </div>

        <button type="submit"
                class="w-full h-10 rounded-xl bg-mainBlue hover:bg-hoverBlue
                       text-white font-semibold text-sm tracking-wide
                       transition-colors duration-300 border-none cursor-pointer shadow-lg shadow-mainBlue/20">
          LOGIN
        </button>

      </form>
    </div>
  </main>

  <script>
  document.addEventListener('DOMContentLoaded', () => {

    // ── Toggle password visibility ──
    const toggleBtn = document.getElementById('togglePassword');
    const pwInput   = document.getElementById('password');
    if (toggleBtn && pwInput) {
      toggleBtn.addEventListener('click', () => {
        const show = pwInput.type === 'password';
        pwInput.type = show ? 'text' : 'password';
        toggleBtn.classList.toggle('fa-eye',      !show);
        toggleBtn.classList.toggle('fa-eye-slash', show);
      });
    }

    // ── Auto-dismiss alerts ──
    ['alert-error','alert-status','alert-success'].forEach(id => {
      const el = document.getElementById(id);
      if (el) setTimeout(() => {
        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        el.style.opacity = '0'; el.style.transform = 'translateY(-8px)';
        setTimeout(() => el.remove(), 400);
      }, 5000);
    });

    // ── Open captcha modal if required ──────────────────────
    @if(session('show_captcha'))
      openCaptchaModal();
      @if(session('captcha_show_image'))
        showImageSectionDirect(); // wrong text submitted — skip checkbox animation
      @endif
    @endif

  });

  // ── Captcha modal helpers ────────────────────────────────────
  function openCaptchaModal() {
    const overlay = document.getElementById('captcha-overlay');
    overlay.style.display = 'flex';
  }

  let robotChecked = false;

  function handleRobotCheck() {
    if (robotChecked) return;

    const spinner  = document.getElementById('robot-spinner-el');
    const check    = document.getElementById('robot-check-el');
    const box      = document.getElementById('robot-checkbox-box');
    const row      = document.getElementById('robot-row');

    // Show spinner
    spinner.style.display = 'block';
    row.style.cursor = 'default';

    setTimeout(() => {
      spinner.style.display = 'none';
      check.style.display   = 'block';
      box.style.borderColor  = '#22c55e';
      box.style.background   = '#f0fdf4';
      row.style.borderColor  = '#86efac';
      row.style.background   = '#f0fdf4';
      robotChecked = true;

      // Slide in image captcha
      setTimeout(() => {
        const section = document.getElementById('image-captcha-section');
        section.style.display = 'block';
        document.getElementById('captcha-input').focus();
      }, 200);
    }, 750);
  }

  function showImageSectionDirect() {
    // Already passed the checkbox step before — skip animation
    const spinner  = document.getElementById('robot-spinner-el');
    const check    = document.getElementById('robot-check-el');
    const box      = document.getElementById('robot-checkbox-box');
    const row      = document.getElementById('robot-row');

    check.style.display   = 'block';
    box.style.borderColor  = '#22c55e';
    box.style.background   = '#f0fdf4';
    row.style.borderColor  = '#86efac';
    row.style.background   = '#f0fdf4';
    row.style.cursor       = 'default';
    robotChecked           = true;

    document.getElementById('image-captcha-section').style.display = 'block';

    // Refresh image (force new captcha since old one was wrong)
    refreshCaptcha();

    document.getElementById('captcha-input').focus();
  }

  function refreshCaptcha() {
    const img = document.getElementById('captcha-img');
    img.src = '{{ route("captcha.image") }}?v=' + Date.now();
  }
  </script>

</body>
</html>
