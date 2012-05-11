<label for="<?=$field_name?>"><?=$label?> <? if ($required) { echo '<span class="required">*</span>'; } ?></label><br />
<textarea name="<?=$field_name?>" id="<?=$fieldid?>" class="textarea<?=$class[$type][$field]?>"><?=$field_value?></textarea>
<p class="description"><?=$description?> <span class="field_message"><?=$message[$type][$field]?></span></p>