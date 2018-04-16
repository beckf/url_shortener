<?php

if (!$db) {
    $db = new PDO('mysql:host=localhost;dbname=url_redirect;charset=latin1', 'url_redirect_db_user', 'url_redirect_db_password');
}

$ldapServer = "ldap.domain.tld";
$allowedGroups = array("CN=FacultyStaff,OU=Groups,DC=domain,DC=tld");
$host = "tinyurlhostname.domain.tld";
$ad_domain = "ad.domain.tld";
$missingRedirectSite = "http://www.github.com";

?>
