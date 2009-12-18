<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: site_links.php
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
include LOCALE.LOCALESET."admin/sitelinks.php";

if (!checkrights("SL") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "sn") {
		$message = $locale['410'];
	} elseif ($_GET['status'] == "su") {
		$message = $locale['411'];
	} elseif ($_GET['status'] == "del") {
		$message = $locale['412'];
	}
	if ($message) {	echo "<div class='admin-message'>".$message."</div>\n"; }
}

if (isset($_GET['action']) && $_GET['action'] == "refresh") {
	$i = 1;
	$result = dbquery("SELECT * FROM ".DB_SITE_LINKS." ORDER BY link_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order='$i' WHERE link_id='".$data['link_id']."'");
		$i++;
	}
	redirect(FUSION_SELF.$aidlink);
} elseif ((isset($_GET['action']) && $_GET['action'] == "moveup") && (isset($_GET['link_id']) && isnum($_GET['link_id']))) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_order='".intval($_GET['order'])."'"));
	$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order+1 WHERE link_id='".$data['link_id']."'");
	$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order-1 WHERE link_id='".$_GET['link_id']."'");
	redirect(FUSION_SELF.$aidlink);
} elseif ((isset($_GET['action']) && $_GET['action'] == "movedown") && (isset($_GET['link_id']) && isnum($_GET['link_id']))) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_order='".intval($_GET['order'])."'"));
	$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order-1 WHERE link_id='".$data['link_id']."'");
	$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order+1 WHERE link_id='".$_GET['link_id']."'");
	redirect(FUSION_SELF.$aidlink);
} elseif ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['link_id']) && isnum($_GET['link_id']))) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_id='".$_GET['link_id']."'"));
	$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order-1 WHERE link_order>'".$data['link_order']."'");
	$result = dbquery("DELETE FROM ".DB_SITE_LINKS." WHERE link_id='".$_GET['link_id']."'");
	redirect(FUSION_SELF.$aidlink."&status=del");
} else {
	if (isset($_POST['savelink'])) {
		$link_name = stripinput($_POST['link_name']);
		$link_url = stripinput($_POST['link_url']);
		$link_visibility = isnum($_POST['link_visibility']) ? $_POST['link_visibility'] : "0";
		$link_position = isset($_POST['link_position']) ? $_POST['link_position'] : "0";
		$link_window = isset($_POST['link_window']) ? $_POST['link_window'] : "0";
		$link_order = isnum($_POST['link_order']) ? $_POST['link_order'] : "";
		if ($link_name && $link_url) {
			if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['link_id']) && isnum($_GET['link_id']))) {
				$old_link_order = dbresult(dbquery("SELECT link_order FROM ".DB_SITE_LINKS." WHERE link_id='".$_GET['link_id']."'"), 0);
				if ($link_order > $old_link_order) {
					$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order-1 WHERE link_order>'$old_link_order' AND link_order<='$link_order'");
				} elseif ($link_order < $old_link_order) {
					$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order+1 WHERE link_order<'$old_link_order' AND link_order>='$link_order'");
				}
				$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_name='$link_name', link_url='$link_url', link_visibility='$link_visibility', link_position='$link_position', link_window='$link_window', link_order='$link_order' WHERE link_id='".$_GET['link_id']."'");
				redirect(FUSION_SELF.$aidlink."&status=su");
			} else {
				if (!$link_order) { $link_order = dbresult(dbquery("SELECT MAX(link_order) FROM ".DB_SITE_LINKS.""), 0) + 1; }
				$result = dbquery("UPDATE ".DB_SITE_LINKS." SET link_order=link_order+1 WHERE link_order>='$link_order'");	
				$result = dbquery("INSERT INTO ".DB_SITE_LINKS." (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('$link_name', '$link_url', '$link_visibility', '$link_position', '$link_window', '$link_order')");
				redirect(FUSION_SELF.$aidlink."&status=sn");
			}
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
	if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['link_id']) && isnum($_GET['link_id']))) {
		$result = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_id='".$_GET['link_id']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$link_name = $data['link_name'];
			$link_url = $data['link_url'];
			$link_visibility = $data['link_visibility'];
			$link_order = $data['link_order'];
			$pos1_check = ($data['link_position']=="1" ? " checked='checked'" : "");
			$pos2_check = ($data['link_position']=="2" ? " checked='checked'" : "");
			$pos3_check = ($data['link_position']=="3" ? " checked='checked'" : "");
			$window_check = ($data['link_window']=="1" ? " checked='checked'" : "");
			$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;link_id=".$data['link_id'];
			opentable($locale['401']);
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} else {
		$link_name = "";
		$link_url = "";
		$link_visibility = "";
		$link_order = "";
		$pos1_check = " checked='checked'";
		$pos2_check = "";
		$pos3_check = "";
		$window_check = "";
		$formaction = FUSION_SELF.$aidlink;
		opentable($locale['400']);
	}
	$visibility_opts = ""; $sel = "";
	$user_groups = getusergroups();
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($link_visibility == $user_group['0'] ? " selected='selected'" : "");
		$visibility_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='layoutform' method='post' action='".$formaction."'>\n";
	echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
	echo "<td class='tbl'>".$locale['420']."</td>\n";
	echo "<td class='tbl'><input type='text' name='link_name' value='".$link_name."' maxlength='100' class='textbox' style='width:240px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'>".$locale['421']."</td>\n";
	echo "<td class='tbl'><input type='text' name='link_url' value='".$link_url."' maxlength='200' class='textbox' style='width:240px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'>".$locale['422']."</td>\n";
	echo "<td class='tbl'><select name='link_visibility' class='textbox' style='width:150px;'>\n".$visibility_opts."</select>\n";
	echo $locale['423']."\n<input type='text' name='link_order'  value='".$link_order."' maxlength='2' class='textbox' style='width:40px;' />";
	echo "</td>\n</tr>\n<tr>\n";
	echo "<td valign='top' class='tbl'>".$locale['424']."</td>\n";
	echo "<td class='tbl'><label><input type='radio' name='link_position' value='1'".$pos1_check." /> ".$locale['425']."</label><br />\n";
	echo "<label><input type='radio' name='link_position' value='2'".$pos2_check." /> ".$locale['426']."</label><br />\n";
	echo "<label><input type='radio' name='link_position' value='3'".$pos3_check." /> ".$locale['427']."</label><hr />\n";
	echo "<label><input type='checkbox' name='link_window' value='1'".$window_check." /> ".$locale['428']."</label></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'>\n";
	echo "<input type='submit' name='savelink' value='".$locale['429']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();
	
	opentable($locale['402']);
	echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n<tr>\n";
	echo "<td class='tbl2'><strong>".$locale['440']."</strong></td>\n";
	echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['441']."</strong></td>\n";
	echo "<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['442']."</strong></td>\n";
	echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['443']."</strong></td>\n";
	echo "</tr>\n";
	$result = dbquery("SELECT * FROM ".DB_SITE_LINKS." ORDER BY link_order");
	if (dbrows($result)) {
		$i = 0; $k = 1;
		while($data = dbarray($result)) {
			$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>\n<td class='".$row_color."'>";
			if ($data['link_position'] == 3) echo "<i>";
			if ($data['link_name'] != "---" && $data['link_url'] == "---") {
				echo "<strong>".$data['link_name']."</strong>\n";
			} else if ($data['link_name'] == "---" && $data['link_url'] == "---") {
				echo "<hr />\n";
			} else {
				if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
					echo "<a href='".$data['link_url']."'>".$data['link_name']."</a>\n";
				} else {
					echo "<a href='".BASEDIR.$data['link_url']."'>".$data['link_name']."</a>\n";
				}
			}
			if ($data['link_position'] == 3) echo "</i>";
			echo "</td>\n";
			echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>".getgroupname($data['link_visibility'])."</td>\n";
			echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>".$data['link_order']."</td>\n";
			echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>\n";
			if (dbrows($result) != 1) {
				$up = $data['link_order'] - 1;
				$down = $data['link_order'] + 1;
				if ($k == 1) {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;link_id=".$data['link_id']."'><img src='".get_image("down")."' alt='".$locale['451']."' title='".$locale['453']."' style='border:0px;' /></a>\n";
				} elseif ($k < dbrows($result)) {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;link_id=".$data['link_id']."'><img src='".get_image("up")."' alt='".$locale['450']."' title='".$locale['452']."' style='border:0px;' /></a>\n";
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;link_id=".$data['link_id']."'><img src='".get_image("down")."' alt='".$locale['451']."' title='".$locale['453']."' style='border:0px;' /></a>\n";
				} else {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;link_id=".$data['link_id']."'><img src='".get_image("up")."' alt='".$locale['450']."' title='".$locale['452']."' style='border:0px;' /></a>\n";
				}
			}
			$k++;
			echo "</td>\n";
			echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;link_id=".$data['link_id']."'>".$locale['444']."</a> -\n";
			echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;link_id=".$data['link_id']."' onclick=\"return confirm('".$locale['460']."');\">".$locale['445']."</a></td>\n";
			echo "</tr>\n";
			$i++;
		}
	} else {
		echo "<tr>\n<td align='center' colspan='4' class='tbl1'>".$locale['446']."</td>\n</tr>\n";
	}
	echo "</table>\n";
	if (dbrows($result)) { echo "<div style='text-align:center;margin-top:5px'>[ <a href='".FUSION_SELF.$aidlink."&amp;action=refresh'>".$locale['454']."</a> ]</div>\n"; }
	closetable();
}

require_once THEMES."templates/footer.php";
?>
