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

$handle = fopen("fake_users_info.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $pieces = explode(',',$line);
        if(count($pieces)!=3) {
            continue;
        }
        $user_name          = trim($pieces[0]);
        $user_email         = trim($pieces[1]);
        $user_password      = md5(trim($pieces[2]));
        //$test_time = DB_Constants::getTime("2014-06-12 13:00:00");
        $test_time = DB_Constants::getTime();
        $query = "INSERT INTO users(user_name, password, user_email, last_login_time) VALUES ('$user_name','$user_password', '$user_email','$test_time')"; // check dup email !
        //echo "$query"."\n";
        $db->insert($query);
        //break;
    }
} else {
    // error opening the file.
    echo "Error processing the file\n";
}

?>
 
 