<?php
require_once __DIR__ . '/config.php';
// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$title = "Přihlášení";
require __DIR__ . '/htmlhead.php';
?>
<link rel="stylesheet" href="./styles/login.css">
  <div class="container">
   <div class="auth-card">
      <h2>Přihlášení</h2>
      <form method="post" id="loginForm" action="index.php?action=login" class="form-card">
          <input type="hidden" name="csrf_token"
         value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      <div class="form-group">
        <label for="login">Uživatelské jméno</label>
        <input type="text" id="login" name="login" placeholder="Uživatelské jméno" required>
      </div>
      <div class="form-group">
        <label for="password">Heslo</label>
        <input type="password" id="password" name="password" placeholder="Heslo" required>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Přihlásit se</button>
      </div>
      <?php if (!empty($error)):?>
          <p class="form-error" style="color: #fb6161;margin-top: 1rem;font-size: .9rem;"> <?= htmlspecialchars($error) ?> </p>
      <?php endif; ?>
      <p class="form-note">Ještě nemáte účet? <a href="index.php?action=registration">Registrace</a></p>
    </form>
  </div>
</div>
<?php require __DIR__ . '/htmlfooter.php';
