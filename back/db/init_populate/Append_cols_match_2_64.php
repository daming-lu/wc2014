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

// ALTER TABLE `user_guesses` ADD `haha` INT NOT NULL ;
$prefix = "match_";

for ($i=2; $i<=64; $i++) {
    $cur_num = strval($i);
    $cur_col = $prefix.$cur_num;
    $query = "ALTER TABLE user_guesses ADD $cur_col varchar(10)";
    //echo "$query"."\n";
    $db->query($query);
    $cur_col .= "_is_correct";
    $query = "ALTER TABLE user_guesses ADD $cur_col BOOLEAN";
    $db->query($query);
    //echo "$query"."\n";
    //break;
}