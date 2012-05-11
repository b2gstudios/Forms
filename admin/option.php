<?php
include("../settings.php");
include("includes.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contact Form Manager</title>

<?php 

$msg=false;
$exclamation = "";

$option = mysql_real_escape_string($_GET["option"]);

if (!$option) {
	reload(0,"forms.php");
	exit;
}

if ($_POST["edit"]) {
	$label = mysql_real_escape_string($_POST["label"]);
	$value = mysql_real_escape_string($_POST["value"]);
	
	if ((!$label) || (!$value)) {
		$exclamation = "<span class='red_text'>Please do not leave necessary fields empty.</span>";
		$error = true;
		$msg = true;
	}
	if (!$error) {
		mysql_query("update `options` set `label`='".$label."',`value`='".$value."' where `id` = '".$option."'") or die(mysql_error());
		$exclamation = "Option has been edited succesfully.";
		$msg=true;
	}
} else {
	$opt = mysql_fetch_array(mysql_query("select * from `options` where `id` = '".$option."'"));
	$label = $opt["label"];
	$value = $opt["value"];
}
$oid = mysql_fetch_array(mysql_query("select `field` from `options` where `id` = '".$option."'"));
$field = $oid["field"];

?>

<link rel="stylesheet" href="<?=site_address?><?=css_folder?>style.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>interface.js"></script>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>ajaxroutine.js" ></script>

<script type="text/javascript">
function go(){
location=
document.page.option.
options[document.page.option.selectedIndex].value
}
</script>

</head>

<body>
<table width="500" align="center" border="0" cellpadding="0" cellspacing="10" style="border:1px solid #CCC; background:#FFFFFF;"><tr>
<td><div id="logo">Contact Form Manager</div></td></tr>
<tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="20">
  <tr>
    <td colspan="3"><strong class="admin_title">Option Details</strong></td>
  </tr>
   <tr>
    <td colspan="3">You can see the option details below. Use the for below in order to edit the option details.</td>
  </tr>
  <form name="page">
    <tr>
    <td colspan="3">Option: <select name="option" size="1" onChange="go()">
    <?
	$opts = mysql_query("SELECT `id`,`label` FROM `options` where `field` = '".$field."' ORDER BY `order` asc");
	while ($op = mysql_fetch_array($opts)) {
		echo "<option value=\"?option=".$op["id"]."\"";
		if ($option == $op["id"]) { echo " selected='selected'"; }
		echo ">".$op[label]."</option>";
	}
	?>
    </select>
  </td>
  </tr>
  </form>
  <tr>
  	<td bgcolor="#F7F7F7" style="border:1px solid #cccccc; padding:20px;">
<table width="100%" cellspacing="10" border="0" bgcolor="#ffffff" style="border:1px solid #cccccc;">
     <form method="post">
      <tr>
        <td width="33%" valign="top">Label <span class="red_text">*</span> </td>
        <td width="67%" valign="top"><input name="label" type="text" value="<?=stripslashes($label)?>"></td>
      </tr>
      <tr>
        <td width="33%" valign="top">Value <span class="red_text">*</span> </td>
        <td width="67%" valign="top"><input name="value" type="text" value="<?=stripslashes($value)?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="edit" id="submit" value="Update Option" onclick="ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>loading.php', '', processGetPost, 'txt', 'order', '<div id=loading><table><tr><td valign=middle><img src=<?=site_address?><?=static_images_folder?>loading.gif /></td><td>Loading...</td></table></table></div><br /><br />');"></td>
      </tr>
	  </form>
	</table>
       <div style="clear:both; height:20px;"></div>
     <div id="order">
  <? if ($msg) {
  	if ($error) { ?>
  <div id="loading"><table><tr><td valign="middle"><img src="<?=site_address.static_images_folder?>delete.gif" /></td><td><?=$exclamation?></td></table></div><br /><br />
  <? } else { ?>
  <div id="loading"><table><tr><td valign="middle"><img src="<?=site_address.static_images_folder?>tick.gif" /></td><td><?=$exclamation?></td></table></div><br /><br />
  <? } } ?>
  </div>
</td>
  </tr>
  <tr>
    <td colspan="3"><a href="<?=site_address.admin_folder."field.php?field=".$field.""?>">Return to Field</a> | <a href="<?=site_address.admin_folder."?logout"?>">Sign Out</a></td>
  </tr>
</table>
</td></tr></table>
<br />
<table width="500" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
<td><strong>Copyright © 2012, <a href="http://www.tomgabrysiak.com" target="_blank">www.tomgabrysiak.com</a></strong><br />
All rights are reserved.</td></tr></table>
</body>
</html>
