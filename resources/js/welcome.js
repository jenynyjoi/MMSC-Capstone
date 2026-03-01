// Password visibility toggle
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
