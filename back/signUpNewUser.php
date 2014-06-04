<?php

include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');


$format = 'json';	

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

file_put_contents(
	"./logs/log1",
	"postData : \n".print_r($postData,true)."\n",
	FILE_APPEND|LOCK_EX
);

$api_response_code = array(
    0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'),
    1 => array('HTTP Response' => 200, 'Message' => 'Success'),
    2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'),
    3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'),
    4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'),
    5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request'),
    6 => array('HTTP Response' => 400, 'Message' => 'Invalid Response Format')
);



	$response['code'] = 1;
	$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
	//$response['data'] = 'Hello World, Daming\'s here :)';

	$data = file_get_contents("users.json");
	$response['data'] = json_decode($data,true);


// >> test db
    // get the MySqlDatabase instance
    $db = MySqlDatabase::getInstance();
    try {
        $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
    }
    catch (Exception $e) {
        die($e->getMessage());
    }

    $query = "SELECT * FROM users LIMIT 10";
    foreach ($db->iterate($query) as $row) {
        file_put_contents(
        	"./logs/log2",
        	"row : \n".print_r($row,true)."\n",
        	FILE_APPEND|LOCK_EX
        );
    }


    $user_name      = $postData['fullName'];
    $user_email     = $postData['email'];
    $user_password  = $postData['password'];
file_put_contents(
    "./logs/log5",
    "$user_name : \n".print_r($user_name,true)."\n",
    FILE_APPEND|LOCK_EX
);
file_put_contents(
    "./logs/log5",
    "$user_email : \n".print_r($user_email,true)."\n",
    FILE_APPEND|LOCK_EX
);
file_put_contents(
    "./logs/log5",
    "$user_password : \n".print_r($user_password,true)."\n",
    FILE_APPEND|LOCK_EX
);

    $query = "INSERT INTO users(user_name, password,user_email) VALUES ('$user_name','$user_password', '$user_email')"; // check dup email !
file_put_contents(
    "./logs/log4",
    "$query : \n".print_r($query,true)."\n",
    FILE_APPEND|LOCK_EX
);
    $last_id = $db->insert($query);
    file_put_contents(
        "./logs/log3",
        "$last_id : \n".print_r($last_id,true)."\n",
        FILE_APPEND|LOCK_EX
    );

if( strcasecmp($format,'json') == 0 ){
	// Set HTTP Response Content Type
	header('Content-Type: application/json; charset=utf-8');

	// Format data into a JSON response
	$json_response = json_encode($response);

	// Deliver formatted data
	echo $json_response;

}
?>
