<?php

$file = file_get_contents('database_config.json');
$data = json_decode($file, True);
$host = $data["host"];
$user = $data["user"];
$password = $data["password"];
echo $data["tables"][0];