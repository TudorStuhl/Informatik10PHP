<?php
session_start();
if ($_SESSION["user_id"] == -1 && isset($_SESSION["user_id"])) {
    echo 'Du bist nicht angemeldet. Hier gehts zum Login: <a href="login.php"><button>Login</button></a>';
} else {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $user_id = $_SESSION['user_id'];
    echo "Willkommen in der Account Verwaltung,<b> $username </b>!";
    echo
    '<br><br>
    Dein Nutzername lautet: <b>' . $username . '</b> <br>
    Deine E-Mail Adresse lautet: <b>' . $email . '</b><br>
    <form method="post"  action="' .  htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <input type="submit" name="change_email" value="E-Mail Adresse ändern"/> <br>
        <input type="submit" name="change_username" value="Nutzernamen ändern"/>
    </form>';
    if (array_key_exists("change_email", $_POST)) {
        echo
        '<form method="post" action="' .  htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <input type="email" name="new_email" placeholder="Neue E-Mail Adresse"/> <br>
            <input type="submit" value="E-Mail Adresse speichern" name="submit_email">
        </form>';
    }
    else if (array_key_exists("change_username", $_POST)) {
        echo
        '<form method="post" action="' .  htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <input type="text" name="new_username" placeholder="Neuer Nutzername"/> <br>
            <input type="submit" value="Nutzernamen speichern" name="submit_username">
        </form>';  
    }
    if (array_key_exists("new_email", $_POST)) {
            $new_email = $_POST["new_email"];
            if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                update_userdata("email", $user_id, $new_email);
                } else {
                    echo "Bitte gebe eine gültige E-Mail Adresse ein.";
                }        
    }
    if (array_key_exists("new_username", $_POST)) {
        update_userdata("username", $user_id, $_POST["new_username"]);
    }
    echo '<a href="index.php">Zurück zur Startseite</a>';
} 
    
      
function update_userdata($type, $id, $value) {
    $file = file_get_contents('database_config.json');
    $data = json_decode($file, True);
    $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]);
    if ($con -> connect_error) { die("Ein Fehler ist aufgetreten"); }   
    if ($type == "email" || $type == "username") {
        $f_value = htmlspecialchars(strip_tags(trim($value)));
        $res = $con -> query("UPDATE users SET $type = '$f_value' WHERE ID = $id;");
        $res = $con -> query("SELECT * FROM users WHERE ID = $id;");
        $user_data = $res -> fetch_assoc();
        $_SESSION["email"] = $user_data["email"];
        $_SESSION["username"] = $user_data["username"];
    }
} 
