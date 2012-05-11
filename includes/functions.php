<?php
function retconfirm ($text) {
	return '' . 'onclick="return confirm(\'' . $text . '\')"';
}

setlocale(LC_ALL, 'en_US.UTF8');
function just_clean($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = str_replace("_", '-', $str);
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}

function reload ($time, $url) {
	echo '' . '<meta http-equiv="REFRESH" content="' . $time . ';url=' . $url . '">';
}
?>