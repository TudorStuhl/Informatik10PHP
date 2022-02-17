<?php

$host = "localhost";
$user = "root";
$pw = "";

$con = new mysqli($host, $user, $pw);

if ($con -> connect_error) {
    die();
}

$all_sql = [
"DROP DATABASE IF EXISTS `forum`",
"CREATE DATABASE `forum` COLLATE utf8mb4_general_ci",
"CREATE TABLE `forum`.`users` ( `ID` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL UNIQUE , `pwd_hash` TEXT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB",
"CREATE TABLE `forum`.`entries` ( `ID` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT , `user_id` INT UNSIGNED NOT NULL , `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `topic` TEXT NOT NULL , `content` TEXT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB",
"CREATE TABLE `forum`.`replies` ( `entry_id` INT UNSIGNED NOT NULL , `user_id` INT UNSIGNED NOT NULL , `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `content` TEXT NOT NULL ) ENGINE = InnoDB"
];

for ($i = 0; $i < sizeof($all_sql); $i++) {
    $sql = $all_sql[$i];
    $res = $con -> query($sql);
}
echo "Done with creating database and table.";
$con -> close();

?>
