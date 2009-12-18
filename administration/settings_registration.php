<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings_registration.php
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
require_once "../maincore.php";
require_once THEMES."templates/admin_header_mce.php";
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("S4") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if ($settings['tinymce_enabled']) {
	echo "<script language='javascript' type='text/javascript'>advanced();</script>\n";
} else {
	require_once INCLUDES."html_buttons_include.php";
}

$settings2 = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));

if (isset($_POST['savesettings'])) {
	$error = 0;
	
	if (addslash($_POST['license_agreement']) != $settings2['license_agreement']) {
		$license_lastupdate = time();
	} else {
		$license_lastupdate = $settings2['license_lastupdate'];
	}
	
	$license_agreement = addslash(preg_replace("(^<p>\s</p>$)", "", $_POST['license_agreement']));
	
	$result = dbquery("UPDATE ".DB_SETTINGS." SET
		enable_registration='".(isnum($_POST['enable_registration']) ? $_POST['enable_registration'] : "1")."',
		email_verification='".(isnum($_POST['email_verification']) ? $_POST['email_verification'] : "1")."',
		admin_activation='".(isnum($_POST['admin_activation']) ? $_POST['admin_activation'] : "0")."',
		display_validation='".(isnum($_POST['display_validation']) ? $_POST['display_validation'] : "1")."',
		validation_method='".$_POST['validation_method']."',
		enable_terms='".(isnum($_POST['enable_terms']) ? $_POST['enable_terms'] : "0")."',
		license_agreement='".$license_agreement."',
		license_lastupdate='".$license_lastupdate."'		
	");
	if (!$result) { $error = 1; }
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

opentable($locale['400']);
require_once ADMIN."settings_links.php";
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['551']."</td>\n";
echo "<td width='50%' class='tbl'><select name='enable_registration' class='textbox'>\n";
echo "<option value='1'".($settings2['enable_registration'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['enable_registration'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['552']."</td>\n";
echo "<td width='50%' class='tbl'><select name='email_verification' class='textbox'>\n";
echo "<option value='1'".($settings2['email_verification'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['email_verification'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['557']."</td>\n";
echo "<td width='50%' class='tbl'><select name='admin_activation' class='textbox'>\n";
echo "<option value='1'".($settings2['admin_activation'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['admin_activation'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['553']."</td>\n";
echo "<td width='50%' class='tbl'><select name='display_validation' class='textbox'>\n";
echo "<option value='1'".($settings2['display_validation'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['display_validation'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
if (function_exists("gd_info")) {
	echo "<td width='50%' class='tbl'>".$locale['554']."</td>\n";
	echo "<td width='50%' class='tbl'><select name='validation_method' class='textbox'>\n";
	echo "<option value='image'".($settings2['validation_method'] == "image" ? " selected='selected'" : "").">".$locale['555']."</option>\n";
	echo "<option value='text'".($settings2['validation_method'] == "text" ? " selected='selected'" : "").">".$locale['556']."</option>\n";
	echo "</select></td>\n";
} else {
	echo "<td class='tbl' colspan='2'><input type='hidden' name='validation_method' value='text' /></td>\n";
}
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['558']."</td>\n";
echo "<td width='50%' class='tbl'><select name='enable_terms' class='textbox'>\n";
echo "<option value='1'".($settings2['enable_terms'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['enable_terms'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl' colspan='2'>".$locale['559']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl' colspan='2'><textarea name='license_agreement' cols='50' rows='10' class='textbox' style='width:320px'>".phpentities(stripslashes($settings2['license_agreement']))."</textarea></td>\n";
echo "</tr>\n";
if (!$settings['tinymce_enabled']) {
	echo "<tr>\n<td class='tbl' colspan='2'>\n";
	echo display_html("settingsform", "license_agreement", true, true, true);
	echo "</td>\n</tr>\n";
}
echo "<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
