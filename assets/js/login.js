(function () {
  // Dynamic greeting in Indonesian based on current time
  // Dynamic theme and greeting based on time
  const title = document.getElementById('greetingTitle');
  const subtitle = document.getElementById('greetingSubtitle');
  const wrapper = document.querySelector('.login-bg');

  function setDynamicTheme() {
    const hour = new Date().getHours();
    let themeClass = '';
    let greet = '';
    let desc = '';

    // Pagi: 05:00 - 10:59
    if (hour >= 5 && hour < 11) {
      themeClass = 'theme-pagi';
      greet = 'Selamat Pagi!';
      desc = 'Awali harimu dengan semangat baru.';
    }
    // Siang: 11:00 - 14:59
    else if (hour >= 11 && hour < 15) {
      themeClass = 'theme-siang';
      greet = 'Selamat Siang!';
      desc = 'Tetap semangat menjalani aktivitas.';
    }
    // Sore: 15:00 - 18:59
    else if (hour >= 15 && hour < 19) {
      themeClass = 'theme-sore';
      greet = 'Selamat Sore!';
      desc = 'Semoga harimu menyenangkan.';
    }
    // Malam: 19:00 - 04:59
    else {
      themeClass = 'theme-malam';
      greet = 'Selamat Malam!';
      desc = 'Istirahatlah, hari esok menanti.';
    }

    // Apply theme (keeping login-bg)
    if (wrapper) {
      // Remove old theme classes first to be safe, though overwriting className works if we manage it fully
      wrapper.className = 'login-bg ' + themeClass;
    }

    // Set text
    if (title) title.textContent = greet;
    if (subtitle) subtitle.textContent = desc;
  }

  setDynamicTheme();

  // Password visibility toggle
  const input = document.getElementById('passwordInput');
  const btn = document.getElementById('togglePassword');
  if (btn && input) {
    btn.addEventListener('click', function () {
      const isPwd = input.type === 'password';
      input.type = isPwd ? 'text' : 'password';
      const icon = this.querySelector('i');
      if (icon) {
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
      }
    });
  }
})();
a