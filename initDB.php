<?php

$file = file_get_contents('database_config.json');
$data = json_decode($file, True);
//creating connection to phpmyadmin
$con = new mysqli($data["host"], $data["user"], $data["password"]);     
//stopping if connection was not succesful
if ($con -> connect_error) {    
    die();
}

$all_sql = [
//make sure to remove the database if it already exists
"DROP DATABASE IF EXISTS `forum`",  
//create the database
"CREATE DATABASE `forum` COLLATE utf8mb4_general_ci", 
"CREATE TABLE `forum`.`users` ( `ID` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL UNIQUE , `pwd_hash` TEXT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB",       //create table for users
"CREATE TABLE `forum`.`entries` ( `ID` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT , `user_id` INT UNSIGNED NOT NULL , `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `topic` TEXT NOT NULL , `content` TEXT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB",    //create table for entries
"CREATE TABLE `forum`.`replies` ( `entry_id` INT UNSIGNED NOT NULL , `user_id` INT UNSIGNED NOT NULL , `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `content` TEXT NOT NULL ) ENGINE = InnoDB"     //create table for replies
];

for ($i = 0; $i < sizeof($all_sql); $i++) {
    $sql = $all_sql[$i];
    $res = $con -> query($sql);
}
echo "Done with creating database and table.";
$con -> close();

?>
