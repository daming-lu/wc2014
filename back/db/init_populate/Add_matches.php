<?php
include_once('../db_singleton/mysqldatabase.php');
include_once('../db_singleton/mysqlresultset.php');
include_once('./constants.php');

$db = MySqlDatabase::getInstance();

try {
    $conn = $db->connect(DB_Constants::$db_url, DB_Constants::$db_username, DB_Constants::$db_password, DB_Constants::$db_name);
} catch (Exception $e) {
    die($e->getMessage());
}
$handle = fopen("group_matches.txt", "r");
if ($handle) {
    $date_prefix = "";
    $match_name_prefix = "match_";
    $match_name_id = 1;
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
            $m_name = $match_name_prefix.strval($match_name_id);
            $match_name_id++;
            $query = "INSERT INTO matches(match_name, left_team, right_team, match_time) VALUES ('$m_name','$left_team','$right_team','$str_datetime')"; // check dup email !
            $db->query($query);
        }
    }
} else {
    // error opening the file.
    echo "Error processing the file\n";
}
fclose($handle);
?>
 