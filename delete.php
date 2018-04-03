<?php

session_start();

if($_SESSION["loggedin"] != true) {
    header('Location: login.php');
}

include_once("config.php");

if ($_POST["id"] && $_SESSION["username"]){
    $delete = $db->prepare("update redirects set enabled = '0' where owner = ? and id = ?");
    $delete->execute(array($_SESSION["username"], $_POST["id"]));
}


?>