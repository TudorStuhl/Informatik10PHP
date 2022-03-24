<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./stylesheets/template.css">                 <!-- importing the stylesheet --> 
    <title>Forum: Account</title>
</head>
<body>
    
</body>
</html>
<?php
session_start();                //starting the session
if ($_SESSION["user_id"] == -1 && isset($_SESSION["user_id"])) {        //checking if user is logged in
    echo 'Du bist nicht angemeldet. Hier gehts zum Login: <a href="login.php"><button>Login</button></a>';
} else {
    $username = $_SESSION['username'];          //setting username, email and User-ID as variables
    $email = $_SESSION['email'];
    $user_id = $_SESSION['user_id'];
    echo "Willkommen in der Account Verwaltung,<b> $username </b>!";
    echo        //displaying userinformation
    '<br><br>
    Dein Nutzername lautet: <b>' . $username . '</b> <br>       
    Deine E-Mail Adresse lautet: <b>' . $email . '</b><br>
    <form method="post"  action="' .  htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <input type="submit" name="change_email" value="E-Mail Adresse ändern"/> <br> 
        <input type="submit" name="change_username" value="Nutzernamen ändern"/>
    </form>';   //form to change username and email
    if (array_key_exists("change_email", $_POST)) {     //checking if input for change of email exists
        echo
        '<form method="post" action="' .  htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <input type="email" name="new_email" placeholder="Neue E-Mail Adresse"/> <br>
            <input type="submit" value="E-Mail Adresse speichern" name="submit_email">
        </form>'; //getting the new email
    }
    else if (array_key_exists("change_username", $_POST)) {     //checking if input for change of username exists
        echo
        '<form method="post" action="' .  htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <input type="text" name="new_username" placeholder="Neuer Nutzername"/> <br>
            <input type="submit" value="Nutzernamen speichern" name="submit_username">
        </form>';  //getting the new username
    }
    if (array_key_exists("new_email", $_POST)) {    //checking if new email inout exists
            $new_email = $_POST["new_email"];
            if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {    //checking if email is valid
                update_userdata("email", $user_id, $new_email);     //executing funtion to change email in database
                } else {
                    echo "Bitte gebe eine gültige E-Mail Adresse ein."; //ggiving feedback if email is not valid
                }        
    }
    if (array_key_exists("new_username", $_POST)) {    //checking if new username exists 
        update_userdata("username", $user_id, $_POST["new_username"]);     //executing function to change username in database
    }
    echo '<a href="index.php">Zurück zur Startseite</a>';       //link to starting page
} 
    
      
function update_userdata($type, $id, $value) {
    $file = file_get_contents('database_config.json');
    $data = json_decode($file, True);
    $con = new mysqli($data["host"], $data["user"], $data["password"], $data["database"]); //connecting to database
    if ($con -> connect_error) { die("Ein Fehler ist aufgetreten"); }   //checking if connection was succesful
    if ($type == "email" || $type == "username") {      //changing username or email in database
        $f_value = htmlspecialchars(strip_tags(trim($value)));
        $res = $con -> query("UPDATE users SET $type = '$f_value' WHERE ID = $id;");
        $res = $con -> query("SELECT * FROM users WHERE ID = $id;");
        $user_data = $res -> fetch_assoc();
        $_SESSION["email"] = $user_data["email"];   //refreshing email in session
        $_SESSION["username"] = $user_data["username"];     //refreshing username in session
    }
} 
