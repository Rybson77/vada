<?php
$title = "Půjčovna filmů";
include("htmlhead.php");
if (isset($_GET['action'])){
   switch($_GET['action']){
      case "registration":
         include 'reg-bckend.php';
         //echo("BUDEME SE REGISTROVAT");
         break;
      case "login":
         //echo("BUDEME SE LOGINOVAT");
         include 'log-bckend.php';
         break;
      case "logout":
         //echo("BUDEME SE ODHLASOVAT");
            include 'logout.php';
            break;
      default:
       include 'site.php';
} 
}else{
   include 'site.php';
}
include("htmlfooter.php");
?>

