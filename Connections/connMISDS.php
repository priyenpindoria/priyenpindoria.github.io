<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connMISDS = "localhost";
$database_connMISDS = "msids";
$username_connMISDS = "root";
$password_connMISDS = "";
$connMISDS = mysql_pconnect($hostname_connMISDS, $username_connMISDS, $password_connMISDS) or trigger_error(mysql_error(),E_USER_ERROR); 
?>