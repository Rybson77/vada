<?php
$title = "Přihlášení";
require("htmlhead.php");
?>
<h2>Přihlášení</h2>
<form method="post" id="login" action="index.php?action=login">
<input type="text" id="login" name="login" placeholder="Uživatelské jméno" required><br>
<input type="password" id="password" name="password" placeholder="Heslo" required><br>
<input type="submit" value="Login">
</form>
<?php
print_r($_POST);
require("htmlfooter.php");
?>