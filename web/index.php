<?php
// index.php – front controller

// 1) Připojíme DB + session + CSRF
require_once __DIR__ . '/config.php';

// 2) Titulek pro šablony (můžeš ho změnit v jednotlivých view)
$title = 'Půjčovna filmů';

// 3) Zjistíme požadovanou akci a metodu
$action = $_GET['action'] ?? 'site';
$method = $_SERVER['REQUEST_METHOD'];

// 4) Dispatch podle action + method
switch ($action) {

  case 'registration':
    if ($method === 'POST') {
      require __DIR__ . '/reg-bckend.php';
    } else {
      require __DIR__ . '/registration.php';
    }
    break;

  case 'login':
    if ($method === 'POST') {
      require __DIR__ . '/log-bckend.php';
    } else {
      require __DIR__ . '/login.php';
    }
    break;

  case 'logout':
    require __DIR__ . '/logout.php';
    break;

  case 'dashboard':
    require __DIR__ . '/dashboard.php';
    break;

  case 'movie':
    require __DIR__ . '/movie.php';
    break;

  case 'rent':
    if ($method === 'POST') {
      require __DIR__ . '/rent-bckend.php';
    } else {
      // GET na rent prostě přesměruj zpět na přehled
      header('Location: index.php?action=dashboard');
      exit;
    }
    break;

  case 'return':
    if ($method === 'POST') {
      require __DIR__ . '/return-bckend.php';
    } else {
      header('Location: index.php?action=dashboard');
      exit;
    }
    break;

  case 'site':
  default:
    require __DIR__ . '/site.php';
    break;
}
