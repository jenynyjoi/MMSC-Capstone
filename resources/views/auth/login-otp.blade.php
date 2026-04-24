<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Verify Login – Messiah School</title>
  @vite(['resources/css/login.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    .otp-box {
      width: 46px; height: 56px;
      text-align: center; font-size: 1.5rem; font-weight: 800;
      border: 2px solid #d1d5db; border-radius: 10px;
      outline: none; caret-color: #0d4c8f;
      transition: border-color 0.2s, box-shadow 0.2s;
      background: transparent;
    }
    .otp-box:focus { border-color: #0d4c8f; box-shadow: 0 0 0 3px rgba(13,76,143,0.12); }
    .otp-box.filled { border-color: #0d4c8f; color: #0d4c8f; }
    .otp-box.error  { border-color: #ef4444; color: #ef4444; animation: shake 0.3s ease; }
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      25%      { transform: translateX(-4px); }
      75%      { transform: translateX(4px); }
    }
  </style>
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

      {{-- Status --}}
      @if(session('status'))
        <div id="alert-status" class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100">
            <i class="fas fa-check text-green-600 text-xs"></i>
          </div>
          <p class="flex-1 text-xs text-green-700">{{ session('status') }}</p>
        </div>
      @endif

      <div class="relative z-8 flex flex-col px-6 pb-10 pt-5">

        {{-- Icon --}}
        <div class="mt-14 mb-3 flex h-14 w-14 items-center justify-center rounded-2xl shadow-md"
             style="background:linear-gradient(135deg,#0d4c8f,#0891b2)">
          <i class="fas fa-shield-halved text-white text-xl"></i>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-1 leading-tight">Security Verification</h1>
        <p class="text-xs text-gray-500 mb-6 leading-relaxed">
          Your account was recently locked. A 6-digit verification code has been sent to<br>
          <span class="font-semibold text-gray-700">{{ $maskedEmail }}</span>.<br>
          Code expires in <span id="countdown" class="font-bold text-red-500">10:00</span>.
        </p>

        {{-- Error --}}
        @if($errors->has('otp'))
          <div id="alert-error" class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <i class="fas fa-xmark text-red-600 text-xs shrink-0"></i>
            <p class="text-xs text-red-600">{{ $errors->first('otp') }}</p>
          </div>
        @endif

        {{-- OTP Form --}}
        <form method="POST" action="{{ route('login.otp.verify') }}" id="otp-form">
          @csrf
          <input type="hidden" name="otp" id="otp-hidden"/>

          <div class="flex gap-2 justify-center mb-6" id="otp-inputs">
            @for($i = 0; $i < 6; $i++)
              <input type="text" inputmode="numeric" maxlength="1" pattern="[0-9]"
                     class="otp-box {{ $errors->has('otp') ? 'error' : '' }}"
                     data-index="{{ $i }}" autocomplete="off"/>
            @endfor
          </div>

          <button type="submit" id="verify-btn"
            class="w-full h-10 rounded-xl bg-mainBlue hover:bg-hoverBlue
                   text-white font-semibold text-sm tracking-wide
                   transition-colors duration-300 border-none cursor-pointer shadow-lg shadow-mainBlue/20">
            VERIFY & LOGIN
          </button>
        </form>

        {{-- Resend --}}
        <div class="mt-5 text-center">
          <p class="text-xs text-gray-400 mb-2">Didn't receive the code?</p>
          <form method="POST" action="{{ route('login.otp.resend') }}">
            @csrf
            <button type="submit" id="resend-btn"
              class="text-xs font-semibold text-blue-500 hover:text-blue-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
              disabled>
              <i class="fas fa-rotate-right text-[10px] mr-1"></i>
              Resend OTP <span id="resend-timer" class="text-gray-400">(60s)</span>
            </button>
          </form>
        </div>

        <a href="{{ route('login') }}"
           class="mt-3 flex items-center justify-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 transition-colors">
          <i class="fas fa-arrow-left text-[10px]"></i>
          Back to Login
        </a>

      </div>
    </div>
  </main>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const boxes  = Array.from(document.querySelectorAll('.otp-box'));
    const hidden = document.getElementById('otp-hidden');
    const form   = document.getElementById('otp-form');

    boxes.forEach((box, i) => {
      box.addEventListener('input', () => {
        box.value = box.value.replace(/\D/, '');
        if (box.value) { box.classList.add('filled'); if (boxes[i+1]) boxes[i+1].focus(); }
        else            { box.classList.remove('filled'); }
        syncHidden();
      });
      box.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !box.value && boxes[i-1]) {
          boxes[i-1].focus(); boxes[i-1].value = ''; boxes[i-1].classList.remove('filled'); syncHidden();
        }
        if (e.key === 'ArrowLeft'  && boxes[i-1]) boxes[i-1].focus();
        if (e.key === 'ArrowRight' && boxes[i+1]) boxes[i+1].focus();
      });
      box.addEventListener('paste', e => {
        e.preventDefault();
        const pasted = (e.clipboardData||window.clipboardData).getData('text').replace(/\D/g,'').slice(0,6);
        pasted.split('').forEach((ch, idx) => { if (boxes[idx]) { boxes[idx].value = ch; boxes[idx].classList.add('filled'); } });
        if (boxes[pasted.length]) boxes[pasted.length].focus(); else boxes[5].focus();
        syncHidden();
      });
    });

    function syncHidden() { hidden.value = boxes.map(b => b.value).join(''); }
    form.addEventListener('input', () => { if (hidden.value.length === 6) form.submit(); });
    if (boxes[0]) boxes[0].focus();

    // Countdown
    let total = 10 * 60;
    const countEl = document.getElementById('countdown');
    function tick() {
      const m = String(Math.floor(total/60)).padStart(2,'0');
      const s = String(total%60).padStart(2,'0');
      countEl.textContent = `${m}:${s}`;
      if (total <= 60) countEl.classList.add('text-red-600');
      if (total <= 0) { countEl.textContent = 'Expired'; document.getElementById('verify-btn').disabled = true; return; }
      total--;
      setTimeout(tick, 1000);
    }
    tick();

    // Resend cooldown
    let resend = 60;
    const resendBtn = document.getElementById('resend-btn');
    const resendTimer = document.getElementById('resend-timer');
    function resendTick() {
      resendTimer.textContent = resend > 0 ? `(${resend}s)` : '';
      if (resend <= 0) { resendBtn.disabled = false; return; }
      resend--;
      setTimeout(resendTick, 1000);
    }
    resendTick();

    // Auto-dismiss alerts
    ['alert-status','alert-error'].forEach(id => {
      const el = document.getElementById(id);
      if (el) setTimeout(() => { el.style.transition='opacity 0.4s ease'; el.style.opacity='0'; setTimeout(()=>el.remove(),400); }, 4000);
    });
  });
  </script>
</body>
</html>
