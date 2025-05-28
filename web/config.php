<?php
// config.php

declare(strict_types=1);
date_default_timezone_set('Europe/Prague');

// 1) Nastavení přístupu k databázi
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'movierental');
define('DB_USER', 'root');
define('DB_PASS', '');

// 2) Pepper pro hesla
//    – ulož někde mimo veřejný repozitář, např. v .env, tady jen příklad
define('PASSWORD_PEPPER', 'F3ar_0f_7he_d4rk');

// 3) Připojení přes PDO
try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8',
        DB_HOST, DB_PORT, DB_NAME
    );
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // v produkci raději loguj do souboru a zobraz generickou chybu
    die('Nepodařilo se připojit k databázi.');
}

// 4) Spuštění session (pro CSRF token, flash zprávy…)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 5) Vygeneruj CSRF token, pokud ještě neexistuje


