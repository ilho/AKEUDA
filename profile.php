<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: profile.php
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
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."view_profile.php";
include LOCALE.LOCALESET."user_fields.php";

if (!isset($_GET['group_id']) || !isnum($_GET['group_id'])) {

	if (!isset($_GET['lookup']) || !isnum($_GET['lookup'])) { redirect("index.php"); }

	$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['lookup']."'");

	if (dbrows($result)) { $user_data = dbarray($result); } else { redirect("index.php"); }

	if ($user_data['user_status'] > "0") { redirect("index.php"); }

	if (iADMIN && checkrights("UG") && $user_data['user_id'] != $userdata['user_id']) {
		if ((isset($_POST['add_to_group'])) && (isset($_POST['user_group']) && isnum($_POST['user_group']))) {
		 	if (!preg_match("(^\.{$_POST['user_group']}$|\.{$_POST['user_group']}\.|\.{$_POST['user_group']}$)", $user_data['user_groups'])) {
				$result = dbquery("UPDATE ".DB_USERS." SET user_groups='".$user_data['user_groups'].".".$_POST['user_group']."' WHERE user_id='".$user_data['user_id']."'");
			}
			redirect(FUSION_SELF."?lookup=".$user_data['user_id']);
		}
	}

	add_to_title($locale['global_200'].$locale['400'].$locale['global_201'].$user_data['user_name']);
	opentable($locale['400']);
	echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
	if ($user_data['user_avatar'] && file_exists(IMAGES."avatars/".$user_data['user_avatar'])) {
		echo "<td rowspan='5' width='1%' class='tbl profile_user_avatar'><!--profile_user_avatar--><img src='".IMAGES."avatars/".$user_data['user_avatar']."' alt='' /></td>\n";
	}
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u001']."</td>\n";
	echo "<td align='right' class='tbl1 profile_user_name'><!--profile_user_name-->".$user_data['user_name']."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'></td>\n";
	echo "<td align='right' class='tbl1 profile_user_level'><!--profile_user_level-->".getuserlevel($user_data['user_level'])."</td>\n";
	echo "</tr>\n";
	if ($user_data['user_hide_email'] != "1" || iADMIN) {
		echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u005']."</td>\n";
		echo "<td align='right' class='tbl1'>".hide_email($user_data['user_email'])."</td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u040']."</td>\n";
	echo "<td align='right' class='tbl1'>".showdate("longdate", $user_data['user_joined'])."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u041']."</td>\n";
	echo "<td align='right' class='tbl1'>".($user_data['user_lastvisit'] ? showdate("longdate", $user_data['user_lastvisit']) : $locale['u042'])."</td>\n";
	echo "</tr>\n";
	if (iMEMBER && $userdata['user_id'] != $user_data['user_id']) {
		echo "<tr><td colspan='".($user_data['user_avatar'] && file_exists(IMAGES."avatars/".$user_data['user_avatar']) ? "3" : "2")."' class='tbl2' style='text-align:center;white-space:nowrap'><a href='messages.php?msg_send=".$user_data['user_id']."' title='".$locale['u043']."'>".$locale['u043']."</a></td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";

	echo "<div style='margin:5px'></div>\n";

	$profile_method = "display"; $i = 0; $user_fields_output = array("", "", "", ""); $ob_active = false;

	$result2 = dbquery("SELECT * FROM ".DB_USER_FIELDS." ORDER BY field_group, field_order");
	if (dbrows($result2)) {
		while($data2 = dbarray($result2)) {
			if ($i != $data2['field_group']) {
				if ($ob_active) {
					$user_fields_output[$i] = ob_get_contents();
					ob_end_clean();
					$ob_active = false;
				}
				$i = $data2['field_group'];
			}
			if (!$ob_active) {
				ob_start();
				$ob_active = true;
			}
			if (file_exists(LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php")) {
				include LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php";
			}
			if (file_exists(INCLUDES."user_fields/".$data2['field_name']."_include.php")) {
				include INCLUDES."user_fields/".$data2['field_name']."_include.php";
			}
		}
	}

	if ($ob_active) {
		$user_fields_output[$i] = ob_get_contents();
		ob_end_clean();
	}

	if (array_key_exists(1, $user_fields_output) && $user_fields_output[1]) {
		echo "<div style='margin:5px'></div>\n";
		echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
		echo "<td colspan='2' class='tbl2'><strong>".$locale['u044']."</strong></td>\n";
		echo "</tr>\n".$user_fields_output[1];
		echo "</table>\n";
	}

	if (array_key_exists(2, $user_fields_output) && $user_fields_output[2]) {
		echo "<div style='margin:5px'></div>\n";
		echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
		echo "<td colspan='2' class='tbl2'><strong>".$locale['u045']."</strong></td>\n";
		echo "</tr>\n".$user_fields_output[2];
		echo "</table>\n";
	}

	if (array_key_exists(4, $user_fields_output) && $user_fields_output[4]) {
		echo "<div style='margin:5px'></div>\n";
		echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
		echo "<td colspan='2' class='tbl2'><strong>".$locale['u047']."</strong></td>\n";
		echo "</tr>\n".$user_fields_output[4];
		echo "</table>\n";
	}

	if (iADMIN && checkrights("M")) {
		echo "<div style='margin:5px'></div>\n";
		echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
		echo "<td colspan='2' class='tbl2'><strong>".$locale['u048']."</strong></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u049']."</td>\n";
		echo "<td align='right' class='tbl1'>".$user_data['user_ip']."</td>\n";
		echo "</tr>\n</table>\n";
	}

	if ($user_data['user_groups']) {
		echo "<div style='margin:5px'></div>\n";
		echo "<table cellpadding='0' cellspacing='1' width='400' class='center tbl-border'>\n<tr>\n";
		echo "<td class='tbl2'><strong>".$locale['401']."</strong></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1'>\n";
		$user_groups = (strpos($user_data['user_groups'], ".") == 0 ? explode(".", substr($user_data['user_groups'], 1)) : explode(".", $user_data['user_groups']));
		for ($i = 0; $i < count($user_groups); $i++) {
			echo "<div style='float:left'><a href='".FUSION_SELF."?group_id=".$user_groups[$i]."'>".getgroupname($user_groups[$i])."</a></div><div style='float:right'>".getgroupname($user_groups[$i], true)."</div><div style='float:none;clear:both'></div>\n";
		}
		echo "</td>\n</tr>\n</table>\n";
	}
	if (iADMIN && checkrights("M") && $user_data['user_id'] != $userdata['user_id']) {
		$user_groups_opts = "";
		if ($user_data['user_level'] < 102) {
			echo "<div style='margin:5px'></div>\n";
			echo "<form name='admin_form' method='post' action='".FUSION_SELF."?lookup=".$user_data['user_id']."'>\n";
			echo "<table cellpadding='0' cellspacing='0' width='400' class='center tbl-border'>\n<tr>\n";
			echo "<td class='tbl2' colspan='2'><strong>".$locale['402']."</strong></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1'><!--profile_admin_options-->\n";
			echo "<a href='".ADMIN."members.php".$aidlink."&amp;step=edit&amp;user_id=".$user_data['user_id']."'>".$locale['410']."</a> ::\n";
			echo "<a href='".ADMIN."members.php".$aidlink."&amp;step=ban&amp;act=on&amp;user_id=".$user_data['user_id']."&amp;status=1' onclick=\"return confirm('".$locale['413']."');\">".$locale['411']."</a> ::\n";
			echo "<a href='".ADMIN."members.php".$aidlink."&amp;step=delete&amp;status=0&amp;user_id=".$user_data['user_id']."' onclick=\"return confirm('".$locale['414']."');\">".$locale['412']."</a></td>\n";
			$result = dbquery("SELECT * FROM ".DB_USER_GROUPS." ORDER BY group_id ASC");
			if (dbrows($result)) {
				while ($data2 = dbarray($result)) {
					if (!preg_match("(^\.{$data2['group_id']}|\.{$data2['group_id']}\.|\.{$data2['group_id']}$)", $user_data['user_groups'])) {
						$user_groups_opts .= "<option value='".$data2['group_id']."'>".$data2['group_name']."</option>\n";
					}
				}
				if (iADMIN && checkrights("UG") && $user_groups_opts) {
					echo "<td align='right' class='tbl1'>".$locale['415']."\n";
					echo "<select name='user_group' class='textbox' style='width:100px'>\n".$user_groups_opts."</select>\n";
					echo "<input type='submit' name='add_to_group' value='".$locale['416']."' class='button'  onclick=\"return confirm('".$locale['417']."');\" /></td>\n";
				}
			}
			echo "</tr>\n</table>\n</form>\n";
		}
	}
} else {
	$result = dbquery("SELECT * FROM ".DB_USER_GROUPS." WHERE group_id='".$_GET['group_id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_groups REGEXP('^\\\.{$_GET['group_id']}$|\\\.{$_GET['group_id']}\\\.|\\\.{$_GET['group_id']}$') ORDER BY user_level DESC, user_name");
		opentable($locale['420']);
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		echo "<td align='center' colspan='2' class='tbl1'><strong>".$data['group_name']."</strong> (".sprintf((dbrows($result) == 1 ? $locale['421'] : $locale['422']), dbrows($result)).")</td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl2'><strong>".$locale['423']."</strong></td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['424']."</strong></td>\n";
		echo "</tr>\n";
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
			echo "<tr>\n<td class='$cell_color'>\n<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></td>\n";
			echo "<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>\n</tr>";
		}
		echo "</table>\n";
	} else {
		redirect("index.php");
	}
}
closetable();

require_once THEMES."templates/footer.php";
?>
