<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 1) Zjistíme, kterou výpůjčku platíme
    $rentalId = (int)($_GET['rental_id'] ?? 0);

    // 2) Načteme původní odhad poplatku
    $stmt = $conn->prepare("SELECT rental_fee FROM rentals WHERE id = ?");
    $stmt->execute([$rentalId]);
    $rental = $stmt->fetch(PDO::FETCH_ASSOC);
    $fee   = $rental ? (float)$rental['rental_fee'] : 0.0;

    // 3) Spočítáme, kolik už uživatel zaplatil
    $stmt2 = $conn->prepare(
      "SELECT COALESCE(SUM(amount),0) AS paid 
         FROM payments 
        WHERE rental_id = ?"
    );
    $stmt2->execute([$rentalId]);
    $paid = (float)$stmt2->fetchColumn();

    // 4) Zbývá doplatit:
    $due = max(0, $fee - $paid);

    // 5) Vykreslíme formulář
    $title = 'Platba výpůjčky';
    include __DIR__ . '/htmlhead.php';
    echo '<link rel="stylesheet" href="./styles/auth.css">';
    echo '<div class="container auth-page"><div class="auth-card">';
    echo "<h2>{$title} #{$rentalId}</h2>";

    if ($due <= 0) {
        echo "<p>Částka již byla plně uhrazena.</p>";
    } else {
        echo "<p>Částka k úhradě: <strong>{$due} Kč</strong></p>";
        echo '<form method="post" action="pay-bckend.php">';
        echo '<input type="hidden" name="csrf_token" value="' 
             . htmlspecialchars($_SESSION['csrf_token']) . '">';
        echo "<input type='hidden' name='rental_id' value='{$rentalId}'>";
        echo '<div class="form-group">
                <label for="amount">Částka</label>
                <input id="amount" name="amount" type="number" step="0.01" '
             . "value='{$due}' required>
              </div>";
        echo '<div class="form-group">
                <label for="method">Způsob platby</label>
                <select id="method" name="method">
                  <option value="hotově">Hotově</option>
                  <option value="kartou">Kartou</option>
                  <option value="paypal">PayPal</option>
                </select>
              </div>';
        echo '<div class="form-actions">
                <button class="btn btn-primary" type="submit">Zaplatit</button>
              </div>';
        echo '</form>';
    }

    echo '</div></div>';
    include __DIR__ . '/htmlfooter.php';
    exit;
}

// POST = odeslání platby
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF kontrola
    if (
      empty($_POST['csrf_token'])
      || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        $error='Neplatný CSRF token.';
        exit;
    }

    $rentalId = (int)($_POST['rental_id'] ?? 0);
    $amount   = (float)($_POST['amount']     ?? 0);
    $method   = $_POST['method']     ?? 'hotově';

    // Uložíme platbu
    $stmt = $conn->prepare("
      INSERT INTO payments (amount, method, payment_date, rental_id)
      VALUES (?, ?, NOW(), ?)
    ");
    $stmt->execute([$amount, $method, $rentalId]);

    // Po zaplacení zpět na dashboard
    header('Location: index.php?action=dashboard');
    exit;
}
