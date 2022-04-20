<?php
    session_start(); //start session
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['user_id'] = -1;
        $_SESSION['username'] = "Gast";
        $_SESSION['email'] = "Du bist nicht angemeldet";
    }
    if (isset($_POST["delete"])) {
        $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            $question_id = htmlspecialchars(stripslashes(trim($_GET["id"])));
            //connecting to the database
            $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]);
            $con -> query("DELETE FROM entries WHERE ID = $question_id"); 
        header("Location: ./index.php");
    }

?>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/question.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Forum für dumme Fragen</title>
</head>
<body>
<!-- Login Hoverbox -->
<div id="blackscreen-login" onclick="quit_blackscreen_login()"></div>
    <div id="hoverbox-bg-login">
        <div id='hoverbox-login'>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . htmlspecialchars(stripslashes(trim($_GET["id"]))); ?>" method="post">
                <br><br>
                <h1 style="color: #404040; font-size: 52px; text-align: center;">Login</h1>
                <input id="login-email" name="login-email" placeholder="E-Mail"><br>
                <input id="login-password" name="login-password" type="password" placeholder="Passwort"><br>
                <input id="login-submit" type="submit" value="Weiter">
            </form>
        </div>
    </div>
    <?php
            $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            //connecting to the database
            $pdo = new PDO("mysql:host=". $data["host"]. ";dbname=" . $data["database"], $data["user"] , $data["password"]);    
            //checking if form has been submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login-email"])) {     
            //setting login-email and login-password from the login-form as variables
                $email = htmlspecialchars(stripslashes(trim($_POST["login-email"])));         
                $password = htmlspecialchars(stripslashes(trim($_POST["login-password"])));   
                // Compare form input with user credentials from database
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {        
                    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");     
                    $result = $statement->execute(array('email' => $email));
                    $user = $statement->fetch();
                    if ($user !== false && password_verify($password, $user['pwd_hash'])) {    
                        $_SESSION['user_id'] = $user['ID'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        echo "<script>window.location.replace('')</script>";
                    } else {
                        echo "<script>setTimeout(function() { alert('Email oder Passwort ist falsch!'); }, 100);</script>";
                    }
                }
                else {
                    echo "<script>setTimeout(function() { alert('Eine gültige Email ist erforderlich!'); }, 100);</script>";    
                }
            }
        ?>
</div>
<!-- Register Hoverbox --> 

<div id="blackscreen-register" onclick="quit_blackscreen_register()"></div>
    <div id="hoverbox-bg-register">
        <div id='hoverbox-register'>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . htmlspecialchars(stripslashes(trim($_GET["id"]))); ?>" method="post">
                <br><br>
                <h1 style="color: #404040; font-size: 52px; text-align: center;">Registrieren</h1>
                <input id="register-username" name="register-username" placeholder="Benutzername"><br>
                <input id="register-email" name="register-email" placeholder="E-Mail"><br>
                <input id="register-password" name="register-password" type="password" placeholder="Passwort"><br>
                <input id="register-password-check" name="register-password-check" type="password" placeholder="Passwort wiederholen"><br>
                <input id="register-submit" type="submit" value="Weiter">
            </form>
        </div>
        <?php
            $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            // Database connection 
            $pdo = new PDO("mysql:host=". $data["host"]. ";dbname=" . $data["database"], $data["user"] , $data["password"]);  
            // checking if form has been submitted and set input as variables 
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register-email"])) {     
                $errors = [];

                $username = htmlspecialchars(stripslashes(trim($_POST["register-username"])));
                $email = htmlspecialchars(stripslashes(trim($_POST["register-email"])));
                $password = htmlspecialchars(stripslashes(trim($_POST["register-password"])));
                $password_check = htmlspecialchars(stripslashes(trim($_POST["register-password-check"])));
                // search for input errors
                if (empty($username)) {
                    $errors[] = "Ein Benutzername ist erforderlich";
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Eine gültige E-Mail ist erforderlich" . filter_var($email, FILTER_VALIDATE_EMAIL);
                }
                if (empty($password)) {
                    $errors[] = "Ein Passwort ist erforderlich";
                }
                if (strlen($username) > 255) {
                    $errors[] = "Der Benutzername ist zu lang";
                }
                if (strlen($email) > 255) {
                    $errors[] = "Die Mailadresse ist zu lang";
                }
                if (strlen($password) > 255) {
                    $errors[] = "Das Passwort ist zu lang";
                }
                if (strlen($password) < 8) {
                    $errors[] = "Das Passwort muss mindestens acht Zeichen lang sein";
                }
                if ($password != $password_check) {
                    $errors[] = "Die beiden Passwörter stimen nicht überein";
                }
                // hashing password and insert user to table 'users' if no input errors are found
                if (sizeof($errors) == 0) {
                    $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]);

                    if ($con -> connect_error) {
                        die("Ein Verbindungsfehler ist aufgetreten");
                    }
                    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                    if ($password_hashed) {
                        $sql = "INSERT INTO `users` (`username`, `email`, `pwd_hash`) VALUES ('$username', '$email', '$password_hashed')";
                        $res = $con -> query($sql);
                    } else {
                        echo "<script>setTimeout(function() { alert('Hash fehlgeschlagen!'); }, 100);</script>";
                    }

                    $con -> close();
                } else {
                    for ($error = 0; $error < sizeof($errors); $error++) { 
                        echo "<script>setTimeout(function() { alert('$errors[$error]!'); }, 100);</script>";
                    }
                }
            }
        ?>
    </div>

