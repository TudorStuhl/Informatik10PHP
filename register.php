<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
</head>
<body>


    <?php
    
    $host = "localhost";
    $user = "root";
    $pw = "";
    $db = "forum";

    if($_SESSION["user_id"] != -1 && $_SESSION["username"] != "guest") {
        echo 'Du bist bereits angemeldet. <br> Möchtest du zurück zur Startseite gehen? <br> <button><a href="index.php">Zurück zur Startseite</a></button>';
    }
    else {
        echo '<form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post">
        <input name="username" placeholder="Benutzername"><br>
        <input name="email" placeholder="E-Mail"><br>
        <input type="password" name="password" placeholder="Passwort"><br>
        <input type="password" name="password_check" placeholder="Passwort wiederholen"><br>
        <input type="submit" value="Registrieren">
        </form>';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];

        $username = htmlspecialchars(stripslashes(trim($_POST["username"])));
        $email = htmlspecialchars(stripslashes(trim($_POST["email"])));
        $password = htmlspecialchars(stripslashes(trim($_POST["password"])));
        $password_check = htmlspecialchars(stripslashes(trim($_POST["password_check"])));

        if (empty($username)) {
            $errors[] = "Ein Benutzername ist erforderlich";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Eine gültige E-Mail ist erforderlich" . filter_var($email, FILTER_VALIDATE_EMAIL);
        }
        if (empty($password)) {
            $errors[] = "Ein Passwort ist erforderlich";
        }
        if (strlen($username) > 255) {
            $errors[] = "Der Benutzername ist zu lang";
        }
        if (strlen($email) > 255) {
            $errors[] = "Die Mailadresse ist zu lang";
        }
        if (strlen($password) > 255) {
            $errors[] = "Das Passwort ist zu lang";
        }
        if (strlen($password) < 8) {
            $errors[] = "Das Passwort muss mindestens acht Zeichen lang sein";
        }
        if ($password != $password_check) {
            $errors[] = "Die beiden Passwörter stimen nicht überein";
        }

        if (sizeof($errors) == 0) {
            $con = new mysqli($host, $user, $pw, $db);

            if ($con -> connect_error) {
                die();
                echo "Ein Verbindungsfehler ist aufgetreten";
            }
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            if ($password_hashed) {
                $sql = "INSERT INTO `users` (`username`, `email`, `pwd_hash`) VALUES ('$username', '$email', '$password_hashed')";
                $res = $con -> query($sql);
                die("Registrierung erfolgreich, weiter zum Login: <br> <a href=login.php><button>Login</button>");
            } else {
                echo "Hash fehlgeschlagen";
            }

            $con -> close();
        } else {
            for ($error = 0; $error < sizeof($errors); $error++) { 
                echo "<br>" . $errors[$error];
            }
        }
    }}

    ?>
</body>
</html>