<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./stylesheets/template.css">
    <title>Forum</title>
</head>
<body>


    <?php
    
    $file = file_get_contents('database_config.json');
    $data = json_decode($file, True);
    
    //checking if user is already logged in
    if($_SESSION["user_id"] != -1 && $_SESSION["username"] != "guest") {
        echo 'Du bist bereits angemeldet. <br> Möchtest du zurück zur Startseite gehen? <br> <button><a href="index.php">Zurück zur Startseite</a></button>';
    }
    else {  //form to submit user credentials 
        echo '<form action=' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' method="post">
        <input name="username" placeholder="Benutzername"><br>
        <input name="email" placeholder="E-Mail"><br>
        <input type="password" name="password" placeholder="Passwort"><br>
        <input type="password" name="password_check" placeholder="Passwort wiederholen"><br>
        <input type="submit" value="Registrieren">
        </form>
        Du hast bereits einen Account? Hier geht es zum <a href="login.php">Login.</a>'
        ;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //array to count the errors in the form input
        $errors = [];

        //preparing variables with the information from the form 
        $username = htmlspecialchars(stripslashes(trim($_POST["username"])));
        $email = htmlspecialchars(stripslashes(trim($_POST["email"])));
        $password = htmlspecialchars(stripslashes(trim($_POST["password"])));        
        $password_check = htmlspecialchars(stripslashes(trim($_POST["password_check"])));

        //checking the form input and adding errors to "$errors"
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

        //hash password and add user to database if the "$errors" array is empty, else display errors 
        if (sizeof($errors) == 0) {
            $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]);

            if ($con -> connect_error) {
                die("Ein Verbindungsfehler ist aufgetreten");
            }
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            if ($password_hashed) {
                $sql = "INSERT INTO `users` (`username`, `email`, `pwd_hash`) VALUES ('$username', '$email', '$password_hashed')";
                $res = $con -> query($sql);
                die("<br>Registrierung erfolgreich, weiter zum Login: <br> <a href=login.php><button>Login</button>");
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