<!-- New answer -->
<div id="blackscreen-answer" onclick="quit_blackscreen_answer()"></div>
    <div id="hoverbox-bg-answer">
        <div id='hoverbox-answer'>
            <form autocomplete='off' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . htmlspecialchars(stripslashes(trim($_GET["id"]))); ?>' method='post'>
                <textarea id='description' name='answer_txt' cols='30' rows='9' placeholder='Antwort hier eingeben...'></textarea>
                <button id='done' class='material-icons' type='submit'>done</button>
            </form>
        </div>
        <?php
            $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            $question_id = htmlspecialchars(stripslashes(trim($_GET["id"])));
            //connecting to the database
            $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]);    
            //checking if form has been submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["answer_txt"])) {  
                // Setting question and description as variable          
                $description = htmlspecialchars(stripslashes(trim($_POST["answer_txt"])));   
                //putting question into database
                if ($description != "") {
                    
                    $sql = "INSERT INTO `replies` (`entry_id`, `user_id`, `date`, `content`) VALUES ($question_id, " . $_SESSION['user_id'] . ", current_timestamp(), '" . $description . "')";
                    $res = $con -> query($sql);
                    $_POST["answer_txt"] = "";
                } else {
                    echo "<script>setTimeout(function() { alert('Achtung: Die Felder für die Frage und die Beschreibung dürfen nicht leer sein!'); }, 100);</script>";
                }
            }
        ?>
    </div>

<!-- Edit account -->
<div id="blackscreen-edit-account" onclick="quit_blackscreen_edit_account()"></div>
    <div id="hoverbox-bg-edit-account">
        <div id='hoverbox-edit-account'>
            <!-- account editing form -->
            <form id="edit-account-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . htmlspecialchars(stripslashes(trim($_GET["id"]))); ?>" method="post">
                <input id="edit-account" name="edit-username" placeholder="Neuer Name">
            </form>
        </div>
        <?php
            $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            // connect to database
            $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]); 
            if ($con -> connect_error) { die("Ein Fehler ist aufgetreten"); }   
            // changing username in table 'users'
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit-username"])) {
                if (strlen($_POST["edit-username"]) == 0 || strlen($_POST["edit-username"]) > 255) {
                    echo "<script>setTimeout(function() { alert('Der Nutzername muss zwischen 1 und 255 Zeichen lang sein!'); }, 100);</script>";
                } else {
                    $res = $con -> query("UPDATE users SET username = '" . $_POST["edit-username"] . "' WHERE ID = " . $_SESSION["user_id"] . ";");
                    $res = $con -> query("SELECT * FROM users WHERE ID = " . $_SESSION["user_id"] . ";");
                    $user_data = $res -> fetch_assoc();
                    //refreshing username in session
                    $_SESSION["username"] = $user_data["username"];     
                }
            // changing email in table 'users'
            } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit-email"])) {
                if (strlen($_POST["edit-email"]) == 0 || strlen($_POST["edit-email"]) > 255 || !filter_var($_POST["edit-email"], FILTER_VALIDATE_EMAIL)) {
                    echo "<script>setTimeout(function() { alert('Die Email muss zwischen 1 und 255 Zeichen lang sein und eine gültige Mailadresse sein!'); }, 100);</script>";
                } else {
                    $res = $con -> query("UPDATE users SET email = '" . $_POST["edit-email"] . "' WHERE ID = " . $_SESSION["user_id"] . ";");
                    $res = $con -> query("SELECT * FROM users WHERE ID = " . $_SESSION["user_id"] . ";");
                    $user_data = $res -> fetch_assoc();
                    //refreshing email in session
                    $_SESSION["email"] = $user_data["email"];   
                }
            // changing password in table 'users'
            } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit-password"])) {
                if (strlen($_POST["edit-password"]) < 8 || strlen($_POST["edit-password"]) > 255) {
                    echo "<script>setTimeout(function() { alert('Die Passwort muss zwischen 8 und 255 Zeichen lang sein!'); }, 100);</script>";
                } else {
                    $res = $con -> query("UPDATE users SET pwd_hash = '" . password_hash($_POST["edit-password"], PASSWORD_DEFAULT) . "' WHERE ID = " . $_SESSION["user_id"] . ";");
                }
            }
        ?>
    </div>
