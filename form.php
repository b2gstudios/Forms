<?php
if (!include("settings.php")) {
	include("settings.php");
}
if (!include("includes.php")) {
	include("includes.php");
}

include("template/language.php");

$form = mysql_fetch_array(mysql_query("select * from `forms` where `id` = '".$form_id."'"));
$name = $form["name"];
$description = $form["description"];
$divid = $form["divid"];
$button = $form["button"];
$receiver_email = $form["email"];

function checknumeric($numeric) {
	$grant = false;
    // numbers 
    if(is_numeric($numeric)) { 
        $grant = true;
    } 
    return($grant);
}

function checkemail($email) {
	$email_flag=preg_match("!^\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$!",$email);
	return $email_flag;
}

$error=false;
$msg=false;
$exclamation = "";

if (isset($_POST["submit".$form_id.""])) {
	$fields = mysql_query("select `id`,`label`, `type`, `default`, `required` from `fields` where `form` = '".$form_id."' order by `order` asc") or die(mysql_error());
	while ($fl = mysql_fetch_array($fields)) {
		
		$field = $fl["id"];
		$type = $fl["type"];
		$required = $fl["required"];
		$error_f[$field]=false;
		
		$message[$type][$field] = "";
		
		$fname = just_clean($fl["label"]).$field;
		
		if (($type=="file") && ($required)) {
			if (!basename($_FILES["".$fname.""]['name'])) {
				$error=true;
				$error_f[$field]=true;
				$msg=true;
				$exclamation = lang_necessary_fields_empty;
				
				$class[$type][$field] = " error";
				$message[$type][$field] = lang_this_field_empty;
			} elseif ($fl["default"]) {
				$exte = explode(".", $_FILES["".$fname.""]['name']);
				$esay = count($exte)-1;
				$ext = strtolower($exte[$esay]);
				$exts = " ";
				$allowed = explode("[", $fl["default"]);
				$cntall = count($allowed);
				for ($i=1; $i<$cntall; $i++) {
					$exts .= str_replace("]","",$allowed[$i]);
					if ($i != $cntall-1) {
						$exts .= ", ";
					}
				}
				if (!preg_match("[".$ext."]", $fl["default"])) { 
					$error=true;
					$error_f[$field]=true;
					$msg=true;
					$exclamation = lang_allowed_file_extensions_are.$exts;
				
					$class[$type][$field] = " error";
					$message[$type][$field] = lang_allowed_file_extensions_are.$exts;
					$error = true;
				}
			}
		}
		
		if ((!$error_f[$field]) && ($type != "hidden") && ($type != "file") && ($type != "space") && ($required)) {
			if (!$_POST["".$fname.""]) {
				$error=true;
				$error_f[$field]=true;
				$msg=true;
				$exclamation = lang_necessary_fields_empty;
				
				$class[$type][$field] = " error";

				if ($type == "checkbox") {
					$message[$type][$field] = lang_checkbox_required;
				} else {
					$message[$type][$field] = lang_this_field_empty;
    			}
			} 
		}
		
		if ((!$error_f[$field]) && ($type=="email") && (!checkemail($_POST["".$fname.""]))) {
			$error=true;
			$error_f[$field]=true;
			$msg=true;
			$exclamation = lang_check_email_field;
			$class[$type][$field] = " error";
			$message[$type][$field] = lang_email_invalid;
		}
		
		if ((!$error_f[$field]) && ($type=="numeric") && (!checknumeric($_POST["".$fname.""]))) {
			$error=true;
			$error_f[$field] = true;
			$msg=true;
			$exclamation = lang_check_numeric_field;
			$class[$type][$field] = " error";
			$message[$type][$field] = lang_only_numeric;
		}
		
		if ((!$error_f[$field]) && ($type=="validation")) {
			if ($_POST["ttl".$field] != $_POST["".$fname.""]) {
				$error=true;
				$error_f[$field]=true;
				$msg=true;
				$exclamation = lang_check_validation_field;
				$class[$type][$field] = " error";
				$message[$type][$field] = lang_validation_error;
			}
		}
		
		if (!$error_f[$field]) {
			$class[$type][$field] = " valid";
		}
	}
	
	if (!$error) {
		
		ob_start(); 
		
		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
		  $eol="\r\n"; 
		} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
		  $eol="\r"; 
		} else { 
		  $eol="\n"; 
		}
		
		$mime_boundary=md5(time()); 
		$to = $receiver_email;
		$subject = lang_email_subject." ".$name."";
		
		# Common Headers 
		$headers .= 'From: '.$receiver_email.''.$eol; 
		$headers .= 'Reply-To: '.$receiver_email.''.$eol; 
		$headers .= 'Return-Path: '.$receiver_email.''.$eol;     // these two to set reply address 
		$headers .= "Message-ID: <".time()." noreply@".$_SERVER['SERVER_NAME'].">".$eol; 
		$headers .= "X-Mailer: PHP v".phpversion().$eol;           // These two to help avoid spam-filters 
		# Boundry for marking the split & Multitype Headers 
		
		$headers .= 'MIME-Version: 1.0'.$eol; 
		$headers .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"".$eol; 
		
		$attachments = "";
		
		# Text Version
		$txt = "--".$mime_boundary.$eol; 
		$txt .= "Content-Type: text/plain; charset=utf-8;".$eol;
		$txt .= "Content-Transfer-Encoding: 8bit".$eol;
		$txt .= lang_email_body_1." ".$name.$eol.$eol;
		$txt .= lang_email_body_2.$eol.$eol;
		
		# HTML Version
		$html = "Content-Type: multipart/alternative".$eol;  
		$html .= "Content-Type: text/html; charset=utf-8".$eol; 
		$html .= "Content-Transfer-Encoding: 8bit".$eol; 
		$html .= "<p>".lang_email_body_1." ".$name."</p><p>".lang_email_body_2."</p><p></p>".$eol;
		
		$footer .= "--".$mime_boundary."--".$eol.$eol;   // finish with two eol's for better security. see Injection. 
		
		
		
		$sent = mysql_query("select `id`,`label`, `type` from `fields` where `form` = '".$form_id."' and `type` != 'space' order by `order` asc") or die(mysql_error());
		while ($row = mysql_fetch_array($sent)) {
			$field = $row["id"];
			$label = $row["label"];
			$type = $row["type"];
			$fname = just_clean($row["label"]).$field;
			$value = $_POST["".$fname.""];
			
			if (($type != "validation") && ($type != "file")) {
				if ($type == "checkbox") {
					if ($value == "1") {
						$value = "checked";
					} else {
						$value = "unchecked";
					}
				} elseif (($type == "multiselect") || ($type == "checkboxlist")) {
					$newvalue = "";
					$cntval = count($value);
					for ($i=0; $i<$cntval+1; $i++) {
						$newvalue .= $value[$i];
						if ($i!=$cntval) { $newvalue .= ", "; }
					}
					$value = $newvalue;
				}
				
				$txt .= "
".$label.": ".$value.$eol."
";
				$html .= "<p><strong>".$label.":</strong> ".$value."</p>".$eol;
			}
			
			if ($type == "file") {
			
				$f_name = $_FILES[$fname]["tmp_name"];
				if ($f_name) {
					$filename = $_FILES[$fname]["name"];
					$handle=fopen($f_name, 'rb'); 
					$f_contents=fread($handle, filesize($f_name)); 
					$f_contents=chunk_split(base64_encode($f_contents));
					$f_type=filetype($f_name); 
					fclose($handle); 
				
					$attachments .= "--".$mime_boundary.$eol; 
					$attachments .= "Content-Type: ".$f_type."; name=\"".just_clean($label)."_".$filename."\"".$eol;
					$attachments .= "Content-Transfer-Encoding: base64".$eol; 
					$attachments .= "Content-Disposition: attachment; filename=\"".just_clean($label)."_".$filename."\"".$eol.$eol;
					$attachments .= $f_contents.$eol.$eol;
				}
			}
		}
		
		
		$msg = $html.$eol.$eol.$txt.$eol.$eol.$attachments.$footer;
		
		# SEND THE EMAIL 
		$ok = @mail($to, $subject, $msg, $headers); 
		
		if (!$ok) {
			$error = true;
			$msg=true;
			$exclamation = lang_email_send_error;
		} else {
			$msg=true;
			$exclamation = lang_form_sent;
			if ($goto_page) {
				echo '<meta http-equiv="REFRESH" content="0;url=' . $goto_page . '">';
			}
		}
	}
}

