<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input name="email" placeholder="E-Mail"><br>
        <input type="password" name="password" placeholder="Passwort"><br>
        <input type="submit" value="Anmelden">
    </form>

    <?php
    
    $host = "localhost";
    $user = "root";
    $pw = "";
    $db = "forum";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errors = [];
        $email = htmlspecialchars(stripslashes(trim($_POST["email"])));
        $password = htmlspecialchars(stripslashes(trim($_POST["password"])));
    }

    ?>
</body>
</html>