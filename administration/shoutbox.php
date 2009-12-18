<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: shoutbox.php
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
include LOCALE.LOCALESET."admin/shoutbox.php";
if (!checkrights("S") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

include_once INCLUDES."bbcode_include.php";

if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "su") {
		$message = $locale['410'];
	} elseif ($_GET['status'] == "del") {
		$message = $locale['411'];
	} elseif ($_GET['status'] == "delall") {
		$message = number_format(intval($_GET['numr']))." ".$locale['412'];
	}
	if ($message) {	echo "<div class='admin-message'>".$message."</div>\n"; }
}

if (isset($_GET['action']) && $_GET['action'] == "deleteshouts") {
	$deletetime = time() - ($_POST['num_days'] * 86400);
	$numrows = dbcount("(shout_id)", DB_SHOUTBOX, "shout_datestamp < '".$deletetime."'");
	$result = dbquery("DELETE FROM ".DB_SHOUTBOX." WHERE shout_datestamp < '".$deletetime."'");
	redirect(FUSION_SELF.$aidlink."&status=delall&numr=$numrows");
} elseif ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
	$result = dbquery("DELETE FROM ".DB_SHOUTBOX." WHERE shout_id='".$_GET['shout_id']."'");
	redirect(FUSION_SELF.$aidlink."&status=del");
} else {
	if (isset($_POST['saveshout']) && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
		$shout_message = str_replace("\n", " ", $_POST['shout_message']);
		$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
		$shout_message = preg_replace("/([^\s]{25})/", "$1\n", $shout_message);
		$shout_message = stripinput($shout_message);
		$shout_message = str_replace("\n", "<br />", $shout_message);
		if ($shout_message) {
			$result = dbquery("UPDATE ".DB_SHOUTBOX." SET shout_message='$shout_message' WHERE shout_id='".$_GET['shout_id']."'");
			redirect(FUSION_SELF.$aidlink."&status=su");
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
	if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
		$result = dbquery("SELECT * FROM ".DB_SHOUTBOX." WHERE shout_id='".$_GET['shout_id']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			opentable($locale['420']);
			echo "<form name='editform' method='post' action='".FUSION_SELF.$aidlink."&amp;shout_id=".$data['shout_id']."'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td class='tbl'>".$locale['421']."</td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'><textarea name='shout_message' cols='60' rows='3' class='textbox' style='width:250px;'>".str_replace("<br />", "", $data['shout_message'])."</textarea></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl' align='center'>".display_bbcodes("150px;", "shout_message", "editform", "smiley|b|u|url|color")."</td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td align='center' class='tbl'><input type='submit' name='saveshout' value='".$locale['422']."' class='button' /></td>\n";
			echo "</tr>\n</table>\n\n</form>";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
	opentable($locale['401']);
	$result = dbquery("SELECT * FROM ".DB_SHOUTBOX);
	$rows = dbrows($result);
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
	if ($rows != 0) {
		if ($_GET['rowstart'] == 0) {
			echo "<div style='text-align:center'>\n";
			echo "<form name='deleteform' method='post' action='".FUSION_SELF.$aidlink."&amp;action=deleteshouts'>\n";
			echo $locale['430']." <select name='num_days' class='textbox' style='width:50px'>\n";
			echo "<option value='90'>90</option>\n";
			echo "<option value='60'>60</option>\n";
			echo "<option value='30'>30</option>\n";
			echo "<option value='20'>20</option>\n";
			echo "<option value='10'>10</option>\n";
			echo "</select>".$locale['431']."<br /><br />\n";
			echo "<input type='submit' name='deleteshouts' value='".$locale['432']."' class='button' onclick=\"return confirm('".$locale['450']."');\" /><br /><br />\n";
			echo "</form>\n</div>\n";
		}
		$i = 0;
		$result = dbquery(
			"SELECT * FROM ".DB_SHOUTBOX." LEFT JOIN ".DB_USERS."
			ON ".DB_SHOUTBOX.".shout_name=".DB_USERS.".user_id
			ORDER BY shout_datestamp DESC LIMIT ".$_GET['rowstart'].",20"
		);
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
		while ($data = dbarray($result)) {
			echo "<tr>\n<td class='".($i % 2 == 0 ? "tbl1" : "tbl2")."'><span class='comment-name'>";
			if ($data['user_name']) {
				echo "<a href='".BASEDIR."profile.php?lookup=".$data['shout_name']."' class='slink'>".$data['user_name']."</a>";
			} else {
				echo $data['shout_name'];
			}
			echo "</span>\n<span class='small'>".$locale['global_071'].showdate("longdate", $data['shout_datestamp'])."</span><br />\n";
			echo str_replace("<br />", "", parseubb(parsesmileys($data['shout_message']), "b|i|u|url|color"))."<br />\n";
			echo "<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;shout_id=".$data['shout_id']."'>".$locale['440']."</a> -\n";
			echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;shout_id=".$data['shout_id']."' onclick=\"return confirm('".$locale['451']."');\">".$locale['441']."</a> -\n";
			echo "<strong>".$locale['442'].$data['shout_ip']."</strong></span></td>\n";
			echo "</tr>\n";
			$i++;
		}
		echo "</table>\n";
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['443']."<br /><br />\n</div>\n";
	}
	closetable();
	echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($_GET['rowstart'],20,$rows,3,FUSION_SELF.$aidlink."&amp;")."\n</div>\n";
}

require_once THEMES."templates/footer.php";
?>
