<?php
// dashboard.php
require_once __DIR__ . '/dashboard-bckend.php';  // natáhne $user a $rentals
$title = 'Můj profil';
require __DIR__ . '/htmlhead.php';
?>
<link rel="stylesheet" href="./styles/dashboard.css">

<div class="container">
  <!-- Profilová karta -->
  <h2>Profil uživatele</h2>
  <div class="profile-card">
    <p><strong>Login:</strong> <?= htmlspecialchars($user['login']) ?></p>
    <p><strong>E-mail:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p>
      <strong>Jméno:</strong>
      <?= htmlspecialchars($user['name']) ?>
      <?= htmlspecialchars($user['surname']) ?>
    </p>
    <p><strong>Telefon:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    <p>
      <strong>Registrován:</strong>
      <?= date('d.m.Y H:i', strtotime($user['created_at'])) ?>
    </p>
  </div>

  <!-- Výpůjčky -->
  <h3>Moje výpůjčky</h3>

  <?php if (empty($rentals)): ?>
    <p>Nemáte žádné výpůjčky.</p>
  <?php else: ?>
    <table class="rentals-table">
      <thead>
        <tr>
          <th>ID zápůjčky</th>
      <?php if ($userRole==='admin'): ?>
        <th>Uživatel</th>
      <?php endif; ?>
          <th>Film</th>
          <th>Kopie filmu</th>
          <th>Datum výpůjčky</th>
          <th>Do kdy</th>
          <th>Vraceno</th>
          <th>Stav</th>
          <th>Uhraz. / Celkem</th>
          <th>Akce</th>
          <th>Vrácení</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rentals as $r): 
          // Spočítáme uhrazeno vs celkem
          $stmtP = $conn->prepare(
            "SELECT COALESCE(SUM(amount),0) FROM payments WHERE rental_id = ?"
          );
          $stmtP->execute([$r['id']]);
          $paid = (float)$stmtP->fetchColumn();
          $due  = max(0, $r['rental_fee'] - $paid);

          // CSS třída podle stavu (bez diakritiky)
          $cls = str_replace(
            [' ', 'í','š','á','é','ů'],
            ['-','i','s','a','e','u'],
            mb_strtolower($r['status'], 'UTF-8')
          );
        ?>
        <tr class="status-<?= htmlspecialchars($cls) ?>">
        <td><?= $r['id'] ?></td>
        <?php if ($userRole==='admin'): ?>
        <td><?= htmlspecialchars($r['user_login']) ?></td>
        <?php endif; ?>
          <td><?= htmlspecialchars($r['title']) ?></td>
          <td><?= htmlspecialchars($r['copy_number']) ?></td>
          <td><?= date('d.m.Y', strtotime($r['rental_date'])) ?></td>
          <td><?= date('d.m.Y', strtotime($r['due_date'])) ?></td>
          <td>
            <?= $r['return_date']
                 ? date('d.m.Y', strtotime($r['return_date']))
                 : '-' 
            ?>
          </td>
          <td class="status"><?= htmlspecialchars($r['status']) ?></td>
          <td>
            <?= $paid ?> Kč / <?= htmlspecialchars($r['rental_fee']) ?> Kč
          </td>
          <td>
            <?php if ($due > 0 && $r['status'] === 'probíhá'): ?>
              <a
                href="index.php?action=pay&rental_id=<?= $r['id'] ?>"
                class="btn btn-primary btn-sm">
                Zaplatit <?= $due ?> Kč
              </a>
            <?php else: ?>
              <span class="badge badge-success">Zaplaceno</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($r['status'] === 'probíhá'): ?>
              <form
                method="post"
                action="index.php?action=return"
                style="display:inline">
                <input
                  type="hidden"
                  name="csrf_token"
                  value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input
                  type="hidden"
                  name="rental_id"
                  value="<?= $r['id'] ?>">
                <button
                  type="submit"
                  class="btn btn-danger btn-sm">
                  Vrátit
                </button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/htmlfooter.php'; ?>
