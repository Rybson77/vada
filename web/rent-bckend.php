<?php
// rent-backend.php – zpracování půjčení filmu
require_once __DIR__ . '/config.php';

// 1) Ověření metody a CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])
    || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
   print_r($_SESSION);
   print_r($_POST);
    die('Neplatný CSRF token.');
}

// 2) Kontrola přihlášení
if (empty($_SESSION['user_id'])) {
    header('Location: index.php?action=login'); exit;
}
$userId  = (int) $_SESSION['user_id'];
$copyId  = isset($_POST['copy_id'])  ? (int) $_POST['copy_id']  : 0;
$dueDate = $_POST['due_date'] ?? '';

// 3) Načtení sazby a kontrola dostupnosti kopie
$stmt = $conn->prepare(
    "SELECT c.status, m.rental_rate
     FROM copies c
     JOIN movies m ON c.movie_id = m.id
     WHERE c.id = ?"
);
$stmt->execute([$copyId]);
$info = $stmt->fetch();
if (!$info) {
    die('Kopie nenalezena.');
}
if ($info['status'] !== 'dostupné') {
    die('Vybraná kopie není dostupná.');
}

// 4) Výpočet ceny
$today = new DateTime();
try {
    $d = new DateTime($dueDate);
} catch (Exception $e) {
    die('Neplatné datum vrácení.');
}
$interval = $today->diff($d);
$days = (int) $interval->format('%a');
if ($days < 1) {
    die('Datum vrácení musí být alespoň zítra.');
}
$rentalFee = $info['rental_rate'] * $days;

// 5) Transakce: update kopie + insert rental
try {
    $conn->beginTransaction();

    // 5a) Rezervace kopie
    $upd = $conn->prepare(
        "UPDATE copies SET status = 'zapůjčené' WHERE id = ? AND status = 'dostupné'"
    );
    $upd->execute([$copyId]);
    if ($upd->rowCount() !== 1) {
        throw new Exception('Kopie již není k dispozici.');
    }

    // 5b) Vložení výpůjčky
    $ins = $conn->prepare(
        "INSERT INTO rentals (user_id, copy_id, rental_date, due_date, rental_fee, status)
         VALUES (?, ?, NOW(), ?, ?, 'probíhá')"
    );
    $ins->execute([$userId, $copyId, $dueDate . ' 23:59:59', $rentalFee]);

    $conn->commit();

    // 6) Přesměrování na dashboard
    header('Location: index.php?action=dashboard'); exit;

} catch (Exception $e) {
    $conn->rollBack();
    die('Chyba při zápůjčce: ' . $e->getMessage());
}
