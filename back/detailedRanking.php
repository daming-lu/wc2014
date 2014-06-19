<?php

include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

// connect to db
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');

$allGroupMatches    = array();
$allUserGuesses     = array();

$maxMatchID = 48; // all the group matches

// get all the match info
$query = "SELECT match_id, left_team, right_team, match_time, result FROM matches WHERE match_id >= 1 AND match_id <= $maxMatchID ";

foreach ($db->iterate($query) as $row) {
    $allGroupMatches []= array(
        'match_id'      => $row->match_id,
        'left_team'     => $row->left_team,
        'right_team'    => $row->right_team,
        'match_time'    => $row->match_time,
        'result'        => $row->result
    );
}

// get all the user guesses
$query = "SELECT * FROM user_guesses ORDER BY user_score DESC";

foreach ($db->iterate($query) as $row) {
    $cur_user_guesses = array();
    $cur_user_guesses['user_name'] = $row->user_name;
    $cur_user_guesses['user_score'] = $row->user_score;
    $match_id = 1;
    while ($match_id <= $maxMatchID) {
        $cur_match_id = "match_".strval($match_id);
        $cur_match_isCorrect = "match_".strval($match_id)."_is_correct";
        //$cur_user_guesses['match_guesses'] = array();
        //$cur_user_guesses['match_guesses'][$match_id]= isset($row->$cur_match_id)?$row->$cur_match_id:"";
        $cur_user_guesses['match_guesses'][]= isset($row->$cur_match_id)?$row->$cur_match_id:"";

        //$cur_user_guesses['match_guesses_isCorrect'] = array();
        //$cur_user_guesses['match_guesses_isCorrect'][$match_id]= isset($row->$cur_match_isCorrect)?$row->$cur_match_isCorrect:"";
        $cur_user_guesses['match_guesses_isCorrect'][]= isset($row->$cur_match_isCorrect)?$row->$cur_match_isCorrect:"";
        $match_id++;
    }
    $allUserGuesses []= $cur_user_guesses;
}




$response = array (
    'allGroupMatches'       => $allGroupMatches,
    'allUserGuesses'        => $allUserGuesses
);

$encoded_response = json_encode($response);
//print_r($encoded_response);
exit($encoded_response);

?>
 