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

$field = mysql_real_escape_string($_GET["field"]);

if (!$field) {
	reload(0,"forms.php");
	exit;
}

if ($_POST["edit"]) {
	$label = mysql_real_escape_string($_POST["label"]);
	$field_description = mysql_real_escape_string($_POST["field_description"]);
	$fieldid = mysql_real_escape_string($_POST["fieldid"]);
	$type = mysql_real_escape_string($_POST["type"]);
	$required = mysql_real_escape_string($_POST["required"]);
	$default = mysql_real_escape_string($_POST["default"]);
	
	if ((!$label) || (!$type)) {
		$exclamation = "<span class='red_text'>Please do not leave necessary fields empty.</span>";
		$error = true;
		$msg = true;
	}
	if (!$error) {
		mysql_query("update `fields` set `label`='".$label."',`description`='".$field_description."',`fieldid`='".$fieldid."',`type`='".$type."',`required`='".$required."',`default`='".$default."' where `id` = '".$field."'") or die(mysql_error());
		$exclamation = "Field has been edited succesfully.";
		$msg=true;
	}
} else {
	$fld = mysql_fetch_array(mysql_query("select * from `fields` where `id` = '".$field."'"));
	$label = $fld["label"];
	$field_description = $fld["description"];
	$fieldid = $fld["fieldid"];
	$type = $fld["type"];
	$required = $fld["required"];
	$default = $fld["default"];
}
$fid = mysql_fetch_array(mysql_query("select `form` from `fields` where `id` = '".$field."'"));
$form = $fid["form"];

if ($_GET["added"]) {
	$exclamation = "Field has been created succesfully.";
	$msg=true;
}

if (isset($_POST["submit"])) {

	$option_label = mysql_real_escape_string($_POST["option_label"]);
	$value = mysql_real_escape_string($_POST["value"]);
	
	if ((!$option_label) || (!$value)) {
		$exclamation = "<span class='red_text'>Please do not leave necessary fields empty.</span>";
		$error = true;
		$msg = true;
	}
	
	if (!$error) {
		mysql_query("insert into `options` (`label`,`value`,`field`,`order`) values ('".$option_label."','".$value."','".$field."','999')");
		$option = mysql_insert_id();
		$exclamation = "New option has been added.";
		$msg = true;
	}

	
} elseif (isset($_GET["delete"])){
	$delete = mysql_real_escape_string($_GET["delete"]);
	mysql_query("delete from `options` where `id` = '".$delete."'");
	$exclamation = "Option has been deleted succesfully.";
	$msg=true;
	reload(1,"?field=".$field."");
}
?>

<link rel="stylesheet" href="<?=site_address?><?=css_folder?>style.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>interface.js"></script>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>ajaxroutine.js" ></script>

<script type="text/javascript">
function go(){
location=
document.page.field.
options[document.page.field.selectedIndex].value
}
</script>

</head>

