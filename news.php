<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: news.php
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

// Predefined variables, do not edit these values
if ($settings['news_style'] == "1") { $i = 0; $rc = 0; $ncount = 1; $ncolumn = 1; $news_[0] = ""; $news_[1] = ""; $news_[2] = ""; } else { $i = 1; }

// Number of news displayed
$items_per_page = $settings['newsperpage'];

add_to_title($locale['global_200'].$locale['global_077']);

if (!isset($_GET['readmore']) || !isnum($_GET['readmore'])) {
	$rows = dbcount("(news_id)", DB_NEWS, groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") AND news_draft='0'");
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
	if ($rows) {
		$result = dbquery(
			"SELECT tn.*, tc.*, user_id, user_name FROM ".DB_NEWS." tn
			LEFT JOIN ".DB_USERS." tu ON tn.news_name=tu.user_id
			LEFT JOIN ".DB_NEWS_CATS." tc ON tn.news_cat=tc.news_cat_id
			WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") AND news_draft='0'
			ORDER BY news_sticky DESC, news_datestamp DESC LIMIT ".$_GET['rowstart'].",$items_per_page"
		);
		$numrows = dbrows($result);
		if ($settings['news_style'] == "1") { $nrows = round((dbrows($result) - 1) / 2); }
		while ($data = dbarray($result)) {
			$news_cat_image = "";
			$news_subject = "<a name='news_".$data['news_id']."' id='news_".$data['news_id']."'></a>".stripslashes($data['news_subject']);
			if ($data['news_cat_image']) {
				$news_cat_image = "<a href='news_cats.php?cat_id=".$data['news_cat_id']."'><img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' class='news-category' /></a>";
			} else {
				$news_cat_image = "";
			}
			$news_news = $data['news_breaks'] == "y" ? nl2br(stripslashes($data['news_news'])) : stripslashes($data['news_news']);
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"news_date" => $data['news_datestamp'],
				"news_ext" => $data['news_extended'] ? "y" : "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => dbcount("(comment_id)", DB_COMMENTS, "comment_type='N' AND comment_item_id='".$data['news_id']."'"),
				"news_allow_comments" => $data['news_allow_comments']
			);
			if ($settings['news_style'] == "1") {
				if ($rows <= 2 || $ncount == 1) {
					$news_[0] .= "<table width='100%' cellpadding='0' cellspacing='0'>\n";
					$news_[0] .= "<tr>\n<td class='tbl2'><strong>".$news_subject."</strong></td>\n</tr>\n";
					$news_[0] .= "<tr>\n<td class='tbl1' style='text-align:justify'>".$news_news."</td>\n</tr>\n";
					$news_[0] .= "<tr>\n<td align='center' class='tbl2'>\n";
					$news_[0] .= "<span class='small2'>".THEME_BULLET." <a href='profile.php?lookup=".$news_info['user_id']."'>".$news_info['user_name']."</a> ".$locale['global_071'].showdate("longdate", $news_info['news_date'])." &middot;\n";
					if ($news_info['news_ext'] == "y" || $news_info['news_allow_comments']) {
						$news_[0] .= $news_info['news_ext'] == "y" ? "<a href='".FUSION_SELF."?readmore=".$news_info['news_id']."'>".$locale['global_072']."</a> &middot;\n" : "";
						$news_[0] .= $news_info['news_allow_comments'] ? "<a href='".FUSION_SELF."?readmore=".$news_info['news_id']."'>".$news_info['news_comments'].($news_info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> &middot;\n" : "";
						$news_[0] .= $news_info['news_reads'].$locale['global_074']." &middot;\n";
					}
					$news_[0] .= "<a href='print.php?type=N&amp;item_id=".$news_info['news_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>";
					if (checkrights("N")) { $news_[0] .= " &middot;  <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$news_info['news_id']."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a></span>\n"; } else { $news_[0] .= "</span>\n"; }
					$news_[0] .= "</td>\n</tr>\n</table>\n";
					if ($ncount != $rows) { $news_[0] .= "<div><img src='".get_image("blank")."' alt='' width='1' height='8' /></div>\n"; }
				} else {
					if ($i == $nrows && $ncolumn != 2) { $ncolumn = 2; $i = 0; }
					$row_color = ($rc % 2 == 0 ? "tbl2" : "tbl1");
					$news_[$ncolumn] .= "<table width='100%' cellpadding='0' cellspacing='0'>\n";
					$news_[$ncolumn] .= "<tr>\n<td class='tbl2'><strong>".$news_subject."</strong></td>\n</tr>\n";
					$news_[$ncolumn] .= "<tr>\n<td class='tbl1' style='text-align:justify'>".$news_news."</td>\n</tr>\n";
					$news_[$ncolumn] .= "<tr>\n<td align='center' class='tbl2'>\n";
					$news_[$ncolumn] .= "<span class='small2'>".THEME_BULLET." <a href='profile.php?lookup=".$news_info['user_id']."'>".$news_info['user_name']."</a> ".$locale['global_071'].showdate("longdate", $news_info['news_date']);
					if ($news_info['news_ext'] == "y" || $news_info['news_allow_comments']) {
						$news_[$ncolumn] .= "<br />\n";
						$news_[$ncolumn] .= $news_info['news_ext'] == "y" ? "<a href='".FUSION_SELF."?readmore=".$news_info['news_id']."'>".$locale['global_072']."</a> &middot;\n" : "";
						$news_[$ncolumn] .= $news_info['news_allow_comments'] ? "<a href='".FUSION_SELF."?readmore=".$news_info['news_id']."#comments'>".$news_info['news_comments'].($news_info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> &middot;\n" : "";
						$news_[$ncolumn] .= $news_info['news_reads'].$locale['global_074']." &middot;\n";
					} else {
						$news_[$ncolumn] .= " &middot;\n";
					}
					$news_[$ncolumn] .= "<a href='print.php?type=N&amp;item_id=".$news_info['news_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>\n";
					if (checkrights("N")) { $news_[$ncolumn] .= " &middot; <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$news_info['news_id']."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a></span>\n"; } else { $news_[$ncolumn] .= "</span>\n"; }
					$news_[$ncolumn] .= "</td>\n</tr>\n</table>\n";
					if ($ncolumn == 1 && $i < ($nrows - 1)) { $news_[$ncolumn] .= "<div><img src='".get_image("blank")."' alt='' width='1' height='8' /></div>\n"; }
					if ($ncolumn == 2 && $i < (dbrows($result) - $nrows - 2)) { $news_[$ncolumn] .= "<div><img src='".get_image("blank")."' alt='' width='1' height='8' /></div>\n"; }
					$i++; $rc++;
				}
				$ncount++;
			} else {
				echo "<!--news_prepost_".$i."-->\n";
				$i++;
				render_news($news_subject, $news_news, $news_info);
			}
		}
		if ($settings['news_style'] == "1") {
			opentable($locale['global_077']);
			echo "<table cellpadding='0' cellspacing='0' style='width:100%'>\n<tr>\n<td colspan='3' style='width:100%'>\n";
			echo $news_[0];
			echo "</td>\n</tr>\n<tr>\n<td style='width:50%;vertical-align:top;'>\n";
			echo $news_[1];
			echo "</td>\n<td style='width:10px'><img src='".get_image("blank")."' alt='' width='10' height='1' /></td>\n<td style='width:50%;vertical-align:top;'>\n";
			echo $news_[2];
			echo "</td>\n</tr>\n</table>\n";
			closetable();
		}
		if ($rows > $items_per_page) echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($_GET['rowstart'],$items_per_page,$rows,3)."\n</div>\n";
	} else {
		opentable($locale['global_077']);
		echo "<div style='text-align:center'><br />\n".$locale['global_078']."<br /><br />\n</div>\n";
		closetable();
	}
} else {
	include INCLUDES."comments_include.php";
	include INCLUDES."ratings_include.php";
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".DB_NEWS." tn
		LEFT JOIN ".DB_USERS." tu ON tn.news_name=tu.user_id
		WHERE news_id='".$_GET['readmore']."' AND news_draft='0'"
	);
	if (dbrows($result)!=0) {
		$data = dbarray($result);
		if (checkgroup($data['news_visibility'])) {
			$news_cat_image = "";
			if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
				 $result2 = dbquery("UPDATE ".DB_NEWS." SET news_reads=news_reads+1 WHERE news_id='".$_GET['readmore']."'");
				 $data['news_reads']++;
			}
			$news_subject = $data['news_subject'];
			if ($data['news_cat']) {
				$result2 = dbquery("SELECT * FROM ".DB_NEWS_CATS." WHERE news_cat_id='".$data['news_cat']."'");
				if (dbrows($result2)) {
					$data2 = dbarray($result2);
					$news_cat_image = "<a href='news_cats.php?cat_id=".$data2['news_cat_id']."'><img src='".get_image("nc_".$data2['news_cat_name'])."' alt='".$data2['news_cat_name']."' class='news-category' /></a>";
				}
			}
			$news_news = stripslashes($data['news_extended'] ? $data['news_extended'] : $data['news_news']);
			if ($data['news_breaks'] == "y") { $news_news = nl2br($news_news); }
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"news_date" => $data['news_datestamp'],
				"news_ext" => "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => dbcount("(comment_id)", DB_COMMENTS, "comment_type='N' AND comment_item_id='".$data['news_id']."'"),
				"news_allow_comments" => $data['news_allow_comments']
			);
			add_to_title($locale['global_201'].$news_subject);
			echo "<!--news_pre_readmore-->";
			render_news($news_subject, $news_news, $news_info);
			echo "<!--news_sub_readmore-->";
			if ($data['news_allow_comments']) { showcomments("N", DB_NEWS, "news_id", $_GET['readmore'], FUSION_SELF."?readmore=".$_GET['readmore']); }
			if ($data['news_allow_ratings']) { showratings("N", $_GET['readmore'], FUSION_SELF."?readmore=".$_GET['readmore']); }
		} else {
			redirect(FUSION_SELF);
		}
	} else {
		redirect(FUSION_SELF);
	}
}

require_once THEMES."templates/footer.php";
?>