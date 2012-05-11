<label for="<?=$field_name?>"><?=$label?> <? if ($required) { echo '<span class="required">*</span>'; } ?></label><br />
<input name="<?=$field_name?>" id="<?=$fieldid?>" type="file" class="file<?=$class[$type][$field]?>" value="<?=$field_value?>" />
<p class="description"><?=$description?> <span class="field_message"><?=$message[$type][$field]?></span></p>