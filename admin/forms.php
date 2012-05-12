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

if (isset($_POST["submit"])) {
	$name = mysql_real_escape_string($_POST["name"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$divid = mysql_real_escape_string($_POST["divid"]);
	$button = mysql_real_escape_string($_POST["button"]);
	$email = mysql_real_escape_string($_POST["email"]);
	
	if ((!$name) || (!$button) || (!$email)) {
		$exclamation = "<span class='red_text'>Please do not leave necessary fields empty.</span>";
		$error = true;
		$msg = true;
	}
	
	if (!$error) {
		mysql_query("insert into `forms` (`name`,`description`,`divid`,`order`,`button`,`email`) values ('".$name."','".$description."','".$divid."','999','".$button."','".$email."')");
		$form = mysql_insert_id();
		reload(0,site_address.admin_folder."fields.php?form=".$form."&added=1");
		exit();
	}

} elseif (isset($_GET["delete"])){
	$delete = mysql_real_escape_string($_GET["delete"]);
	mysql_query("delete from `forms` where `id` = '".$delete."'");
	mysql_query("delete from `fields` where `form` = '".$delete."'");
	$exclamation = "Form has been deleted succesfully.";
	$msg=true;
	reload(0,site_address.admin_folder."forms.php");
}
?>

<link rel="stylesheet" href="<?=site_address?><?=css_folder?>style.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>interface.js"></script>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>ajaxroutine.js" ></script>

</head>

<body>
<table width="500" align="center" border="0" cellpadding="0" cellspacing="10" style="border:1px solid #CCC; background:#FFFFFF;"><tr>
<td><div id="logo">Contact Form Manager</div></td></tr>
<tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="20">
  <tr>
    <td colspan="3"><strong class="admin_title">Forms List</strong></td>
  </tr>
   <tr>
    <td colspan="3">Forms are listed below. You can drag and drop forms in order to change their order. To add a new form use the form below. To add fields to forms, click on the "edit" icon near the form.</td>
  </tr>
  <tr>
  	<td bgcolor="#F7F7F7" style="border:1px solid #cccccc; padding:20px;">
     <div id="order">
  <? if ($msg) {
  	if ($error) { ?>
  <div id="loading"><table><tr><td valign="middle"><img src="<?=site_address.static_images_folder?>delete.gif" /></td><td><?=$exclamation?></td></table></div><br /><br />
  <? } else { ?>
  <div id="loading"><table><tr><td valign="middle"><img src="<?=site_address.static_images_folder?>tick.gif" /></td><td><?=$exclamation?></td></table></div><br /><br />
  <? } } ?>
  </div>
	<div id="files">
    <?php
$forms = mysql_query("select `name`,`id`,`description` from `forms` order by `order` asc");
$say = mysql_num_rows($forms);
if (!$say) { 
	echo "<div style='padding:20px;'>No forms have been added yet. Use the form below to add a new form.</div>"; 
} else {
echo '<div id="sort1" class="groupWrapper1">';
$i=0;
while ($row = mysql_fetch_array($forms)) {
	$i++;

?>
<div id="sort-<?=$i."-".$row["id"]?>" class="groupItem" style="cursor:move; background-color:#FFFFFF; margin:4px; margin-bottom:0px;<? if ($i == $say) { ?> margin-bottom:4px;<? } ?>"><div class="itemHeader1"><? echo "<table width='100%' cellspacing='5'><tr><td align='left' valign='top' width='25'><img src=\"".site_address.static_images_folder."bullet.gif\" alt=\"\" /></td><td align='left' valign='top'><strong>".$row["name"]."</strong><br />".$row["description"]."</td><td align='right' valign='top' width='150'><a href='".site_address.admin_folder."fields.php?form=".$row["id"]."'><img src=\"".site_address.static_images_folder."edit.gif\" border='0' alt='Edit Form' title='Edit Form' /></a> <a href=\"".site_address.admin_folder."forms.php?delete=".$row["id"]."\" onClick=\"return confirm('If you delete this form, all the fields inside this form will also be deleted. Are you sure?');\"><img src=\"".site_address.static_images_folder."delete.gif\" border='0' alt='Delete Form' title='Delete Form' /></a></td></tr></table>"; ?></div></div>
<?
}
echo "</div>";
}
	?>
    </div>
    
</p>

<div style="clear:both; height:20px;"></div>

<table width="100%" cellspacing="10" border="0" bgcolor="#ffffff" style="border:1px solid #cccccc;">
     <form method="post">
      <tr>
        <td width="30%" valign="top">Name <span class="red_text">*</span> </td>
        <td width="70%" valign="top"><input name="name" type="text" value="<?=stripslashes($name)?>"></td>
      </tr>
      <tr>
        <td valign="top">Receiver E-mail <span class="red_text">*</span> </td>
        <td width="70%" valign="top"><input name="email" type="text" value="<?=stripslashes($email)?>"></td>
      </tr>
      <tr>
        <td valign="top">Button Value <span class="red_text">*</span> </td>
        <td width="70%" valign="top"><input name="button" type="text" value="<?=stripslashes($button)?>"></td>
      </tr>
      <tr>
        <td valign="top">Description</td>
        <td width="70%" valign="top"><textarea name="description" cols="40" rows="4"><?=stripslashes($description)?></textarea>
         </td>
      </tr>
      <tr>
        <td valign="top">Div id </td>
        <td width="70%" valign="top"><input name="divid" type="text" value="<?=stripslashes($divid)?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" id="submit" value="Add Form" onclick="ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>loading.php', '', processGetPost, 'txt', 'order', '<div id=loading><table><tr><td valign=middle><img src=<?=site_address?><?=static_images_folder?>loading.gif /></td><td>Loading...</td></table></table></div><br /><br />');"></td>
      </tr>
	  </form>
	</table>
</td>
  </tr>
  <tr>
    <td colspan="3"><a href="<?=site_address.admin_folder."?logout"?>">Sign Out</a></td>
  </tr>
</table>
</td></tr></table>
<br />
<table width="880" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
<td><strong>Copyright © 2012, <a href="http://www.tomgabrysiak.com" target="_blank">www.tomgabrysiak.com</a></strong><br />
All rights are reserved.</td></tr></table>
</body>
<script type="text/javascript">
$(document).ready(
	function () {
		$('a.closeEl').bind('click', toggleContent);
		$('div.groupWrapper1').Sortable(
			{
				accept: 'groupItem',
				helperclass: 'sortHelper',
				activeclass : 	'sortableactive',
				hoverclass : 	'sortablehover',
				handle: 'div.itemHeader1',
				tolerance: 'pointer',
				axis: 'vertically',
				onChange : function()
				{
					serialize();
				},
				onStart : function()
				{
					$.iAutoscroller.start(this, document.getElementsByTagName('body'));
				},
				onStop : function()
				{
					$.iAutoscroller.stop();
				}
			}
		);
	}
);
var toggleContent = function(e)
{
	var targetContent = $('div.itemContent', this.parentNode.parentNode);
	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(300);
		$(this).html('[-]');
	} else {
		targetContent.slideUp(300);
		$(this).html('[+]');
	}
	return false;
};

function serialize(s)
{
	serial = $.SortSerialize(s);
	var str=serial.hash;
	ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>order-forms.php', str + "&", processGetPost, 'txt', 'order', '<div id="loading"><table><tr><td valign="middle"><img src="<?=site_address?><?=static_images_folder?>loading.gif" /></td><td>Loading...</td></table></table></div><br /><br />');
};
</script>
</html>
