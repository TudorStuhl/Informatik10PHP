<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input name="username" placeholder="Benutzername"><br>
        <input name="email" placeholder="E-Mail"><br>
        <input type="password" name="password" placeholder="Passwort"><br>
        <input type="password" name="password_check" placeholder="Passwort wiederholen"><br>
        <input type="submit" value="Registrieren">
    </form>

    <?php
    
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
            echo "yaaay";
        } else {
            for ($error = 0; $error < sizeof($errors); $error++) { 
                echo "<br>" . $errors[$error];
            }
        }
    }

    ?>
</body>
</html>