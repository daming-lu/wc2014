<?php
	
$format = 'json';	

file_put_contents(
	"log1",
	"_POST : \n".print_r($_POST,true)."\n",
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

if( strcasecmp($format,'json') == 0 ){
	// Set HTTP Response Content Type
	header('Content-Type: application/json; charset=utf-8');

	// Format data into a JSON response
	$json_response = json_encode($response);

	// Deliver formatted data
	echo $json_response;

}
?>
