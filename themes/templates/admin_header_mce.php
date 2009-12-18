<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin_header.php
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

define("ADMIN_PANEL", true);

require_once INCLUDES."output_handling_include.php";
require_once INCLUDES."header_includes.php";
require_once THEME."theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) { redirect(BASEDIR."maintenance.php"); }
if (iMEMBER) { $result = dbquery("UPDATE ".DB_USERS." SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'"); }

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' media='screen' />\n";
if (file_exists(IMAGES."favicon.ico")) { echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n"; }
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "<script type='text/javascript' src='".INCLUDES."jscript.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES."jquery.js'></script>\n";

if ($settings['tinymce_enabled'] == 1) {
	echo "<script language='javascript' type='text/javascript' src='".INCLUDES."jscripts/tiny_mce/tiny_mce.js'></script>
<script type='text/javascript'>
function advanced() {
	tinyMCE.init({
		mode:'textareas',
		theme:'advanced',
		width:'100%',
		height:'250',
		language:'".$locale['tinymce']."',
		entities:'60,lt,62,gt',
		document_base_url:'".$settings['siteurl']."',
		relative_urls:'false',
		convert_newlines_to_brs: false,
		forced_root_block: false,
		force_br_newlines: true,
		force_p_newlines: false,
		plugins:'table,advhr,advimage,advlink,insertdatetime,searchreplace,contextmenu,ibrowser,fullscreen,pagebreak',
		pagebreak_separator : '<--PAGEBREAK-->',
		theme_advanced_buttons1_add_before:'pagebreak,separator',
		theme_advanced_buttons1_add:'fontsizeselect',
		theme_advanced_buttons2_add:'separator,insertdate,inserttime,separator,forecolor,backcolor,separator,fullscreen',
		theme_advanced_buttons3_add_before:'ibrowser,tablecontrols,separator',
		theme_advanced_buttons3_add:'advhr',
		theme_advanced_toolbar_location:'bottom',
		theme_advanced_toolbar_align:'center',
		theme_advanced_toolbar_location:'top',
		theme_advanced_statusbar_location : 'bottom',
		content_css:'".str_replace("../", "", THEME)."styles.css',
		external_image_list_url:'".str_replace("../", "", IMAGES)."imagelist.js',
		plugin_insertdate_dateFormat:'%d-%m-%Y',
		plugin_insertdate_timeFormat:'%H:%M:%S',
		invalid_elements:'script,object,applet,iframe',
		theme_advanced_resize_horizontal : true,
		theme_advanced_resizing : true,
		apply_source_formatting : true,
		convert_urls : false,
		extended_valid_elements:'a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]'
	});
}

function simple() {
	tinyMCE.init({
	mode:'textareas',
	theme:'simple',
	language:'en',
	convert_newlines_to_brs:'true',
	force_br_newlines:'true',
	force_p_newlines:'false'
	});
}

function showtiny(EditorID) {
	tinyMCE.removeMCEControl(tinyMCE.getEditorId(EditorID));
	tinyMCE.addMCEControl(document.getElementById(EditorID),EditorID);
}

function hidetiny(EditorID) {
	tinyMCE.removeMCEControl(tinyMCE.getEditorId(EditorID));
}
</script>\n";
}

echo "</head>\n<body>\n";

require_once THEMES."templates/panels.php";

ob_start();
?>