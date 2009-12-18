<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: submit.php
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
include LOCALE.LOCALESET."submit.php";

if (!iMEMBER) { redirect("index.php"); }

if (!isset($_GET['stype']) || !preg_check("/^[a-z]$/", $_GET['stype'])) { redirect("index.php"); }

$submit_info = array();

if ($_GET['stype'] == "l") {
	if (isset($_POST['submit_link'])) {
		if ($_POST['link_name'] != "" && $_POST['link_url'] != "" && $_POST['link_description'] != "") {
			$submit_info['link_category'] = stripinput($_POST['link_category']);
			$submit_info['link_name'] = stripinput($_POST['link_name']);
			$submit_info['link_url'] = stripinput($_POST['link_url']);
			$submit_info['link_description'] = stripinput($_POST['link_description']);
			$result = dbquery("INSERT INTO ".DB_SUBMISSIONS." (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES ('l', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			add_to_title($locale['global_200'].$locale['400']);
			opentable($locale['400']);
			echo "<div style='text-align:center'><br />\n".$locale['410']."<br /><br />\n";
			echo "<a href='submit.php?stype=l'>".$locale['411']."</a><br /><br />\n";
			echo "<a href='index.php'>".$locale['412']."</a><br /><br />\n</div>\n";
			closetable();
		}
	} else {
		$opts = "";
		add_to_title($locale['global_200'].$locale['400']);
		opentable($locale['400']);
		$result = dbquery("SELECT * FROM ".DB_WEBLINK_CATS." WHERE ".groupaccess("weblink_cat_access")." ORDER BY weblink_cat_name");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$opts .= "<option value='".$data['weblink_cat_id']."'>".$data['weblink_cat_name']."</option>\n";
			}
			echo $locale['420']."<br /><br />\n";
			echo "<form name='submit_form' method='post' action='".FUSION_SELF."?stype=l' onsubmit='return validateLink(this);'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n";
			echo "<tr>\n<td class='tbl'>".$locale['421']."</td>\n";
			echo "<td class='tbl'><select name='link_category' class='textbox'>\n$opts</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['422']."</td>\n";
			echo "<td class='tbl'><input type='text' name='link_name' maxlength='100' class='textbox' style='width:300px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['423']."</td>\n";
			echo "<td class='tbl'><input type='text' name='link_url' value='http://' maxlength='200' class='textbox' style='width:300px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['424']."</td>\n";
			echo "<td class='tbl'><input type='text' name='link_description' maxlength='200' class='textbox' style='width:300px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td align='center' colspan='2' class='tbl'><br />\n";
			echo "<input type='submit' name='submit_link' value='".$locale['425']."' class='button' />\n</td>\n";
			echo "</tr>\n</table>\n</form>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['551']."<br /><br />\n</div>\n";
		}
		closetable();
	}
} elseif ($_GET['stype'] == "n") {
	if (isset($_POST['submit_news'])) {
		if ($_POST['news_subject'] != "" && $_POST['news_body'] != "") {
			$submit_info['news_subject'] = stripinput($_POST['news_subject']);
			$submit_info['news_cat'] = isnum($_POST['news_cat']) ? $_POST['news_cat'] : "0";
			$submit_info['news_body'] = descript($_POST['news_body']);
			$submit_info['news_breaks'] = (isset($_POST['line_breaks']) ? "y" : "n");
			$result = dbquery("INSERT INTO ".DB_SUBMISSIONS." (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES('n', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			add_to_title($locale['global_200'].$locale['450']);
			opentable($locale['450']);
			echo "<div style='text-align:center'><br />\n".$locale['460']."<br /><br />\n";
			echo "<a href='submit.php?stype=n'>".$locale['461']."</a><br /><br />\n";
			echo "<a href='index.php'>".$locale['412']."</a><br /><br />\n</div>\n";
			closetable();
		}
	} else {
		if (isset($_POST['preview_news'])) {
			$news_subject = stripinput($_POST['news_subject']);
			$news_cat = isnum($_POST['news_cat']) ? $_POST['news_cat'] : "0";
			$news_body = phpentities(descript(stripslash($_POST['news_body'])));
			$breaks = (isset($_POST['line_breaks']) ? " checked='checked'" : "");
			opentable($news_subject);
			echo (isset($_POST['line_breaks']) ? nl2br($news_body) : $news_body);
			closetable();
			tablebreak();
		}
		if (!isset($_POST['preview_news'])) {
			$news_subject = "";
			$news_cat = "0";
			$news_body = "";
			$breaks = " checked='checked'";
		}
		$cat_list = ""; $sel = "";
		$result2 = dbquery("SELECT * FROM ".DB_NEWS_CATS." ORDER BY news_cat_name");
		if (dbrows($result2)) {
			while ($data2 = dbarray($result2)) {
				if (isset($_POST['preview_news'])) { $sel = ($news_cat == $data2['news_cat_id'] ? " selected" : ""); }
				$cat_list .= "<option value='".$data2['news_cat_id']."'".$sel.">".$data2['news_cat_name']."</option>\n";
			}
		}
		add_to_title($locale['global_200'].$locale['450']);
		opentable($locale['450']);
		echo $locale['470']."<br /><br />\n";
		echo "<form name='submit_form' method='post' action='".FUSION_SELF."?stype=n' onsubmit='return validateNews(this);'>\n";
		echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
		echo "<td class='tbl'>".$locale['471']."</td>\n";
		echo "<td class='tbl'><input type='text' name='news_subject' value='$news_subject' maxlength='64' class='textbox' style='width:300px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td width='100' class='tbl'>".$locale['476']."</td>\n";
		echo "<td width='80%' class='tbl'><select name='news_cat' class='textbox'>\n<option value='0'>".$locale['477']."</option>\n".$cat_list."</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td valign='top' class='tbl'>".$locale['472']."</td>\n";
		echo "<td class='tbl'><textarea name='news_body' cols='60' rows='8' class='textbox' style='width:300px;'>$news_body</textarea></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td align='center' colspan='2' class='tbl'><br />\n";
		echo "<input type='checkbox' name='line_breaks' value='yes'".$breaks." />".$locale['473']."<br /><br />\n";
		echo "<input type='submit' name='preview_news' value='".$locale['474']."' class='button' />\n";
		echo "<input type='submit' name='submit_news' value='".$locale['475']."' class='button' />\n</td>\n";
		echo "</tr>\n</table>\n</form>\n";
		closetable();
	}
} elseif ($_GET['stype'] == "a") {
	if (isset($_POST['submit_article'])) {
		if ($_POST['article_subject'] != "" && $_POST['article_body'] != "") {
			$submit_info['article_cat'] = isnum($_POST['article_cat']) ? $_POST['article_cat'] : "0";
			$submit_info['article_subject'] = stripinput($_POST['article_subject']);
			$submit_info['article_snippet'] = descript($_POST['article_snippet']);
			$submit_info['article_body'] = descript($_POST['article_body']);
			$submit_info['article_breaks'] = (isset($_POST['line_breaks']) ? "y" : "n");
			$result = dbquery("INSERT INTO ".DB_SUBMISSIONS." (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES ('a', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			add_to_title($locale['global_200'].$locale['500']);
			opentable($locale['500']);
			echo "<div style='text-align:center'><br />\n".$locale['510']."<br /><br />\n";
			echo "<a href='submit.php?stype=a'>".$locale['511']."</a><br /><br />\n";
			echo "<a href='index.php'>".$locale['412']."</a><br /><br />\n</div>\n";
			closetable();
		}
	} else {
		if (isset($_POST['preview_article'])) {
			$article_cat = isnum($_POST['article_cat']) ? $_POST['article_cat'] : "0";
			$article_subject = stripinput($_POST['article_subject']);
			$article_snippet = phpentities(descript(stripslash($_POST['article_snippet'])));
			$article_body = phpentities(descript(stripslash($_POST['article_body'])));
			$breaks = (isset($_POST['line_breaks']) ? " checked='checked'" : "");
			opentable($article_subject);
			echo (isset($_POST['line_breaks']) ? nl2br($article_body) : $article_body);
			closetable();
			tablebreak();
		}
		if (!isset($_POST['preview_article'])) {
			$article_cat = "0";
			$article_subject = "";
			$article_snippet = "";
			$article_body = "";
			$breaks = " checked='checked'";
		}
		$cat_list = ""; $sel = "";
		add_to_title($locale['global_200'].$locale['500']);
		opentable($locale['500']);
		$result = dbquery("SELECT * FROM ".DB_ARTICLE_CATS." WHERE ".groupaccess("article_cat_access")." ORDER BY article_cat_name");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				if (isset($_POST['preview_article'])) { $sel = $article_cat == $data['article_cat_id'] ? " selected" : ""; }
				$cat_list .= "<option value='".$data['article_cat_id']."'".$sel.">".$data['article_cat_name']."</option>\n";
			}
			echo $locale['520']."<br /><br />\n";
			echo "<form name='submit_form' method='post' action='".FUSION_SELF."?stype=a' onsubmit='return validateArticle(this);'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td width='100' class='tbl'>".$locale['521']."</td>\n";
			echo "<td class='tbl'><select name='article_cat' class='textbox'>\n$cat_list</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['522']."</td>\n";
			echo "<td class='tbl'><input type='text' name='article_subject' value='$article_subject' maxlength='64' class='textbox' style='width:300px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td valign='top' class='tbl'>".$locale['523']."</td>\n";
			echo "<td class='tbl'><textarea name='article_snippet' cols='60' rows='3' class='textbox' style='width:300px;'>$article_snippet</textarea></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td valign='top' class='tbl'>".$locale['524']."</td>\n";
			echo "<td class='tbl'><textarea name='article_body' cols='60' rows='8' class='textbox' style='width:300px;'>$article_body</textarea></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td align='center' colspan='2' class='tbl'><br />\n";
			echo "<input type='checkbox' name='line_breaks' value='yes'".$breaks." />".$locale['525']."<br /><br />\n";
			echo "<input type='submit' name='preview_article' value='".$locale['526']."' class='button' />\n";
			echo "<input type='submit' name='submit_article' value='".$locale['527']."' class='button' />\n</td>\n";
			echo "</tr>\n</table>\n</form>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['551']."<br /><br />\n</div>\n";
		}
		closetable();
	}
} elseif ($_GET['stype'] == "p") {
	if (isset($_POST['submit_photo'])) {
		require_once INCLUDES."photo_functions_include.php";
		$error = "";
		$submit_info['photo_title'] = stripinput($_POST['photo_title']);
		$submit_info['photo_description'] = stripinput($_POST['photo_description']);
		$submit_info['album_id'] = isnum($_POST['album_id']) ? $_POST['album_id'] : "0";
		if (is_uploaded_file($_FILES['photo_pic_file']['tmp_name'])) {
			$photo_types = array(".gif",".jpg",".jpeg",".png");
			$photo_pic = $_FILES['photo_pic_file'];
			$photo_name = strtolower(substr($photo_pic['name'], 0, strrpos($photo_pic['name'], ".")));
			$photo_ext = strtolower(strrchr($photo_pic['name'],"."));
			$photo_dest = PHOTOS."submissions/";
			if (!preg_match("/^[-0-9A-Z_\[\]]+$/i", $photo_name)) {
				$error = 1;
			} elseif ($photo_pic['size'] > $settings['photo_max_b']){
				$error = 2;
			} elseif (!in_array($photo_ext, $photo_types)) {
				$error = 3;
			} else {
				$photo_file = image_exists($photo_dest, $photo_name.$photo_ext);
				move_uploaded_file($photo_pic['tmp_name'], $photo_dest.$photo_file);
				chmod($photo_dest.$photo_file, 0644);
				$imagefile = @getimagesize($photo_dest.$photo_file);
				if (!verify_image($photo_dest.$photo_file)) {
					$error = 3;
					unlink($photo_dest.$photo_file);
				} elseif ($imagefile[0] > $settings['photo_max_w'] || $imagefile[1] > $settings['photo_max_h']) {
					$error = 4;
					unlink($photo_dest.$photo_file);
				} else {
					$submit_info['photo_file'] = $photo_file;
				}
			}
		}
		add_to_title($locale['global_200'].$locale['570']);
		opentable($locale['570']);
		if (!$error) {
			$result = dbquery("INSERT INTO ".DB_SUBMISSIONS." (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES ('p', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			echo "<div style='text-align:center'><br />\n".$locale['580']."<br /><br />\n";
			echo "<a href='submit.php?stype=p'>".$locale['581']."</a><br /><br />\n";
			echo "<a href='index.php'>".$locale['412']."</a><br /><br />\n</div>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['600']."<br /><br />\n";
			if ($error == 1) { echo $locale['601']; }
			elseif ($error == 2) { echo sprintf($locale['602'], $settings['photo_max_b']); }
			elseif ($error == 3) { echo $locale['603']; }
			elseif ($error == 4) { echo sprintf($locale['604'], $settings['photo_max_w'], $settings['photo_max_h']); }
			echo "<br /><br />\n<a href='submit.php?stype=p'>".$locale['581']."</a><br /><br />\n</div>\n";
		}
		closetable();
	} else {
		$opts = "";
		add_to_title($locale['global_200'].$locale['570']);
		opentable($locale['570']);
		$result = dbquery("SELECT * FROM ".DB_PHOTO_ALBUMS." WHERE ".groupaccess("album_access")." ORDER BY album_title");
		if (dbrows($result)) {
			while ($data = dbarray($result)) $opts .= "<option value='".$data['album_id']."'>".$data['album_title']."</option>\n";
			echo $locale['620']."<br /><br />\n";
			echo "<form name='submit_form' method='post' action='".FUSION_SELF."?stype=p' enctype='multipart/form-data' onsubmit='return validatePhoto(this);'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td class='tbl'>".$locale['621']."</td>\n";
			echo "<td class='tbl'><input type='text' name='photo_title' maxlength='100' class='textbox' style='width:250px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td valign='top' class='tbl'>".$locale['622']."</td>\n";
			echo "<td class='tbl'><textarea name='photo_description' cols='60' rows='5' class='textbox' style='width:300px;'></textarea></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td valign='top' class='tbl'>".$locale['623']."</td>\n";
			echo "<td class='tbl'><input type='file' name='photo_pic_file' class='textbox' style='width:250px;' /><br />\n";
			echo "<span class='small2'>".sprintf($locale['624'], parsebytesize($settings['photo_max_b']), $settings['photo_max_w'], $settings['photo_max_h'])."</span></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['625']."</td>\n";
			echo "<td class='tbl'><select name='album_id' class='textbox'>\n$opts</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td align='center' colspan='2' class='tbl'><br />\n";
			echo "<input type='submit' name='submit_photo' value='".$locale['626']."' class='button' />\n</td>\n";
			echo "</tr>\n</table>\n</form>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['551']."<br /><br />\n</div>\n";
		}
		closetable();
	}
} else {
	redirect("index.php");
}
echo "<script type='text/javascript'>
function validateLink(frm) {
	if (frm.link_name.value==\"\" || frm.link_name.value==\"\" || frm.link_description.value==\"\") {
		alert(\"".$locale['550']."\"); return false;
	}
}
function validateNews(frm) {
	if (frm.news_subject.value==\"\" || frm.news_body.value==\"\") {
		alert(\"".$locale['550']."\"); return false;
	}
}
function validateArticle(frm) {
	if (frm.article_subject.value==\"\" || frm.article_snippet.value==\"\" || frm.article_body.value==\"\") {
		alert(\"".$locale['550']."\");
		return false;
	}
}
function validatePhoto(frm) {
	if (frm.photo_title.value==\"\" || frm.photo_description.value==\"\" || frm.photo_pic_file.value==\"\") {
		alert(\"".$locale['550']."\");
		return false;
	}
}
</script>\n";

require_once THEMES."templates/footer.php";
?>