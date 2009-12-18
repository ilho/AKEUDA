<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: print.php
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
include LOCALE.LOCALESET."print.php";

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html>\n<head>\n";
echo "<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<style type=\"text/css\">\n";
echo "body { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:14px; }\n";
echo "hr { height:1px;color:#ccc; }\n";
echo ".small { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px; }\n";
echo ".small2 { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px;color:#666; }\n";
echo "</style>\n</head>\n<body>\n";
if ((isset($_GET['type']) && $_GET['type'] == "A") && (isset($_GET['item_id']) && isnum($_GET['item_id']))) {
	$result = dbquery(
		"SELECT ta.*,tac.*, tu.user_id,user_name FROM ".DB_ARTICLES." ta
		INNER JOIN ".DB_ARTICLE_CATS." tac ON ta.article_cat=tac.article_cat_id
		LEFT JOIN ".DB_USERS." tu ON ta.article_name=tu.user_id
		WHERE article_id='".$_GET['item_id']."' AND article_draft='0'"
	);
	$res = false;
	if (dbrows($result)) {
		$data = dbarray($result);
		if (checkgroup($data['article_cat_access'])) {
			$res = true;
			$article = str_replace("<--PAGEBREAK-->", "", stripslashes($data['article_article']));
			if ($data['article_breaks'] == "y") { $article = nl2br($article); }
			echo "<strong>".$data['article_subject']."</strong><br />\n";
			echo "<span class='small'>".$locale['400'].$data['user_name'].$locale['401'].ucfirst(showdate("longdate", $data['article_datestamp']))."</span>\n";
			echo "<hr />".$article."\n";
		}
	}
	if (!$res) { redirect("index.php"); }
} elseif ((isset($_GET['type']) && $_GET['type'] == "N") && (isset($_GET['item_id']) && isnum($_GET['item_id']))) {
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".DB_NEWS." tn
		LEFT JOIN ".DB_USERS." tu ON tn.news_name=tu.user_id
		WHERE news_id='".$_GET['item_id']."' AND news_draft='0'"
	);
	$res = false;
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		if (checkgroup($data['news_visibility'])) {
			$res = true;
			$news = stripslashes($data['news_news']);
			if ($data['news_breaks'] == "y") { $news = nl2br($news); }
			if ($data['news_extended']) {
				$news_extended = stripslashes($data['news_extended']);
				if ($data['news_breaks'] == "y") { $news_extended = nl2br($news_extended); }
			} else {
				$news_extended = "";
			}
			echo "<strong>".$data['news_subject']."</strong><br />\n";
			echo "<span class='small'>".$locale['400'].$data['user_name'].$locale['401'].ucfirst(showdate("longdate", $data['news_datestamp']))."</span>\n";
			echo "<hr />".$news."\n";
			if ($news_extended) { echo "<hr />\n<strong>".$locale['402']."</strong>\n<hr>\n$news_extended\n"; }
		}
	}
	if (!$res) { redirect("index.php"); }
} elseif ((isset($_GET['type']) && $_GET['type'] == "F") && (isset($_GET['thread']) && isNum($_GET['thread'])) && !isset($_GET['post'])) {
	$result = dbquery("SELECT fp.*, fu.user_name AS user_name, fe.user_name AS edit_name, ft.thread_subject, ff.forum_access FROM ".DB_THREADS." ft INNER JOIN ".DB_POSTS." fp ON ft.thread_id = fp.thread_id INNER JOIN ".DB_FORUMS." ff ON ff.forum_id = ft.forum_id INNER JOIN ".DB_USERS." fu ON fu.user_id = fp.post_author LEFT JOIN ".DB_USERS." fe ON fe.user_id = fp.post_edituser WHERE ft.thread_id=".$_GET['thread']." ORDER BY fp.post_datestamp");
	$res = false; $i = 0;
	if (dbrows($result)) {
		while ($data = dbarray($result)) {
			if (checkgroup($data['forum_access'])) {
				$res = true;
				if ($i == 0) echo $locale['500']." <strong>".$settings['sitename']." :: ".$data['thread_subject']."</strong><hr /><br />\n";
				echo "<div style='margin-left:20px'>\n";
				echo "<div style='float:left'>".$locale['501'].$data['user_name'].$locale['502'].showdate("forumdate", $data['post_datestamp'])."</div><div style='float:right'>#".($i+1)."</div><div style='float:none;clear:both'></div><hr />\n";
				echo parseubb(nl2br($data['post_message']));
				if ($data['edit_name']!='') {
					echo "<div style='margin-left:20px'>\n<hr />\n";
					echo $locale['503'].$data['edit_name'].$locale['502'].showdate("forumdate", $data['post_edittime']);
					echo "</div>\n";
				}
				echo "</div>\n";
				echo "<br />\n";
				$i++;
			}
		}		
	}
	if (!$res) { redirect("index.php"); }
} elseif ((isset($_GET['type']) && $_GET['type'] == "F") && (isset($_GET['thread']) && isNum($_GET['thread'])) && (isset($_GET['post']) && isNum($_GET['post'])) && (isset($_GET['nr']) && isNum($_GET['nr']))) {
	$result = dbquery("SELECT fp.*, fu.user_name AS user_name, fe.user_name AS edit_name, ft.thread_subject, ff.forum_access FROM ".DB_THREADS." ft INNER JOIN ".DB_POSTS." fp ON ft.thread_id = fp.thread_id INNER JOIN ".DB_FORUMS." ff ON ff.forum_id = ft.forum_id INNER JOIN ".DB_USERS." fu ON fu.user_id = fp.post_author LEFT JOIN ".DB_USERS." fe ON fe.user_id = fp.post_edituser WHERE ft.thread_id=".$_GET['thread']." AND fp.post_id = ".$_GET['post']);
	$res = false;
	if (dbrows($result)) {
		$data = dbarray($result);
		if (checkgroup($data['forum_access'])) {
			$res = true;
			echo $locale['500']." <strong>".$settings['sitename']." :: ".$data['thread_subject']."</strong><hr /><br />\n";
			echo "<div style='margin-left:20px'>\n";
			echo "<div style='float:left'>".$locale['501'].$data['user_name'].$locale['502'].showdate("forumdate", $data['post_datestamp'])."</div><div style='float:right'>#".$_GET['nr']."</div><div style='float:none;clear:both'></div><hr />\n";
			echo parseubb(nl2br($data['post_message']));
			if ($data['edit_name']!='') {
				echo "<div style='margin-left:20px'>\n<hr />\n";
				echo $locale['503'].$data['edit_name'].$locale['502'].showdate("forumdate", $data['post_edittime']);
				echo "</div>\n";
			}
			echo "</div>\n";
			echo "<br />\n";
		}
	}
	if (!$res) { redirect("index.php"); }
} elseif (isset($_GET['type']) && $_GET['type'] == "T" && $settings['enable_terms'] == 1) {
	echo "<strong>".$settings['sitename']." ".$locale['600']."</strong><br />\n";
	echo "<span class='small'>".$locale['601']." ".ucfirst(showdate("longdate", $settings['license_lastupdate']))."</span>\n";
	echo "<hr />".stripslashes($settings['license_agreement'])."\n";
} else {
	redirect("index.php");
}
echo "</body>\n</html>\n";
?>
