<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: html_buttons_include.php
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

include LOCALE.LOCALESET."admin/html_buttons.php";

function display_html($formname, $textarea, $html = true, $colors = false, $images = false, $folder = "") {
	
	global $locale; $res = "";
	
	if ($html) {
		$res .= "<input type='button' value='b' class='button' style='font-weight:bold;width:25px' onclick=\"addText('".$textarea."', '&lt;strong&gt;', '&lt;/strong&gt;', '".$formname."');\" />\n";
		$res .= "<input type='button' value='i' class='button' style='font-style:italic;width:25px' onclick=\"addText('".$textarea."', '&lt;i&gt;', '&lt;/i&gt;', '".$formname."');\" />\n";
		$res .= "<input type='button' value='u' class='button' style='text-decoration:underline;width:25px' onclick=\"addText('".$textarea."', '&lt;u&gt;', '&lt;/u&gt;', '".$formname."');\" />\n";
		$res .= "<input type='button' value='link' class='button' style='width:35px' onclick=\"addText('".$textarea."', '&lt;a href=\'', '\' target=\'_blank\'>Link&lt;/a&gt;', '".$formname."');\" />\n";
		$res .= "<input type='button' value='img' class='button' style='width:35px' onclick=\"addText('".$textarea."', '&lt;img src=\'".str_replace("../","",$folder)."', '\' style=\'margin:5px\' align=\'left\' /&gt;', '".$formname."');\" />\n";
		$res .= "<input type='button' value='center' class='button' style='width:45px' onclick=\"addText('".$textarea."', '&lt;center&gt;', '&lt;/center&gt;', '".$formname."');\" />\n";
		$res .= "<input type='button' value='small' class='button' style='width:40px' onclick=\"addText('".$textarea."', '&lt;span class=\'small\'&gt;', '&lt;/span&gt;', '".$formname."');\" />\n";
		$res .= "<input type='button' value='small2' class='button' style='width:45px' onclick=\"addText('".$textarea."', '&lt;span class=\'small2\'&gt;', '&lt;/span&gt;', '".$formname."');\" />\n";
		$res .= "<input type='button' value='alt' class='button' style='width:25px' onclick=\"addText('".$textarea."', '&lt;span class=\'alt\'&gt;', '&lt;/span&gt;', '".$formname."');\" />\n";
	}
	
	if ($html && ($colors || $images)) { $res .= "<br />\n"; }
	
	if ($colors) {
		$res .= "<select name='setcolor' class='textbox' style='margin-top:5px' onchange=\"addText('".$textarea."', '&lt;span style=\'color:' + this.options[this.selectedIndex].value + '\'&gt;', '&lt;/span&gt;', '".$formname."');this.selectedIndex=0;\">\n";
		$res .= "<option value=''>".$locale['html400']."</option>\n";
		$res .= "<option value='maroon' style='color:maroon'>Maroon</option>\n";
		$res .= "<option value='red' style='color:red'>Red</option>\n";
		$res .= "<option value='orange' style='color:orange'>Orange</option>\n";
		$res .= "<option value='brown' style='color:brown'>Brown</option>\n";
		$res .= "<option value='yellow' style='color:yellow'>Yellow</option>\n";
		$res .= "<option value='green' style='color:green'>Green</option>\n";
		$res .= "<option value='lime' style='color:lime'>Lime</option>\n";
		$res .= "<option value='olive' style='color:olive'>Olive</option>\n";
		$res .= "<option value='cyan' style='color:cyan'>Cyan</option>\n";
		$res .= "<option value='blue' style='color:blue'>Blue</option>\n";
		$res .= "<option value='navy' style='color:navy'>Navy Blue</option>\n";
		$res .= "<option value='purple' style='color:purple'>Purple</option>\n";
		$res .= "<option value='violet' style='color:violet'>Violet</option>\n";
		$res .= "<option value='black' style='color:black'>Black</option>\n";
		$res .= "<option value='gray' style='color:gray'>Gray</option>\n";
		$res .= "<option value='silver' style='color:silver'>Silver</option>\n";
		$res .= "<option value='white' style='color:white'>White</option>\n";
		$res .= "</select>\n";
	}
	
	if ($images && $folder) {
		$image_files = makefilelist($folder, ".|..|index.php", true);
		$image_list = makefileopts($image_files);
		$res .= "<select name='insertimage' class='textbox' style='margin-top:5px' onchange=\"insertText('".$textarea."', '&lt;img src=\'".str_replace("../","",$folder)."' + this.options[this.selectedIndex].value + '\' style=\'margin:5px\' align=\'left\' /&gt;', '".$formname."');this.selectedIndex=0;\">\n";
		$res .= "<option value=''>".$locale['html401']."</option>\n".$image_list."</select>\n";
	}
	
	return $res;
}
?>