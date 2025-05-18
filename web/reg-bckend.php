<?php
require_once __DIR__ . '/config.php';
// 1) CSRF check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Neplatný CSRF token.");
    }
}

// 2) Základní validace
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)
    or die("Neplatný email.");
$login = trim($_POST['login'] ?? '');
preg_match('/^[a-zA-Z0-9_]{3,20}$/', $login)
    or die("Login musí být 3–20 znaků: písmena, čísla nebo _.");
// … ověření jména, příjmení …
$tel = trim($_POST['tel'] ?? '');
preg_match('/^(?:\+420\s)?\d{3}(?:\s?\d{3}){2}$/', $tel)
    or die("Telefon ve formátu +420 123 456 789 nebo 123456789.");

// 3) Heslo a síla
$password = $_POST['password'] ?? '';
$_POST['confirmpassword'] === $password
    or die("Hesla se neshodují.");
strlen($password) >= 8 or die("Min. délka hesla 8 znaků.");
preg_match('/[A-Z]/', $password) or die("Heslo musí obsahovat velké písmeno.");
preg_match('/\d/', $password)   or die("Heslo musí obsahovat číslici.");

// 4) Duplicitní uživatel…
$stmt = $conn->prepare(
  "SELECT COUNT(*) FROM movierental.users WHERE email=? OR login=?"
);
$stmt->execute([$email, $login]);
if ($stmt->fetchColumn() > 0) {
    require_once("login.php");
    die("Email nebo login již existuje.");
}

// 5) Hash + insert
$toHash = $password . PASSWORD_PEPPER;
$hash   = password_hash($toHash, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
  "INSERT INTO movierental.users
    (email,password_hash,login,name,surname,phone)
   VALUES (?,?,?,?,?,?)"
);
try {
    $stmt->execute([$email, $hash, $login,
                    $_POST['name'], $_POST['surname'], $tel]);
    echo "Registrace úspěšná!";
} catch (PDOException $e) {
    // v produkci raději loguj do souboru než echo
    echo "Chyba při registraci.";
}
