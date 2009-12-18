<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: blacklist.php
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
include LOCALE.LOCALESET."admin/blacklist.php";

if (!checkrights("B") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['status'])) {
	if ($_GET['status'] == "del") {
		$title = $locale['400'];
		$message = "<strong>".$locale['401']."</strong>";
	}
	opentable($title);
	echo "<div style='text-align:center'>".$message."</div>\n";
	closetable();
}

		if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['blacklist_id']) && isnum($_GET['blacklist_id']))) {
	$result = dbquery("DELETE FROM ".DB_BLACKLIST." WHERE blacklist_id='".$_GET['blacklist_id']."'");
	redirect(FUSION_SELF.$aidlink."&status=del");
} else {
	if (isset($_POST['blacklist_user'])) {
		$blacklist_ip = stripinput($_POST['blacklist_ip']);
		$blacklist_email = stripinput($_POST['blacklist_email']);
		$blacklist_reason = stripinput($_POST['blacklist_reason']);
		if ($blacklist_ip || $blacklist_email) {
		if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['blacklist_id']) && isnum($_GET['blacklist_id']))) {
				$result = dbquery("UPDATE ".DB_BLACKLIST." SET blacklist_ip='$blacklist_ip', blacklist_email='$blacklist_email', blacklist_reason='$blacklist_reason' WHERE blacklist_id='".$_GET['blacklist_id']."'");
			} else {
					$result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_ip, blacklist_email, blacklist_reason) VALUES ('$blacklist_ip', '$blacklist_email', '$blacklist_reason')");
			}
		}
		redirect(FUSION_SELF.$aidlink);
	}
	if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['blacklist_id']) && isnum($_GET['blacklist_id']))) {
		$result = dbquery("SELECT * FROM ".DB_BLACKLIST." WHERE blacklist_id='".$_GET['blacklist_id']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$blacklist_ip = $data['blacklist_ip'];
			$blacklist_email = $data['blacklist_email'];
			$blacklist_reason = $data['blacklist_reason'];
			$form_title = $locale['421'];
			$form_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;blacklist_id=".$data['blacklist_id'];
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} else {
		$blacklist_ip = "";
		$blacklist_email = "";
		$blacklist_reason = "";
		$form_title = $locale['420'];
		$form_action = FUSION_SELF.$aidlink;
	}
	opentable($form_title);
	echo "<table cellpadding='0' cellspacing='0' width='450' class='center'>\n<tr>\n";
	echo "<td class='tbl'>".$locale['440']."\n";
	echo "<hr /></td>\n</tr>\n</table>\n";
	echo "<form name='blacklist_form' method='post' action='$form_action'>\n";
	echo "<table align='center' width='450' cellpadding='0' cellspacing='0'>\n<tr>\n";
	echo "<td class='tbl'>".$locale['441']."</td>\n";
	echo "<td class='tbl'><input type='text' name='blacklist_ip' value='".$blacklist_ip."' class='textbox' style='width:150px' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'>".$locale['442']."</td>\n";
	echo "<td class='tbl'><input type='text' name='blacklist_email' value='".$blacklist_email."' class='textbox' style='width:250px' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td valign='top' class='tbl'>".$locale['443']."</td>\n";
	echo "<td class='tbl'><textarea name='blacklist_reason' cols='46' rows='3' class='textbox'>".$blacklist_reason."</textarea></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'><br />\n";
	echo "<input type='submit' name='blacklist_user' value='".$locale['444']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();

	opentable($locale['460']);
	$result = dbquery("SELECT * FROM ".DB_BLACKLIST);
	if (dbrows($result) != 0) {
		$i = 0;
		echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
		echo "<td class='tbl2'>".$locale['461']."</td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['462']."</td>\n";
		echo "</tr>\n";
		while ($data = dbarray($result)) {
			$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>\n";
			echo "<td class='$row_color'>".($data['blacklist_ip'] ? $data['blacklist_ip'] : $data['blacklist_email']);
			if ($data['blacklist_reason']) {
				echo "<br /><span class='small2'>".$data['blacklist_reason']."</span>";
			}
			echo "</td>\n<td align='center' width='1%' class='$row_color' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;blacklist_id=".$data['blacklist_id']."'>".$locale['463']."</a> -\n";
			echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;blacklist_id=".$data['blacklist_id']."'>".$locale['464']."</a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['465']."<br /><br />\n</div>\n";
	}
	closetable();
}

require_once THEMES."templates/footer.php";
?>