<?php
require_once __DIR__ . '/config.php'; 
if (empty($_SESSION['csrf_token'])) {
    // 32 B náhodných bytů → hex string 64 znaků
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
include("htmlhead.php");
$title = "Registration";
?>
  <form method="post" id="registrationForm" action="index.php?action=registration">
  <!-- Login: jen písmena/čísla/_ a 3–20 znaků -->
  <label for="login">Login:</label>
  <input id="login" name="login" required
         pattern="^[A-Za-z0-9_]{3,20}$"
         title="Login: 3–20 znaků, písmena, čísla nebo _">
  <br>
  <label for="name">Jméno:</label>
  <input id="name" type="text" name="name" required>
  <br>
  
  <label for="surname">Přijmení:</label>
  <input id="surname" type="text" name="surname" required>
  <br>

  <!-- Email: HTML5 -->
  <label for="email">E-mail:</label>
  <input id="email" type="email" name="email" required
         title="pepicek@neco.com">
  <br>

  <!-- Telefon: už máš -->
  <label for="tel">Tel:</label>
  <input id="tel" type="tel" name="tel" required
         pattern="^(?:\+420\s)?\d{3}(?:\s?\d{3}){2}$"
         title="+420 123 456 789, 123456789 nebo 123 456 789">
  <br>

  <!-- Heslo -->
  <label for="password">Heslo:</label>
  <input id="password" type="password" name="password" required>
  <div id="passwordHelp" style="font-size:.9em;color:#666">
    Heslo musí mít min. 8 znaků, 1 velké písmeno a 1 číslici.
  </div>
  <br>

  <!-- Potvrzení hesla -->
  <label for="confirmPassword">Potvrď heslo:</label>
  <input id="confirmPassword" type="password" name="confirmpassword" required>
  <br>

  <!-- CSRF -->
  <input type="hidden" name="csrf_token"
         value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
  <input type="submit" value="Register">
</form>

<script>
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
</script>

<?php
include("htmlfooter.php");
print_r($_POST);
?>