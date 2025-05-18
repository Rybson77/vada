<?php
$action = $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST'&& $_POST['password'] === $_POST['confirmpassword']) {
        $t_name = "movierental.users";
        $login = $_POST['login'] ?? '';
        $email = $_POST['email'] ?? '';
        $name = $_POST['name'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $tel = $_POST['tel'] ?? '';
        $password = $_POST['password'] ?? '';
        //$hash = password_hash($password, PASSWORD_DEFAULT);
        $toHash = $password . PASSWORD_PEPPER;
        $hash   = password_hash($toHash, PASSWORD_DEFAULT);
        

        $stmt = $conn->prepare("INSERT INTO $t_name (email, password_hash, login, name, surname, phone) VALUES (?,?,?,?,?,?)");
        try {
            $stmt->execute([$email, $hash, $login, $name, $surname, $tel]);
            echo "Registrace úspěšná!";
        } catch (PDOException $e) {
            echo "Chyba při registraci: " . $e->getMessage();
        }
    } else {
        require("registration.php");
    }

?>