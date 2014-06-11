<?php
session_start();

//$_SESSION['form_token'];

file_put_contents(
	"./logs/log1",
	"_SESSION : ".print_r($_SESSION,true)."\n",
	FILE_APPEND|LOCK_EX
);

include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

$user_email     = $postData['email'];
$user_password  = $postData['password'];

$form_token = $postData['token'];

if($form_token == $_SESSION['form_token']) {
    file_put_contents(
    	"./logs/log2",
    	"form_token == _SESSION['form_token']\n",
    	FILE_APPEND|LOCK_EX
    );
} else {
    file_put_contents(
    	"./logs/log2",
    	"form_token != _SESSION['form_token']\n NOOOOOOOO!",
    	FILE_APPEND|LOCK_EX
    );
}

$user_password = md5($user_password);

$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

$query = "SELECT * FROM users WHERE user_email = '$user_email' AND password = '$user_password'";
// to trigger return results that is more than 1, we can simply remove one 'where' so that
// users with same password can be returned :)

file_put_contents(
	"./logs/log5",
	"$query",
	FILE_APPEND|LOCK_EX
);

$result = $db->iterate($query);
file_put_contents(
	"./logs/log5",
	"result == ".print_r($result,true)."\n",
	FILE_APPEND|LOCK_EX
);
if ($result->getNumRows()!=1) {
    $count = $result->getNumRows();
    file_put_contents(
    	"./logs/log4",
    	"result->getNumRows()!=1, == $count"."\n",
    	FILE_APPEND|LOCK_EX
    );
    $_SESSION['login'] = "";
    //header ("Location: ../front/main.php");
    //header ("Location: http://damingl-mbp15ret.zoosk.local/wc2014/front/main.php");
    header('HTTP/1.0' . ' ' . '401' . ' ' . 'OK');
    //$GLOBALS['http_response_code'] = '200';
    exit();
} else {
    file_put_contents(
    	"./logs/log4",
    	"login works!!!"."\n",
    	FILE_APPEND|LOCK_EX
    );
    $cur_row = $result->getResultResource();


    $row = mysql_fetch_assoc($result->getResultResource());
    file_put_contents(
    	"./logs/log4",
    	"cur_row \n[".print_r($row,true)."]\n",
    	FILE_APPEND|LOCK_EX
    );
    $_SESSION['login'] = "1";



    $_SESSION['user_name'] = $row['user_name'];
    $_SESSION['user_id'] = $row['user_id'];
    //header ("Location: ../front/main.php");
    //header ("Location: http://damingl-mbp15ret.zoosk.local/wc2014/front/main.php");
    header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');
    $GLOBALS['http_response_code'] = '200';

    /*
    //echo '200';
    HttpResponse::status(200);
    HttpResponse::setContentType('text/xml');
    HttpResponse::setHeader('From', 'Lymber');
    HttpResponse::setData('<?xml version="1.0"?><note>Thank you for posting your data! We love php!</note>');
    HttpResponse::send();    //exit;
    */
    http_response_code(200);

    $redirectToMainPage = array (
        "whereTo"           => "main",
        "Name"              => "To Get UserName",
        "isLoginSuccessful" => true
    );
    //$_SESSION["user_name"] = "user_name";

    $encoded = json_encode($redirectToMainPage);

    exit($encoded);
    //$b = http_response_code(200);

}
