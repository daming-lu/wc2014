<?php
include_once('./mysqldatabase.php');
include_once('./mysqlresultset.php');
include_once('./constants.php');

// connect to db
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

$query = "SELECT user_email FROM users";

foreach ($db->iterate($query) as $row) {
    file_put_contents(
        "allEmails.txt",
        print_r($row->user_email,true).",",
        FILE_APPEND|LOCK_EX
    );
}
 
