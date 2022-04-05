<?php 
session_start();
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
        <span class="material-icons">home</span>
        <span class="material-icons" onclick="new_question()">edit_note</span>
        <span class="material-icons logout">logout</span>
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
        <span id="logout-side" class="material-icons logout">logout</span>
    </div>
    </div>
    <content>
    <questions>
    <div class="question r">
        <div class="topic">
            <span class="name", text-align="center">Warum ist die Banane krumm und nicht dreieckig?</span>
        </div>
        <div class="user">

        </div>
        <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat vero ipsam aliquam accusamus? Veritatis, hic reprehenderit corporis eius, placeat sed officiis possimus delectus voluptatem repudiandae sint nam, assumenda soluta quaerat.
        </p>
        
        <?php
            $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]);
            $question_id = $_GET["id"];
            $res = $con -> query("SELECT * FROM entries WHERE ID = $question_id");
            $i = $res -> fetch_assoc();
            

        ?>
    </div> 
        </questions>   
    <answers>

    <answers>
    <content>
</body>
</html>