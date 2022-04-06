<?php
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'reload':
                reload();
                break;
            }
    }

    function reload() {
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
        exit;
    }
?>