<body>
<table width="500" align="center" border="0" cellpadding="0" cellspacing="10" style="border:1px solid #CCC; background:#FFFFFF;"><tr>
<td><div id="logo">Contact Form Manager</div></td></tr>
<tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="20">
  <tr>
    <td colspan="3"><strong class="admin_title">Field Details</strong></td>
  </tr>
   <tr>
    <td colspan="3">You can see the field details below. Fill in the form below in order to change these details. Each field type has its own kind of details.</td>
  </tr>
  <form name="page">
    <tr>
    <td colspan="3">Field: <select name="field" size="1" onChange="go()">
    <?
	$flds = mysql_query("SELECT `id`,`label` FROM `fields` where `form` = '".$form."' ORDER BY `order` asc");
	while ($fl = mysql_fetch_array($flds)) {
		echo "<option value=\"?field=".$fl["id"]."\"";
		if ($field == $fl["id"]) { echo " selected='selected'"; }
		echo ">".$fl[label]."</option>";
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
      <? if ($type!="hidden") { ?>
      <tr>
        <td valign="top">Field id </td>
        <td width="67%" valign="top"><input name="fieldid" type="text" value="<?=stripslashes($fieldid)?>"></td>
      </tr>
      <? } ?>
      <tr>
        <td valign="top">Field Type <span class="red_text">*</span> </td>
        <td width="67%" valign="top">
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
	  <? if (($type!="hidden") && ($type!="validation")) { ?>
      <tr>
        <td valign="top">
        <? if ($type=="space") { ?>
        Show Label
        <? } else { ?>
        Required 
        <? } ?></td>
        <td width="67%" valign="top">
		<input name="required" type="checkbox" value="1"<? if ($required==1) { echo "checked='checked'"; } ?> />
        </td>
      </tr>
      <?
	  }
		if (($type!="checkboxlist") && ($type!="multiselect") && ($type!="validation") && ($type!="space")) {
			if ($type=="file") {
			?>
       <tr>
        <td valign="top">Allowed File Extensions</td>
        <td width="67%" valign="top">
        <?	echo '<input name="default" type="text" value="'.$default.'" />'; ?><br />
		<strong>Format Example:</strong> [jpg][png][doc]<br />
		<strong>Note:</strong> If you leave this field empty, then all file extensions will be approved.
        </td>
      </tr>     
            <?
			} else {
		?>
      <tr>
        <td valign="top"><? if ($type!="hidden") { ?>Default<? } ?> Value </td>
        <td width="67%" valign="top">
        <?
		if (($type=="dropdown") || ($type=="radio")) {
			echo '
			<select name="default">
			<option value="">None</option>';
			$options = mysql_query("select `value`,`label` from options where `field` = '".$field."'");
			while ($opt = mysql_fetch_array($options)) {
				echo '<option value="'.$opt["value"].'"';
				if ($opt["value"] == $default) { echo ' selected="selected"'; }
				echo '>'.$opt["label"].'</option>';
			}
			echo '</select>';
		} elseif ($type == "textarea") {
			echo '<textarea name="default" cols="30" rows="3">'.$default.'</textarea>';
		} elseif ($type == "checkbox") {
			echo '<select name="default">
			<option value="0">Not Checked</option>
			<option value="1"';
			if ($default=="1") { echo ' selected="selected"'; }
			echo '>Checked</option>
			</select>';
		} else {
			echo '<input name="default" type="text" value="'.$default.'" />';
		}
		?>
        </td>
      </tr>
      <? } } ?>
      <? if ($type!="hidden") { ?>
      <tr>
        <td valign="top">Description</td>
        <td width="67%" valign="top"><textarea name="field_description" cols="30" rows="4"><?=stripslashes($field_description)?></textarea>
         </td>
      </tr>
      <? } ?>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="edit" id="submit" value="Update Field" onclick="ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>loading.php', '', processGetPost, 'txt', 'order', '<div id=loading><table><tr><td valign=middle><img src=<?=site_address?><?=static_images_folder?>loading.gif /></td><td>Loading...</td></table></table></div><br /><br />');"></td>
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
<? if (($type == "dropdown") || ($type == "multiselect") || ($type == "checkboxlist") || ($type == "radio")) { ?>
<div style="clear:both; height:20px;"></div>
Options are listed below. Use the form below to add new option. Click on "edit" icon near the option in order to change that option details.<br />
<br />
<br />
	<div id="files">
    <?php
$options = mysql_query("select `id`,`label` from `options` where `field` = '".$field."' order by `order` asc");
$say = mysql_num_rows($options);
if (!$say) { 
	echo "<div style='padding:20px;'>No options have been added to this field yet. Use the form below to add a new option.</div>"; 
} else {
echo '<div id="sort1" class="groupWrapper1">';
$i=0;
while ($row = mysql_fetch_array($options)) {
	$i++;

?>
<div id="sort-<?=$i."-".$row["id"]?>" class="groupItem" style="cursor:move; background-color:#FFFFFF; margin:4px; margin-bottom:0px;<? if ($i == $say) { ?> margin-bottom:4px;<? } ?>"><div class="itemHeader1"><? echo "<table width='100%' cellspacing='5'><tr><td align='left' valign='top'>".$row["label"]."</td><td align='right' valign='top' width='150'><a href='".site_address.admin_folder."option.php?option=".$row["id"]."'><img src=\"".site_address.static_images_folder."edit.gif\" border='0' alt='Edit Option' title='Edit Option' /></a> <a href=\"?field=".$field."&delete=".$row["id"]."\" onClick=\"return confirm('Are you sure?');\"><img src=\"".site_address.static_images_folder."delete.gif\" border='0' alt='Delete Option' title='Delete Option' /></a></td></tr></table>"; ?></div></div>
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
        <td width="74%" valign="top"><input name="option_label" type="text" value="<?=stripslashes($option_label)?>"></td>
      </tr>
      <tr>
        <td width="26%" valign="top">Value <span class="red_text">*</span> </td>
        <td width="74%" valign="top"><input name="value" type="text" value="<?=stripslashes($value)?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" id="submit" value="Add Option" onclick="ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>loading.php', '', processGetPost, 'txt', 'order', '<div id=loading><table><tr><td valign=middle><img src=<?=site_address?><?=static_images_folder?>loading.gif /></td><td>Loading...</td></table></table></div><br /><br />');"></td>
      </tr>
	  </form>
	</table>
    
<? } ?>
</td>
  </tr>
  <tr>
    <td colspan="3"><a href="<?=site_address.admin_folder."fields.php?form=".$form.""?>">Return to Fields</a> | <a href="<?=site_address.admin_folder."?logout"?>">Sign Out</a></td>
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
	ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>order-options.php', str + "&", processGetPost, 'txt', 'order', '<div id="loading"><table><tr><td valign="middle"><img src="<?=site_address?><?=static_images_folder?>loading.gif" /></td><td>Loading...</td></table></table></div><br /><br />');
};
</script>
</html>
