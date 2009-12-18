<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: members.php
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
include LOCALE.LOCALESET."admin/members.php";
include LOCALE.LOCALESET."user_fields.php";

if (!checkrights("M") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (!isset($_GET['step'])) { $_GET['step'] = ""; }

if ($_GET['step'] == "add") {
	if (isset($_POST['add_user'])) {
		$error = "";
		
		$username = trim(preg_replace("/ +/i", " ", $_POST['username']));
		
		if ($username == "" || trim($_POST['password1']) == "" || trim($_POST['email']) == "") { $error .= $locale['451']."<br />\n"; }
		
		if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) { $error .= $locale['452']."<br />\n"; }
		
		if (preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['password1'])) {
			if ($_POST['password1'] != $_POST['password2']) { $error .= $locale['456']."<br />\n"; }
		} else {
			$error .= $locale['457']."<br />\n";
		}
	 
		if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['email'])) {
			$error .= $locale['454']."<br />\n";
		}
		
		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='$username'");
		if (dbrows($result)) { $error = $locale['453']."<br />\n"; }
		
		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_email='".$_POST['email']."'");
		if (dbrows($result)) { $error = $locale['455']."<br />\n"; }

		$profile_method = "validate_insert"; $db_fields = ""; $db_values = "";
		$result = dbquery("SELECT * FROM ".DB_USER_FIELDS." ORDER BY field_order");
		if (dbrows($result)) {
			while($data = dbarray($result)) {
				if (file_exists(LOCALE.LOCALESET."user_fields/".$data['field_name'].".php")) {
					include LOCALE.LOCALESET."user_fields/".$data['field_name'].".php";
				}
				if (file_exists(INCLUDES."user_fields/".$data['field_name']."_include.php")) {
					include INCLUDES."user_fields/".$data['field_name']."_include.php";
				}
			}
		}
		
		if ($error == "") {
			$result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status".(isset($db_fields) ? $db_fields : "").") VALUES('$username', '".md5(md5($_POST['password1']))."', '', '".$_POST['email']."', '".intval($_POST['hide_email'])."', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '0'".(isset($db_values) ? $db_values : "").")");
			opentable($locale['480']);
			echo "<div style='text-align:center'><br />\n".$locale['481']."<br /><br />\n";
			echo "<a href='members.php".$aidlink."'>".$locale['432']."</a><br /><br />\n";
			echo "<a href='index.php".$aidlink."'>".$locale['433']."</a><br /><br />\n";
			echo "</div>\n";
			closetable();
		} else {
			opentable($locale['480']);
			echo "<div style='text-align:center'><br />\n".$locale['482']."<br /><br />\n".$error."<br />\n";
			echo "<a href='members.php".$aidlink."'>".$locale['432']."</a><br /><br />\n";
			echo "<a href='index.php".$aidlink."'>".$locale['433']."</a><br /><br />\n";
			echo "</div>\n";
			closetable();
		}
	} else {
		opentable($locale['480']);
		echo "<form name='addform' method='post' action='".FUSION_SELF.$aidlink."&amp;step=add'>\n";
		echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u001']."<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='text' name='username' maxlength='30' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u002']."<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u004']."<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='password' name='password2' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u005']."<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='text' name='email' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u006']."</td>\n";
		echo "<td class='tbl'><label><input type='radio' name='hide_email' value='1' />".$locale['u007']."</label> <label><input type='radio' name='hide_email' value='0' checked='checked' />".$locale['u008']."</label></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td align='center' colspan='2'><br />\n";
		echo "<input type='submit' name='add_user' value='".$locale['480']."' class='button' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
		closetable();
	}
} elseif ($_GET['step'] == "view" && isnum($_GET['user_id'])) {
	$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'");
	if (dbrows($result)) { $user_data = dbarray($result); } else { redirect(FUSION_SELF.$aidlink); }
	
	opentable($locale['470']);
	echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
	if ($user_data['user_avatar'] && file_exists(IMAGES."avatars/".$user_data['user_avatar'])) {
		echo "<td rowspan='5' width='1%' class='tbl'><img src='".IMAGES."avatars/".$user_data['user_avatar']."' alt='' /></td>\n";
	}
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u001']."</td>\n";
	echo "<td align='right' class='tbl1'>".$user_data['user_name']."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'></td>\n";
	echo "<td align='right' class='tbl1'>".getuserlevel($user_data['user_level'])."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u005']."</td>\n";
	echo "<td align='right' class='tbl1'>".hide_email($user_data['user_email'])."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u040']."</td>\n";
	echo "<td align='right' class='tbl1'>".showdate("longdate", $user_data['user_joined'])."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u041']."</td>\n";
	echo "<td align='right' class='tbl1'>".($user_data['user_lastvisit'] ? showdate("longdate", $user_data['user_lastvisit']) : $locale['u042'])."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td colspan='".($user_data['user_avatar'] && file_exists(IMAGES."avatars/".$user_data['user_avatar']) ? "3" : "2")."' class='tbl2' style='text-align:center;white-space:nowrap'><a href='".BASEDIR."messages.php?msg_send=".$user_data['user_id']."' title='".$locale['u043']."'>".$locale['u043']."</a></td>\n";
	echo "</tr>\n</table>\n";
	
	echo "<div style='margin:5px'></div>\n";
	
	$profile_method = "display"; $user_fields_output = ""; $i = 0; $ob_active = false;
	
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
	
	echo "<div style='margin:5px'></div>\n";
	echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
	echo "<td colspan='2' class='tbl2'><strong>".$locale['u048']."</strong></td>\n";
	echo "</tr>\n<tr>\n";		
	echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u049']."</td>\n";
	echo "<td align='right' class='tbl1'>".$user_data['user_ip']."</td>\n";
	echo "</tr>\n</table>\n";
	closetable();
} elseif ($_GET['step'] == "edit" && isnum($_GET['user_id'])) {
	$user_data = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'"));
	if (!$user_data || $user_data['user_level'] >= 102) { redirect(FUSION_SELF.$aidlink); }
	if (isset($_POST['savechanges'])) {
		require_once "updateuser.php";
		if ($error == "") {
			opentable($locale['430']);
			echo "<div style='text-align:center'><br />\n";
			echo $locale['431']."<br /><br />\n";
			echo "<a href='members.php".$aidlink."'>".$locale['432']."</a><br /><br />\n";
			echo "<a href='index.php".$aidlink."'>".$locale['433']."</a><br /><br />\n";
			echo "</div>\n";
			closetable();
		} else {
			opentable($locale['430']);
			echo "<div style='text-align:center'><br />\n";
			echo $locale['434']."<br /><br />\n".$error."<br />\n";
			echo "<a href='members.php".$aidlink."'>".$locale['432']."</a><br /><br />\n";
			echo "<a href='index.php".$aidlink."'>".$locale['433']."</a><br /><br />\n";
			echo "</div>\n";
			closetable();
		}
	} else {
		require_once INCLUDES."bbcode_include.php";
		$offset_list = "";
		for ($i = -13; $i < 17; $i++) {
			if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
			$offset_list .= "<option".($offset == $data['user_offset'] ? " selected='selected'" : "").">".$offset."</option>\n";
		}
		opentable($locale['430']);
		echo "<form name='inputform' method='post' action='".FUSION_SELF.$aidlink."&amp;step=edit&amp;user_id=".$_GET['user_id']."' enctype='multipart/form-data'>\n";
		echo "<table cellpadding='0' cellspacing='0' class='center'>\n";
		echo "<tr>\n<td class='tbl'>".$locale['u001'].":<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='text' name='user_name' value='".$user_data['user_name']."' maxlength='30' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u003'].":</td>\n";
		echo "<td class='tbl'><input type='password' name='user_new_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u004'].":</td>\n";
		echo "<td class='tbl'><input type='password' name='user_new_password2' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u005'].":<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='text' name='user_email' value='".$user_data['user_email']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['u006'].":</td>\n";
		echo "<td class='tbl'><input type='radio' name='user_hide_email' value='1'".($user_data['user_hide_email'] == "1" ? " checked='checked'" : "")." />".$locale['u007']." ";
		echo "<input type='radio' name='user_hide_email' value='0'".($user_data['user_hide_email'] == "0" ? " checked='checked'" : "")." />".$locale['u008']."</td>\n";
		echo "</tr>\n";
		
		if (!$user_data['user_avatar']) {
			echo "<tr>\n";
			echo "<td valign='top' class='tbl'>".$locale['u010'].":</td>\n";
			echo "<td class='tbl'><input type='file' name='user_avatar' class='textbox' style='width:200px;' /><br />\n";
			echo "<span class='small2'>".$locale['u011']."</span><br />\n";
			echo "<span class='small2'>".sprintf($locale['u012'], parsebytesize(30720), 100, 100)."</span></td>\n";
			echo "</tr>\n";
		} else {
			echo "<tr>\n";
			echo "<td valign='top' class='tbl'>".$locale['u010'].":</td>\n";
			echo "<td class='tbl'><img src='".IMAGES."avatars/".$user_data['user_avatar']."' alt='".$locale['u010']."' /><br />\n";
			echo "<input type='checkbox' name='del_avatar' value='y' /> ".$locale['u013']."\n";
			echo "<input type='hidden' name='user_avatar' value='".$user_data['user_avatar']."' /></td>\n";
			echo "</tr>\n";
		}

		$profile_method = "input";
		$result2 = dbquery("SELECT * FROM ".DB_USER_FIELDS." WHERE field_group != '4' GROUP BY field_group");
		while($data2 = dbarray($result2)) {
			$result3 = dbquery("SELECT * FROM ".DB_USER_FIELDS." WHERE field_group='".$data2['field_group']."' ORDER BY field_order");
			if (dbrows($result3)) {
				echo "<tr>\n<td class='tbl2'></td>\n";
				echo "<td class='tbl2'><strong>";
				if ($data2['field_group'] == 1) {
					echo $locale['u044'];
				} elseif ($data2['field_group'] == 2) {
					echo $locale['u045'];
				} elseif ($data2['field_group'] == 3) {
					echo $locale['u046'];
				}
				echo "</strong></td>\n</tr>\n";
				while($data3 = dbarray($result3)) {
					if (file_exists(LOCALE.LOCALESET."user_fields/".$data3['field_name'].".php")) {
						include LOCALE.LOCALESET."user_fields/".$data3['field_name'].".php";
					}
					if (file_exists(INCLUDES."user_fields/".$data3['field_name']."_include.php")) {
						include INCLUDES."user_fields/".$data3['field_name']."_include.php";
					}
				}
			}
		}
		
		echo "<tr>\n<td align='center' colspan='2' class='tbl'><br />\n";
		echo "<input type='hidden' name='user_hash' value='".$user_data['user_password']."' />\n";
		echo "<input type='submit' name='savechanges' value='".$locale['440']."' class='button' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
		closetable();
	}
} else {
	opentable($locale['400']);
	if ($_GET['step'] == "ban" && isnum($_GET['user_id'])) {
		if ($_GET['act'] == "on") {
			$udata = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'"));
			if ($udata && $udata['user_level'] < 102) {
				$result = dbquery("UPDATE ".DB_USERS." SET user_status='1' WHERE user_id='".$_GET['user_id']."'");
				echo "<div style='text-align:center'>".$locale['420']."<br /><br /></div>\n";
			}
		} elseif ($_GET['act'] == "off") {
			$result = dbquery("UPDATE ".DB_USERS." SET user_status='0' WHERE user_id='".$_GET['user_id']."'");
			echo "<div style='text-align:center'>".$locale['421']."<br /><br /></div>\n";
		}
	} elseif ($_GET['step'] == "activate" && isnum($_GET['user_id'])) {
		$result = dbquery("SELECT user_name,user_email FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'");
		if (dbrows($result) != 0) {
			$udata = dbarray($result);
			$result = dbquery("UPDATE ".DB_USERS." SET user_status='0' WHERE user_id='".$_GET['user_id']."'");
			if ($settings['email_verification'] == "1") {
				require_once INCLUDES."sendmail_include.php";
				sendemail($udata['user_name'],$udata['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['425'].$settings['sitename'],str_replace("[USER_NAME]", $udata['user_name'], $locale['426']));
			}
			echo "<div style='text-align:center'>".$locale['424']."<br /><br /></div>\n";
		}
	} elseif ($_GET['step'] == "delete" && isnum($_GET['user_id'])) {
		$udata = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'"));
		if ($udata['user_level'] < 102) {
			$result = dbquery("DELETE FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_ARTICLES." WHERE article_name='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_name='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_to='".$_GET['user_id']."' OR message_from='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_NEWS." WHERE news_name='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_POLL_VOTES." WHERE vote_user='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_RATINGS." WHERE rating_user='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_SHOUTBOX." WHERE shout_name='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_THREADS." WHERE thread_author='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_POSTS." WHERE post_author='".$_GET['user_id']."'");
			$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_user='".$_GET['user_id']."'");
			echo "<div style='text-align:center'>".$locale['422']."<br /><br /></div>\n";
		}
	}
	if (!isset($_GET['status']) || !isnum($_GET['status'])) { $_GET['status'] = "0"; }
	if (isset($_GET['search_text']) && preg_check("/^[-0-9A-Z_@\s]+$/i", $_GET['search_text'])) {
		$user_name = " user_name LIKE '".stripinput($_GET['search_text'])."%' AND";
		$list_link = "search_text=".stripinput($_GET['search_text']);
	} elseif (isset($_GET['sortby']) && preg_check("/^[0-9A-Z]$/", $_GET['sortby'])) { 
		$user_name = ($_GET['sortby'] == "all" ? "" : " user_name LIKE '".stripinput($_GET['sortby'])."%' AND");
		$list_link = "sortby=".stripinput($_GET['sortby']);
	} else {
		$user_name = "";
		$list_link = "sortby=all";
		$_GET['sortby'] = "all";
	}
	$result = dbquery("SELECT * FROM ".DB_USERS." WHERE".$user_name." user_status='".$_GET['status']."'AND user_level<'102'");
	$rows = dbrows($result);
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) $_GET['rowstart'] = 0; 
	$result = dbquery("SELECT * FROM ".DB_USERS." WHERE".$user_name." user_status='".$_GET['status']."'AND user_level<'102' ORDER BY user_status DESC, user_level DESC, user_name LIMIT ".$_GET['rowstart'].",20");
	echo "<div style='text-align:center;margin-bottom:10px;'>\n";
	echo "<a href='".FUSION_SELF.$aidlink."&amp;".$list_link."&amp;status=0'>".$locale['417']."</a> ::\n";
	echo "<a href='".FUSION_SELF.$aidlink."&amp;".$list_link."&amp;status=2'>".$locale['418']."</a> ::\n";
	echo "<a href='".FUSION_SELF.$aidlink."&amp;".$list_link."&amp;status=1'>".$locale['419']."</a> ::\n";
	echo "<a href='".FUSION_SELF.$aidlink."&amp;step=add'>".$locale['402']."</a>\n";
	echo "</div>\n";
	if ($rows) {
		$i = 0;
		echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n<tr>\n";
		echo "<td class='tbl2'><strong>".$locale['401']."</strong></td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['403']."</strong></td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['404']."</strong></td>\n";
		echo "</tr>\n";
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>\n<td class='$cell_color'><a href='".FUSION_SELF.$aidlink."&amp;step=view&amp;user_id=".$data['user_id']."'>".$data['user_name']."</a></td>\n";
			echo "<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>\n";
			echo "<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>";
			if ($data['user_level'] < 102) echo "<a href='".FUSION_SELF.$aidlink."&amp;step=edit&amp;user_id=".$data['user_id']."'>".$locale['406']."</a>\n";
			if ($data['user_status'] == "2") {
				echo "- <a href='".FUSION_SELF.$aidlink."&amp;step=activate&amp;".$list_link."&amp;status=".$_GET['status']."&amp;rowstart=".$_GET['rowstart']."&amp;user_id=".$data['user_id']."'>".$locale['407']."</a>\n";
			} elseif ($data['user_status'] == "1") {
				echo "- <a href='".FUSION_SELF.$aidlink."&amp;step=ban&amp;act=off&amp;".$list_link."&amp;status=".$_GET['status']."&amp;rowstart=".$_GET['rowstart']."&amp;user_id=".$data['user_id']."'>".$locale['408']."</a>\n";
			} else {
				if ($data['user_level'] < 102) echo "- <a href='".FUSION_SELF.$aidlink."&amp;step=ban&amp;act=on&amp;".$list_link."&amp;status=".$_GET['status']."&amp;rowstart=".$_GET['rowstart']."&amp;user_id=".$data['user_id']."'>".$locale['409']."</a>\n";
			}
			if ($data['user_level'] < 102) echo "- <a href='".FUSION_SELF.$aidlink."&amp;step=delete&amp;".$list_link."&amp;status=".$_GET['status']."&amp;rowstart=".$_GET['rowstart']."&amp;user_id=".$data['user_id']."' onclick='return DeleteMember();'>".$locale['410']."</a>";
			echo "</td>\n</tr>\n"; $i++;
		}
		echo "</table>\n";
	} else {
		if (isset($_GET['status']) && $_GET['status'] == "0") { $user_types = ""; }
		elseif (isset($_GET['status']) && $_GET['status'] == "1") { $user_types = $locale['419']; }
		elseif (isset($_GET['status']) && $_GET['status'] == "2") { $user_types = $locale['418']; }
		
		if (isset($_GET['search_text']) && preg_check("/^[-0-9A-Z_@\s]+$/i", $_GET['search_text'])) {
			echo "<div style='text-align:center'><br />".sprintf($locale['411'], $user_types).$locale['413']."'".stripinput($_GET['search_text'])."'<br /><br />\n</div>\n";
		} else {
			echo "<div style='text-align:center'><br />".sprintf($locale['411'], $user_types).($_GET['sortby'] == "all" ? "" : $locale['412'].$_GET['sortby']).".<br /><br />\n</div>\n";
		}
	}
	$alphanum = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
		"S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9"
	);
	echo "<table cellpadding='0' cellspacing='1' class='tbl-border center' style='margin-top:10px;'>\n<tr>\n";
	echo "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF.$aidlink."&amp;sortby=all&amp;status=".$_GET['status']."'>".$locale['414']."</a></td>";
	for ($i=0;$i < 36;$i++) {
		echo "<td align='center' class='tbl1'><div class='small'><a href='".FUSION_SELF.$aidlink."&amp;sortby=".$alphanum[$i]."&amp;status=".$_GET['status']."'>".$alphanum[$i]."</a></div></td>";
		echo ($i==17 ? "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF.$aidlink."&amp;sortby=all&amp;status=".$_GET['status']."'>".$locale['414']."</a></td>\n</tr>\n<tr>\n" : "\n");
	}
	echo "</tr>\n</table>\n";
	echo "<hr />\n<form name='searchform' method='get' action='".FUSION_SELF."'>\n";
	echo "<div style='text-align:center'>\n";
	echo "<input type='hidden' name='aid' value='".iAUTH."' />\n";
	echo "<input type='hidden' name='status' value='".$_GET['status']."' />\n";
	echo $locale['415']." <input type='text' name='search_text' class='textbox' style='width:150px'/>\n";
	echo "<input type='submit' name='search' value='".$locale['416']."' class='button' />\n";
	echo "</div>\n</form>\n";
	closetable();
	if ($rows > 20) echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'],20,$rows,3,FUSION_SELF.$aidlink."&amp;".$list_link."&amp;status=".$_GET['status']."&amp;")."\n</div>\n";
	echo "<script type='text/javascript'>"."\n"."function DeleteMember(username) {\n";
	echo "return confirm('".$locale['423']."');\n}\n</script>\n";
}

require_once THEMES."templates/footer.php";
?>
