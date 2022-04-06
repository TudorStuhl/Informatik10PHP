<!DOCTYPE html>
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
    <title>Forum</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./stylesheets/index2.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <span id="login-check" style="display: none;"><?php if ($_SESSION['user_id'] != -1) { echo "logged_in"; } else { echo "logged_out"; } ?></span>
    <div id="blackscreen-question" onclick="quit_blackscreen_question()"></div>
    <div id="hoverbox-bg-question">
            <?php
            if ($_SESSION['user_id'] != -1) {
                echo "
                <div id='hoverbox-question'>
                    <form autocomplete='off' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                        <input id='question' name='question' maxlength='57' placeholder='Frage hier eingeben...'><br>
                        <textarea id='description' name='description' cols='30' rows='9' placeholder='Beschreibung hier eingeben...'></textarea>
                        <button id='done' class='material-icons' type='submit'>done</button>
                    </form>
                </div>
                ";
            } else {
                echo "
                <div id='hoverbox-question' style='display: flex; justify-content: center; align-items: center;'>
                    <h1 style='color: #404040; font-size: 52px; text-align: center;'>Du must angemeldet sein,<br>um Fragen verfassen zu können</h1>
                </div>
                ";
            }
        ?>
        <?php
            $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]);    //connecting to the database
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["question"])) {     //checking if form has been submitted
            
                $question = htmlspecialchars(stripslashes(trim($_POST["question"])));         //setting question from the form as variable
                $description = htmlspecialchars(stripslashes(trim($_POST["description"])));   //setting description from the form as variable
                
                if ($question != "" && $description != "") {
                    //putting question into database
                    $sql = "INSERT INTO `entries` (`user_id`, `date`, `topic`, `content`) VALUES (" . $_SESSION['user_id'] . ", current_timestamp(), '$question', '$description')";
                    $res = $con -> query($sql);
                } else {
                    echo "<script>setTimeout(function() { alert('Achtung: Die Felder für die Frage und die Beschreibung dürfen nicht leer sein!'); }, 100);</script>";
                }
            }
        ?>
    </div>
    <div id="blackscreen-login" onclick="quit_blackscreen_login()"></div>
    <div id="hoverbox-bg-login">
        <div id='hoverbox-login'>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <br><br>
                <h1 style="color: #404040; font-size: 52px; text-align: center;">Login</h1>
                <input id="login-email" name="login-email" placeholder="E-Mail"><br>
                <input id="login-password" name="login-password" type="password" placeholder="Passwort"><br>
                <input id="login-submit" type="submit" value="Weiter">
            </form>
        </div>
        <?php
            $file = file_get_contents('database_config.json');
            $data = json_decode($file, True);
            $pdo = new PDO("mysql:host=". $data["host"]. ";dbname=" . $data["database"], $data["user"] , $data["password"]);    //connecting to the database
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login-email"])) {     //checking if form has been submitted
            
                $email = htmlspecialchars(stripslashes(trim($_POST["login-email"])));         //setting login-email from the form as variable
                $password = htmlspecialchars(stripslashes(trim($_POST["login-password"])));   //setting login-password from the form as variable
                
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {        //checking if email is valid
                    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");     //getting user credentials from database
                    $result = $statement->execute(array('email' => $email));
                    $user = $statement->fetch();
                    if ($user !== false && password_verify($password, $user['pwd_hash'])) {     //checking if the user login data matches the data in the database
                        $_SESSION['user_id'] = $user['ID'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        echo "<script>document.getElementById('login-check').innerHTML='logged_in';</script>";
                    } else {
                        echo "<script>setTimeout(function() { alert('Email oder Passwort ist falsch!'); }, 100);</script>";
                    }
                }
                else {
                    echo "<script>setTimeout(function() { alert('Eine gültige Email ist erforderlich!'); }, 100);</script>";    //giving feedback if email isn't valid
                }
            }
        ?>
    </div>
    <div id="navbar">
        <span class="material-icons">home</span>
        <span class="material-icons" onclick="new_question()">edit_note</span>
        <span class="material-icons logout"><?php if ($_SESSION['user_id'] != -1) { echo "logout"; } else { echo "login"; } ?></span>
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
        <span id="logout-side" class="material-icons logout"><?php if ($_SESSION['user_id'] != -1) { echo "logout"; } else { echo "login"; } ?></span>
    </div>
    <content>
        <img id="reload" src="./img/reload.svg" alt="reload">
        <h1>Vorgeschlagene Fragen</h1>
        <questions>
        <?php
        // Getting three random questions from the entries table
        $file = file_get_contents('database_config.json');
        $data = json_decode($file, True);
        $con = new mysqli($data['host'], $data['user'], $data['password'], $data['database']);

        if ($con->connect_error) {
            die('Ein Verbindungsfehler ist aufgetreten');
        }
        $res = $con->query("SELECT * FROM entries ORDER BY RAND() LIMIT 3");

        $colors = ["g", "b", "r"];
        if ($res->num_rows > 0) {
            while ($i = $res->fetch_assoc()) {
                $user_id = $i["user_id"];
                $username = "USERNAME_MISSING";
                $result = $con->query("SELECT * FROM users WHERE ID = $user_id;");
                $user_data = $result->fetch_assoc();
                if ($user_data != NULL) {
                    $username = $user_data["username"];
                } else {
                    $username = "deleted user";
                }
                $date = $i['date'];
                $question_id = $i['ID'];
                $topic = $i['topic'];
                $content = $i['content'];
                if (strlen($content) > 235) {
                    $content = substr($content, 0, 234) . "…";
                }
                // Setting a random color for each question out of the three available
                $color = $colors[array_rand($colors)];
                $key = array_search($color, $colors);
                unset($colors[$key]);
                echo "<a href='question.php?id=$question_id'>
                <div class='question $color'>
                <div>
                    <span class='name' style='text-align: center;'>$username</span><br>
                    <span class='answers'>10<br>Antworten</span>
                </div>
                <h2>$topic</h2>
                <p>
                    $content
                </p>
            </div>
            </a>
                ";
            }
        }
        $con->close();
        ?>
        </questions>
    </content>
