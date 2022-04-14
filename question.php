<?php
    session_start(); //start session
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = -1;
        $_SESSION['username'] = "Gast";
        $_SESSION['email'] = "Du bist nicht angemeldet";
    }
?>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/question.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Forum für dumme Fragen</title>
</head>
<body>
    
    <div id="navbar">
        <span class="material-icons" onclick="window.location.href= './index.php'">home</span>
        <span class="material-icons" onclick="new_question()">edit_note</span>
    <span class="material-icons logout" onclick="logout()">logout</span>
    </div>
    <div id="sidebar">
    <img id="avatar" src="./img/avatar.svg" alt="avatar">
        <h2><?php echo $_SESSION['username']; ?></h2>
        <h3><?php echo $_SESSION['email']; ?></h3>
        <h4>Accountmenü</h4>
        <span>
            <?php
            if ($_SESSION['user_id'] == -1) {
                echo "
                <s>Nutzernamen ändern</s><br>
                <s>Email ändern</s><br>
                <s>Passwort ändern</s>
                ";
            } else {
                echo "
                <span>Nutzernamen ändern</span><br>
                <span>Email ändern</span><br>
                <span>Passwort ändern</span>
                ";
            }
            ?>
        </span>
        <br><br><br><br><br>
        <span id="logout-side" class="material-icons logout" onclick="logout()">logout</span>
    </div>
    </div>
    <content>
    <?php
        if (!isset($_GET["id"])) {
            echo "Fehler: keine Frage abgerufen.";
        }
        else {

            //Connecting to the database and fetching the needed information
            $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]);
            $question_id = htmlspecialchars(stripslashes(trim($_GET["id"])));
            $_POST["question_id"] = $question_id;
            $res = $con -> query("SELECT * FROM entries WHERE ID = $question_id");
            $i = $res -> fetch_assoc();
            $topic = $i["topic"];
            $content = $i["content"];
            $user_id = $i["user_id"];

            //Converting from Y/M/D H/I/S to D/M/Y date format
            $new_datetime = DateTime::createFromFormat("Y-m-d H:i:s", $i["date"]);
            $date = "Am ". $new_datetime -> format("d.m.y");

            //Querry and fetch the user from the database
            $res = $con -> query("SELECT username FROM users WHERE ID = $user_id");
            $b = $res -> fetch_array();
            $username = $b[0];
       
            //Display the question with the content
            echo "<div class='question'>
                <div class='topic r'>
                    <span class='name', text-align='center'>$topic</span>
                </div>
                <div class='user'>
                    <img src='./img/avatar.svg' alt='avatar' style='margin-top: 80px;'>
                    <h3>$username</h3>
                    <span>$date</span>
                </div>
                <p>
                   $content     
                </p>
                

            </div>";
            //Query all replies from the database
            $res = $con -> query("SELECT * FROM replies WHERE entry_id = $question_id");
            if ($res -> num_rows > 0) {  
                while($i = $res->fetch_assoc()) {
                    $user_id = $i["user_id"];

                    //Converting from Y/M/D H/I/S to D/M/Y date format
                    $new_datetime = DateTime::createFromFormat("Y-m-d H:i:s", $i["date"]);
                    $date = "Am ". $new_datetime -> format("d.m.y");
                    $content = $i["content"];

                    //Query and fetch the user
                    $ras = $con -> query("SELECT username FROM users WHERE ID = $user_id");
                    $b = $ras -> fetch_array();
                    $username = $b[0];
                    
                    //Display the answer with the content
                    echo "
                    <div class='answer'>
                        <div class='user'>
                            <img src='./img/avatar.svg' alt='avatar'>
                            <h3>$username</h3>
                            <span>$date</span>
                        </div>
                        <p>
                            $content
                        
                        </p>
                    </div>
                    
                    
                    ";
                }
            
        }
        else {
            //If there are no replies in the database, give the user a feedback
            echo "<p class='anti-answer'>Es gibt noch keine Antworten auf diese Frage.</p>";
        }
        
    }

    echo "<script>
            function logout() {
            window.location.href = 'question_logout.php?id=" . "$question_id';
            }

            
            </script>";

    ?>
    <content>
</body>
</html>
