document.addEventListener("DOMContentLoaded", function () {

    const langToggle = document.getElementById('lang-toggle');
    const langDropdown = document.getElementById('lang-dropdown');
  

    const userToggle = document.getElementById('user-toggle');
    const userDropdown = document.getElementById('user-dropdown');
  
 
    function closeAllMenus() {
      langDropdown.style.display = 'none';
      userDropdown.style.display = 'none';
    }
  

    langToggle.addEventListener('click', function (e) {
      e.stopPropagation();

      if (userDropdown.style.display === 'block') {
        userDropdown.style.display = 'none';
      }
      langDropdown.style.display =
        langDropdown.style.display === 'block' ? 'none' : 'block';
    });
  

    userToggle.addEventListener('click', function (e) {
      e.stopPropagation();

      if (langDropdown.style.display === 'block') {
        langDropdown.style.display = 'none';
      }
      userDropdown.style.display =
        userDropdown.style.display === 'block' ? 'none' : 'block';
    });
  

    document.addEventListener('click', function (e) {
      if (
        !langDropdown.contains(e.target) &&
        e.target.id !== 'lang-toggle' &&
        !userDropdown.contains(e.target) &&
        e.target.id !== 'user-toggle'
      ) {
        closeAllMenus();
      }
    });

    const toggleBtn = document.getElementById('navbar-toggle');
    const navWrapper = document.getElementById('nav-wrapper');
  
    toggleBtn.addEventListener('click', () => {
      navWrapper.classList.toggle('show');
    });
  
    document.addEventListener('click', (e) => {
      if (!toggleBtn.contains(e.target) && !navWrapper.contains(e.target)) {
        navWrapper.classList.remove('show');
      }
    });
  });
  