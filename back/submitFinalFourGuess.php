<?php
/**
 * @package 
 * @author Brian Backhaus <brianb@zoosk.com>
 * @copyright Copyright (c) 2007-20011 Zoosk Inc.
 * @version $Id$
 */
 
include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

$user_name  = $postData['userName'];
$user_id    = $postData['userID'];
$user_guesses = $postData['guesses'];
$submit_time = $postData['timestamp'];

// connect to db
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

// test if exists
$query = "SELECT user_final_four_id FROM user_final_four WHERE user_id = $user_id";
$result = $db->iterate($query);
if ($result->getNumRows()==0) {
    $query = "INSERT INTO user_final_four (user_id, Brazil) VALUES ($user_id,1)";

    $db->insert($query);
}

// user 1st fill
foreach ($user_guesses as $team => $rank) {
    //$query = "UPDATE user_guesses ug SET ug.$match_name = '$guess' WHERE ug.user_id = $user_id";

    $query = "UPDATE user_final_four uff  SET uff.$team = $rank WHERE uff.user_id = $user_id";

    $db->update($query);

    //break;
}
header('HTTP/1.0' . ' ' . '200' . ' ' . 'Your submission succeeded!');
$ff_success['msg'] = 'Your final-four submission succeeded!';
$encoded = json_encode($ff_success);

exit($encoded);
