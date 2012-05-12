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

$form = mysql_real_escape_string($_GET["form"]);

if (!$form) {
	reload(0,"forms.php");
	exit;
}

if ($_POST["edit"]) {
	$name = mysql_real_escape_string($_POST["name"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$divid = mysql_real_escape_string($_POST["divid"]);
	$button = mysql_real_escape_string($_POST["button"]);
	$receiver_email = mysql_real_escape_string($_POST["receiver_email"]);
	
	if ((!$name) || (!$button) || (!$receiver_email)) {
		$exclamation = "<span class='red_text'>Please do not leave necessary fields empty.</span>";
		$error = true;
		$msg = true;
	}
	if (!$error) {
		mysql_query("update `forms` set `name`='".$name."',`description`='".$description."',`divid`='".$divid."',`button`='".$button."',`email`='".$receiver_email."' where `id` = '".$form."'");
		$exclamation = "Form has been edited succesfully.";
		$msg=true;
	}
} else {
	$frm = mysql_fetch_array(mysql_query("select * from `forms` where `id` = '".$form."'"));
	$name = $frm["name"];
	$description = $frm["description"];
	$divid = $frm["divid"];
	$button = $frm["button"];
	$receiver_email = $frm["email"];
}

if ($_GET["added"]) {
	$exclamation = "Form has been created succesfully.";
	$msg=true;
}

if (isset($_POST["submit"])) {

	$label = mysql_real_escape_string($_POST["label"]);
	$field_description = mysql_real_escape_string($_POST["field_description"]);
	$fieldid = mysql_real_escape_string($_POST["fieldid"]);
	$type = mysql_real_escape_string($_POST["type"]);
	$required = mysql_real_escape_string($_POST["required"]);
	
	if ((!$label) || (!$type)) {
		$exclamation = "<span class='red_text'>Please do not leave necessary fields empty.</span>";
		$error = true;
		$msg = true;
	}
	
	if (!$error) {
		mysql_query("insert into `fields` (`label`,`description`,`fieldid`,`type`,`required`,`form`,`order`) values ('".$label."','".$field_description."','".$fieldid."','".$type."','".$required."','".$form."','999')");
		$field = mysql_insert_id();
		reload(0,site_address.admin_folder."field.php?field=".$field."&added=1");
		exit();
	}

	
} elseif (isset($_GET["delete"])){
	$delete = mysql_real_escape_string($_GET["delete"]);
	mysql_query("delete from `fields` where `id` = '".$delete."'");
	$exclamation = "Field has been deleted succesfully.";
	$msg=true;
	reload(0,"?form=".$form."");
}
?>

<link rel="stylesheet" href="<?=site_address?><?=css_folder?>style.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>interface.js"></script>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>ajaxroutine.js" ></script>

<script type="text/javascript">
function go(){
location=
document.page.form.
options[document.page.form.selectedIndex].value
}
</script>

</head>

<body>
<table width="500" align="center" border="0" cellpadding="0" cellspacing="10" style="border:1px solid #CCC; background:#FFFFFF;"><tr>
<td><div id="logo">Contact Form Manager</div></td></tr>
<tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="20">
  <tr>
    <td colspan="3"><strong class="admin_title">Fields List</strong></td>
  </tr>
   <tr>
    <td colspan="3">Fields are listed below. You can drag and drop fields in order to change their order. To add a new field use the form below. </td>
  </tr>
  <form name="page">
    <tr>
    <td colspan="3">Form: <select name="form" size="1" onChange="go()">
    <?
	$frms = mysql_query("SELECT `id`,`name` FROM `forms` ORDER BY `order` asc");
	while ($fr = mysql_fetch_array($frms)) {
		echo "<option value=\"?form=".$fr["id"]."\"";
		if ($form == $fr["id"]) { echo " selected='selected'"; }
		echo ">".$fr[name]."</option>";
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
        <td width="29%" valign="top">Name <span class="red_text">*</span> </td>
        <td width="71%" valign="top"><input name="name" type="text" value="<?=stripslashes($name)?>"></td>
      </tr>
      <tr>
        <td valign="top">Receiver E-mail <span class="red_text">*</span> </td>
        <td width="71%" valign="top"><input name="receiver_email" type="text" value="<?=stripslashes($receiver_email)?>"></td>
      </tr>
      <tr>
        <td valign="top">Button Value <span class="red_text">*</span> </td>
        <td width="71%" valign="top"><input name="button" type="text" value="<?=stripslashes($button)?>"></td>
      </tr>
      <tr>
        <td valign="top">Description</td>
        <td width="71%" valign="top"><textarea name="description" cols="40" rows="4"><?=stripslashes($description)?></textarea>
         </td>
      </tr>
      <tr>
        <td valign="top">Div id </td>
        <td width="71%" valign="top"><input name="divid" type="text" value="<?=stripslashes($divid)?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="edit" id="submit" value="Edit Form Info" onclick="ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>loading.php', '', processGetPost, 'txt', 'order', '<div id=loading><table><tr><td valign=middle><img src=<?=site_address?><?=static_images_folder?>loading.gif /></td><td>Loading...</td></table></table></div><br /><br />');"></td>
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
	<strong>Form Embed Code:</strong><br />
	Add the lines below to your php or html files:<br />
	<textarea name="embed" cols="60" rows="6"  onclick="this.focus();this.select()" readonly="readonly">&lt;?php
$form_id = &quot;<?=$form?>&quot;;
$css_link = &quot;css/form.css&quot;;
$ie_css_link = &quot;css/ie_form.css&quot;;
include(&quot;<?=site_root.script_folder?>form.php&quot;);
?&gt;</textarea>
<div style="clear:both; height:20px;"></div>
	<div id="files">
    <?php
$fields = mysql_query("select `id`,`label` from `fields` where `form` = '".$form."' order by `order` asc");
$say = mysql_num_rows($fields);
if (!$say) { 
	echo "<div style='padding:20px;'>No fields have been added to this form yet. Use the form below to add a new field.</div>"; 
} else {
echo '<div id="sort1" class="groupWrapper1">';
$i=0;
while ($row = mysql_fetch_array($fields)) {
	$i++;

?>
<div id="sort-<?=$i."-".$row["id"]?>" class="groupItem" style="cursor:move; background-color:#FFFFFF; margin:4px; margin-bottom:0px;<? if ($i == $say) { ?> margin-bottom:4px;<? } ?>"><div class="itemHeader1"><? echo "<table width='100%' cellspacing='5'><tr><td align='left' valign='top'>".$row["label"]."</td><td align='right' valign='top' width='150'><a href='".site_address.admin_folder."field.php?field=".$row["id"]."&form=".$form."'><img src=\"".site_address.static_images_folder."edit.gif\" border='0' alt='Edit Field' title='Edit Field' /></a> <a href=\"?form=".$form."&delete=".$row["id"]."\" onClick=\"return confirm('Are you sure?');\"><img src=\"".site_address.static_images_folder."delete.gif\" border='0' alt='Delete Field' title='Delete Field' /></a></td></tr></table>"; ?></div></div>
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
        <td width="26%" valign="top">Label <span class="red_text">*</span> </td>
        <td width="74%" valign="top"><input name="label" type="text" value="<?=stripslashes($label)?>"></td>
      </tr>
      <tr>
        <td valign="top">Field id </td>
        <td width="74%" valign="top"><input name="fieldid" type="text" value="<?=stripslashes($fieldid)?>"></td>
      </tr>
      <tr>
        <td valign="top">Field Type <span class="red_text">*</span> </td>
        <td width="74%" valign="top">
        <select name="type">
        <option value="text"<? if ($type=="text") { echo "selected='selected'"; } ?>>Text Field</option>
        <option value="email"<? if ($type=="email") { echo "selected='selected'"; } ?>>E-mail Field</option>
        <option value="numeric"<? if ($type=="numeric") { echo "selected='selected'"; } ?>>Numeric Field</option>
        <option value="textarea"<? if ($type=="textarea") { echo "selected='selected'"; } ?>>Text Area</option>
        <option value="file"<? if ($type=="file") { echo "selected='selected'"; } ?>>File Field</option>
        <option value="checkbox"<? if ($type=="checkbox") { echo "selected='selected'"; } ?>>Single Checkbox</option>
        <option value="dropdown"<? if ($type=="dropdown") { echo "selected='selected'"; } ?>>Dropdown List</option>
        <option value="multiselect"<? if ($type=="multiselect") { echo "selected='selected'"; } ?>>Multiselect List</option>
        <option value="checkboxlist"<? if ($type=="checkboxlist") { echo "selected='selected'"; } ?>>Checkboxes List</option>
        <option value="radio"<? if ($type=="radio") { echo "selected='selected'"; } ?>>Radio Group</option>
        <option value="validation"<? if ($type=="validation") { echo "selected='selected'"; } ?>>Validation Field</option>
        <option value="hidden"<? if ($type=="hidden") { echo "selected='selected'"; } ?>>Hidden Field</option>
        <option value="space"<? if ($type=="space") { echo "selected='selected'"; } ?>>Space</option>
        </select>
        </td>
      </tr>
      <tr>
        <td valign="top">Required </td>
        <td width="74%" valign="top">
		<input name="required" type="checkbox" value="1"<? if ($required==1) { echo "checked='checked'"; } ?> />
        </td>
      </tr>
      <tr>
        <td valign="top">Description</td>
        <td width="74%" valign="top"><textarea name="field_description" cols="40" rows="4"><?=stripslashes($field_description)?></textarea>
         </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" id="submit" value="Add Field" onclick="ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>loading.php', '', processGetPost, 'txt', 'order', '<div id=loading><table><tr><td valign=middle><img src=<?=site_address?><?=static_images_folder?>loading.gif /></td><td>Loading...</td></table></table></div><br /><br />');"></td>
      </tr>
	  </form>
	</table>
</td>
  </tr>
  <tr>
    <td colspan="3"><a href="<?=site_address.admin_folder."forms.php"?>">Return to Forms</a> | <a href="<?=site_address.script_folder."test.php?form=".$form.""?>" target="_blank">Test Your Form</a> | <a href="<?=site_address.admin_folder."?logout"?>">Sign Out</a></td>
  </tr>
</table>
</td></tr></table>
<br />
<table width="500" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
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
	ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>order-fields.php', str + "&", processGetPost, 'txt', 'order', '<div id="loading"><table><tr><td valign="middle"><img src="<?=site_address?><?=static_images_folder?>loading.gif" /></td><td>Loading...</td></table></table></div><br /><br />');
};
</script>
</html>
