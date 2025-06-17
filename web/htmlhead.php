<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="./styles/header.css">
   <title>
      <?php echo $title;?>
   </title>
</head>
<body>
  <!-- HEADER -->
  <header class="site-header">
    <div class="container header-inner">
      <a href="index.php" class="logo">🎬 Moje Půjčovna Filmů</a>
      <div class="auth-links">
        <?php if (empty($_SESSION['user_id'])): ?>
          <a href="index.php?action=login" class="btn">Přihlášení</a>
          <a href="index.php?action=registration" class="btn btn-primary">Registrace</a>
        <?php else: ?>
          <span class="user-name">Vítejte, <?= htmlspecialchars($_SESSION['user_login']) ?></span>
          <a href="index.php?action=dashboard" class="btn">Profil</a>
          <a href="index.php?action=logout" class="btn">Odhlásit se</a>
        <?php endif; ?>
      </div>
    </div>
  </header>
   
