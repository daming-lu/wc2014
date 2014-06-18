<?php

include_once('../db_singleton/mysqldatabase.php');
include_once('../db_singleton/mysqlresultset.php');
include_once('./constants.php');

echo "\nphp judge_win_loss.php [from_match_id] [to_match_id] [weight=1]\n";

$fromMatchNum = -1;
$toMatchNum = -1;

if ($argc < 3) {
    echo "missing args\n";
    exit(1);
}

$fromMatchNum = $argv[1];
$toMatchNum = $argv[2];

$win_loss_weight = 1;
if($argc == 4) {
    $win_loss_weight = $argv[3];
}

$fromMatchNum = intval($fromMatchNum);
$toMatchNum = intval($toMatchNum);

if ($fromMatchNum > $toMatchNum) {
    echo "from > to !\n";
    exit(1);
}

$db = MySqlDatabase::getInstance();

try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
} catch (Exception $e) {
    die($e->getMessage());
}

// judge each match
$i=0;
for($i=$fromMatchNum; $i<=$toMatchNum; $i++) {
    // get the match result of match $i
    $query = "SELECT result, weight FROM matches WHERE match_id = $i";
    $result = $db->iterate($query);
    if ($result->getNumRows()!=1) {
        DB_Constants::sendAlertEmail("match $i is not unique\n");
    }
    $curMatchResult = "";
    $curMatchWeight = 3;
    foreach ($result as $row) {
        if(is_null($row->result)) {
            echo "Is null!";
            DB_Constants::sendAlertEmail("match $i result is NULL\n");
        }
        //echo "$row->result\n";
        $curMatchResult = trim($row->result);
        $curMatchWeight = $row->weight;
    }

    $match_name = "match_".strval($i);

    //$query = "UPDATE user_guesses SET $match_name_isCorrect = FALSE WHERE $match_name IS NULL OR $match_name != '$curMatchResult'";
    $query = "SELECT user_id, $match_name FROM user_guesses WHERE $match_name IS NOT NULL AND $match_name != 'missed'";
    $result = $db->iterate($query);
    foreach ($result as $row) {
        if (isWinLossCorrect($curMatchResult, $row->$match_name)) {
            $guess = $row->$match_name;
            $correct_user_id = $row->user_id;
            echo "$row->user_id guesses $match_name win_loss correct :). His guess is $guess, the actual result is $curMatchResult\n";
            $query = "UPDATE user_guesses SET user_score = user_score+$win_loss_weight WHERE user_id = $correct_user_id";
            $db->update($query);
        }
    }

}

function isWinLossCorrect($matchResult, $guess) {
    //echo "matchResult = $matchResult\n";
    //echo "guess = $guess\n";
    $pieces1 = explode(":",$matchResult);
    $win_loss = intval($pieces1[0]) - intval($pieces1[1]);

    $pieces2 = explode(":", $guess);
    $guess_win_loss = intval($pieces2[0]) - intval($pieces2[1]);

    if($win_loss>0 && $guess_win_loss>0 || $win_loss==0 && $guess_win_loss==0 || $win_loss<0 && $guess_win_loss<0) {
        return true;
    }
    return false;
}

?>