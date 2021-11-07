<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input name="email" placeholder="E-Mail"><br>
        <input name="password" type="password" placeholder="Passwort"><br>
        <input type="submit" value="Anmelden">
    </form>

    <?php
    session_start();
    $pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $password = $_POST['password'];
        $email = $_POST['email'];
        

        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();

        if ($user !== false && password_verify($password, $user['pwd_hash'])) {
            $_SESSION['user_id'] = $user['ID'];
            die("Login erfolgreich, weiter zum Forum: <br> <a href= test.php><button>Forum</button></a>");
        } else {
            die("Email oder Passwort sind falsch!");
        }
    }
    
    ?>
</body>
</html>
