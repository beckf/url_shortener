<?php
include_once("config.php");

$tempurl = explode("/", $_SERVER["REQUEST_URI"]);
$url = $tempurl[1];

$lookupURL = $db->prepare("select redirect, id from redirects where shorturl = ? and enabled = '1'");
$lookupURL->execute(array($url));
$redirect = $lookupURL->fetch(PDO::FETCH_ASSOC);

if($redirect["redirect"]) {
    $browser = get_browser();
    $eventLog = $db->prepare("insert into access_log (url, redirect_id, ipaddr, referer, user_agent, browser, time_stamp) values (?, ?, ?, ?, ?, ?, NOW())");
    $eventLog->execute(array($redirect["redirect"], $redirect["id"], $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_REFERER"], $_SERVER["HTTP_USER_AGENT"], $browser->browser));
    header('Location: ' . $redirect["redirect"]);
} else {
    header('Location: ' . $missingRedirectSite);
}
?>
