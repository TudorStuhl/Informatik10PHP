<?php 
// unsetting session to log-out the user
session_start();
session_unset();
// set user to guest
$_SESSION['user_id'] = -1;
$_SESSION['username'] = "Gast";
$_SESSION['email'] = "Du bist nicht angemeldet";
$id = $_GET["id"];
header("Location: question.php?id=$id");
?>