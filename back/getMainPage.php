<?php
include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

$user_name  = $postData['userName'];
$user_id    = $postData['userID'];

// connect to db
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

// get the left ranking
$conciseRanking = getConciseRanking($db);

header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');

$responseToMainPage = array (
    'conciseRanking'   => $conciseRanking
);

$encoded = json_encode($responseToMainPage);



exit($encoded);


function getConciseRanking($db) {
    $conciseRanking = array();

    $match_prefix = "match_";

    $handle = fopen("nextMatchID.txt", "r");
    $line = fgets($handle);
    $nextMatchID = intval($line);
    file_put_contents(
    	"./logs/log2",
    	"curMatchID : ".print_r($nextMatchID,true)."\n",
    	FILE_APPEND|LOCK_EX
    );

    // you have to deal with nextMatch anyway:
    $next_match_name = $match_prefix.strval($nextMatchID);
    $query = "SELECT left_team, right_team FROM matches WHERE match_id = $nextMatchID";
    $next_match_left_team = "";
    $next_match_right_team = "";
    foreach ($db->iterate($query) as $row) {
        $next_match_left_team = $row->left_team;
        $next_match_right_team = $row->right_team;
        break;
    }
    $conciseRanking['PastMatch'] = "";
    $conciseRanking['NextMatch'] = array(
        "left_team" => $next_match_left_team,
        "right_team" => $next_match_right_team
    );

    // first match, then 'Past Match' is empty
    if ($nextMatchID == 1) {
        // retrieve user guesses
        $conciseRanking['user_guesses'] = array();
        $query = "SELECT user_id, user_name, $next_match_name, user_score FROM user_guesses ORDER BY user_score DESC LIMIT 30";
        foreach ($db->iterate($query) as $row) {
            $conciseRanking['user_guesses'] []= array(
                'user_name' => $row->user_name,
                'past_match'   => "",
                'next_match'   => $row->$next_match_name,
                'user_score'=> $row->user_score
            );
        }
        return $conciseRanking;
    } else {
        // also need past match info
        $pastMatchID = $nextMatchID-1;
        $past_match_name = $match_prefix.strval($pastMatchID);
        $query = "SELECT left_team, right_team FROM matches WHERE match_id = $pastMatchID";
        $past_match_left_team = "";
        $past_match_right_team = "";
        foreach ($db->iterate($query) as $row) {
            $past_match_left_team = $row->left_team;
            $past_match_right_team = $row->right_team;
            break;
        }
        $conciseRanking['PastMatch'] = array(
            "left_team" => $past_match_left_team,
            "right_team" => $past_match_right_team
        );
        // retrieve user guesses
        $conciseRanking['user_guesses'] = array();
        $query = "SELECT user_id, user_name, $past_match_name, $next_match_name, user_score FROM user_guesses ORDER BY user_score DESC LIMIT 30";
        foreach ($db->iterate($query) as $row) {
            $conciseRanking['user_guesses'] []= array(
                'user_name' => $row->user_name,
                'past_match'   => $row->$past_match_name,
                'next_match'   => $row->$next_match_name,
                'user_score'=> $row->user_score
            );
        }
        return $conciseRanking;
    }
}