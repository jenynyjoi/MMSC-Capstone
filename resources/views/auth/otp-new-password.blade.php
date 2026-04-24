<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create New Password – Messiah School</title>
  @vite(['resources/css/login.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    .req-item { display:flex; align-items:center; gap:8px; font-size:12px; color:#94a3b8; transition:color 0.2s; }
    .req-item.met { color:#22c55e; }
    .req-item.unmet-submit { color:#ef4444; }
    .req-icon { width:16px; height:16px; border-radius:50%; border:1.5px solid #cbd5e1;
                display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.2s; }
    .req-item.met .req-icon { background:#22c55e; border-color:#22c55e; }
    .req-item.unmet-submit .req-icon { border-color:#ef4444; }
    .req-item .req-icon i { font-size:8px; color:#fff; display:none; }
    .req-item.met .req-icon i { display:block; }
    .strength-track { height:5px; border-radius:9999px; background:#e5e7eb; overflow:hidden; margin-top:10px; }
    .strength-fill  { height:100%; border-radius:9999px; transition:width 0.3s ease, background 0.3s ease; width:0; }
  </style>
</head>

<body class="font-poppins min-h-screen overflow-hidden bg-glass-gradient flex flex-col">

  <!-- Header -->
  <header class="relative z-10 h-[70px] sm:h-[85px] md:h-[95px] overflow-hidden flex-shrink-0">
    <div class="absolute inset-0 z-1" style="transform:skewX(-30deg);width:103%;height:45px;background:#5fb6e5;left:-5%;"></div>
    <div class="absolute z-0" style="top:45px;left:-5%;width:95%;height:100px;background:#0d4c8f;transform:skewX(-30deg);"></div>
    <a href="{{ url('/') }}" class="relative z-10 flex items-center gap-2 sm:gap-3 px-4 sm:px-6 md:px-8 h-full no-underline">
      <img src="{{ asset('images/messiah-logo.png') }}" alt="Logo" class="w-8 sm:w-10 md:w-12 h-auto drop-shadow"/>
    </a>
  </header>

  <main class="flex-1 flex items-center justify-center px-4" style="margin-top:-60px">
    <div class="relative w-[410px] rounded-none bg-white shadow-card border border-white/20 backdrop-blur-xl overflow-hidden animate-fadeUp">

      {{-- Server-side errors --}}
      @if($errors->any())
        <div id="alert-error" class="mx-6 mt-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 mt-0.5">
            <i class="fas fa-xmark text-red-600 text-xs"></i>
          </div>
          <div class="flex-1">
            <p class="text-xs font-semibold text-red-700 mb-1">Please fix the following:</p>
            @foreach($errors->all() as $err)
              <p class="text-xs text-red-500">• {{ $err }}</p>
            @endforeach
          </div>
        </div>
      @endif

      <form method="POST" action="{{ route('password.otp-reset.store') }}"
            id="reset-form"
            class="relative z-8 flex flex-col gap-0 px-6 pb-11 pt-5" novalidate>
        @csrf

        {{-- Icon + heading --}}
        <div class="mt-14 mb-3 flex h-14 w-14 items-center justify-center rounded-2xl shadow-md"
             style="background:linear-gradient(135deg,#0d4c8f,#0891b2)">
          <i class="fas fa-key text-white text-xl"></i>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-1 leading-tight">Create New Password</h1>
        <p class="text-xs text-gray-500 mb-6 leading-relaxed">
          Choose a strong password that meets all the requirements below.
        </p>

        {{-- New Password --}}
        <div class="float-label relative border-b-2 border-gray-800 mb-1 @error('password') border-red-500 @enderror">
          <i class="fas fa-lock absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input id="password" type="password" name="password" placeholder=" " required
            class="w-full h-11 bg-transparent border-none outline-none text-gray-900 text-sm"
            oninput="updateRequirements(this.value); updateStrength(this.value);"/>
          <label for="password">New Password</label>
          <i id="togglePassword"
             class="fas fa-eye toggle-password absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer text-gray-800 text-sm"></i>
        </div>

        {{-- Strength bar --}}
        <div class="strength-track mb-4">
          <div class="strength-fill" id="strength-fill"></div>
        </div>

        {{-- Requirements checklist --}}
        <div class="mb-5 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 space-y-2" id="req-list">
          <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide mb-1">Password must include:</p>
          <div class="req-item" id="req-length">
            <div class="req-icon"><i class="fas fa-check"></i></div>
            <span>At least 8 characters</span>
          </div>
          <div class="req-item" id="req-upper">
            <div class="req-icon"><i class="fas fa-check"></i></div>
            <span>One uppercase letter (A–Z)</span>
          </div>
          <div class="req-item" id="req-number">
            <div class="req-icon"><i class="fas fa-check"></i></div>
            <span>One number (0–9)</span>
          </div>
          <div class="req-item" id="req-special">
            <div class="req-icon"><i class="fas fa-check"></i></div>
            <span>One special character (!@#$…)</span>
          </div>
        </div>

        {{-- Confirm Password --}}
        <div class="float-label relative border-b-2 border-gray-800 mb-1 @error('password_confirmation') border-red-500 @enderror">
          <i class="fas fa-lock-open absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input id="password_confirmation" type="password" name="password_confirmation"
            placeholder=" " required
            class="w-full h-11 bg-transparent border-none outline-none text-gray-900 text-sm"
            oninput="updateMatchMsg()"/>
          <label for="password_confirmation">Confirm New Password</label>
          <i id="toggleConfirm"
             class="fas fa-eye toggle-password absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer text-gray-800 text-sm"></i>
        </div>
        <p id="match-msg" class="text-[11px] mt-1 mb-5" style="color:transparent; min-height:16px;">—</p>

        <button type="submit" id="submit-btn"
          class="w-full h-10 rounded-xl bg-mainBlue hover:bg-hoverBlue
                 text-white font-semibold text-sm tracking-wide
                 transition-colors duration-300 border-none cursor-pointer shadow-lg shadow-mainBlue/20">
          RESET PASSWORD
        </button>

      </form>
    </div>
  </main>

  <script>
  document.addEventListener('DOMContentLoaded', () => {

    // ── Toggle password visibility ───────────────────────────
    [['togglePassword','password'],['toggleConfirm','password_confirmation']].forEach(([btnId, inputId]) => {
      const btn   = document.getElementById(btnId);
      const input = document.getElementById(inputId);
      if (!btn || !input) return;
      btn.addEventListener('click', () => {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.classList.toggle('fa-eye',      !show);
        btn.classList.toggle('fa-eye-slash', show);
      });
    });

    // ── Auto-dismiss server error ─────────────────────────────
    const alertEl = document.getElementById('alert-error');
    if (alertEl) setTimeout(() => {
      alertEl.style.transition = 'opacity 0.4s ease';
      alertEl.style.opacity = '0';
      setTimeout(() => alertEl.remove(), 400);
    }, 6000);

  });

  // ── Requirements check ───────────────────────────────────────
  const rules = {
    'req-length':  v => v.length >= 8,
    'req-upper':   v => /[A-Z]/.test(v),
    'req-number':  v => /[0-9]/.test(v),
    'req-special': v => /[^A-Za-z0-9]/.test(v),
  };

  function updateRequirements(val) {
    Object.entries(rules).forEach(([id, test]) => {
      const el = document.getElementById(id);
      el.classList.toggle('met', test(val));
      el.classList.remove('unmet-submit');
    });
  }

  function allRequirementsMet(val) {
    return Object.values(rules).every(test => test(val));
  }

  // ── Strength bar ─────────────────────────────────────────────
  function updateStrength(val) {
    const fill = document.getElementById('strength-fill');
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    if (val.length >= 12)         score = Math.min(score + 1, 4); // bonus for length

    const levels = [
      { w:'0%',   bg:'#e5e7eb' },
      { w:'25%',  bg:'#ef4444' },
      { w:'50%',  bg:'#f59e0b' },
      { w:'75%',  bg:'#3b82f6' },
      { w:'100%', bg:'#22c55e' },
    ];
    const lvl = val.length === 0 ? levels[0] : levels[score] ?? levels[4];
    fill.style.width      = lvl.w;
    fill.style.background = lvl.bg;
  }

  // ── Match message ─────────────────────────────────────────────
  function updateMatchMsg() {
    const pw   = document.getElementById('password').value;
    const conf = document.getElementById('password_confirmation').value;
    const msg  = document.getElementById('match-msg');
    if (!conf) { msg.textContent = '—'; msg.style.color = 'transparent'; return; }
    if (pw === conf) {
      msg.textContent = '✓ Passwords match';
      msg.style.color = '#22c55e';
    } else {
      msg.textContent = '✗ Passwords do not match';
      msg.style.color = '#ef4444';
    }
  }

  // ── Block submit if requirements not met ─────────────────────
  document.getElementById('reset-form').addEventListener('submit', function(e) {
    const pw   = document.getElementById('password').value;
    const conf = document.getElementById('password_confirmation').value;

    // Highlight unmet requirements
    let allMet = true;
    Object.entries(rules).forEach(([id, test]) => {
      if (!test(pw)) {
        document.getElementById(id).classList.add('unmet-submit');
        document.getElementById(id).classList.remove('met');
        allMet = false;
      }
    });

    if (!allMet) {
      e.preventDefault();
      document.getElementById('req-list').scrollIntoView({ behavior:'smooth', block:'center' });
      return;
    }

    if (pw !== conf) {
      e.preventDefault();
      const msg = document.getElementById('match-msg');
      msg.textContent = '✗ Passwords do not match';
      msg.style.color = '#ef4444';
      document.getElementById('password_confirmation').focus();
    }
  });
  </script>

</body>
</html>
