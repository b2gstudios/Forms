<label for="<?=$field_name?>"><?=$label?> <? if ($required) { echo '<span class="required">*</span>'; } ?></label><br />
<select name="<?=$field_name?>[]" size="5" multiple="multiple" class="multiselect<?=$class[$type][$field]?>" id="<?=$fieldid?>">
<?
$i=0;
$options = mysql_query("select `label`, `value`, `id` from `options` where `field` = '".$field."'");
while ($opt = mysql_fetch_array($options)) {
	$selected = false;
		foreach ($_POST[$field_name] as $post) {
			if ($opt["value"] == $post) {
				$selected = true;
			}
		}
?>
<option value="<?=$opt["value"]?>"<? if ($selected) { echo ' selected="selected"'; } ?>><?=$opt["label"]?></option>
<? 
$i++;
} 
?>
</select>
<p class="description"><?=$description?> <span class="field_message"><?=$message[$type][$field]?></span></p>