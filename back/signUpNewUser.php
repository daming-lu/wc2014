<?php

include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

session_start();

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

// >> test db
// get the MySqlDatabase instance
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

    $user_name      = $postData['fullName'];
    $user_email     = $postData['email'];
    $user_password  = $postData['password'];

$username = filter_var($user_name, FILTER_SANITIZE_STRING);
$password = filter_var($user_password, FILTER_SANITIZE_STRING);

$user_password = md5($user_password);

try {
    $query = "INSERT INTO users(user_name, password,user_email) VALUES ('$user_name','$user_password', '$user_email')"; // check dup email !

    $last_id = $db->insert($query);

    $query = "SELECT user_id FROM users WHERE user_email = '$user_email'"; // check dup email !

    $user_id = "";

    $result = $db->iterate($query);
    if ($result->getNumRows() != 1) {
        // dup!
        header('HTTP/1.0' . ' ' . '401' . ' ' . 'duplicate account!');
        exit('duplicate account!');
    }
    foreach ($result as $row) {
        $user_id = $row->user_id;
        break;
    }
    $query = "INSERT INTO user_guesses (user_id, user_name) VALUES ('$user_id','$user_name')"; // check dup email !


    $last_id = $db->insert($query);

} catch(Exception $e)
{
   /*** check if the username already exists ***/
   if( $e->getCode() == 23000)
   {
       $message = 'Username already exists';
   }
   else
   {
       /*** if we are here, something has gone wrong with the database ***/
       $message = 'We are unable to process your request. Please try again later"';
   }
}

$_SESSION['user_name'] = $user_name;
$_SESSION['user_id'] = $user_id;
$_SESSION['login'] = sha1($user_name.$user_id);

header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');
$GLOBALS['http_response_code'] = '200';

http_response_code(200);
exit();
?>
