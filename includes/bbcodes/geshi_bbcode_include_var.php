<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: geshi_bbcode_include_var.php
| Author: Wooya
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

if (!function_exists("generate_geshi_langs")) {
	function generate_geshi_langs($textarea_name, $inputform_name) {
	   $generated = "";
	   $geshi_langs = array("php", "mysql", "html4strict", "javascript", "css", "xml");
	   for ($i=0;$i<count($geshi_langs);$i++) {
	      $generated .= "<input type='button' value='".$geshi_langs[$i]."' class='button' style='width:100px' onclick=\"addText('".$textarea_name."', '[geshi=".$geshi_langs[$i]."]', '[/geshi]', '".$inputform_name."');return false;\" /><br />";
	   }
	   return $generated;
	}
}

$__BBCODE__[] = 
array(
'description'		=>	$locale['bb_geshi_description'],
'value'			=>	"geshi",
'bbcode_start'		=>	"[geshi=".$locale['bb_geshi_lang']."]",
'bbcode_end'		=>	"[/geshi]",
'usage'			=>	"[geshi=".$locale['bb_geshi_lang']."]".$locale['bb_geshi_usage']."[/geshi]",
'onclick'		=>	"return overlay(this, 'bbcode_geshi_".$textarea_name."', 'rightbottom');",
'onmouseover'		=>	"",
'onmouseout'		=>	"",
'html_start'		=>	"<div id='bbcode_geshi_".$textarea_name."' class='tbl1' style='display: none; border:1px solid black; position: absolute; width: auto; height: auto; text-align: center' onclick=\"overlayclose('bbcode_geshi_".$textarea_name."');\">",
'includejscript'	=>	"",
'calljscript'		=>	"",
'phpfunction'		=>	"echo generate_geshi_langs('".$textarea_name."', '".$inputform_name."');",
'html_middle'		=>	"",
'html_end'		=>	"</div>"
);
?>