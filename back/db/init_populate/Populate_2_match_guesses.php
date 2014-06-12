<?php

include_once('../db_singleton/mysqldatabase.php');
include_once('../db_singleton/mysqlresultset.php');
include_once('./constants.php');

$db = MySqlDatabase::getInstance();

try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
} catch (Exception $e) {
    die($e->getMessage());
}

$handle = fopen("fake_user_guesses.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $pieces = explode(',',$line);
        if(count($pieces)!=6) {
            continue;
        }
        $user_id = trim($pieces[0]);
        $game1_guess = trim($pieces[1]);
        if($game1_guess=="-") {
            $game1_guess = "missed";
        }
        $game2_guess = trim($pieces[2]);
        if($game2_guess=="-") {
            $game2_guess = "missed";
        }
        $game3_guess = trim($pieces[3]);
        if($game3_guess=="-") {
            $game3_guess = "missed";
        }
        $game4_guess = trim($pieces[4]);
        if($game4_guess=="-") {
            $game4_guess = "missed";
        }
        $game5_guess = trim($pieces[5]);
        if($game5_guess=="-") {
            $game5_guess = "missed";
        }
        $query = "INSERT INTO user_guesses(user_id, match_1, match_2,match_3,match_4,match_5)
        VALUES ('$user_id','$game1_guess','$game2_guess','$game3_guess','$game4_guess','$game5_guess')"; // check dup email !
        //echo "$query"."\n";
        //break;
        $db->query($query);
    }
    $query = "UPDATE users u, user_guesses ug SET ug.user_name = u.user_name, ug.user_score = u.score WHERE ug.user_id = u.user_id";
    $db->query($query);
    /*
    UPDATE users u, user_guesses ug
    SET ug.user_name = u.user_name
    WHERE
    ug.user_id = u.user_id;
    */
} else {
    echo "Error processing the file\n";
}
fclose($handle);

?>


 