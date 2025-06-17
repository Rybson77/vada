<?php
// registration.php
require_once __DIR__ . '/config.php';

// CSRF token jen pokud ještě neexistuje
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$title = "Registrace";
include __DIR__ . '/htmlhead.php';
?>
<link rel="stylesheet" href="./styles/registration.css">
<script src="./scripts/reg.js" defer></script>
<body>
  <div class="container">
    <div class="auth-card">
      <h2>Registrace</h2>
      <form id="registrationForm" method="post" action="index.php?action=registration">
        
        <div class="form-group">
          <label for="login">Login</label>
          <input id="login"
                 name="login"
                 required
                 pattern="^[A-Za-z0-9_]{3,20}$"
                 title="3–20 znaků: písmena, čísla nebo _">
        </div>

        <div class="form-group">
          <label for="name">Jméno</label>
          <input id="name" type="text" name="name" required>
        </div>

        <div class="form-group">
          <label for="surname">Příjmení</label>
          <input id="surname" type="text" name="surname" required>
        </div>

        <div class="form-group">
          <label for="email">E-mail</label>
          <input id="email"
                 type="email"
                 name="email"
                 required
                 title="pepicek@neco.com">
        </div>

        <div class="form-group">
          <label for="tel">Telefon</label>
          <input id="tel"
                 type="tel"
                 name="tel"
                 required
                 pattern="^(?:\+420\s)?\d{3}(?:\s?\d{3}){2}$"
                 title="+420 123 456 789, 123456789 nebo 123 456 789">
        </div>

        <div class="form-group">
          <label for="password">Heslo</label>
          <input id="password" type="password" name="password" required>
          <small class="form-note">Min. 8 znaků, 1 velké písmeno a 1 číslice.</small>
        </div>

        <div class="form-group">
          <label for="confirmPassword">Potvrď heslo</label>
          <input id="confirmPassword" type="password" name="confirmpassword" required>
        </div>

        <input type="hidden"
               name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Registrovat se</button>
        </div>

        <div class="form-note">
          Už máte účet? <a href="index.php?action=login">Přihlaste se</a>
        </div>

      </form>
    </div>
  </div>

<?php include __DIR__ . '/htmlfooter.php'; ?>
</body>
</html>
