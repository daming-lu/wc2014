<?php
include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

$user_name  = $postData['userName'];
$user_id    = $postData['userID'];

file_put_contents(
	"./logs/log1",
	"display postData \n[".print_r($postData,true)."]\n",
	FILE_APPEND|LOCK_EX
);

// connect to db
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

// get the left ranking
$conciseRanking = getConciseRanking($db);

header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');

$redirectToMainPage = array (
    "whereTo"           => "main",
    "Name"              => "To Get UserName",
    "isLoginSuccessful" => true,
    'conciseRanking'   => $conciseRanking
);
//$_SESSION["user_name"] = "user_name";

$encoded = json_encode($redirectToMainPage);



exit($encoded);


function getConciseRanking($db) {
    $conciseRanking = array();

    $query = "SELECT user_id, match_1, match_2 FROM user_guesses";
    $result = $db->iterate($query);
    foreach ($db->iterate($query) as $row) {
        $conciseRanking[$row->user_id] = array(
            'match_1'   => $row->match_1,
            'match_2'   => $row->match_2);
    }
    return $conciseRanking;
}