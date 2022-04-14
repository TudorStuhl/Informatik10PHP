<?php 
session_start();
session_unset();
$_SESSION['user_id'] = -1;
$_SESSION['username'] = "Gast";
$_SESSION['email'] = "Du bist nicht angemeldet";
$id = $_GET["id"];
header("Location: question.php?id=$id");
?>