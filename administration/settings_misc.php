<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings_misc.php
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
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("S6") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_POST['savesettings'])) {
	$error = 0;
	if (isnum($_POST['newsperpage'])) {
		if ($_POST['newsperpage'] % 2 == 0) {
			$_POST['newsperpage']++;
		}
	} else {
		$_POST['newsperpage'] = 11;
	}
	$result = dbquery("UPDATE ".DB_SETTINGS." SET
		tinymce_enabled='".(isnum($_POST['tinymce_enabled']) ? $_POST['tinymce_enabled'] : "0")."',
		smtp_host='".stripinput($_POST['smtp_host'])."',
		smtp_username='".stripinput($_POST['smtp_username'])."',
		smtp_password='".stripinput($_POST['smtp_password'])."',
		bad_words_enabled='".(isnum($_POST['bad_words_enabled']) ? $_POST['bad_words_enabled'] : "0")."',
		bad_words='".addslash($_POST['bad_words'])."',
		bad_word_replace='".stripinput($_POST['bad_word_replace'])."',
		guestposts='".(isnum($_POST['guestposts']) ? $_POST['guestposts'] : "0")."',
		numofshouts='".(isnum($_POST['numofshouts']) ? $_POST['numofshouts'] : "10")."',
		userthemes='".(isnum($_POST['userthemes']) ? $_POST['userthemes'] : "0")."',
		newsperpage='".(isnum($_POST['newsperpage']) ? $_POST['newsperpage'] : "11")."',
		flood_interval='".(isnum($_POST['flood_interval']) ? $_POST['flood_interval'] : "15")."',
		maintenance='".(isnum($_POST['maintenance']) ? $_POST['maintenance'] : "0")."',
		maintenance_message='".addslash(descript($_POST['maintenance_message']))."'
	");
	if (!$result) { $error = 1; }
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));

opentable($locale['400']);
require_once ADMIN."settings_links.php";
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['662']."<br /><span class='small2'>".$locale['663']."</span></td>\n";
echo "<td width='50%' class='tbl'><select name='tinymce_enabled' class='textbox'>\n";
echo "<option value='1'".($settings2['tinymce_enabled'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['tinymce_enabled'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['664']."<br /><span class='small2'>".$locale['665']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='smtp_host' value='".$settings2['smtp_host']."' maxlength='200' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['666']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='smtp_username' value='".$settings2['smtp_username']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['667']."</td>\n";
echo "<td width='50%' class='tbl'><input type='password' name='smtp_password' value='".$settings2['smtp_password']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['659']."</td>\n";
echo "<td width='50%' class='tbl'><select name='bad_words_enabled' class='textbox'>\n";
echo "<option value='1'".($settings2['bad_words_enabled'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['bad_words_enabled'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['651']."<br /><span class='small2'>".$locale['652']."<br />".$locale['653']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='bad_words' cols='50' rows='5' class='textbox' style='width:200px;'>".$settings2['bad_words']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['654']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='bad_word_replace' value='".$settings2['bad_word_replace']."' maxlength='128' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['655']."</td>\n";
echo "<td width='50%' class='tbl'><select name='guestposts' class='textbox'>\n";
echo "<option value='1'".($settings2['guestposts'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['guestposts'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
// Start added
echo "<td width='50%' class='tbl'>".$locale['668']."?</td>\n";
echo "<td width='50%' class='tbl'><select name='userthemes' class='textbox'>\n";
echo "<option value='1'".($settings2['userthemes'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['userthemes'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['669'].":</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='newsperpage' value='".$settings2['newsperpage']."' maxlength='2' class='textbox' style='width:50px;' /> (".$locale['670'].")</td>\n";
echo "</tr>\n<tr>\n";
// End added
echo "<td width='50%' class='tbl'>".$locale['656']."</td>\n";
echo "<td width='50%' class='tbl'><select name='numofshouts' class='textbox' style='width:50px;'>\n";
echo "<option".($settings2['numofshouts'] == 5 ? " selected='selected'" : "").">5</option>\n";
echo "<option".($settings2['numofshouts'] == 10 ? " selected='selected'" : "").">10</option>\n";
echo "<option".($settings2['numofshouts'] == 15 ? " selected='selected'" : "").">15</option>\n";
echo "<option".($settings2['numofshouts'] == 20 ? " selected='selected'" : "").">20</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['660']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='flood_interval' value='".$settings2['flood_interval']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['657']."</td>\n";
echo "<td width='50%' class='tbl'><select name='maintenance' class='textbox' style='width:50px;'>\n";
echo "<option value='1'".($settings2['maintenance'] == "1" ? " selected='selected'" : "").">".$locale['502']."</option>\n";
echo "<option value='0'".($settings2['maintenance'] == "0" ? " selected='selected'" : "").">".$locale['503']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['658']."</td>\n";
echo "<td width='50%' class='tbl'><textarea name='maintenance_message' cols='50' rows='5' class='textbox' style='width:200px;'>".stripslashes($settings2['maintenance_message'])."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
