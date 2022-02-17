<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - Fragen</title>
</head>
<body>
    <?php

    $host = "localhost";
    $user = "root";
    $pw = "";
    $db = "forum";

    $con = new mysqli($host, $user, $pw, $db);

    if ($con -> connect_error) {
        die("Ein Verbindungsfehler ist aufgetreten");
    }
    $res = $con -> query("SELECT * FROM entries");

    if ($res->num_rows > 0) {
        while ($i = $res->fetch_assoc()) {
            $user_id = $i["user_id"];
            $username = "USERNAME_MISSING";
            $result = $con -> query("SELECT * FROM users WHERE ID = $user_id;");
            $user_data = $result -> fetch_assoc();
            $username = $user_data["username"];
            echo $i["topic"] . " - " . $username . " - " . $i["date"] . "<br><br>";
        }
    }

    $con -> close();
        
    ?>
</body>
</html>