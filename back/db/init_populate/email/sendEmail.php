<?php
include_once('mysqldatabase.php');
include_once('mysqlresultset.php');
include_once('constants.php');

if ($argc != 2) {
    echo "missing dev/prod\n";
    exit(1);
}

$isDev = true;
if ($argv[1]==="prod") {
    $isDev = false;
}

$db = MySqlDatabase::getInstance();

try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
} catch (Exception $e) {
    die($e->getMessage());
}


// gather match info
$todayMatches = "\n";
$curTime = DB_Constants::getTime();
$curDay = DB_Constants::getTimeToday();

$query = "SELECT match_id, left_team, right_team, match_time FROM matches WHERE match_time BETWEEN '$curTime' AND DATE_ADD('$curDay',INTERVAL + 3 DAY)";
$result = $db->iterate($query);
foreach ($result as $row) {
    $todayMatches .= $row->left_team;
    $todayMatches .= " vs. ";
    $todayMatches .= $row->right_team;
    $todayMatches .= " @ ";
    $todayMatches .= $row->match_time." PDT \n";
}

$query = "";

if($isDev) {
    $query = "SELECT user_name, user_email FROM users WHERE user_id>0 AND user_id<=3";
} else {
    $query = "SELECT user_name, user_email FROM users WHERE user_id>0";

}
file_put_contents("misc.txt","", LOCK_EX);

$result = $db->iterate($query);
foreach ($result as $row) {
    $curEmail = $row->user_email;
    $curUserName = $row->user_name;
    $content = "Dear $curUserName, \n\n";
    $content .= "Upcoming matches for $curDay and the next 2 days are \n";
    $content .= $todayMatches;
    $content .="\n"."Please go to \n\n\t"."http://goo.gl/hN0ZLU"."\n\n"."to make your guesses for today :)\n\n";
    $content .="Good Luck!\nDaming\n";

    $content = wordwrap($content, 70);
    mail(
        $curEmail,
        "World Cup Guess for ".$curDay,
        $content
    );
    file_put_contents("misc.txt","$curEmail,", FILE_APPEND|LOCK_EX);
}


