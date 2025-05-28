<?php
// logout.php
require_once __DIR__ . '/config.php';

// 1) Ujistíme se, že session běží
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Vymažeme všechna data v session
$_SESSION = [];

// 3) Smažeme session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params['path'], 
        $params['domain'], 
        $params['secure'], 
        $params['httponly']
    );
}

// 4) Ukončíme session
session_destroy();

// 5) (Volitelně) smažeme CSRF token cookie, pokud jsi ho ukládal jako cookie
setcookie('csrf_token', '', time() - 42000, '/');

// 6) Přesměrujeme uživatele na přihlašovací stránku nebo domovskou
header('Location: index.php');
exit;