</div>
<!-- The side layout itself --> 
    <div id="navbar">
        <span class="material-icons" onclick="window.location.href= './index.php'">home</span>
        <?php if ($_SESSION['user_id'] != -1) { echo "<span class='material-icons' onclick='new_answer()'>edit_note"; } else { echo "<span class='material-icons' onclick='register()'>person_add"; } ?></span>
        <?php if ($_SESSION['user_id'] != -1) { echo "<span class='material-icons' onclick='logout()'>logout"; } else { echo "<span class='material-icons logout'>login"; } ?></span>
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
                <span onclick='edit_username()'>Nutzernamen ändern</span><br>
                <span onclick='edit_email()'>Email ändern</span><br>
                <span onclick='edit_password()'>Passwort ändern</span>
                ";
            }
            ?>
        </span>
        <br><br><br><br><br>
        <?php if ($_SESSION['user_id'] != -1) { echo "<span id='logout-side' class='material-icons' onclick='logout()'>logout"; } else { echo "<span id='logout-side' class='material-icons logout'>login"; } ?></span>
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
            if ($b == []) {
                $username = "deleted user";
            }
            else {
               $username = $b[0]; 
            }
       
            //Display the question with the content
            if ($_SESSION["user_id"] == $user_id) {
                $delete = "<form method='POST' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . htmlspecialchars(stripslashes(trim($_GET["id"]))) . "'><button type='submit' name='delete' class='delete'><span class='material-icons delete' style='font-size: 40px;'>delete</span></button></form></div>";

            }
            else { $delete = "</div>";}
            $q = "<div class='question'>
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
            </p>". $delete;
        

            echo $q;

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
                    if ($b == []) {
                        $username = "deleted user";
                    }
                    else {
                       $username = $b[0]; 
                    }
                    
                    
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
<script>
    // functions to open the ui's for account management, compose questions etc.
    function new_answer() {
            document.getElementById('blackscreen-answer').id='blackscreen-anim-1-answer';
            document.getElementById('hoverbox-bg-answer').id='hoverbox-bg-anim-1-answer';
        }
    function register() {
            document.getElementById('blackscreen-register').id='blackscreen-anim-1-register';
            document.getElementById('hoverbox-bg-register').id='hoverbox-bg-anim-1-register';
        }
    function edit_username() {
        document.getElementById('edit-account-form').innerHTML = '<input id="edit-account" name="edit-username" placeholder="Neuer Name">';
        document.getElementById('blackscreen-edit-account').id='blackscreen-anim-1-edit-account';
        document.getElementById('hoverbox-bg-edit-account').id='hoverbox-bg-anim-1-edit-account';
    }
    function edit_email() {
        document.getElementById('edit-account-form').innerHTML = '<input id="edit-account" name="edit-email" placeholder="Neue Email">';
        document.getElementById('blackscreen-edit-account').id='blackscreen-anim-1-edit-account';
        document.getElementById('hoverbox-bg-edit-account').id='hoverbox-bg-anim-1-edit-account';
    }
    function edit_password() {
        document.getElementById('edit-account-form').innerHTML = '<input id="edit-account" name="edit-password" type="password" placeholder="Neues Passwort">';
        document.getElementById('blackscreen-edit-account').id='blackscreen-anim-1-edit-account';
        document.getElementById('hoverbox-bg-edit-account').id='hoverbox-bg-anim-1-edit-account';
    }
    function quit_blackscreen_answer() {
        document.getElementById('blackscreen-anim-1-answer').id='blackscreen-anim-2-answer';
        document.getElementById('hoverbox-bg-anim-1-answer').id='hoverbox-bg-anim-2-answer';
        setTimeout(() => { document.getElementById('blackscreen-anim-2-answer').id='blackscreen-answer';
        document.getElementById('hoverbox-bg-anim-2-answer').id='hoverbox-bg-answer'; }, 500);
    }
    function quit_blackscreen_login() {
        document.getElementById('blackscreen-anim-1-login').id='blackscreen-anim-2-login';
        document.getElementById('hoverbox-bg-anim-1-login').id='hoverbox-bg-anim-2-login';
        setTimeout(() => { document.getElementById('blackscreen-anim-2-login').id='blackscreen-login'; document.getElementById('hoverbox-bg-anim-2-login').id='hoverbox-bg-login'; }, 500);
    }
    function quit_blackscreen_register() {
        document.getElementById('blackscreen-anim-1-register').id='blackscreen-anim-2-register';
        document.getElementById('hoverbox-bg-anim-1-register').id='hoverbox-bg-anim-2-register';
        setTimeout(() => { document.getElementById('blackscreen-anim-2-register').id='blackscreen-register'; document.getElementById('hoverbox-bg-anim-2-register').id='hoverbox-bg-register'; }, 500);
    }
    function quit_blackscreen_edit_account() {
        document.getElementById('blackscreen-anim-1-edit-account').id='blackscreen-anim-2-edit-account';
        document.getElementById('hoverbox-bg-anim-1-edit-account').id='hoverbox-bg-anim-2-edit-account';
        setTimeout(() => { document.getElementById('blackscreen-anim-2-edit-account').id='blackscreen-edit-account'; document.getElementById('hoverbox-bg-anim-2-edit-account').id='hoverbox-bg-edit-account'; }, 500);
    }
    $(document).ready(function(){ 
        $('.logout').click(function(){
                document.getElementById('blackscreen-login').id='blackscreen-anim-1-login';
                document.getElementById('hoverbox-bg-login').id='hoverbox-bg-anim-1-login';
            }
        );
    });
</script>
