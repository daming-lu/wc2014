<?php
include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');
 
$data = file_get_contents("php://input");
$postData = json_decode($data,true);

$user_name  = $postData['userName'];
$user_id    = $postData['userID'];
$user_guesses = $postData['guesses'];
$submit_time = $postData['timestamp'];

// connect to db
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

foreach ($user_guesses as $match_id => $guess) {
    // sanity check
    $pieces = explode(':',$guess);
    if (strlen($guess)!=3 || count($pieces)!=2 || !is_numeric($pieces[0]) || !is_numeric($pieces[1])) {
        header('HTTP/1.0' . ' ' . '401' . ' ' . 'Your guess is in bad format.');
        exit();
    }

    //$submit_time
    $query = "SELECT left_team FROM matches WHERE match_time >= DATE_SUB('$submit_time',INTERVAL 7 HOUR) AND match_id = '$match_id'";


    $result = $db->iterate($query);
    if ($result->getNumRows()!=1) {
        header('HTTP/1.0' . ' ' . '401' . ' ' . 'Your submission expired.');
        exit();
    }

    $match_prefix = "match_";
    $match_name = $match_prefix.strval($match_id);
    $query = "UPDATE user_guesses ug SET ug.$match_name = '$guess' WHERE ug.user_id = $user_id";

    $db->update($query);
}
header('HTTP/1.0' . ' ' . '200' . ' ' . 'Your submission succeeded!');
exit('Your submission succeeded!');
