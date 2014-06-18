<?php

include_once('../db_singleton/mysqldatabase.php');
include_once('../db_singleton/mysqlresultset.php');
include_once('./constants.php');
 
$fromMatchNum = -1;
$toMatchNum = -1;

if ($argc != 3) {
    echo "missing args\n";
    exit(1);
}

$fromMatchNum = $argv[1];
$toMatchNum = $argv[2];

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
    $query = "SELECT result, weight FROM matches WHERE match_id = $i";
    $result = $db->iterate($query);
    //print_r($result->result);
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
    $match_name_isCorrect = $match_name."_is_correct";

    $query = "UPDATE user_guesses SET $match_name_isCorrect = TRUE WHERE $match_name = '$curMatchResult'";
    $db->update($query);

    $query = "UPDATE user_guesses SET $match_name_isCorrect = FALSE WHERE $match_name IS NULL OR $match_name != '$curMatchResult'";
    $db->update($query);

    $query = "UPDATE user_guesses SET user_score = user_score+$curMatchWeight WHERE $match_name = '$curMatchResult'";
    $db->update($query);

    //break;
}

// move nextMatchID
file_put_contents(
    "../../nextMatchID.txt",
    "$i\n",
    LOCK_EX
);
