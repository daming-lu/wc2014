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

$mock_users = array(
    array(
        'user_name'     =>      '1',
        'password'      =>      md5('1111'),
        'user_email'    =>      'handtemple@gmail.com'
    ),
    array(
        'user_name'     =>      '2',
        'password'      =>      md5('2222'),
        'user_email'    =>      'daminglu2014@hotmail.com'
    ),
    array(
        'user_name'     =>      '3',
        'password'      =>      md5('3333'),
        'user_email'    =>      'damingl@zoosk.com'
    ),
    array(
        'user_name'     =>      '4',
        'password'      =>      md5('4444'),
        'user_email'    =>      'lubigbright@yahoo.com'
    )
);

//$query = "INSERT INTO users(user_name, password,user_email) VALUES ('$user_name','$user_password', '$user_email')"; // check dup email !

$query = "DELETE FROM users WHERE user_name in ('1','2', '3', '4')"; // check dup email !

$db->delete($query);

foreach ($mock_users as $user) {
    $user_name          = $user['user_name'];
    $user_password      = $user['password'];
    $user_email         = $user['user_email'];
    $query = "INSERT INTO users(user_name, password,user_email) VALUES ('$user_name','$user_password', '$user_email')"; // check dup email !
    $db->insert($query);
}