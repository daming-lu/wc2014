<?php
session_start();

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    // session started more than 30 minutes ago
    session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

$user_email     = $postData['email'];
$user_password  = $postData['password'];
$password = filter_var($user_password, FILTER_SANITIZE_STRING);

$form_token = $postData['token'];

if($form_token != $_SESSION['form_token']) {
    header('HTTP/1.0' . ' ' . '401' . ' ' . 'invalid login');
    exit('invalid login');
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

$result = $db->iterate($query);

if ($result->getNumRows()!=1) {
    $count = $result->getNumRows();
    $_SESSION['login'] = "";
    header('HTTP/1.0' . ' ' . '401' . ' ' . 'OK');
    exit('login failed!');
} else {

    $cur_row = $result->getResultResource();
    $row = mysql_fetch_assoc($result->getResultResource());

    $_SESSION['login'] = sha1($row['user_name'].$row['user_id']);
    $_SESSION['user_name'] = $row['user_name'];
    $_SESSION['user_id'] = $row['user_id'];
    header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');
    $GLOBALS['http_response_code'] = '200';

    http_response_code(200);
    exit();
}
