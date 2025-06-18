<?php
// dashboard.php – přehled profilu a výpůjček přihlášeného uživatele
require_once __DIR__ . '/config.php';


// Ochrana stránky: pouze přihlášení uživatelé
if (empty($_SESSION['user_id'])) {
    header('Location: index.php?action=login');
    exit;
}


$userId = (int) $_SESSION['user_id'];
$userRole = $_SESSION['role'] ?? 'user';

// Načtení údajů o uživateli
$stmt = $conn->prepare(
    "SELECT login, email, name, surname, phone, created_at
     FROM users
     WHERE id = ?"
);
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Načteme výpůjčky – pokud je admin, bez WHERE, jinak jen svoje
if ($userRole === 'admin') {
    $stmt2 = $conn->prepare(
        "SELECT r.id, r.rental_date, r.due_date, r.return_date,
                r.status, r.rental_fee, m.title, c.copy_number,
                u.login AS user_login
           FROM rentals r
           JOIN copies c   ON r.copy_id   = c.id
           JOIN movies m   ON c.movie_id  = m.id
           JOIN users u    ON r.user_id    = u.id
          ORDER BY r.rental_date DESC"
    );
    $stmt2->execute();
} else {
    $stmt2 = $conn->prepare(
        "SELECT r.id, r.rental_date, r.due_date, r.return_date,
                r.status, r.rental_fee, m.title, c.copy_number
           FROM rentals r
           JOIN copies c   ON r.copy_id   = c.id
           JOIN movies m   ON c.movie_id  = m.id
          WHERE r.user_id = ?
          ORDER BY r.rental_date DESC"
    );
    $stmt2->execute([$userId]);
}

$rentals = $stmt2->fetchAll();

?>