<?php
include_once('./db/db_singleton/mysqldatabase.php');
include_once('./db/db_singleton/mysqlresultset.php');
include_once('./constants.php');

$data = file_get_contents("php://input");
$postData = json_decode($data,true);

$user_name  = $postData['userName'];
$user_id    = $postData['userID'];

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

$upcomingMatches = getUpcomingMatches($db, $user_id);

//$userCurrentGuesses = $upcomingMatches['userCurrentGuesses'];
//unset($upcomingMatches['userCurrentGuesses']);

header('HTTP/1.0' . ' ' . '200' . ' ' . 'OK');

$responseToMainPage = array (
    'conciseRanking'    => $conciseRanking,
    'upcomingMatches'   => $upcomingMatches
);

$encoded = json_encode($responseToMainPage);

exit($encoded);
//------------------------------------------------------------------------------------------------
function getUpcomingMatches($db, $user_id, $time="") {
    $result = array();

    $userCurrentGuesses = array();
    $match_ids = array();

    $curTime = $time;
    if($time=="") {
        //$curTime = DB_Constants::getTime("2014-06-12 12:00:00 ");
        $curTime = DB_Constants::getTime();
        $curDay = DB_Constants::getTimeToday();
    }
    $query = "SELECT match_id, left_team, right_team, match_time FROM matches WHERE match_time BETWEEN '$curTime' AND DATE_ADD('$curDay',INTERVAL + 3 DAY)";

    foreach ($db->iterate($query) as $row) {

        $user_guess_left = "";
        $user_guess_right = "";
        $match_id_str = "match_".strval($row->match_id);


        $guess_query = "SELECT $match_id_str FROM user_guesses WHERE user_id = $user_id";

        foreach ($db->iterate($guess_query) as $guess_row) {

            $user_guess = $guess_row->$match_id_str;
            if (!is_null($user_guess)) {
                $pieces = explode(":", $user_guess);
                $user_guess_left = $pieces[0];
                $user_guess_right = $pieces[1];
            }
            break;
        }

        $result []= array(
            'match_id'          => $row->match_id,
            'left_team'         => $row->left_team,
            'right_team'        => $row->right_team,
            'match_time'        => $row->match_time,
            'user_guess_left'   => $user_guess_left,
            'user_guess_right'  => $user_guess_right
        );
        //break;
    }

    return $result;
}

//------------------------------------------------------------------------------------------------
function getConciseRanking($db) {
    $conciseRanking = array();

    $match_prefix = "match_";

    $handle = fopen("nextMatchID.txt", "r");
    $line = fgets($handle);
    $nextMatchID = intval($line);

    // you have to deal with nextMatch anyway:
    $next_match_name = $match_prefix.strval($nextMatchID);
    $query = "SELECT left_team, right_team FROM matches WHERE match_id = $nextMatchID";
    $next_match_left_team = "";
    $next_match_right_team = "";
    foreach ($db->iterate($query) as $row) {
        $next_match_left_team = $row->left_team;
        $next_match_right_team = $row->right_team;
        break;
    }
    $conciseRanking['PastMatch'] = "";
    $conciseRanking['NextMatch'] = array(
        "left_team" => $next_match_left_team,
        "right_team" => $next_match_right_team
    );

    // first match, then 'Past Match' is empty
    if ($nextMatchID == 1) {
        // retrieve user guesses
        $conciseRanking['user_guesses'] = array();
        $query = "SELECT user_id, user_name, $next_match_name, user_score FROM user_guesses ORDER BY user_score DESC LIMIT 50";
        foreach ($db->iterate($query) as $row) {
            $conciseRanking['user_guesses'] []= array(
                'user_name' => $row->user_name,
                'past_match'   => "",
                'next_match'   => $row->$next_match_name,
                'user_score'=> $row->user_score
            );
        }
        return $conciseRanking;
    } else {
        // also need past match info
        $pastMatchID = $nextMatchID-1;
        $past_match_name = $match_prefix.strval($pastMatchID);
        $query = "SELECT left_team, right_team FROM matches WHERE match_id = $pastMatchID";
        $past_match_left_team = "";
        $past_match_right_team = "";
        foreach ($db->iterate($query) as $row) {
            $past_match_left_team = $row->left_team;
            $past_match_right_team = $row->right_team;
            break;
        }
        $conciseRanking['PastMatch'] = array(
            "left_team" => $past_match_left_team,
            "right_team" => $past_match_right_team
        );
        // retrieve user guesses
        $conciseRanking['user_guesses'] = array();
        $query = "SELECT user_id, user_name, $past_match_name, $next_match_name, user_score FROM user_guesses ORDER BY user_score DESC LIMIT 50";
        foreach ($db->iterate($query) as $row) {
            $conciseRanking['user_guesses'] []= array(
                'user_name' => $row->user_name,
                'past_match'   => $row->$past_match_name,
                'next_match'   => $row->$next_match_name,
                'user_score'=> $row->user_score
            );
        }
        return $conciseRanking;
    }
}
//------------------------------------------------------------------------------------------------
