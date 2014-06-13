<?php

class DB_Constants {
    // local
/*
    public static $db_url           = "localhost";

    public static $db_username      = "root";
    public static $db_password      = "";

    //public static $db_name          = "daming2014";
    public static $db_name          = "damingTest";
*/
    // server
    public static $db_url           = "mysql.peirongli.dreamhosters.com";

    public static $db_username      = "daming";
    public static $db_password      = "WorldCup2014";

    public static $db_name          = "daming2014";

    public static function getTime($input = "") {
        if($input == "") {
            $timezone = date_default_timezone_get();
            //echo "default timezone = $timezone\n";
            date_default_timezone_set('America/Los_Angeles');
            $datetime = date('Y-m-d H:i:s',time());
            date_default_timezone_set($timezone);
            return $datetime;
        }
        $datetime = strtotime($input);
        $datetime = date('Y-m-d H:i:s',$datetime);
        return $datetime;
    }

    public static function getTimeToday($input = "") {
        if($input == "") {
            $timezone = date_default_timezone_get();
            //echo "default timezone = $timezone\n";
            date_default_timezone_set('America/Los_Angeles');
            $datetime = date('Y-m-d',time());
            date_default_timezone_set($timezone);
            return $datetime;
        }
        $datetime = strtotime($input);
        $datetime = date('Y-m-d',$datetime);
        return $datetime;
    }
};
?>
