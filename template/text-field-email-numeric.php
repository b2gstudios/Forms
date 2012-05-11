<label for="<?=$field_name?>"><?=$label?> <? if ($required) { echo '<span class="required">*</span>'; } ?></label><br />
<input name="<?=$field_name?>" type="text" value="<?=$field_value?>" id="<?=$fieldid?>" class="text<?=$class[$type][$field]?>" />
<p class="description"><?=$description?> <span class="field_message"><?=$message[$type][$field]?>
</span></p>