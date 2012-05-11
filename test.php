<?php include("settings.php"); include("includes.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Created Form</title>
<link rel="stylesheet" href="<?=site_address?><?=css_folder?>style.css" type="text/css" media="screen" /></head>

<body>
<table width="880" align="center" border="0" cellpadding="0" cellspacing="10" style="border:1px solid #CCC; background:#FFFFFF;"><tr>
<td><div id="logo">Created Form</div></td></tr>
<tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="20">
  <tr>
    <td><strong class="admin_title">Test Your Form</strong></td>
  </tr>
   <tr>
    <td>
    <?php
	$form_id = mysql_real_escape_string($_GET["form"]);
	$frm = mysql_fetch_array(mysql_query("select `name` from `forms` where `id` = '".$form_id."'"));
	?>
    Below is the form named &quot;<?=$frm["name"]?>&quot; embeded into a php file.</td>
  </tr>
  <tr>
  	<td bgcolor="#F7F7F7" style="border:1px solid #cccccc; padding:20px;">
<?php
$form_id = "5";
$css_link = "css/form.css";
$ie_css_link = "css/ie_form.css";
include("/Applications/MAMP/htdocs/dev/forms/form.php");
?>
</td>
</tr>
  <tr>
    <td><a href="<?=site_address?>dev/forms/admin/">Goto Contact Form Manager Admin Panel Demo</a></td>
  </tr>
</table>
</td></tr></table>
<br />
<table width="880" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
<td><strong>Copyright © 2012, <a href="http://www.tomgabrysiak.com" target="_blank">www.tomgabrysiak.com</a></strong><br />
All rights are reserved.</td></tr></table>
</body>
</html>
