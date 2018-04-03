<?php

// What to do for logging out.
if ($_GET["logout"]) {
    session_start();

    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
}

// What to do for logging in
if($_POST["username"] && $_POST["password"]) {

    include_once("config.php");

    // Meat and Potatoes
    $ldapConn = ldap_connect($ldapServer)
    or $this->msg = "Could not connect to LDAP server.";
    if ($ldapConn) {
        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);
    }

    // For bug in PHP where blank username or password will return true on ldap_bind
    if ($_POST['username'] == "" || $_POST['password'] == "") {
        header('Location: login.php?error=blank');
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
    }

    $ldapUPN = $username . "@" . $ad_domain;
    $ldapbind = ldap_bind($ldapConn, $ldapUPN, $password);

    if ($ldapbind) {
        $attributes_ad = array("displayName","givenName","sn","mail","sAMAccountName","memberOf");
        $ad_result = ldap_search($ldapConn, "DC=school, DC=da, DC=org", "(samaccountname=$username)", $attributes_ad);
        $ad_info = ldap_get_entries($ldapConn, $ad_result);

        if (array_intersect($allowedGroups, $ad_info[0]["memberof"])) {
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $ad_info[0]["samaccountname"][0];
            header('Location: manage.php');
        } else {
            header('Location: login.php');
        }
    } else {
        header('Location: login.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        URL - Login
    </title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
<!--            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>-->
            <a class="navbar-brand" href="#">DA URL Redirect</a>
        </div>
<!--        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>--><!--/.nav-collapse -->
    </div>
</nav>

<div class="container main">
    <br />
    <br />
    <div class="form">
        <?php
        if ($_GET[logout] == "true"){
            print ("<p class=\"warn_text\">Logged Out</p>");
        }
        ?>
        <h4>Please Login</h4>
        <form class="login-form" action="login.php" method="post">
            <input type="text" placeholder="Username" id="username" name="username"/>
            <br />
            <input type="password" placeholder="Password" id="password" name="password"/>
            <br />
            <button>Login</button>
        </form>
    </div>
</div>

</body>
</html>
