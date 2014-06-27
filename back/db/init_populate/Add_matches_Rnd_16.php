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
$handle = fopen("round_of_16.txt", "r");
if ($handle) {
    $date_prefix = "";
    $match_name_prefix = "match_";
    $match_name_id = 49;
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        //$pieces = explode(" ",$line);
        $pieces = preg_split("/[\s,]+/", $line);
        //print_r($pieces);
        //continue;
        $day = "";
        $month = "07";
        if(count($pieces)<3) {
            continue;
        }
        if (trim($pieces[3])=="2014") {
            if($pieces[2]=="June") {
                $month = "06";
            }
            $day = trim($pieces[1], ",");
            $date_prefix = "2014-".$month."-".$day;
            echo "date prefix : ".$date_prefix."\n";
            continue;
        }
        if($pieces[1]=="vs") {
            $time = $pieces[3];
            $time = str_replace(".",":",$time);
            echo "date time : ".$date_prefix." ".$time."\n";

            $datetime = new DateTime($date_prefix." ".$time, new DateTimeZone('GMT'));
            $tz = new DateTimeZone('America/Los_Angeles');
            $datetime->setTimezone($tz);
            $str_datetime = $datetime->format("Y-m-d H:i:s");
            echo "\$str_datetime : $str_datetime\n";


            $left_team = trim($pieces[0]);

            /*
            $right_team = "";
            $i++;
            while(true) {
                if ($pieces[$i]=="Group") {
                    break;
                }
                $right_team .= $pieces[$i]." ";
                $i++;
            }
            */
            $right_team = trim($pieces[2]);
            $m_name = $match_name_prefix.strval($match_name_id);
            $match_name_id++;
            $query = "INSERT INTO matches(match_name, left_team, right_team, match_time, weight) VALUES ('$m_name','$left_team','$right_team','$str_datetime', 4)"; // check dup email !
            echo "query is :\n";
            echo $query."\n";
            //break;
            $db->query($query);
            if($match_name_id > 56) {
                echo "49-56 done\n";
                break;
            }
        }
        if($pieces[2]=="vs") {
            $time = $pieces[4];
            $time = str_replace(".",":",$time);
            echo "date time : ".$date_prefix." ".$time."\n";

            $datetime = new DateTime($date_prefix." ".$time, new DateTimeZone('GMT'));
            $tz = new DateTimeZone('America/Los_Angeles');
            $datetime->setTimezone($tz);
            $str_datetime = $datetime->format("Y-m-d H:i:s");
            echo "\$str_datetime : $str_datetime\n";


            $left_team = trim($pieces[0]." ".$pieces[1]);

            /*
            $right_team = "";
            $i++;
            while(true) {
                if ($pieces[$i]=="Group") {
                    break;
                }
                $right_team .= $pieces[$i]." ";
                $i++;
            }
            */
            $right_team = trim($pieces[3]);
            $m_name = $match_name_prefix.strval($match_name_id);
            $match_name_id++;
            $query = "INSERT INTO matches(match_name, left_team, right_team, match_time, weight) VALUES ('$m_name','$left_team','$right_team','$str_datetime', 4)"; // check dup email !
            echo "query is :\n";
            echo $query."\n";
            //break;
            $db->query($query);
            if($match_name_id > 56) {
                echo "49-56 done\n";
                break;
            }
        }
    }
} else {
    // error opening the file.
    echo "Error processing the file\n";
}
fclose($handle);
?>
 