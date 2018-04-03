<?php

session_start();

if($_SESSION["loggedin"] != true) {
    header('Location: login.php');
}

include_once("config.php");

if ($_POST["id"] && $_SESSION["username"]){
    // Verify user owns this url and store the new value in stat_id
    $stat_verify = $db->prepare("select id from redirects where owner = ? and id = ?");
    $stat_verify->execute(array($_SESSION["username"], $_POST["id"]));
    $stat_id = $stat_verify->fetch(PDO::FETCH_ASSOC);

    $obtain_stats = $db->prepare("select * from access_log where redirect_id = ?");
    $obtain_stats->execute(array($stat_id["id"]));
    $stat_data = $obtain_stats->fetchAll();

    // Hit Counts
    print("<strong>Hit Counts</strong><br />");
    //Print 30 Days Count
    $ThirtyDayCount = 0;
    foreach($stat_data as $val){
        if ($val["time_stamp"] > date("Y-m-d H:i:s", strtotime('-30 days'))){
            $ThirtyDayCount++;
        }
    }
    print("Hits in the last 30 days:  " . $ThirtyDayCount . "<br />");

    //Print 60 Days Count
    $SixtyDayCount = 0;
    foreach($stat_data as $val){
        if ($val["time_stamp"] > date("Y-m-d H:i:s", strtotime('-60 days'))){
            $SixtyDayCount++;
        }
    }
    print("Hits in the last 60 days:  " . $SixtyDayCount . "<br />");

    //Print 90 Days Count
    $NinetyDayCount = 0;
    foreach($stat_data as $val){
        if ($val["time_stamp"] > date("Y-m-d H:i:s", strtotime('-90 days'))){
            $NinetyDayCount++;
        }
    }
    print("Hits in the last 90 days:  " . $NinetyDayCount . "<br />");

    // Referer Data
    print("<br /><strong>Referer Stats - By Domain</strong><br />");
    $re = array();
    foreach($stat_data as $key => $value) {
        $re[] = parse_url($value["referer"], PHP_URL_HOST);
    }

    foreach(array_count_values($re) as $key => $val) {
        print($key . ":  <strong>" . $val . "</strong><br />");
    }

    // Output User Agent Data
    print("<br /><strong>User Agent Stats</strong><br />");
    $ua = array();
    foreach($stat_data as $val) {
        $ua[] = $val["user_agent"];
    }
    foreach(array_count_values($ua) as $key => $val) {
        print($key . ":  <strong>" . $val . "</strong><br />");
    }


}



?>