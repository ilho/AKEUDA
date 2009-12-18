<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: postreply.php
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

if (isset($_POST['previewreply'])) {
	$message = trim(stripinput(censorwords($_POST['message'])));
	$sig_checked = isset($_POST['show_sig']) ? " checked='checked'" : "";
	$disable_smileys_check = isset($_POST['disable_smileys']) || preg_match("#\[code\](.*?)\[/code\]#si", $message) ? " checked='checked'" : "";
	if ($settings['thread_notify']) $notify_checked = isset($_POST['notify_me']) ? " checked='checked'" : "";
	if ($message == "") {
		$previewmessage = $locale['421'];
	} else {
		$previewmessage = $message;
		if ($sig_checked) { $previewmessage = $previewmessage."\n\n".$userdata['user_sig']; }
		if (!$disable_smileys_check) {  $previewmessage = parsesmileys($previewmessage); }
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	$is_mod = iMOD && iUSER < "102" ? true : false;
	opentable($locale['402']);
	echo "<div class='tbl2' style='margin-bottom:5px'><span class='small'><a href='index.php'>".$settings['sitename']."</a> :: ".$caption."</span></div>\n";

	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td colspan='2' class='tbl2'><strong>".$tdata['thread_subject']."</strong></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl2' style='width:140px;'><a href='../profile.php?lookup=".$userdata['user_id']."'>".$userdata['user_name']."</a></td>\n";
	echo "<td class='tbl2'>".$locale['426'].showdate("forumdate", time())."</td>\n";
	echo "</tr>\n<tr>\n<td valign='top' width='140' class='tbl2'>\n";
	if ($userdata['user_avatar'] && file_exists(IMAGES."avatars/".$userdata['user_avatar'])) {
		echo "<img src='".IMAGES."avatars/".$userdata['user_avatar']."' alt='' /><br /><br />\n";
	}
	echo "<span class='small'>".getuserlevel($userdata['user_level'])."</span><br /><br />\n";
	echo "<span class='small'><strong>".$locale['423']."</strong> ".$userdata['user_posts']."</span><br />\n";
	echo "<span class='small'><strong>".$locale['425']."</strong> ".showdate("%d.%m.%y", $userdata['user_joined'])."</span><br />\n";
	echo "<br /></td>\n<td valign='top' class='tbl1'>".$previewmessage."</td>\n";
	echo "</tr>\n</table>\n";
	closetable();
}
if (isset($_POST['postreply'])) {
	$message = trim(stripinput(censorwords($_POST['message'])));
	$flood = false; $error = 0;
	$sig = isset($_POST['show_sig']) ? "1" : "0";
	$smileys = isset($_POST['disable_smileys']) || preg_match("#\[code\](.*?)\[/code\]#si", $message) ? "0" : "1";
	if (iMEMBER) {
		if ($message != "") {
			require_once INCLUDES."flood_include.php";
			if (!flood_control("post_datestamp", DB_POSTS, "post_author='".$userdata['user_id']."'")) {
				$result = dbquery("INSERT INTO ".DB_POSTS." (forum_id, thread_id, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('".$_GET['forum_id']."', '".$_GET['thread_id']."', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".time()."', '".USER_IP."', '0', '0')");
				$newpost_id = mysql_insert_id();
				$result = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='".time()."', forum_postcount=forum_postcount+1, forum_lastuser='".$userdata['user_id']."' WHERE forum_id='".$_GET['forum_id']."'");
				$result = dbquery("UPDATE ".DB_THREADS." SET thread_lastpost='".time()."', thread_lastpostid='$newpost_id', thread_postcount=thread_postcount+1, thread_lastuser='".$userdata['user_id']."' WHERE thread_id='".$_GET['thread_id']."'");
				$result = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
				if ($settings['thread_notify'] && isset($_POST['notify_me'])) {
					if (!dbcount("(thread_id)", DB_THREAD_NOTIFY, "thread_id='".$_GET['thread_id']."' AND notify_user='".$userdata['user_id']."'")) {
						$result = dbquery("INSERT INTO ".DB_THREAD_NOTIFY." (thread_id, notify_datestamp, notify_user, notify_status) VALUES('".$_GET['thread_id']."', '".time()."', '".$userdata['user_id']."', '1')");
					}
				}

				if ($fdata['forum_attach'] && checkgroup($fdata['forum_attach'])) {
					$attach = $_FILES['attach'];
					if ($attach['name'] != "" && !empty($attach['name']) && is_uploaded_file($attach['tmp_name'])) {
						$attachname = substr($attach['name'], 0, strrpos($attach['name'], "."));
						$attachext = strtolower(strrchr($attach['name'],"."));
						if (preg_match("/^[-0-9A-Z_\[\]]+$/i", $attachname) && $attach['size'] <= $settings['attachmax']) {
							$attachtypes = explode(",", $settings['attachtypes']);
							if (in_array($attachext, $attachtypes)) {
								$attachname = attach_exists(strtolower($attach['name']));
								move_uploaded_file($attach['tmp_name'], FORUM."attachments/".$attachname);
								chmod(FORUM."attachments/".$attachname,0644);
								if (in_array($attachext, $imagetypes) && (!@getimagesize(FORUM."attachments/".$attachname) || !@verify_image(FORUM."attachments/".$attachname))) {
									unlink(FORUM."attachments/".$attachname);
									$error = 1;
								}
								if (!$error) $result = dbquery("INSERT INTO ".DB_FORUM_ATTACHMENTS." (thread_id, post_id, attach_name, attach_ext, attach_size) VALUES ('".$_GET['thread_id']."', '".$newpost_id."', '$attachname', '$attachext', '".$attach['size']."')");
							} else {
								@unlink($attach['tmp_name']);
								$error = 1;
							}
						} else {
							@unlink($attach['tmp_name']);
							$error = 2;
						}
					}
				}
			} else {
					redirect("viewforum.php?forum_id=".$_GET['forum_id']);
			}
		} else {
			$error = 3;
		}
	} else {
		$error = 4;
	}
	if ($error > 2) {
		redirect("postify.php?post=reply&error=$error&forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id']);
	} else {
		redirect("postify.php?post=reply&error=$error&forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id']."&post_id=$newpost_id");
	}
} else {
	if (!isset($_POST['previewreply'])) {
		$message = "";
		$disable_smileys_check = "";
		$sig_checked = " checked='checked'";
		if ($settings['thread_notify']) {
			if (dbcount("(thread_id)", DB_THREAD_NOTIFY, "thread_id='".$_GET['thread_id']."' AND notify_user='".$userdata['user_id']."'")) {
				$notify_checked = " checked='checked'";
			} else {
				$notify_checked = "";
			}
		}
	}
	if (isset($_GET['quote']) && isnum($_GET['quote'])) {
		$result = dbquery(
			"SELECT * FROM ".DB_POSTS."
			INNER JOIN ".DB_USERS." ON ".DB_POSTS.".post_author=".DB_USERS.".user_id
			WHERE thread_id='".$_GET['thread_id']."' and post_id='".$_GET['quote']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$message = "[quote][b]".$data['user_name'].$locale['429']."[/b]\n".strip_bbcodes($data['post_message'])."[/quote]";
		}
	}
	add_to_title($locale['global_201'].$locale['403']);
	echo "<!--pre_postreply-->";
	opentable($locale['403']);
	if (!isset($_POST['previewreply'])) echo "<div class='tbl2' style='margin-bottom:5px'><span class='small'><a href='index.php'>".$settings['sitename']."</a> :: ".$caption."</span></div>\n";

	echo "<form name='inputform' method='post' action='".FUSION_SELF."?action=reply&amp;forum_id=".$_GET['forum_id']."&amp;thread_id=".$_GET['thread_id']."' enctype='multipart/form-data'>\n";
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td valign='top' width='145' class='tbl2'>".$locale['461']."</td>\n";
	echo "<td class='tbl1'><textarea name='message' cols='60' rows='15' class='textbox' style='width:98%'>$message</textarea></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='145' class='tbl2'>&nbsp;</td>\n";
	echo "<td class='tbl1'>".display_bbcodes("99%", "message")."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td valign='top' width='145' class='tbl2'>".$locale['463']."</td>\n";
	echo "<td class='tbl1'>\n";
	echo "<label><input type='checkbox' name='disable_smileys' value='1'".$disable_smileys_check." /> ".$locale['482']."</label>";
	if (array_key_exists("user_sig", $userdata) && $userdata['user_sig']) {
		echo "<br />\n<label><input type='checkbox' name='show_sig' value='1'".$sig_checked." /> ".$locale['483']."</label>";
	}
	if ($settings['thread_notify']) {
		echo "<br />\n<label><input type='checkbox' name='notify_me' value='1'".$notify_checked." /> ".$locale['486']."</label>";
	}
	echo "</td>\n</tr>\n";
	if ($fdata['forum_attach'] && checkgroup($fdata['forum_attach'])) {
		echo "<tr>\n<td width='145' class='tbl2'>".$locale['464']."</td>\n";
		echo "<td class='tbl1'><input type='file' name='attach' class='textbox' style='width:200px;' /><br />\n";
		echo "<span class='small2'>".sprintf($locale['466'], parsebytesize($settings['attachmax']), str_replace(',', ' ', $settings['attachtypes']))."</span></td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n<td align='center' colspan='2' class='tbl1'>\n";
	echo "<input type='submit' name='previewreply' value='".$locale['402']."' class='button' />\n";
	echo "<input type='submit' name='postreply' value='".$locale['404']."' class='button' />\n";
	echo "</td>\n</tr>\n</table>\n</form>\n";
	closetable();
	echo "<!--sub_postreply-->";
}
?>
