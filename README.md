# URL Redirect
This is a simple php project that will allow you to run a URL shortener using your own domain.  Users authenticate to Active Directory through LDAP.  Users can manage, create, and delete their own short urls.  Database is mariaDB. \
 
## To Setup

### Setup Apache

Insert to Apache:
<Directory "/path/to/url/dir"> \
RewriteEngine on \
RewriteCond %{REQUEST_FILENAME} !-d \
RewriteCond %{REQUEST_FILENAME} !-f \
RewriteRule . redirect.php [L] \
</Directory> \
 \
This will redirect anything not found in directory to redirect.php which will check the SQL database.

###  Setup config.php
ldapServer = ip address/hostname of ldap server \
allowedGroups = CN of group that should be allowed access URLs \
host = hostname part of shorturl \
ad_domain = domain name of active directory domain to authenticate to \

