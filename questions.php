<!DOCTYPE html>
<html lang='de'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' media="screen and (min-aspect-ratio: 16/9)" href='./stylesheets/index2.css'>
    <link rel='stylesheet' media="screen and (max-aspect-ratio: 16/9)" href='./stylesheets/index3.css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Forum - Fragen</title>
</head>

<body>
    <div id="navbar">
        <span class="material-icons">home</span>
        <span class="material-icons">edit_note</span>
        <span class="material-icons">logout</span>
    </div>
    <div id="sidebar">
        <img id="avatar" src="./img/avatar.svg" alt="avatar">
        <h2>Otto von Bismarck</h2>
        <h3>otto.von.bismarck@gmail.com</h3>
        <h4>Accountmenü</h4>
        <span>
            <a href="">Nutzernamen ändern</a><br>
            <a href="">Email ändern</a><br>
            <a href="">Passwort ändern</a>
        </span>
        <br><br><br><br><br>
        <span id="logout-side" class="material-icons">logout</span>
    </div>
    <content>
        <?php
        $file = file_get_contents('database_config.json');
        $data = json_decode($file, True);
        $con = new mysqli($data['host'], $data['user'], $data['password'], $data['database']);

        if ($con->connect_error) {
            die('Ein Verbindungsfehler ist aufgetreten');
        }
        $res = $con->query("SELECT * FROM entries");

        if ($res->num_rows > 0) {
            while ($i = $res->fetch_assoc()) {
                $user_id = $i["user_id"];
                $username = "USERNAME_MISSING";
                $result = $con->query("SELECT * FROM users WHERE ID = $user_id;");
                $user_data = $result->fetch_assoc();
                $username = $user_data["username"];
                $date = $i['date'];
                $topic = $i['topic'];
                $content = $i['content'];
                echo "
                <div class='question b'>
                <div>
                    <span class='name'>$username</span><br>
                    <span class='answers'>10<br>Antworten</span>
                </div>
                <h2>$topic</h2>
                <p>
                    $content
                </p>
            </div>
                ";
            }
        }
        $con->close();

        ?>
    </content>
</body>

</html>