if (!$css_js_added) {
?>
<script type="text/javascript" src="<?=site_address?><?=javascript_folder?>ajaxroutine.js" ></script>
<link rel="stylesheet" href="<?=$css_link?>" type="text/css" media="screen" charset="utf-8" />
<!--[if lte IE 8]>
        <style type="text/css">
            @import url(<?=$ie_css_link?>);
        </style>
    <![endif]-->
<?php
$css_js_added = true;
}

include("template/form-header.php");

if ($msg) {
  	if ($error) { 
		include("template/error-message.php");
	} else { 
		include("template/success-message.php");
	} 
}

$fields = mysql_query("select `id`,`fieldid`, `label`, `type`, `default`, `required`, `description` from `fields` where `form` = '".$form_id."' order by `order` asc") or die(mysql_error());
while ($fl = mysql_fetch_array($fields)) {
	$field = $fl["id"];
	$label = $fl["label"];
	$fieldid = $fl["fieldid"];
	$type = $fl["type"];
	$default = $fl["default"];
	$required = $fl["required"];
	$description = $fl["description"];
	$field_name = just_clean($label).$field;
	
	if (isset($_POST["submit".$form_id.""])) {
		$field_value = $_POST[just_clean($label).$field];
	} else {
		$field_value = $default;
	}
	
	if (($type=="text") || ($type=="email") || ($type=="numeric")) {
		include("template/text-field-email-numeric.php");
	} elseif ($type=="textarea") {
		include("template/text-area.php");
	} elseif ($type=="space") {
		$show_label = $required;
		include("template/space.php");
	} elseif ($type=="file") {
		include("template/file.php");
	} elseif ($type=="checkbox") {
		include("template/checkbox.php");
	} elseif ($type=="hidden") {
		include("template/hidden.php");
	} elseif ($type=="validation") {
		$n1 = mt_rand(1,5);
		$n2 = mt_rand(1,5);
		$check_value = $n1+$n2;
		include("template/validation.php");
	} elseif ($type=="dropdown") {
		include("template/dropdown.php");
	} elseif ($type=="multiselect") {
		include("template/multiselect.php");
	} elseif ($type=="checkboxlist") {
		include("template/checkbox-list.php");
	} elseif ($type=="radio") {
		include("template/radio.php");
	}
}

include("template/form-footer.php");
?>