(function(){
  // Dynamic greeting in Indonesian based on current time
  const title = document.getElementById('greetingTitle');
  const subtitle = document.getElementById('greetingSubtitle');

  function setGreeting(date=new Date()){
    const h = date.getHours();
    let greet='Selamat Datang!';
    let desc='Selesaikan tugasmu dan nikmati sisa harimu.';
    if(h >= 5 && h < 11){
      greet = 'Selamat Pagi!';
      desc = 'Awali harimu dengan semangat dan fokus.';
    } else if(h >= 11 && h < 15){
      greet = 'Selamat Siang!';
      desc = 'Tetap produktif, istirahat sejenak bila perlu.';
    } else if(h >= 15 && h < 18){
      greet = 'Selamat Sore!';
      desc = 'Selesaikan tugasmu dan nikmati sisa harimu.';
    } else {
      greet = 'Selamat Malam!';
      desc = 'Waktunya menutup hari dengan hasil terbaik.';
    }
    if(title) title.textContent = greet;
    if(subtitle) subtitle.textContent = desc;
  }
  setGreeting();

  // Password visibility toggle
  const input = document.getElementById('passwordInput');
  const btn = document.getElementById('togglePassword');
  if(btn && input){
    btn.addEventListener('click', function(){
      const isPwd = input.type === 'password';
      input.type = isPwd ? 'text' : 'password';
      const icon = this.querySelector('i');
      if(icon){
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
      }
    });
  }
})();
