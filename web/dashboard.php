<?php
// dashboard.php – přehled profilu a výpůjček přihlášeného uživatele
require_once __DIR__ . '/config.php';

// Ochrana stránky: pouze přihlášení uživatelé
if (empty($_SESSION['user_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$userId = (int) $_SESSION['user_id'];

// Načtení údajů o uživateli
$stmt = $conn->prepare(
    "SELECT login, email, name, surname, phone, created_at
     FROM users
     WHERE id = ?"
);
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Načtení výpůjček uživatele - spojení skrz copies -> movies
$stmt2 = $conn->prepare(
    "SELECT r.id, r.rental_date, r.due_date, r.return_date, r.status, m.title
     FROM rentals r
     JOIN copies c ON r.copy_id = c.id
     JOIN movies m ON c.movie_id = m.id
     WHERE r.user_id = ?
     ORDER BY r.rental_date DESC"
);
$stmt2->execute([$userId]);
$rentals = $stmt2->fetchAll();

require __DIR__ . '/htmlhead.php';
?>

<div class="container">
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
  <?php else: ?>
    <table class="rentals-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Film</th>
          <th>Datum výpůjčky</th>
          <th>Do kdy</th>
          <th>Vraceno</th>
          <th>Stav</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rentals as $r): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['title']) ?></td>
          <td><?= date('d.m.Y', strtotime($r['rental_date'])) ?></td>
          <td><?= date('d.m.Y', strtotime($r['due_date'])) ?></td>
          <td><?= $r['return_date'] ? date('d.m.Y', strtotime($r['return_date'])) : '-' ?></td>
          <td><?= htmlspecialchars($r['status']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/htmlfooter.php';
