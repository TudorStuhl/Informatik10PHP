<?php
session_start();
$_SESSION["username"] = "guest";
$_SESSION["user_id"] = -1;
$_SESSION["email"] = Null;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="stylesheets/index.css">
</head>
    <body>
        <a href="register.php">
            <button>Registrieren</button>
        </a>
        <a href="login.php">
            <button>Login</button>
        </a>
    </body>
</html>