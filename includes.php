<?php
$conection = @mysql_connect($hostname, $username, $password);
if (!@mysql_select_db($databasename)) { die("There is a problem in database connection. Please revise your settings.php file for database settings and refresh this page once done."); }
$charset = 'utf-8';  
$cs=$mysql_charsets[$charset];
mysql_query( "SET CHARSET '".$cs."' "  );  
mysql_query( "SET NAMES utf-8"  );  

require_once(site_root.includes_folder."functions.php");
?>
