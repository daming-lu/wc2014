<?php

include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

$user_email     = $postData['email'];
$user_password  = $postData['password'];
$user_password = md5($user_password);

$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

$query = "SELECT * FROM users WHERE user_email = '$user_email' AND password = '$user_password'";

file_put_contents(
	"./logs/log5",
	"$query",
	FILE_APPEND|LOCK_EX
);

$result = $db->iterate($query);

if ($result->getNumRows()!=1) {
    file_put_contents(
    	"./logs/log4",
    	"result->getNumRows()!=1"."\n",
    	FILE_APPEND|LOCK_EX
    );
} else {
    file_put_contents(
    	"./logs/log4",
    	"login works!!!"."\n",
    	FILE_APPEND|LOCK_EX
    );
    session_start();
    $_SESSION['login'] = "1";
    //header ("Location: ../front/main.php");
    //header ("Location: http://damingl-mbp15ret.zoosk.local/wc2014/front/main.php");
    header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');
    $GLOBALS['http_response_code'] = '200';


    //echo '200';
    HttpResponse::status(200);
    HttpResponse::setContentType('text/xml');
    HttpResponse::setHeader('From', 'Lymber');
    HttpResponse::setData('<?xml version="1.0"?><note>Thank you for posting your data! We love php!</note>');
    HttpResponse::send();    //exit;

    //$b = http_response_code(200);

}
