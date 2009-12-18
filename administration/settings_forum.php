<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings_forum.php
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

if (!checkrights("S3") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['action']) && $_GET['action'] == "count_posts") {
	$result = dbquery("SELECT post_author, COUNT(post_id) as num_posts FROM ".DB_POSTS." GROUP BY post_author");
	if (dbrows($result)) {
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".DB_USERS." SET user_posts='".$data['num_posts']."' WHERE user_id='".$data['post_author']."'");
		}
	}
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	$result = dbquery("UPDATE ".DB_SETTINGS." SET
		numofthreads='".(isnum($_POST['numofthreads']) ? $_POST['numofthreads'] : "5")."',
		forum_ips='".(isnum($_POST['forum_ips']) ? $_POST['forum_ips'] : "103")."',
		attachmax='".(isnum($_POST['attachmax']) ? $_POST['attachmax'] : "150000")."',
		attachtypes='".$_POST['attachtypes']."',
		thread_notify='".(isnum($_POST['thread_notify']) ? $_POST['thread_notify'] : "0")."',
		forum_ranks='".(isnum($_POST['forum_ranks']) ? $_POST['forum_ranks'] : "0")."',
		forum_edit_lock='".(isnum($_POST['forum_edit_lock']) ? $_POST['forum_edit_lock'] : "0")."'
	");
	if (!$result) { $error = 1; }
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));

opentable($locale['400']);
require_once ADMIN."settings_links.php";
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['505']."<br /><span class='small2'>".$locale['506']."</span></td>\n";
echo "<td width='50%' class='tbl'><select name='numofthreads' class='textbox'>\n";
echo "<option".($settings2['numofthreads'] == 5 ? " selected='selected'" : "").">5</option>\n";
echo "<option".($settings2['numofthreads'] == 10 ? " selected='selected'" : "").">10</option>\n";
echo "<option".($settings2['numofthreads'] == 15 ? " selected='selected'" : "").">15</option>\n";
echo "<option".($settings2['numofthreads'] == 20 ? " selected='selected'" : "").">20</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['507']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_ips' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_ips'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_ips'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['508']."<br /><span class='small2'>".$locale['509']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='attachmax' value='".$settings2['attachmax']."' maxlength='150' class='textbox' style='width:100px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['510']."<br /><span class='small2'>".$locale['511']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='attachtypes' value='".$settings2['attachtypes']."' maxlength='150' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['512']."</td>\n";
echo "<td width='50%' class='tbl'><select name='thread_notify' class='textbox'>\n";
echo "<option value='1'".($settings2['thread_notify'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['thread_notify'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['520']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_ranks' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_ranks'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_ranks'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['521']."<br /><span class='small2'>".$locale['522']."</span></td>\n";
echo "<td width='50%' class='tbl'><select name='forum_edit_lock' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_edit_lock'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_edit_lock'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br /><a href='".FUSION_SELF.$aidlink."&amp;action=count_posts'>".$locale['523']."</a>".(isset($_GET['action']) && $_GET['action'] == "count_posts" ? " ".$locale['524'] : "")."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