</body>
</html>
<script>
    // Calling the functions in ajax.php to load other questions and logout etc.
    $(document).ready(function(){
        $('#reload').click(function(){
            var ajaxurl = 'ajax.php',
            data =  {'action': 'reload'};
            $.post(ajaxurl, data, function (response) {
                document.getElementsByTagName("questions")[0].innerHTML = response;
            });
        });
        $('.logout').click(function(){
            if ($('#login-check').text() == "logged_in") {
                $(location).prop('href', './logout.php');
            } else {
                document.getElementById('blackscreen-login').id='blackscreen-anim-1-login';
                document.getElementById('hoverbox-bg-login').id='hoverbox-bg-anim-1-login';
            }
        });
    });
</script>
<style>
    @keyframes opacity-animation-1 {
        from {opacity: 0;}
        to {opacity: 1;}
    }

	@keyframes opacity-animation-2 {
        from {opacity: 1;}
        to {opacity: 0;}
    }
    
    /* Hoverbox ask new question */

    #blackscreen-question {
        display: none;
    }


    #blackscreen-anim-1-question {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: opacity-animation-1 0.5s ease;
		position: fixed;
		z-index: 98;
        background-color: rgba(0, 0, 0, 0.26);
        width: 100%;
        height: 100%;
    }

	#blackscreen-anim-2-question {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: opacity-animation-2 0.5s ease;
		position: fixed;
		z-index: 98;
        background-color: rgba(0, 0, 0, 0.26);
        width: 100%;
        height: 100%;
		opacity: 0;
    }

    #hoverbox-question {
        width: 1373px;
        height: 480px;
        border-radius: 50px;
        background-color: #FBFBFB;
        box-shadow: 0px 4px 24px rgba(97, 97, 97, 0.16);
        pointer-events: auto;
    }

    #hoverbox-bg-question {
        display: none;
		position: fixed;
		z-index: 99;
        background-color: transparent;
        width: 100%;
        height: 100%;
        opacity: 0;
    }

    #hoverbox-bg-question {
        display: none;
    }

    #hoverbox-bg-anim-1-question {
        pointer-events: none;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: opacity-animation-1 0.5s ease;
		position: fixed;
		z-index: 99;
        background-color: transparent;
        width: 100%;
        height: 100%;
    }

	#hoverbox-bg-anim-2-question {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: opacity-animation-2 0.5s ease;
		position: fixed;
		z-index: 99;
        background-color: transparent;
        width: 100%;
        height: 100%;
		opacity: 0;
    }

    /* Hoverbox login */

    #blackscreen-login {
        display: none;
    }


    #blackscreen-anim-1-login {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: opacity-animation-1 0.5s ease;
		position: fixed;
		z-index: 98;
        background-color: rgba(0, 0, 0, 0.26);
        width: 100%;
        height: 100%;
    }

	#blackscreen-anim-2-login {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: opacity-animation-2 0.5s ease;
		position: fixed;
		z-index: 98;
        background-color: rgba(0, 0, 0, 0.26);
        width: 100%;
        height: 100%;
		opacity: 0;
    }

    #hoverbox-login {
        width: 850px;
        height: 417px;
        border-radius: 50px;
        background-color: #FBFBFB;
        box-shadow: 0px 4px 24px rgba(97, 97, 97, 0.16);
        pointer-events: auto;
    }

    #hoverbox-bg-login {
        display: none;
		position: fixed;
		z-index: 99;
        background-color: transparent;
        width: 100%;
        height: 100%;
        opacity: 0;
    }

    #hoverbox-bg-login {
        display: none;
    }

    #hoverbox-bg-anim-1-login {
        pointer-events: none;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: opacity-animation-1 0.5s ease;
		position: fixed;
		z-index: 99;
        background-color: transparent;
        width: 100%;
        height: 100%;
    }

	#hoverbox-bg-anim-2-login {
        display: flex;
        justify-content: center;
        align-items: center;
        animation: opacity-animation-2 0.5s ease;
		position: fixed;
		z-index: 99;
        background-color: transparent;
        width: 100%;
        height: 100%;
		opacity: 0;
    }

    /* Inside the hoverbox */

    #question {
        position: fixed;
        outline: none;
        border: none;
        border-bottom: 2px solid #707070;
        margin-top: 50px;
        margin-left: 111px;
        width: 1150px;
        padding-bottom: 6px;
        font-size: 36px;
        color: #404040;
    }

    #description {
        postion: fixed;
        outline: none;
        resize: none;
        border: 2px solid #707070;
        border-radius: 30px;
        margin-top: 120px;
        margin-left: 91px;
        width: 1191px;
        padding: 20px;
        font-size: 20px;
        color: #404040;
    }

    #done {
        position: fixed;
        border: none;
        margin-top: 380px;
        font-size: 66px;
        color: rgba(64, 64, 64, 0.26);
        background-color: transparent;
        cursor: pointer;
        transition: 300ms;
    }

    #done:hover {
        color: rgb(64, 64, 64);
    }

    #login-email {
        position: fixed;
        outline: none;
        border: none;
        border: 2px solid #707070;
        border-radius: 30px;
        margin-left: 196px;
        width: 458px;
        padding: 6px;
        padding-left: 27px;
        padding-right: 27px;
        text-align: center;
        font-size: 30px;
        color: #404040;
    }

    #login-password {
        position: fixed;
        outline: none;
        border: none;
        border: 2px solid #707070;
        border-radius: 30px;
        margin-top: 57px;
        margin-left: 196px;
        width: 458px;
        padding: 6px;
        padding-left: 27px;
        padding-right: 27px;
        text-align: center;
        font-size: 30px;
        color: #404040;
    }

    #login-submit {
        position: fixed;
        outline: none;
        border: none;
        border: 2px solid #707070;
        border-radius: 30px;
        margin-top: 114px;
        margin-left: 287px;
        width: 282px;
        padding: 6px;
        padding-left: 27px;
        padding-right: 27px;
        text-align: center;
        font-size: 30px;
        color: #404040;
        background-color: transparent;
        cursor: pointer;
        transition: 300ms;
    }

    #login-submit:hover {
        color: #FBFBFB;
        background-color: #707070;
    }
</style>
<script>
    function new_question() {
        document.getElementById('blackscreen-question').id='blackscreen-anim-1-question';
        document.getElementById('hoverbox-bg-question').id='hoverbox-bg-anim-1-question';
    }
    function quit_blackscreen_question() {
        document.getElementById('blackscreen-anim-1-question').id='blackscreen-anim-2-question';
        document.getElementById('hoverbox-bg-anim-1-question').id='hoverbox-bg-anim-2-question';
        setTimeout(() => { document.getElementById('blackscreen-anim-2-question').id='blackscreen-question'; document.getElementById('hoverbox-bg-anim-2-question').id='hoverbox-bg-question'; }, 500);
    }
    function quit_blackscreen_login() {
        document.getElementById('blackscreen-anim-1-login').id='blackscreen-anim-2-login';
        document.getElementById('hoverbox-bg-anim-1-login').id='hoverbox-bg-anim-2-login';
        setTimeout(() => { document.getElementById('blackscreen-anim-2-login').id='blackscreen-login'; document.getElementById('hoverbox-bg-anim-2-login').id='hoverbox-bg-login'; }, 500);
    }
</script>