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

// create users table

$query = "DROP TABLE IF EXISTS user_guesses"; // MUST drop this first ?
$db->query($query);

$query = "DROP TABLE IF EXISTS users";
$db->query($query);

$query = "
    CREATE TABLE users(user_id INT(11) NOT NULL AUTO_INCREMENT,
    user_name varchar (50), password varchar (50), user_email varchar (50), last_login_time DATETIME,PRIMARY KEY(user_id))";

echo $query."\n";
$db->query($query);

$handle = fopen("fake_users_info.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $pieces = explode(',',$line);
        if(count($pieces)!=3) {
            continue;
        }
        $user_name          = trim($pieces[0]);
        $user_email         = trim($pieces[1]);
        $user_password      = md5(trim($pieces[2]));

        $query = "INSERT INTO users(user_name, password,user_email) VALUES ('$user_name','$user_password', '$user_email')"; // check dup email !
        $db->insert($query);
    }
} else {
    // error opening the file.
    echo "Error processing the file\n";
}

// create user_guesses table


$query = "DROP TABLE IF EXISTS user_guesses";
$db->query($query);

$query = "
    CREATE TABLE user_guesses(
    user_guess_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    match_1 varchar(10), match_2 varchar(10),
    PRIMARY KEY(user_guess_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
    )";

echo "\nForeign key : $query \n";
$db->query($query);

$handle = fopen("fake_user_guesses.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $pieces = explode(',',$line);
        if(count($pieces)!=3) {
            continue;
        }
        $user_id = trim($pieces[0]);
        $game1_guess = trim($pieces[1]);
        if($game1_guess=="-") {
            $game1_guess = "missed";
        }
        $game2_guess = trim($pieces[2]);
        if($game2_guess=="-") {
            $game2_guess = "missed";
        }
        $query = "INSERT INTO user_guesses(user_id, match_1, match_2)
        VALUES ('$user_id','$game1_guess','$game2_guess')"; // check dup email !
        $db->query($query);
    }
} else {
    echo "Error processing the file\n";
}
fclose($handle);
