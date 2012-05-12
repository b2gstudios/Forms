<?
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

session_start();
ob_start();

function reload ($time, $url) {
	echo '' . '<meta http-equiv="REFRESH" content="' . $time . ';url=' . $url . '">';
}

include("../settings.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contact Form Manager Admin Login</title>

<?
if (isset($_GET["logout"])) {
	unset($_SESSION["script_user"]);
	unset($_SESSION["script_pass"]);
	unset($_SESSION["script_code"]);

	session_regenerate_id();

	session_write_close();
		
	setcookie("script_user", "", "-10000");
	setcookie("script_code", "", "-10000");
	setcookie("script_session_id", "", "-10000");

	reload(0, site_address.admin_folder);
	exit();
}

$suser = $_SESSION["script_user"];
$spass = $_SESSION["script_pass"];
$scode = $_SESSION["script_code"];

$cuser = $_COOKIE["script_user"];
$csession = $_COOKIE["script_session_id"];
$ccode = $_COOKIE["script_code"];

$loginerror=false;

if ($suser != $cuser) { $loginerror=true; }
if ($suser != md5(admin_username)) { $loginerror=true; }
if ($spass != md5(admin_password)) { $loginerror=true; }
if ($scode != $ccode) { $loginerror=true; }
if ($csession != session_id()) { $loginerror=true; }

if (!$loginerror) { 
reload(0,site_address.admin_folder."forms.php"); 
exit; }
//LOGIN
$error=false;
if (isset($_POST["submit"])) {
	if ($_POST["username"] == "") {
		$exclamation = "Please do not leave the username field empty.";
		$error = true;
	}
	elseif ($_POST["password"] == "") {
		$exclamation = "Please do not leave the password field empty.";
		$error=true;
	} else {
		if ((md5($_POST["password"]) == md5(admin_password)) && (md5($_POST["username"]) == md5(admin_username))) { 
			$code = md5(rand (10000000,99999999));
			$_SESSION["script_user"] = md5(strtolower($_POST["username"]));
			$_SESSION["script_pass"] = md5($_POST["password"]);
			$_SESSION["script_code"] = md5($code);

			setcookie("script_user",md5(strtolower($_POST["username"])),time()+3600);
			setcookie("script_session_id",session_id(),time()+3600);
			setcookie("script_code",md5($code),time()+3600);
					
			reload(0, site_address.admin_folder."forms.php");
		}
		else { 
			$exclamation = "Wrong username / password combination.";
			$error=true;
		}
	}
}
?>
<link rel="stylesheet" href="<?=site_address?><?=css_folder?>style.css" type="text/css" media="screen" />
</head>

<body>
<table width="400" align="center" border="0" cellpadding="0" cellspacing="10" style="border:1px solid #CCC; background:#FFFFFF;"><tr>
<td><div id="logo">Contact Form Manager</div></td></tr>
<tr><td>
<table width="100%" border="0" cellpadding="0" cellspacing="10" style="margin:10px; margin-top:0px;">
  <tr>
    <td colspan="2" valign="top"><strong>Login</strong>
    <? if ($error) { echo '<br /><br /><div id="order"><div id="loading"><table><tr><td valign="middle"><img src="'.site_address.static_images_folder.'delete.gif" /></td><td>'.$exclamation.'</td></table></div></div><br />'; } ?>
    </td>
  </tr>
  <form method="post">
  <tr>
    <td width="75" height="25" valign="top">Username:</td>
    <td valign="top"><label>
      <input type="text" name="username" />
    </label></td>
  </tr>
  <tr>
    <td height="25" valign="top">Password:</td>
    <td valign="top"><input type="password" name="password" /></td>
  </tr>
  <tr>
    <td height="25"></td>
    <td valign="top"><input name="submit" type="submit" value="Login" /></td>
  </tr>
  </form>
</table>
</td>
</tr>
</table>
<br />
<table width="400" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
<td align="center"><strong>Copyright © 2012, <a href="http://www.tomgabrysiak.com" target="_blank">www.tomgabrysiak.com</a></strong><br />
All rights are reserved.</td></tr></table>
</body>
</html>
