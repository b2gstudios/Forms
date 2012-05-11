<label for="<?=$field_name?>"><?=$label?> <? if ($required) { echo '<span class="required">*</span>'; } ?></label><br />
<select name="<?=$field_name?>" class="dropdown<?=$class[$type][$field]?>" id="<?=$fieldid?>">
<option value="">Please Select...</option>
<?
$options = mysql_query("select `label`, `value`, `id` from `options` where `field` = '".$field."'");
while ($opt = mysql_fetch_array($options)) {
?>
<option value="<?=$opt["value"]?>"<? if($field_value==$opt["value"]) { echo ' selected="selected"'; } ?>><?=$opt["label"]?></option>
<?
}
?>
</select>
<p class="description"><?=$description?> <span class="field_message"><?=$message[$type][$field]?></span></p>