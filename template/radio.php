<label for="<?=$field_name?>"><?=$label?> <? if ($required) { echo '<span class="required">*</span>'; } ?></label><br />
<div id="<?=$fieldid?>">
<?
$options = mysql_query("select `label`, `value`, `id` from `options` where `field` = '".$field."'");
while ($opt = mysql_fetch_array($options)) {
?>
<label class="checkbox_radio_label">
<input name="<?=$field_name?>" type="radio" value="<?=$opt["value"]?>" <? if ($field_value==$opt["value"]) { echo 'checked="checked"'; } ?> class="checkbox_radio" /> <?=$opt["label"]?></label>
<br />
<?
}
?>
</div>
<p class="description"><?=$description?> <span class="field_message"><?=$message[$type][$field]?></span></p>