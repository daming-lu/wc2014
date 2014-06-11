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

$query = "DELETE FROM users WHERE user_name in ('1','2', '3', '4')"; // check dup email !

$query = "DROP TABLE IF EXISTS matches";

$db->query($query);

$query = "CREATE TABLE matches(match_num INT(11) NOT NULL AUTO_INCREMENT, left_team varchar (50), right_team varchar (50), match_time DATETIME, PRIMARY KEY(match_num))";
echo $query."\n";
$db->query($query);

// open file, parse string and insert into database

$handle = fopen("group_matches.txt", "r");
if ($handle) {
    $date_prefix = "";
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        $pieces = explode(" ",$line);
        $day = "";
        $month = "07";
        if (count($pieces)==4 && trim($pieces[3])=="2014") {
            if($pieces[1]=="JUNE") {
                $month = "06";
            }
            $day = rtrim($pieces[2], ",");
            $date_prefix = "2014-".$month."-".$day;
            echo "date prefix : ".$date_prefix."\n";
            continue;
        }
        if(count($pieces)>=8) {
            $time = $pieces[1];
            echo "date time : ".$date_prefix." ".$time."\n";

            $datetime = new DateTime($date_prefix." ".$time, new DateTimeZone('GMT'));
            $tz = new DateTimeZone('America/Los_Angeles');
            $datetime->setTimezone($tz);
            $str_datetime = $datetime->format("Y-m-d H:i:s");
            echo "\$str_datetime : $str_datetime\n";


            $left_team = "";
            $i = 3;
            while(true) {
                if ($pieces[$i]=="VS") {
                    break;
                }
                $left_team .= $pieces[$i]." ";
                $i++;
            }
            $left_team = trim($left_team);

            $right_team = "";
            $i++;
            while(true) {
                if ($pieces[$i]=="Group") {
                    break;
                }
                $right_team .= $pieces[$i]." ";
                $i++;
            }
            $right_team = trim($right_team);
            $query = "INSERT INTO matches(left_team, right_team,match_time) VALUES ('$left_team','$right_team','$str_datetime')"; // check dup email !
            $db->query($query);
        }
    }
} else {
    // error opening the file.
    echo "Error processing the file\n";
}
fclose($handle);

