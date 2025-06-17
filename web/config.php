<?php


declare(strict_types=1);
date_default_timezone_set('Europe/Prague');

define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'movierental');
define('DB_USER', 'root');
define('DB_PASS', '');


define('PASSWORD_PEPPER', 'F3ar_0f_7he_d4rk');


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
    die('Nepodařilo se připojit k databázi.');
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



