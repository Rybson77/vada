<?php
require_once __DIR__ . 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?action=dashboard');
    exit;
}

// CSRF
if (
  empty($_POST['csrf_token'])
  || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die('Neplatný CSRF token.');
}

$rentalId = (int)($_POST['rental_id'] ?? 0);

// 1) Načteme detaily výpůjčky
$stmt = $conn->prepare("
  SELECT r.rental_date, r.rental_fee, c.movie_id, m.rental_rate
    FROM rentals r
    JOIN copies c  ON r.copy_id  = c.id
    JOIN movies m  ON c.movie_id = m.id
   WHERE r.id = ?
");
$stmt->execute([$rentalId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$data) {
    die('Výpůjčka nenalezena.');
}

// 2) Spočteme skutečnou částku podle dní
$rentedAt = new DateTime($data['rental_date']);
$now      = new DateTime('now');
$daysUsed = max(1, $rentedAt->diff($now)->days);
$actualFee = $daysUsed * (float)$data['rental_rate'];

// 3) Zjistíme, co už bylo zaplaceno
$stmt2 = $conn->prepare(
  "SELECT COALESCE(SUM(amount),0) FROM payments WHERE rental_id = ?"
);
$stmt2->execute([$rentalId]);
$paid = (float)$stmt2->fetchColumn();

// 4) Pokud musí doplatit, pošleme ho na pay-backend
if ($paid < $actualFee) {
    $due = $actualFee - $paid;
    header("Location: pay-backend.php?rental_id={$rentalId}&due={$due}");
    exit;
}

// 5) Upravíme záznam ve `rentals` a uvolníme kopii
$lateFee = 0; // případně logika pro poplatek za zpoždění

$conn->beginTransaction();
$conn->prepare("
  UPDATE rentals
     SET return_date = NOW(),
         rental_fee  = ?,
         late_fee    = ?,
         status      = 'vráceno'
   WHERE id = ?
")->execute([$actualFee, $lateFee, $rentalId]);

$conn->prepare("
  UPDATE copies
     SET status = 'dostupné'
   WHERE id = (
     SELECT copy_id FROM rentals WHERE id = ?
   )
")->execute([$rentalId]);

$conn->commit();

header('Location: index.php?action=dashboard');
exit;
