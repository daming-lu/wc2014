<?php

include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

$db = MySqlDatabase::getInstance();

try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
} catch (Exception $e) {
    die($e->getMessage());
}

$query = "SELECT * from matches WHERE match_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 2 DAY)";
echo "$query\n";
foreach ($db->iterate($query) as $row) {

    echo $row->left_team." vs ".$row->right_team." @ ".$row->match_time."\n";
}

echo "\n";

