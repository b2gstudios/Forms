<?php
ob_start();
session_start();
$conection = @mysql_connect($hostname, $username, $password);
if (!@mysql_select_db($databasename)) { die("FATAL ERROR: ".mysql_error()); }
$charset = 'utf-8';  
$cs=$mysql_charsets[$charset];
mysql_query( "SET CHARSET '".$cs."' "  );  
mysql_query( "SET NAMES utf-8"  );  

include(site_root.includes_folder."functions.php");

$suser = $_SESSION["script_user"];
$spass = $_SESSION["script_pass"];
$scode = $_SESSION["script_code"];

$cuser = $_COOKIE["script_user"];
$csession = $_COOKIE["script_session_id"];
$ccode = $_COOKIE["script_code"];

$loginerror=false;

if ($suser != $cuser) { $loginerror=true; }
if ($suser != md5(admin_username)) { $loginerror=true; }
if ($spass != md5(admin_password)) { $loginerror=true; }
if ($scode != $ccode) { $loginerror=true; }
if ($csession != session_id()) { $loginerror=true; }

if ($loginerror) { 
reload(0,site_address.admin_folder); 
exit; }
?>
