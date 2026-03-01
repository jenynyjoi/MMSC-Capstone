<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login – Messiah School</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
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
      background:#5fb6e5;left: -5%;
      ">     
    </div>
    <!-- Dark blue skew overlay -->
    <div class="absolute z-0" style="
      top:45px; left:-5%;
      width:95%; height:100px;
      background:#0d4c8f;
      transform:skewX(-30deg);
    "></div>
    <!-- Logo sits on top (z-10) -->
    <a href="index.php" class="relative z-10 flex items-center gap-2 sm:gap-3 px-4 sm:px-6 md:px-8 h-full no-underline">
      <img src="images/messiah logo.png" alt="Logo" class="w-8 sm:w-10 md:w-12 h-auto drop-shadow"/>
      <!-- <span class="font-serif4 text-sm uppercase font-medium text-white tracking-widest">My Messiah School of Cavite</span> -->
    </a>
  </header>

  <!--Centered login card  -->
  <main class="flex-1 flex items-center justify-center px-4" style="margin-top:-60px">

    <div
      class="relative w-[410px] rounded-none bg-white shadow-card border border-white/20
             backdrop-blur-xl overflow-hidden
             animate-fadeUp"
    >

      <!-- Form -->
      <form action="#" class="relative z-8 flex flex-col gap-0 px-6 pb-10 pt-2" novalidate>

        <h1 class="text-4xl font-bold text-gray-900 mt-20 mb-10 leading-tight">Login</h1>

        <!-- Email -->
        <div class="float-label relative border-b-2 border-gray-800 mb-4">
          <i class="fas fa-envelope absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input
            id="email"
            type="email"
            placeholder=" "
            required
            class="w-full h-10 bg-transparent border-none outline-none text-gray-900 text-sm"
          />
          <label for="email">Enter your Email</label>
        </div>

        <!-- Password -->
        <div class="float-label relative border-b-2 border-gray-800 mb-1">
          <i class="fas fa-lock absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-800 text-sm"></i>
          <input
            id="password"
            type="password"
            placeholder=" "
            required
            class="w-full h-10 bg-transparent border-none outline-none text-gray-900 text-sm"
          />
          <label for="password">Enter your Password</label>
          <i
            id="togglePassword"
            class="fas fa-eye toggle-password absolute right-2.5 top-1/2 -translate-y-1/2 cursor-pointer text-gray-800 text-sm"
          ></i>
        </div>

        <!-- Forgot password -->
        <div class="flex justify-end mt-1 mb-8">
          <a href="#" class="text-xs text-blue-500 hover:underline">Forgot Password?</a>
        </div>

        <!-- Submit -->
        <button
          type="submit"
          class="w-full h-10 rounded-xl bg-mainBlue hover:bg-hoverBlue
                 text-white font-semibold text-sm tracking-wide
                 transition-colors duration-300 border-none cursor-pointer"
        >
          LOGIN
        </button>

      </form>
    </div>
  </main>
<script>
  document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('togglePassword');
  const pwInput = document.getElementById('password');

  if (toggleBtn && pwInput) {
    toggleBtn.addEventListener('click', () => {
      const isPassword = pwInput.type === 'password';
      pwInput.type = isPassword ? 'text' : 'password';
      toggleBtn.classList.toggle('fa-eye', !isPassword);
      toggleBtn.classList.toggle('fa-eye-slash', isPassword);
    });
  }
});

</script>
</body>
</html>