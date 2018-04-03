<?php

session_start();

if($_SESSION["loggedin"] != true) {
    header('Location: login.php');
}

include_once("config.php");

if ($_POST["shorturl"] && $_SESSION["username"]){
    $delete = $db->prepare("insert into redirects (shorturl, redirect, owner, date_created, enabled) values (?, ?, ?, NOW(), '1')");
    $delete->execute(array($_POST["shorturl"], $_POST["redirect_url"], $_SESSION["username"]));

    header('Location: manage.php');
}


?>