<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_usautomall = "localhost";
$database_usautomall = "usautomall";
$username_usautomall = "root";
$password_usautomall = "1andonly";
$usautomall = mysql_pconnect($hostname_usautomall, $username_usautomall, $password_usautomall) or trigger_error(mysql_error(),E_USER_ERROR); 
?>