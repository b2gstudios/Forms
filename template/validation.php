<label for="<?=$field_name?>"><?=$label?> <? if ($required) { echo '<span class="required">*</span>'; } ?></label><br />
<input name="ttl<?=$field?>" type="hidden" value="<?=$check_value?>" />
<input name="<?=$field_name?>" type="text" id="<?=$fieldid?>" value="<?=$field_value?>" class="validation<?=$class[$type][$field]?>" />
<?=$n1?> + <?=$n2?>
<p class="description"><?=$description?> <span class="field_message"><?=$message[$type][$field]?></span></p>
