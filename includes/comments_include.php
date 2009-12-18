<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: comments_include.php
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

include LOCALE.LOCALESET."comments.php";

function showcomments($ctype, $cdb, $ccol, $cid, $clink) {

	global $settings, $locale, $userdata, $aidlink;

	$link = FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "");
	$link = preg_replace("^(&amp;|\?)c_action=(edit|delete)&amp;comment_id=\d*^", "", $link);

	if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "delete") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
		if ((iADMIN && checkrights("C")) || (iMEMBER && dbcount("(comment_id)", DB_COMMENTS, "comment_id='".$_GET['comment_id']."' AND comment_name='".$userdata['user_id']."'"))) {
			$result = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_id='".$_GET['comment_id']."'".(iADMIN ? "" : " AND comment_name='".$userdata['user_id']."'"));
		}
		redirect($clink);
	}

	if ((iMEMBER || $settings['guestposts'] == "1") && isset($_POST['post_comment'])) {

		if (iMEMBER) {
			$comment_name = $userdata['user_id'];
		} elseif ($settings['guestposts'] == "1") {
			$comment_name = trim(stripinput($_POST['comment_name']));
			$comment_name = preg_replace("(^[0-9]*)", "", $comment_name);
			if (isnum($comment_name)) { $comment_name = ""; }
			include_once INCLUDES."securimage/securimage.php";
			$securimage = new Securimage();
			if (!isset($_POST['com_captcha_code']) || $securimage->check($_POST['com_captcha_code']) == false) { redirect($link); }
		}

		$comment_message = trim(stripinput(censorwords($_POST['comment_message'])));

		if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "edit") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
			$comment_updated = false;
			if ((iADMIN && checkrights("C")) || (iMEMBER && dbcount("(comment_id)", DB_COMMENTS, "comment_id='".$_GET['comment_id']."' AND comment_name='".$userdata['user_id']."'"))) {
				if ($comment_message) {
					$result = dbquery("UPDATE ".DB_COMMENTS." SET comment_message='$comment_message' WHERE comment_id='".$_GET['comment_id']."'".(iADMIN ? "" : " AND comment_name='".$userdata['user_id']."'"));
					$comment_updated = true;
				}
			}
			if ($comment_updated) {
				$c_start = (ceil(dbcount("(comment_id)", DB_COMMENTS, "comment_id<='".$_GET['comment_id']."' AND comment_item_id='".$cid."' AND comment_type='".$ctype."'") / 10) - 1) * 10;
			}
			redirect($clink."&amp;rstart=".(isset($c_start) && isnum($c_start) ? $c_start : ""));
		} else {
			if (!dbcount("(".$ccol.")", $cdb, $ccol."='".$cid."'")) { redirect(BASEDIR."index.php"); }
			if ($comment_name && $comment_message) {
				require_once INCLUDES."flood_include.php";
				if (!flood_control("comment_datestamp", DB_COMMENTS, "comment_ip='".USER_IP."'")) {
					$result = dbquery("INSERT INTO ".DB_COMMENTS." (comment_item_id, comment_type, comment_name, comment_message, comment_datestamp, comment_ip) VALUES ('$cid', '$ctype', '$comment_name', '$comment_message', '".time()."', '".USER_IP."')");
				}
			}
			$c_start = (ceil(dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$cid."' AND comment_type='".$ctype."'") / 10) - 1) * 10;
			redirect($clink."&amp;rstart=".$c_start);
		}
	}

	opentable($locale['c100']);
	echo "<a id='comments' name='comments'></a>";
	$c_rows = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='$cid' AND comment_type='$ctype'");
	if (!isset($_GET['c_start']) || !isnum($_GET['c_start'])) { $_GET['c_start'] = 0; }
	$result = dbquery(
		"SELECT tcm.*,user_name FROM ".DB_COMMENTS." tcm
		LEFT JOIN ".DB_USERS." tcu ON tcm.comment_name=tcu.user_id
		WHERE comment_item_id='$cid' AND comment_type='$ctype'
		ORDER BY comment_datestamp ASC LIMIT ".$_GET['c_start'].",10"
	);
	if (dbrows($result)) {
		$i = $_GET['c_start']+1;
		if ($c_rows > 10) {
			echo "<div style='text-align:center;margin-bottom:5px;'>".makecommentnav($_GET['c_start'], 10, $c_rows, 3, $clink."&amp;")."</div>\n";
		}
		while ($data = dbarray($result)) {
			echo "<div class='tbl2'>\n";
			if ((iADMIN && checkrights("C")) || (iMEMBER && $data['comment_name'] == $userdata['user_id'] && isset($data['user_name']))) {
				echo "<div style='float:right' class='comment_actions'><!--comment_actions-->\n<a href='".FUSION_REQUEST."&amp;c_action=edit&amp;comment_id=".$data['comment_id']."#edit_comment'>".$locale['c108']."</a> |\n";
				echo "<a href='".FUSION_REQUEST."&amp;c_action=delete&amp;comment_id=".$data['comment_id']."'>".$locale['c109']."</a>\n</div>\n";
			}
			echo "<a href='".FUSION_REQUEST."#c".$data['comment_id']."' id='c".$data['comment_id']."' name='c".$data['comment_id']."'>#".$i."</a> | ";
			if ($data['user_name']) {
				echo "<span class='comment-name'><a href='".BASEDIR."profile.php?lookup=".$data['comment_name']."'>".$data['user_name']."</a></span>\n";
			} else {
				echo "<span class='comment-name'>".$data['comment_name']."</span>\n";
			}
			echo "<span class='small'>".$locale['global_071'].showdate("longdate", $data['comment_datestamp'])."</span>\n";
			echo "</div>\n<div class='tbl1 comment_message'><!--comment_message-->".nl2br(parseubb(parsesmileys($data['comment_message'])))."</div>\n";
			$i++;
		}
		if (iADMIN && checkrights("C")) {
			echo "<div align='right' class='tbl2'><a href='".ADMIN."comments.php".$aidlink."&amp;ctype=$ctype&amp;cid=$cid'>".$locale['c106']."</a></div>\n";
		}
		if ($c_rows > 10) {
			echo "<div style='text-align:center;margin-top:5px;'>".makecommentnav($_GET['c_start'], 10, $c_rows, 3, $clink."&amp;")."</div>\n";
		}
	} else {
		echo $locale['c101']."\n";
	}
	closetable();

	opentable($locale['c102']);
	if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "edit") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
		$eresult = dbquery(
			"SELECT tcm.*,user_name FROM ".DB_COMMENTS." tcm
			LEFT JOIN ".DB_USERS." tcu ON tcm.comment_name=tcu.user_id
			WHERE comment_id='".$_GET['comment_id']."' AND comment_item_id='".$cid."' AND comment_type='".$ctype."'"
		);
		if (dbrows($eresult)) {
			$edata = dbarray($eresult);
			if ((iADMIN && checkrights("C")) || (iMEMBER && $edata['comment_name'] == $userdata['user_id'] && isset($edata['user_name']))) {
				$clink .= "&amp;c_action=edit&amp;comment_id=".$edata['comment_id'];
				$comment_message = $edata['comment_message'];
			}
		} else {
			$comment_message = "";
		}
	} else {
		$comment_message = "";
	}
	if (iMEMBER || $settings['guestposts'] == "1") {
		require_once INCLUDES."bbcode_include.php";
		echo "<a id='edit_comment' name='edit_comment'></a>\n";
		echo "<form name='inputform' method='post' action='".$clink."'>\n";
		if (iGUEST) {
			echo "<div align='center' class='tbl'>\n".$locale['c104']."<br />\n";
			echo "<input type='text' name='comment_name' maxlength='30' class='textbox' style='width:360px' />\n";
			echo "</div>\n";
		}
		echo "<div align='center' class='tbl'>\n";
		echo "<textarea name='comment_message' cols='70' rows='6' class='textbox' style='width:360px'>".$comment_message."</textarea><br />\n";
		echo display_bbcodes("360px", "comment_message");
		if (iGUEST) {
			echo $locale['global_158']."<br />\n";
			echo "<img id='com_captcha' src='".INCLUDES."securimage/securimage_show.php' alt='' /><br />\n";
			echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' class='tbl-border' style='margin-bottom:1px' /></a>\n";
			echo "<a href='#' onclick=\"document.getElementById('com_captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' class='tbl-border' /></a><br />\n";
			echo $locale['global_159']."<br />\n<input type='text' name='com_captcha_code' class='textbox' style='width:100px' />\n";
		}
		echo "<br />\n<input type='submit' name='post_comment' value='".($comment_message ? $locale['c103'] : $locale['c102'])."' class='button' />\n";
		echo "</div>\n</form>\n";
	} else {
		echo $locale['c105']."\n";
	}
	closetable();
}

