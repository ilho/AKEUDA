<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: captcha_include.php
| Author: Nick Jones (Digitanium)
| Co-Author: Robert Gaudyn (Wooya) / Amra (sumotoy.net)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (isset($_GET['captcha_code']) && preg_match("/^[0-9A-Za-z]+$/", $_GET['captcha_code'])) {
	function rgb_grayscale($rgb) {
		$color['r'] = 0.299 * $rgb['r'] + 0.587 * $rgb['g'] + 0.114 * $rgb['b'];
		$color['g'] = 0.299 * $rgb['r'] + 0.587 * $rgb['g'] + 0.114 * $rgb['b'];
		$color['b'] = 0.299 * $rgb['r'] + 0.587 * $rgb['g'] + 0.114 * $rgb['b'];
		return $color;
	}
	function rgb_complementary($rgb) {
		$color['r'] = 255 - $rgb['r'];
		$color['g'] = 255 - $rgb['g'];
		$color['b'] = 255 - $rgb['b'];
		return $color;
	}
	function rgb_rand($min=0,$max=255) {
		$color['r'] = rand($min,$max);
		$color['g'] = rand($min,$max);
		$color['b'] = rand($min,$max);
		return $color;
	}
	function rgb_create($r=0,$g=0,$b=0) {
		$color['r'] = $r;
		$color['g'] = $g;
		$color['b'] = $b;
		return $color;
	}
	function rgb_merge($lhs, $rhs) {
		$color['r'] = ($lhs['r'] + $rhs['r']) >> 1;
		$color['g'] = ($lhs['g'] + $rhs['g']) >> 1;
		$color['b'] = ($lhs['b'] + $rhs['b']) >> 1;
		return $color;
	}
		
	require_once "../config.php";
	require_once "multisite_include.php";
	
	mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db($db_name);
	
	$cresult = mysql_query("SELECT * FROM ".DB_CAPTCHA." WHERE captcha_encode='".$_GET['captcha_code']."'");
	
	if (mysql_num_rows($cresult)) {
		$cdata = mysql_fetch_assoc($cresult);
		$im_wdth = 100;
		$im_hght = 20;
		$im = imagecreate($im_wdth,$im_hght);
		$strt = 0;
		$rgb = Array();
		$rgb['background'] = rgb_rand(0, 255);
		$rgb['foreground'] = rgb_grayscale(rgb_complementary($rgb['background']));
		if ( $rgb['foreground']['r'] > 127) {
			$strt = -127;
			$rgb['foreground'] = rgb_merge($rgb['foreground'], rgb_create(255, 255, 255));
			$rgb['shadow'] = rgb_merge(rgb_complementary($rgb['foreground']), rgb_create(0, 0, 0));
		} else {
			$strt = 0;
			$rgb['foreground'] = rgb_merge($rgb['foreground'], rgb_create(0, 0, 0));
			$rgb['shadow'] = rgb_merge(rgb_complementary($rgb['foreground']), rgb_create(255, 255, 255));
		}
		$color = array();
		foreach($rgb as $name => $value) {
			$color[$name] = imagecolorallocate($im, $value['r'], $value['g'], $value['b']);
		}
		imagefilledrectangle($im, 0, 0, $im_wdth, $im_hght, $color['background']);
		for ($i = 0; $i < rand(5, 9); $i++) {
			$x = rand(0, $im_wdth);
			$y = rand(0, $im_hght);
			$f = rand(0, 5);
			$c = rgb_grayscale(rgb_rand(127 - $strt, 254 - $strt));
			$color[$i] = imagecolorallocate($im,$c['r'], $c['g'], $c['b']);
			imagestring($im, $f, $x, $y, $cdata['captcha_string'], $color[$i] );
		}
		$x = (rand($im_wdth - (floor($im_wdth / 2)), $im_wdth + (floor($im_wdth / 2))) - (imagefontwidth(7) * strlen($cdata['captcha_string']))) >> 1;
		$y = (rand($im_hght - (floor($im_hght / 2)), $im_hght + (floor($im_hght / 2))) - imagefontheight(7)) >> 1;
		imagestring($im, 7, $x + 1, $y + 1, $cdata['captcha_string'], $color['shadow']);
		imagestring($im, 7, $x - 1, $y - 1, $cdata['captcha_string'], $color['shadow']);
		imagestring($im, 7, $x + 1, $y - 1, $cdata['captcha_string'], $color['shadow']);
		imagestring($im, 7, $x - 1, $y + 1, $cdata['captcha_string'], $color['shadow']);
		imagestring($im, 7, $x, $y, $cdata['captcha_string'], $color['foreground']);
		header('Content-type: image/png');
		imagepng($im);
		foreach($color as $name => $value) {
			imagecolordeallocate($im, $value);
		}
		imagedestroy($im);
	}
	exit;
}
?>