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
    <div id="blackscreen" onclick="quit_blackscreen()"></div>
    <div id="hoverbox-bg">
        <div id="hoverbox">
            <!--<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input name="question" maxlength="57" placeholder="Frage hier eingeben..."><br>
                <textarea name="description" cols="30" rows="10"></textarea>
            </form>-->
        </div>
    </div>
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
                $topic = $i['topic'];
                $content = $i['content'];
                // Setting a random color for each question out of the three available
                $color = $colors[array_rand($colors)];
                $key = array_search($color, $colors);
                unset($colors[$key]);
                echo "
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
            var ajaxurl = 'ajax.php',
            data =  {'action': 'logout'};
            $.post(ajaxurl, data, function (response) {
                document.getElementsByTagName("questions")[0].innerHTML = document.getElementsByTagName("questions")[0].innerHTML +
                '<?php session_unset();
                $_SESSION['user_id'] = -1;
                $_SESSION['username'] = "Gast";
                $_SESSION['email'] = "Du bist nicht angemeldet";?>';
                location.reload();
            });
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
    
    #blackscreen {
        display: none;
    }


    #blackscreen-anim-1 {
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

	#blackscreen-anim-2 {
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

    #hoverbox {
        width: 1373px;
        height: 480px;
        border-radius: 50px;
        background-color: #FBFBFB;
        box-shadow: 0px 4px 24px rgba(97, 97, 97, 0.16);
        pointer-events: auto;
    }

    #hoverbox-bg {
        display: none;
		position: fixed;
		z-index: 99;
        background-color: transparent;
        width: 100%;
        height: 100%;
        opacity: 0;
    }

    #hoverbox-bg {
        display: none;
    }

    #hoverbox-bg-anim-1 {
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

	#hoverbox-bg-anim-2 {
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
</style>
<script>
    function new_question() {
        document.getElementById('blackscreen').id='blackscreen-anim-1';
        document.getElementById('hoverbox-bg').id='hoverbox-bg-anim-1';
    }
    function quit_blackscreen() {
        document.getElementById('blackscreen-anim-1').id='blackscreen-anim-2';
        document.getElementById('hoverbox-bg-anim-1').id='hoverbox-bg-anim-2';
        setTimeout(() => { document.getElementById('blackscreen-anim-2').id='blackscreen'; document.getElementById('hoverbox-bg-anim-2').id='hoverbox-bg'; }, 500);
    }
</script>