<?php session_start();
?>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input name="email" placeholder="E-Mail"><br>
        <input name="password" type="password" placeholder="Passwort"><br>
        <input type="submit" value="Anmelden">
    </form>

    <?php
    $file = file_get_contents('database_config.json');
    $data = json_decode($file, True);
    $pdo = new PDO("mysql:host=". $data["host"]. ";dbname=" . $data["database"], $data["user"] , $data["password"]);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $email = htmlspecialchars(stripslashes(trim($_POST["email"])));
        $password = htmlspecialchars(stripslashes(trim($_POST["password"])));
        
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $result = $statement->execute(array('email' => $email));
            $user = $statement->fetch();
            if ($user !== false && password_verify($password, $user['pwd_hash'])) {
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                die("Login erfolgreich, weiter zum Forum: <br> <a href=index.php><button>Forum</button></a>");
            } else {
                die("E-Mail oder Passwort ist falsch!");
            }
        }
        else {
            echo "Eine gültige E-Mail ist erforderlich";
        }



    }
    ?>
    <br><br>
    Du hast noch keinen Account? <a href="register.php">Hier kannst du einen erstellen.</a><br>
    <a href="index.php">Zurück zur Startseite</a><br>
</body>
</html>
