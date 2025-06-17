
(function(){
  const form = document.getElementById('registrationForm');
  const login = document.getElementById('login');
  const pwd   = document.getElementById('password');
  const pwd2  = document.getElementById('confirmPassword');
  const phone = document.getElementById('tel');

  // vzory
  const loginRx = /^[A-Za-z0-9_]{3,20}$/;
  const pwdRx   = /^(?=.{8,}$)(?=.*[A-Z])(?=.*\d).+$/;
  const phoneRxs = [
    /^\+420\s\d{3}\s\d{3}\s\d{3}$/,
    /^\d{9}$/,
    /^\d{3}\s\d{3}\s\d{3}$/
  ];

  function validateLogin(){
    if (!loginRx.test(login.value)) {
      login.setCustomValidity(
        'Login: 3–20 znaků, písmena, čísla nebo _'
      );
    } else {
      login.setCustomValidity('');
    }
  }

  function validatePassword(){
    if (!pwdRx.test(pwd.value)) {
      pwd.setCustomValidity(
        'Heslo musí mít min. 8 znaků, 1 velké písmeno a 1 číslici.'
      );
    } else {
      pwd.setCustomValidity('');
    }
  }

  function validateConfirm(){
    if (pwd2.value !== pwd.value) {
      pwd2.setCustomValidity('Hesla se neshodují');
    } else {
      pwd2.setCustomValidity('');
    }
  }

  function validatePhone(){
    const v = phone.value.trim();
    const ok = phoneRxs.some(rx => rx.test(v));
    phone.setCustomValidity(
      ok ? '' :
      'Telefon: +420 123 456 789, 123456789 nebo 123 456 789'
    );
  }

  // eventy
  login.addEventListener('input', validateLogin);
  pwd  .addEventListener('input', validatePassword);
  pwd2 .addEventListener('input', validateConfirm);
  phone.addEventListener('input', validatePhone);

  form.addEventListener('submit', e => {
    // vždy znovu validovat všechna pole
    validateLogin();
    validatePassword();
    validateConfirm();
    validatePhone();
    if (!form.checkValidity()) {
      form.reportValidity();
      e.preventDefault();
    }
  });
})();