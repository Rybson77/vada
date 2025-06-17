<?php
require __DIR__ . '/dashboard-bckend.php';
require __DIR__ . '/htmlhead.php';
?>
<link rel="stylesheet" href="./styles/dashboard.css">
<div class="container">
  <div class="profile">
  <h2>Profil uživatele</h2>
  <div class="profile-card">
    <p><strong>Login:</strong> <?= htmlspecialchars($user['login']) ?></p>
    <p><strong>E-mail:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Jméno:</strong> <?= htmlspecialchars($user['name']) ?> <?= htmlspecialchars($user['surname']) ?></p>
    <p><strong>Telefon:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    <p><strong>Registrován:</strong> <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></p>
  </div>

  <h3>Moje výpůjčky</h3>
  <?php if (empty($rentals)): ?>
    <p>Nemáte žádné výpůjčky.</p>
  <?php else:?>
    <table class="rentals-table">
      <thead>
        <tr>
          <th>ID zapujčení</th>
          <th>Film</th>
          <th>Kopie filmu</th>
          <th>Datum výpůjčky</th>
          <th>Do kdy</th>
          <th>Vraceno</th>
          <th>Cena</th>
          <th>Stav</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rentals as $r): ?>
        <?php 
          $cls = str_replace([' ', 'í', 'š', 'á', 'é', 'ů'], ['-','i','s','a','e','u'], mb_strtolower($r['status'], 'UTF-8'));
        ?>
        <tr class="status-<?= $cls ?>">
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['title']) ?></td>
          <td><?= htmlspecialchars($r['copy_number']) ?></td>
          <td><?= date('d.m.Y', strtotime($r['rental_date'])) ?></td>
          <td><?= date('d.m.Y', strtotime($r['due_date'])) ?></td>
          <td><?= $r['return_date'] ? date('d.m.Y', strtotime($r['return_date'])) : '-' ?></td>
          <td><?= htmlspecialchars($r['rental_fee']) ?> Kč</td>
          <td><?= htmlspecialchars($r['status']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</div>

<?php require __DIR__ . '/htmlfooter.php'; ?>