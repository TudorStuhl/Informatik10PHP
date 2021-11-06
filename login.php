<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="?login=1" method="post">
        <input type="email" placeholder="E-Mail"><br>
        <input type="password" name="password" placeholder="Passwort"><br>
        <input type="submit" value="Anmelden">
    </form>

    <?php
    session_start();
    $pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
    
    if (isset($_GET['login'])) {
        $email = $_POST['email'];
        $passwort = $_POST['password'];

        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();

        if ($user !== false && password_verify($passwort, $user['passwort'])) {
            $_SESSION['userid'] = $user['id'];
            die("Login erfolgreich, weiter zum Forum: <br> <a href= test.php><button>Forum</button></a>");
        } else {
            die("Email oder Passwort sind falsch!");
        }
    }

    ?>
</body>
</html>