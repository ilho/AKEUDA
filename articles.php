<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: articles.php
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
include LOCALE.LOCALESET."articles.php";

add_to_title($locale['global_200'].$locale['400']);

if (isset($_GET['article_id']) && isnum($_GET['article_id'])) {
	$result = dbquery(
		"SELECT ta.*,tac.*, tu.user_id,user_name FROM ".DB_ARTICLES." ta
		INNER JOIN ".DB_ARTICLE_CATS." tac ON ta.article_cat=tac.article_cat_id
		LEFT JOIN ".DB_USERS." tu ON ta.article_name=tu.user_id
		WHERE ".groupaccess('article_cat_access')." AND article_id='".$_GET['article_id']."' AND article_draft='0'"
	);
	if (dbrows($result)) {
		require_once INCLUDES."comments_include.php";
		require_once INCLUDES."ratings_include.php";
		$data = dbarray($result);
		if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
		if ($_GET['rowstart'] == 0) { $result = dbquery("UPDATE ".DB_ARTICLES." SET article_reads=article_reads+1 WHERE article_id='".$_GET['article_id']."'"); }
		$article = stripslashes($data['article_article']);
		if (preg_match('/<--PAGEBREAK-->/', $article)) {
			$article = explode("<--PAGEBREAK-->", $article);
		} else {
			$article = explode("<!-- pagebreak -->", $article);
		}
		$pagecount = count($article);
		$article_subject = stripslashes($data['article_subject']);
		$article_info = array(
			"article_id" => $data['article_id'],
			"user_id" => $data['user_id'],
			"user_name" => $data['user_name'],
			"article_date" => $data['article_datestamp'],
			"article_breaks" => $data['article_breaks'],
			"article_comments" => dbcount("(comment_id)", DB_COMMENTS, "comment_type='A' AND comment_item_id='".$data['article_id']."'"),
			"article_reads" => $data['article_reads'],
			"article_allow_comments" => $data['article_allow_comments']
		);
		add_to_title($locale['global_201'].$article_subject);
		echo "<!--pre_article-->";
		render_article($article_subject, $article[$_GET['rowstart']], $article_info);
		echo "<!--sub_article-->";
		if ($pagecount > 1) {
			echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 1, $pagecount, 3, FUSION_SELF."?article_id=".$_GET['article_id']."&amp;")."\n</div>\n";
		}
		if ($data['article_allow_comments']) { showcomments("A", DB_ARTICLES, "article_id", $_GET['article_id'], FUSION_SELF."?article_id=".$_GET['article_id']); }
		if ($data['article_allow_ratings']) { showratings("A", $_GET['article_id'], FUSION_SELF."?article_id=".$_GET['article_id']); }
	} else {
		redirect(FUSION_SELF);
	}
} elseif (!isset($_GET['cat_id']) || !isnum($_GET['cat_id'])) {
	opentable($locale['400']);
	echo "<!--pre_article_idx-->\n";
	$result = dbquery("SELECT * FROM ".DB_ARTICLE_CATS." WHERE ".groupaccess('article_cat_access')." ORDER BY article_cat_name");
	$rows = dbrows($result);
	if ($rows) {
		$counter = 0; $columns = 2;
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
			$num = dbcount("(article_cat)", DB_ARTICLES, "article_cat='".$data['article_cat_id']."' AND article_draft='0'");
			echo "<td valign='top' width='50%' class='tbl article_idx_cat_name'><!--article_idx_cat_name--><a href='".FUSION_SELF."?cat_id=".$data['article_cat_id']."'>".$data['article_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data['article_cat_description'] != "") { echo "<br />\n<span class='small'>".$data['article_cat_description']."</span>"; }
			echo "</td>\n";
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['401']."<br /><br />\n</div>\n";
	}
	echo "<!--sub_article_idx-->\n";
	closetable();
} else {
	$res = 0;
	$result = dbquery("SELECT * FROM ".DB_ARTICLE_CATS." WHERE article_cat_id='".$_GET['cat_id']."'");
	if (dbrows($result) != 0) {
		$cdata = dbarray($result);
		if (checkgroup($cdata['article_cat_access'])) {
			$res = 1;
			add_to_title($locale['global_201'].$cdata['article_cat_name']);
			opentable($locale['400'].": ".$cdata['article_cat_name']);
			echo "<!--pre_article_cat-->";
			$rows = dbcount("(article_id)", DB_ARTICLES, "article_cat='".$_GET['cat_id']."' AND article_draft='0'");
			if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
			if ($rows != 0) {
				$result = dbquery(
					"SELECT ta.article_id, ta.article_subject, ta.article_snippet, ta.article_datestamp FROM ".DB_ARTICLES." ta
					WHERE article_cat='".$_GET['cat_id']."' AND article_draft='0' ORDER BY ".$cdata['article_cat_sorting']." LIMIT ".$_GET['rowstart'].",15"
				);
				$numrows = dbrows($result); $i = 1;
				while ($data = dbarray($result)) {
					if ($data['article_datestamp'] + 604800 > time() + ($settings['timeoffset'] * 3600)) {
						$new = "&nbsp;<span class='small'>[".$locale['402']."]</span>";
					} else {
						$new = "";
					}
					echo "<a href='".FUSION_SELF."?article_id=".$data['article_id']."'>".$data['article_subject']."</a>$new<br />\n".stripslashes($data['article_snippet']);
				echo ($i != $numrows ? "<br /><br />\n" : "\n"); $i++;
				}
				echo "<!--sub_article_cat-->";
				closetable();
				if ($rows > 15) echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 15, $rows, 3, FUSION_SELF."?cat_id=".$_GET['cat_id']."&amp;")."\n</div>\n";
			} else {
				echo "<div style='text-align:center'>".$locale['403']."</div>\n";
				echo "<!--sub_article_cat-->";
				closetable();
			}
		}
	}
	if ($res == 0) { redirect(FUSION_SELF); }
}

require_once THEMES."templates/footer.php";
?>
