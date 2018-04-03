<?php

if(isset($_POST["shorturl"])) {

    include_once("config.php");

    $url_verify = $db->prepare("select 'x' from redirects where shorturl = ?");
    $url_verify->execute(array($_POST["shorturl"]));

    if ($url_verify->fetchAll()) {
        echo('<img src="red_x.png" />');
    } else {
        echo('<img src="green_check.png" />');
    }
}
?>