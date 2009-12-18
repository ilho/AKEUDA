<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: submissions.php
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
include LOCALE.LOCALESET."admin/submissions.php";

if (!checkrights("SU") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }
$links = ""; $news = ""; $articles = ""; $photos = "";

if (!isset($_GET['action']) || $_GET['action'] == "1") {
	if (isset($_GET['delete']) && isnum($_GET['delete'])) {
		$result = dbquery("SELECT * FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['delete']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			if ($data['submit_type'] == "p") { 
				$submit_criteria = unserialize($data['submit_criteria']);
				@unlink(PHOTOS."submissions/".$submit_criteria['photo_file']);
			}
			opentable($locale['400']);
			$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['delete']."'");
			echo "<br /><div style='text-align:center'>".$locale['401']."<br /><br />\n";
			echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
			echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} else {
		$result = dbquery("SELECT * FROM ".DB_SUBMISSIONS." WHERE submit_type='l' ORDER BY submit_datestamp DESC");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$links .= "<tr>\n<td class='tbl1'>".$submit_criteria['link_name']."</td>\n";
				$links .= "<td align='right' width='1%' class='tbl1' style='white-space:nowrap'><span class='small'><a href='".FUSION_SELF.$aidlink."&amp;action=2&amp;t=l&amp;submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |\n";
				$links .= "<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;delete=".$data['submit_id']."'>".$locale['418']."</a></span></td>\n</tr>\n";
			}
		} else {
			$links = "<tr>\n<td colspan='2' class='tbl1'>".$locale['414']."</td>\n</tr>\n";
		}
		$result = dbquery("SELECT * FROM ".DB_SUBMISSIONS." WHERE submit_type='n' ORDER BY submit_datestamp DESC");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$news .= "<tr>\n<td class='tbl1'>".$submit_criteria['news_subject']."</td>\n";
				$news .= "<td align='right' width='1%' class='tbl1' style='white-space:nowrap'><span class='small'><a href='".FUSION_SELF.$aidlink."&amp;action=2&amp;t=n&amp;submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |\n";
				$news .= "<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;delete=".$data['submit_id']."'>".$locale['418']."</a></span></td>\n</tr>\n";
			}
		} else {
			$news = "<tr>\n<td colspan='2' class='tbl1'>".$locale['415']."</td>\n</tr>\n";
		}
		$result = dbquery("SELECT * FROM ".DB_SUBMISSIONS." WHERE submit_type='a' ORDER BY submit_datestamp DESC");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$articles .= "<tr>\n<td class='tbl1'>".$submit_criteria['article_subject']."</td>\n";
				$articles .= "<td align='right' width='1%' class='tbl1' style='white-space:nowrap'><span class='small'><a href='".FUSION_SELF.$aidlink."&amp;action=2&amp;t=a&amp;submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |\n";
				$articles .= "<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;delete=".$data['submit_id']."'>".$locale['418']."</a></span></td>\n</tr>\n";
			}
		} else {
			$articles = "<tr>\n<td colspan='2' class='tbl1'>".$locale['416']."</td>\n</tr>\n";
		}
		$result = dbquery("SELECT * FROM ".DB_SUBMISSIONS." WHERE submit_type='p' ORDER BY submit_datestamp DESC");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$photos .= "<tr>\n<td class='tbl1'>".$submit_criteria['photo_title']."</td>\n";
				$photos .= "<td align='right' width='1%' class='tbl1' style='white-space:nowrap'><span class='small'><a href='".FUSION_SELF.$aidlink."&amp;action=2&amp;t=p&amp;submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |\n";
				$photos .= "<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;delete=".$data['submit_id']."'>".$locale['418']."</a></span></td>\n</tr>\n";
			}
		} else {
			$photos = "<tr>\n<td colspan='2' class='tbl1'>".$locale['420']."</td>\n</tr>\n";
		}
		opentable($locale['410']);
		echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
		echo "<td colspan='2' class='tbl2'><a id='link_submissions' name='link_submissions'></a>\n".$locale['411']."</td>\n";
		echo "</tr>".$links."<tr>\n";
		echo "<td colspan='2' class='tbl2'><a id='news_submissions' name='news_submissions'></a>\n".$locale['412']."</td>\n";
		echo "</tr>\n".$news."<tr>\n";
		echo "<td colspan='2' class='tbl2'><a id='article_submissions' name='article_submissions'></a>\n".$locale['413']."</td>\n";
		echo "</tr>\n".$articles."<tr>\n";
		echo "<td colspan='2' class='tbl2'><a id='photo_submissions' name='photo_submissions'></a>\n".$locale['419']."</td>\n";
		echo "</tr>\n".$photos."</table>\n";
		closetable();
	}
}
if ((isset($_GET['action']) && $_GET['action'] == "2") && (isset($_GET['t']) && $_GET['t'] == "l")) {
	if (isset($_POST['add']) && (isset($_GET['submit_id']) && isnum($_GET['submit_id']))) {
		$link_name = stripinput($_POST['link_name']);
		$link_url = stripinput($_POST['link_url']);
		$link_description = stripinput($_POST['link_description']);
		$result = dbquery("INSERT INTO ".DB_WEBLINKS." (weblink_name, weblink_description, weblink_url, weblink_cat, weblink_datestamp, weblink_count) VALUES ('$link_name', '$link_description', '$link_url', '".$_POST['link_category']."', '".time()."', '0')");
		$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'");
		opentable($locale['430']);
		echo "<br /><div style='text-align:center'>".$locale['431']."<br /><br />\n";
		echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
		echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
		closetable();
	} else if (isset($_POST['delete']) && (isset($_GET['submit_id']) && isnum($_GET['submit_id']))) {
		opentable($locale['432']);
		$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'");
		echo "<br /><div style='text-align:center'>".$locale['433']."<br /><br />\n";
		echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
		echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
		closetable();
	} else {
		$result = dbquery(
			"SELECT ts.*, user_id,user_name FROM ".DB_SUBMISSIONS." ts
			LEFT JOIN ".DB_USERS." tu ON ts.submit_user=tu.user_id
			WHERE submit_id='".$_GET['submit_id']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$opts = ""; $sel = "";
			$submit_criteria = unserialize($data['submit_criteria']);
			$posted = showdate("longdate", $data['submit_datestamp']);	
			$result2 = dbquery("SELECT * FROM ".DB_WEBLINK_CATS." ORDER BY weblink_cat_name");
			if (dbrows($result2) != 0) {
				while($data2 = dbarray($result2)) {
					if (isset($submit_criteria['link_category'])) {
						$sel = ($submit_criteria['link_category'] == $data2['weblink_cat_id'] ? " selected='selected'" : "");
					}
					$opts .= "<option value='".$data2['weblink_cat_id']."'$sel>".$data2['weblink_cat_name']."</option>\n";
				}
			} else {
				$opts .= "<option value='0'>".$locale['434']."</option>\n";
			}
			add_to_title($locale['global_200'].$locale['448'].$locale['global_201'].$submit_criteria['link_name']."?");
			opentable($locale['440']);
			echo "<form name='publish' method='post' action='".FUSION_SELF.$aidlink."&amp;action=2&amp;t=l&amp;submit_id=".$_GET['submit_id']."'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td style='text-align:center;' class='tbl'>".$locale['441']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>".$locale['442'].$posted."</td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td style='text-align:center;' class='tbl'><a href='".$submit_criteria['link_url']."' target='_blank'>".$submit_criteria['link_name']."</a> - ".$submit_criteria['link_url']."</td>\n";
			echo "</tr>\n</table>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td class='tbl'>".$locale['443']."</td>\n";
			echo "<td class='tbl'><select name='link_category' class='textbox'>\n".$opts."</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['444']."</td>\n";
			echo "<td class='tbl'><input type='text' name='link_name' value='".$submit_criteria['link_name']."' class='textbox' style='width:300px' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['445']."</td>\n";
			echo "<td class='tbl'><input type='text' name='link_url' value='".$submit_criteria['link_url']."' class='textbox' style='width:300px' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['446']."</td>\n";
			echo "<td class='tbl'><input type='text' name='link_description' value='".$submit_criteria['link_description']."' class='textbox' style='width:300px' /></td>\n";
			echo "</tr>\n</table>\n";
			echo "<div style='text-align:center'><br />\n";
			echo $locale['447']."<br />\n";
			echo "<input type='submit' name='add' value='".$locale['448']."' class='button' />\n";
			echo "<input type='submit' name='delete' value='".$locale['449']."' class='button' /></div>\n";
			echo "</form>\n";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
}
if ((isset($_GET['action']) && $_GET['action'] == "2") && (isset($_GET['t']) && $_GET['t'] == "n")) {
	if (isset($_POST['publish']) && (isset($_GET['submit_id']) && isnum($_GET['submit_id']))) {
		$result = dbquery(
			"SELECT ts.*, user_id,user_name FROM ".DB_SUBMISSIONS." ts
			LEFT JOIN ".DB_USERS." tu ON ts.submit_user=tu.user_id
			WHERE submit_id='".$_GET['submit_id']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$news_subject = stripinput($_POST['news_subject']);
			$news_cat = isnum($_POST['news_cat']) ? $_POST['news_cat'] : "0";
			$news_body = addslash($_POST['news_body']);
			$news_breaks = ($_POST['news_breaks'] == "y") ? "y" : "n";
			$result = dbquery("INSERT INTO ".DB_NEWS." (news_subject, news_cat, news_news, news_extended, news_breaks, news_name, news_datestamp, news_start, news_end, news_visibility, news_reads, news_allow_comments, news_allow_ratings) VALUES ('$news_subject', '$news_cat', '$news_body', '', '$news_breaks', '".$data['user_id']."', '".time()."', '0', '0', '0', '0', '1', '1')");
			$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'");
			opentable($locale['490']);
			echo "<br /><div style='text-align:center'>".$locale['491']."<br /><br />\n";
			echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
			echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} else if (isset($_POST['delete']) && (isset($_GET['submit_id']) && isnum($_GET['submit_id']))) {
		opentable($locale['492']);
		$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'");
		echo "<br /><div style='text-align:center'>".$locale['493']."<br /><br />\n";
		echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
		echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
		closetable();
	} else {	
		if ($settings['tinymce_enabled'] == 1) echo "<script type='text/javascript'>advanced();</script>\n";
		$result = dbquery(
			"SELECT ts.*, user_id,user_name FROM ".DB_SUBMISSIONS." ts
			LEFT JOIN ".DB_USERS." tu ON ts.submit_user=tu.user_id
			WHERE submit_id='".$_GET['submit_id']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$submit_criteria = unserialize($data['submit_criteria']);
			$news_subject = $submit_criteria['news_subject'];
			$news_cat = $submit_criteria['news_cat'];
			$news_body = phpentities(stripslashes($submit_criteria['news_body']));
			$news_breaks = $submit_criteria['news_breaks'];
			$news_cat_opts = ""; $sel = "";
			$result2 = dbquery("SELECT * FROM ".DB_NEWS_CATS." ORDER BY news_cat_name");
			if (dbrows($result2)) {
				while ($data2 = dbarray($result2)) {
					if (isset($news_cat)) $sel = ($news_cat == $data2['news_cat_id'] ? " selected='selected'" : "");
					$news_cat_opts .= "<option value='".$data2['news_cat_id']."'$sel>".$data2['news_cat_name']."</option>\n";
				}
			}	
			add_to_title($locale['global_200'].$locale['503'].$locale['global_201'].$news_subject."?");
			opentable($locale['500']);
			echo "<form name='publish' method='post' action='".FUSION_SELF.$aidlink."&amp;sub=submissions&amp;action=2&amp;t=n&amp;submit_id=".$_GET['submit_id']."'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td width='100' class='tbl'>".$locale['505']."</td>\n";
			echo "<td width='80%' class='tbl'><input type='text' name='news_subject' value='$news_subject' class='textbox' style='width: 250px' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td width='100' class='tbl'>".$locale['506']."</td>\n";
			echo "<td width='80%' class='tbl'><select name='news_cat' class='textbox'>\n";
			echo "<option value='0'>".$locale['507']."</option>\n".$news_cat_opts."</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td valign='top' width='100' class='tbl'>".$locale['508']."</td>\n";
			echo "<td width='80%' class='tbl'><textarea name='news_body' cols='60' rows='10' class='textbox' style='width:300px;'>".$news_body."</textarea></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td align='center' colspan='2' class='tbl1'><br />\n";
			echo $locale['501']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br /><br />\n";
			echo $locale['502']."<br />\n";
			echo "<input type='hidden' name='news_breaks' value='".$news_breaks."' />\n";
			echo "<input type='submit' name='publish' value='".$locale['503']."' class='button' />\n";
			echo "<input type='submit' name='delete' value='".$locale['504']."' class='button' />\n";
			echo "</td>\n</tr>\n</table>\n</form>\n";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
}
if ((isset($_GET['action']) && $_GET['action'] == "2") && (isset($_GET['t']) && $_GET['t'] == "a")) {
	if (isset($_POST['publish']) && (isset($_GET['submit_id']) && isnum($_GET['submit_id']))) {
		$result = dbquery(
			"SELECT ts.*, user_id,user_name FROM ".DB_SUBMISSIONS." ts
			LEFT JOIN ".DB_USERS." tu ON ts.submit_user=tu.user_id
			WHERE submit_id='".$_GET['submit_id']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$submit_criteria = unserialize($data['submit_criteria']);
			$article_cat = $_POST['article_cat'];
			$article_subject = $_POST['article_subject'];
			$article_snippet = addslash($_POST['article_snippet']);
			$article_body = addslash($_POST['article_body']);
			$article_breaks = ($_POST['article_breaks'] == "y") ? "y" : "n";
			$result = dbquery("INSERT INTO ".DB_ARTICLES." (article_cat, article_subject, article_snippet, article_article, article_breaks, article_name, article_datestamp, article_reads, article_allow_comments, article_allow_ratings) VALUES ('$article_cat', '$article_subject', '$article_snippet', '$article_body', '$article_breaks', '".$data['user_id']."', '".time()."', '0', '1', '1')");
			$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'");
			opentable($locale['530']);
			echo "<br /><div style='text-align:center'>".$locale['531']."<br /><br />\n";
			echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
			echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} else if (isset($_POST['delete']) && (isset($_GET['submit_id']) && isnum($_GET['submit_id']))) {
		opentable($locale['532']);
		$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'");
		echo "<br /><div style='text-align:center'>".$locale['533']."<br /><br />\n";
		echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
		echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
		closetable();
	} else {	
		if ($settings['tinymce_enabled'] == 1) {
			echo "<script type='text/javascript'>advanced();</script>\n";
		}
		$result = dbquery(
			"SELECT ts.*, user_id,user_name FROM ".DB_SUBMISSIONS." ts
			LEFT JOIN ".DB_USERS." tu ON ts.submit_user=tu.user_id
			WHERE submit_id='".$_GET['submit_id']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$submit_criteria = unserialize($data['submit_criteria']);
			$article_cat = $submit_criteria['article_cat'];
			$article_subject = $submit_criteria['article_subject'];
			$article_snippet = phpentities(stripslashes($submit_criteria['article_snippet']));
			$article_body = phpentities(stripslashes($submit_criteria['article_body']));
			$article_breaks = $submit_criteria['article_breaks'];
			$result2 = dbquery("SELECT * FROM ".DB_ARTICLE_CATS." ORDER BY article_cat_name DESC");
			$article_cat_opts = ""; $sel = "";
			while ($data2 = dbarray($result2)) {
				if (isset($article_cat)) $sel = ($article_cat == $data2['article_cat_id'] ? " selected='selected'" : "");
				$article_cat_opts .= "<option value='".$data2['article_cat_id']."'$sel>".$data2['article_cat_name']."</option>\n";
			}
			add_to_title($locale['global_200'].$locale['543'].$locale['global_201'].$article_subject."?");
			opentable($locale['540']);
			echo "<form name='publish' method='post' action='".FUSION_SELF.$aidlink."&amp;sub=submissions&amp;action=2&amp;t=a&amp;submit_id=".$_GET['submit_id']."'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td width='100' class='tbl'>".$locale['506']."</td>\n";
			echo "<td width='80%' class='tbl'><select name='article_cat' class='textbox'>\n".$article_cat_opts."</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td width='100' class='tbl'>".$locale['505']."</td>\n";
			echo "<td width='80%' class='tbl'><input type='text' name='article_subject' value='$article_subject' class='textbox' style='width: 250px' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td valign='top' width='100' class='tbl'>".$locale['547']."</td>\n";
			echo "<td width='80%' class='tbl'><textarea name='article_snippet' cols='60' rows='5' class='textbox' style='width:300px;'>".$article_snippet."</textarea></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td valign='top' width='100' class='tbl'>".$locale['548']."</td>\n";
			echo "<td width='80%' class='tbl'><textarea name='article_body' cols='60' rows='10' class='textbox' style='width:300px;'>".$article_body."</textarea></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td align='center' colspan='2' class='tbl1'><br />\n";
			echo $locale['541']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br /><br />\n";
			echo $locale['542']."<br />\n";
			echo "<input type='hidden' name='article_breaks' value='".$article_breaks."' />\n";
			echo "<input type='submit' name='publish' value='".$locale['543']."' class='button' />\n";
			echo "<input type='submit' name='delete' value='".$locale['544']."' class='button' />\n";
			echo "</td>\n</tr>\n</table>\n</form>\n";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
}
if ((isset($_GET['action']) && $_GET['action'] == "2") && (isset($_GET['t']) && $_GET['t'] == "p")) {
	if (isset($_POST['publish']) && (isset($_GET['submit_id']) && isnum($_GET['submit_id']))) {
		define("SAFEMODE", @ini_get("safe_mode") ? true : false);
		require_once INCLUDES."photo_functions_include.php";
		$photo_file = ""; $photo_thumb1 = ""; $photo_thumb2 = "";
		$result = dbquery(
			"SELECT ts.*, user_id,user_name FROM ".DB_SUBMISSIONS." ts
			LEFT JOIN ".DB_USERS." tu ON ts.submit_user=tu.user_id
			WHERE submit_id='".$_GET['submit_id']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$submit_criteria = unserialize($data['submit_criteria']);
			$photo_title = stripinput($_POST['photo_title']);
			$photo_description = stripinput($_POST['photo_description']);
			$album_id = isnum($_POST['album_id']) ? $_POST['album_id'] : "0";
			$photo_name = strtolower(substr($submit_criteria['photo_file'], 0, strrpos($submit_criteria['photo_file'], ".")));
			$photo_ext = strtolower(strrchr($submit_criteria['photo_file'],"."));
			$photo_dest = PHOTOS.(!SAFEMODE ? "album_".$album_id."/" : "");
			$photo_file = image_exists($photo_dest, $photo_name.$photo_ext);
			
			copy(PHOTOS."submissions/".$submit_criteria['photo_file'], $photo_dest.$photo_file);
			chmod($photo_dest.$photo_file, 0644);
			unlink(PHOTOS."submissions/".$submit_criteria['photo_file']);
			$imagefile = @getimagesize($photo_dest.$photo_file);
			
			$photo_thumb1 = image_exists($photo_dest, $photo_name."_t1".$photo_ext);
			createthumbnail($imagefile[2], $photo_dest.$photo_file, $photo_dest.$photo_thumb1, $settings['thumb_w'], $settings['thumb_h']);
			if ($imagefile[0] > $settings['photo_w'] || $imagefile[1] > $settings['photo_h']) {
				$photo_thumb2 = image_exists($photo_dest, $photo_name."_t2".$photo_ext);
				createthumbnail($imagefile[2], $photo_dest.$photo_file, $photo_dest.$photo_thumb2, $settings['photo_w'], $settings['photo_h']);
			}
			$photo_order = dbresult(dbquery("SELECT MAX(photo_order) FROM ".DB_PHOTOS." WHERE album_id='$album_id'"), 0) + 1;
			$result = dbquery("INSERT INTO ".DB_PHOTOS." (album_id, photo_title, photo_description, photo_filename, photo_thumb1, photo_thumb2, photo_datestamp, photo_user, photo_views, photo_order, photo_allow_comments, photo_allow_ratings) VALUES ('$album_id', '$photo_title', '$photo_description', '$photo_file', '$photo_thumb1', '$photo_thumb2', '".time()."', '".$data['submit_user']."', '0', '$photo_order', '1', '1')");
			$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'");
			opentable($locale['580']);
			echo "<br /><div style='text-align:center'>".$locale['581']."<br /><br />\n";
			echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
			echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} else if (isset($_POST['delete']) && (isset($_GET['submit_id']) && isnum($_GET['submit_id']))) {
		opentable($locale['582']);
		$data = dbarray(dbquery("SELECT * FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'"));
		$submit_criteria = unserialize($data['submit_criteria']);
		@unlink(PHOTOS."submissions/".$submit_criteria['photo_file']);
		$result = dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_id='".$_GET['submit_id']."'");
		echo "<br /><div style='text-align:center'>".$locale['583']."<br /><br />\n";
		echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['402']."</a><br /><br />\n";
		echo "<a href='index.php".$aidlink."'>".$locale['403']."</a></div><br />\n";
		closetable();
	} else {	
		$result = dbquery(
			"SELECT ts.*, user_id,user_name FROM ".DB_SUBMISSIONS." ts
			LEFT JOIN ".DB_USERS." tu ON ts.submit_user=tu.user_id
			WHERE submit_id='".$_GET['submit_id']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$submit_criteria = unserialize($data['submit_criteria']);
			$photo_title = $submit_criteria['photo_title'];
			$photo_description = $submit_criteria['photo_description'];
			$photo_file = $submit_criteria['photo_file'];
			$album_id = $submit_criteria['album_id'];
			$photo_albums = ""; $sel = "";
			$result2 = dbquery("SELECT * FROM ".DB_PHOTO_ALBUMS." ORDER BY album_title");
			if (dbrows($result2)) {
				while ($data2 = dbarray($result2)) {
					if (isset($album_id)) $sel = ($album_id == $data2['album_id'] ? " selected='selected'" : "");
					$photo_albums .= "<option value='".$data2['album_id']."'$sel>".$data2['album_title']."</option>\n";
				}
			}	
			add_to_title($locale['global_200'].$locale['594'].$locale['global_201'].$photo_title."?");
			opentable($locale['580']);
			echo "<form name='publish' method='post' action='".FUSION_SELF.$aidlink."&amp;sub=submissions&amp;action=2&amp;t=p&amp;submit_id=".$_GET['submit_id']."'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td width='100' class='tbl'>".$locale['596']."</td>\n";
			echo "<td width='80%' class='tbl'><input type='text' name='photo_title' value='".$photo_title."' class='textbox' style='width: 250px' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td width='100' class='tbl'>".$locale['597']."</td>\n";
			echo "<td width='80%' class='tbl'><textarea name='photo_description' cols='60' rows='5' class='textbox' style='width:300px;'>".$photo_description."</textarea></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td width='100' class='tbl'>".$locale['598']."</td>\n";
			echo "<td width='80%' class='tbl'><select name='album_id' class='textbox'>\n";
			echo "<option value='0'>".$locale['507']."</option>\n".$photo_albums."</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td align='center' colspan='2' class='tbl1'><br />\n";
			echo "<a href='".PHOTOS."submissions/".$photo_file."' target='_blank'>".$locale['591']."</a><br /><br />\n";
			echo $locale['592']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br /><br />\n";
			echo $locale['593']."<br />\n";
			echo "<input type='submit' name='publish' value='".$locale['594']."' class='button' />\n";
			echo "<input type='submit' name='delete' value='".$locale['595']."' class='button' />\n";
			echo "</td>\n</tr>\n</table>\n</form>\n";
			closetable();
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
}

require_once THEMES."templates/footer.php";
?>