function makecommentnav($start, $count, $total, $range = 0, $link) {

	global $locale;

	$pg_cnt = ceil($total / $count);
	if ($pg_cnt <= 1) { return ""; }

	$idx_back = $start - $count;
	$idx_next = $start + $count;
	$cur_page = ceil(($start + 1) / $count);

	$res = $locale['global_092']." ".$cur_page.$locale['global_093'].$pg_cnt.": ";
	if ($idx_back >= 0) {
		if ($cur_page > ($range + 1)) {
			$res .= "<a href='".$link."ce4dstart=0'>1</a>...";
		}
	}
	$idx_fst = max($cur_page - $range, 1);
	$idx_lst = min($cur_page + $range, $pg_cnt);
	if ($range == 0) {
		$idx_fst = 1;
		$idx_lst = $pg_cnt;
	}
	for ($i = $idx_fst; $i <= $idx_lst; $i++) {
		$offset_page = ($i - 1) * $count;
		if ($i == $cur_page) {
			$res .= "<span><strong>".$i."</strong></span>";
		} else {
			$res .= "<a href='".$link."c_start=".$offset_page."'>".$i."</a>";
		}
	}
	if ($idx_next < $total) {
		if ($cur_page < ($pg_cnt - $range)) {
			$res .= "...<a href='".$link."c_start=".($pg_cnt - 1) * $count."'>".$pg_cnt."</a>\n";
		}
	}

	return "<div class='pagenav'>\n".$res."</div>\n";
}
?>
