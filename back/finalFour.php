<?php

include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

// connect to db
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
}
catch (Exception $e) {
    die($e->getMessage());
}

header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');

$allUserGuesses     = array();


// get all the match info
$query = "SELECT u.user_name, uff.Brazil, uff.Germany, uff.Netherlands, uff.Argentina FROM users u, user_final_four uff WHERE u.user_id = uff.user_id";

foreach ($db->iterate($query) as $row) {
    $finalFour = array();
    $finalFour['Brazil']        =  $row->Brazil;
    $finalFour['Germany']       =  $row->Germany;
    $finalFour['Netherlands']   =  $row->Netherlands;
    $finalFour['Argentina']     =  $row->Argentina;

    asort($finalFour);
    $finalFour = array_flip($finalFour);
    $allUserGuesses []= array(
        'user_name'     => $row->user_name,
        'finalFour'     => $finalFour
    );
}


$response = array (
    'allUserGuesses'        => $allUserGuesses
);

$encoded_response = json_encode($response);
exit($encoded_response);
?>
 
