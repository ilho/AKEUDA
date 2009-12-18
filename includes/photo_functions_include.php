<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: photo_functions_include
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

function createthumbnail($filetype, $origfile, $thumbfile, $new_w, $new_h) {
	
	global $settings;
	
	if ($filetype == 1) { $origimage = imagecreatefromgif($origfile); }
	elseif ($filetype == 2) { $origimage = imagecreatefromjpeg($origfile); }
	elseif ($filetype == 3) { $origimage = imagecreatefrompng($origfile); }
	
	$old_x = imagesx($origimage);
	$old_y = imagesy($origimage);
	
	if ($old_x > $new_w || $old_y > $new_h) {
		if ($old_x < $old_y) {
			$thumb_w = round(($old_x * $new_h) / $old_y);
			$thumb_h = $new_h;
		} elseif ($old_x > $old_y) {
			$thumb_w = $new_w;
			$thumb_h = round(($old_y * $new_w) / $old_x);
		} else {
			$thumb_w = $new_w;
			$thumb_h = $new_h;
		}
	} else {
		$thumb_w = $old_x;
		$thumb_h = $old_y;
	}
	
	if ($settings['thumb_compression'] == "gd1") {
		$thumbimage = imagecreate($thumb_w,$thumb_h);
		$result = imagecopyresized($thumbimage, $origimage, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
	} else {
		$thumbimage = imagecreatetruecolor($thumb_w,$thumb_h);
		$result = imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
	}
	
	touch($thumbfile);

	if ($filetype == 1) { imagegif($thumbimage, $thumbfile); }
	elseif ($filetype == 2) { imagejpeg($thumbimage, $thumbfile); }
	elseif ($filetype == 3) { imagepng($thumbimage, $thumbfile); }
}

function image_exists($dir, $image) {
	$i = 1;
	$image_name = substr($image, 0, strrpos($image, "."));
	$image_ext = strrchr($image, ".");
	while (file_exists($dir.$image)) {
		$image = $image_name."_".$i.$image_ext;
		$i++;
	}
	return $image;
}
?>