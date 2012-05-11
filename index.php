<?php if (@include("settings.php")) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contact Form Manager</title>
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
</head>

<body>
<table width="880" align="center" border="0" cellpadding="0" cellspacing="10" style="border:1px solid #CCC; background:#FFFFFF;"><tr>
<td><div id="logo">Contact Form Manager</div></td></tr>
<tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="20">
  <tr>
    <td><strong class="admin_title">Installation</strong></td>
  </tr>
   <tr>
    <td>
<?php

$conection = @mysql_connect($hostname, $username, $password);
if (!@mysql_select_db($databasename)) { die("There is a problem in database connection. Please revise your settings.php file for database settings and refresh this page once done."); }
$charset = 'utf-8';  
$cs=$mysql_charsets[$charset];
mysql_query( "SET CHARSET '".$cs."' "  );  
mysql_query( "SET NAMES utf-8"  );  

if (!file_exists(site_root.script_folder."form.php")) {
	$error = true;
	echo "<p class='red_text'>There is a problem in the script folder. Please check your settings.php or the script folder.</p>";
} else { echo "<p>Script Folder: OK</p>"; 

if (!file_exists(site_root.includes_folder."functions.php")) {
	$error = true;
	echo "<p class='red_text'>There is a problem in the includes folder. Please check your settings.php or the includes folder.</p>";
} else { 
	require_once(site_root.includes_folder."functions.php");
	echo "<p>Includes: OK</p>"; 
}

if (file_exists(site_root.script_folder."db.sql")) {
	$myFile = site_root.script_folder."db.sql";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, filesize($myFile));
	fclose($fh);

	$dbs = explode("\n", $theData);
	$cnt = count($dbs);
	$i=0;
	for ($i=0; $i<$cnt; $i++) {
		$query = mysql_query($dbs[$i]) or die(mysql_error());
	}
	echo "<p>Successfully created tables in database: OK</p>";
} else {
	$error = true;
 ?>
<p class="red_text">Can not find the database (sql) file. Please upload the db.sql file to the script folder.</p>

<? 	} 

	if (!file_exists(site_root.javascript_folder."ajaxroutine.js")) {
		$error = true;
		echo "<p class='red_text'>There is a problem in the javascript folder. Please check your settings.php or the javascript folder.</p>";
	} else { echo "<p>Javascript Folder: OK</p>"; }

	if (!file_exists(site_root.css_folder."style.css")) {
		$error = true;
		echo "<p class='red_text'>There is a problem in the CSS folder. Please check your settings.php or the CSS folder.</p>";
	} else { echo "<p>CSS Folder: OK</p>"; }
	
	if (!file_exists(site_root.admin_folder."index.php")) {
		$error = true;
		echo "<p class='red_text'>There is a problem in the admin folder. Please check your settings.php or the admin folder.</p>";
	} else { echo "<p>Admin Folder: OK</p>"; }
	
	if (!file_exists(site_root.ajaxcalls_folder."loading.php")) {
		$error = true;
		echo "<p class='red_text'>There is a problem in the ajax calls folder. Please check your settings.php or the ajax calls folder.</p>";
	} else { echo "<p>Ajax Calls Folder: OK</p>"; }

	if (!file_exists(site_root.static_images_folder."edit.gif")) {
		$error = true;
		echo "<p class='red_text'>There is a problem in the static images folder. Please check your settings.php or the images folder.</p>";
	} else { echo "<p>Static Images Folder: OK</p>"; }

}
if (!$error) { ?>
    <p>Your Contact Form Manager has been installed succesfully.</p>
      <p>Your admin information are below:</p>
      <p><strong>Username:</strong> <?=admin_username?><br />
      <strong>Password:</strong> <?=admin_password?></p>
      <br />
		<a href="<?=site_address?><?=admin_folder?>">Login and start creating forms</a>
<? } ?>
      </td>
  </tr>
</table>
</td></tr></table>
<br />
<table width="880" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
<td><strong>Copyright © 2012, <a href="http://www.tomgabrysiak.com" target="_blank">www.tomgabrysiak.com</a></strong><br />
All rights are reserved.</td></tr></table>
</body>
</html>
<? } else { echo "<title>Contact Form Manager</title>
Cannot locate settings.php file. Please check the files uploaded and reload this page once done."; } ?>