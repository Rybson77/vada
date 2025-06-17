<?php
// login-backend.php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) CSRF validace
    if (
        empty($_POST['csrf_token'])
        || empty($_SESSION['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die('Neplatný CSRF token.');
    }

    // 2) Načtení vstupů
    $login    = trim($_POST['login']    ?? '');
    $password = $_POST['password']      ?? '';

    // 3) Hledáme uživatele (login NEBO email)
    $stmt = $conn->prepare("
        SELECT id, login, password_hash, role
          FROM users
         WHERE login = :login
            OR email = :email
         LIMIT 1
    ");
    // Musíme dodat oba klíče, i když mají stejnou hodnotu
    $stmt->execute([
        'login' => $login,
        'email' => $login,
    ]);
    $user = $stmt->fetch();

    // 4) Ověření hesla + pepper
    $ok = false;
    if ($user) {
        $raw = $password . PASSWORD_PEPPER;
        if (password_verify($raw, $user['password_hash'])) {
            $ok = true;
        }
    }

    if ($ok) {
        // 5) Uložení do session a přesměrování
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        // 6) Chyba přihlášení
        $error = 'Neplatné jméno nebo heslo.';
        require __DIR__ . '/login.php';
    }

} else {
    // GET → zobrazíme form
    require __DIR__ . '/login.php';
}
