<label for="<?=$field_name?>"><?=$label?> <? if ($required) { echo '<span class="required">*</span>'; } ?></label><br />
<div id="<?=$fieldid?>">
<?
$i=0;
$options = mysql_query("select `label`, `value` from `options` where `field` = '".$field."'");
while ($opt = mysql_fetch_array($options)) {
?>
<label class="checkbox_radio_label"><input name="<?=$field_name?>[<?=$i?>]" type="checkbox" value="<?=$opt["value"]?>" class="checkbox_radio"<? if ($_POST[$field_name][$i]==$opt["value"]) { echo ' checked="checked"'; } ?> /> <?=$opt["label"]?></label><br />
<?
$i++;
}
?>
</div>
<p class="description"><?=$description?> <span class="field_message"><?=$message[$type][$field]?></span></p>