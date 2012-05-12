<div style="clear:both;"></div>
<input name="submit<?=$form_id?>" type="submit" class="button" value="<?=$button?>" onclick="ajaxpack.getAjaxRequest('<?=site_address?><?=ajaxcalls_folder?>loading.php', '', processGetPost, 'txt', 'order', '<div id=loading><table><tr><td valign=middle><img src=<?=site_address?><?=static_images_folder?>loading.gif /></td><td>Loading...</td></table></table></div><br /><br />');" />
</form>
<div id="order"></div>
</div>