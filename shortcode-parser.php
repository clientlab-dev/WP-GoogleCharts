<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

$str = $_POST['shortcode_text'];

	$isShortcode = false;



	if (preg_match("/\[googleChart/", $str )==1) {
		$isShortcode = true;
	}



	if ($isShortcode) {
		$atts = '';
		$pregRes = array();
		preg_match("/\[googleChart (.*?) \]/", $str, $pregRes);
		$atts = $pregRes[1];
		$res = shortcode_parse_atts( trim(stripcslashes ($atts)) );

		echo json_encode($res,JSON_HEX_QUOT);
 }

?>
