<?
include("../../settings.php"); 
include("../includes.php"); 

$size = count ($_GET["sort1"]);

for ($i=0; $i<$size; $i++) {
	$sira = explode("-",$_GET["sort1"][$i]);
	$idsi = $sira[2];
	mysql_query("update `forms` set `order` = '".$i."' where `id` = '".$idsi."'");
}
?>
<div id="loading"><table><tr><td valign="middle"><img src="<?=site_address.static_images_folder?>tick.gif" /></td><td>Order Saved...</td></table></table></div><br /><br />