<?php
session_start();


if (!isset($_SESSION["user_id"])) { //Required so we don't overwrite the session vars everytime the index gets loaded
    $_SESSION["username"] = "guest";
    $_SESSION["user_id"] = -1;
    $_SESSION["email"] = Null;
}


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
        <form method="POST">
            <input type="submit" name="button" value="Abmelden (Für Debug)">
        </form>
        <a href="register.php">
            <button>Registrieren</button>
        </a>
        <a href="login.php">
            <button>Login</button>
        </a>
        <a href="account.php">
            <button>Account</button>
        </a>
    </body>
</html>
<?php
if (array_key_exists('button', $_POST)) {
    session_unset();
    $_SESSION["username"] = "guest";
    $_SESSION["user_id"] = -1;
    $_SESSION["email"] = Null;
}
?>