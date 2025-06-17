<?php
$title = "Půjčovna filmů";
if (isset($_GET['action'])){
   switch($_GET['action']){
      case "registration":
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/reg-bckend.php';
         } else {
            require __DIR__ . '/registration.php';
         }
    break;
      case "login":
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require __DIR__ . '/log-bckend.php';
         } else {
            require __DIR__ . '/login.php';
         }
    break;
      case "dashboard":
            require __DIR__ . '/dashboard.php';

    break;
      case "logout":
         //echo("BUDEME SE ODHLASOVAT");
            require __DIR__ .'/logout.php';
            break;
      default:
       include 'site.php';
} 
}else{
   include 'site.php';
}
?>

