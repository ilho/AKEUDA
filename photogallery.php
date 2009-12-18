<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: photogallery.php
| Author: Nick Jones (Digitanium)
| Co-Author: Robert Gaudyn (Wooya)
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
include LOCALE.LOCALESET."photogallery.php";

define("SAFEMODE", @ini_get("safe_mode") ? true : false);

add_to_title($locale['global_200'].$locale['400']);

if (isset($_GET['photo_id']) && isnum($_GET['photo_id'])){
	$result = dbquery(
		"SELECT tp.*, ta.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes
		FROM ".DB_PHOTOS." tp
		LEFT JOIN ".DB_PHOTO_ALBUMS." ta USING (album_id)
		LEFT JOIN ".DB_USERS." tu ON tp.photo_user=tu.user_id
		LEFT JOIN ".DB_RATINGS." tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
		WHERE photo_id='".$_GET['photo_id']."' GROUP BY tp.photo_id"
	);
	$data = dbarray($result);
	if (!checkgroup($data['album_access'])) {
		redirect(FUSION_SELF);
	} else {
		define("PHOTODIR", PHOTOS.(!SAFEMODE ? "album_".$data['album_id']."/" : ""));
		include INCLUDES."comments_include.php";
		include INCLUDES."ratings_include.php";
		$result=dbquery("UPDATE ".DB_PHOTOS." SET photo_views=(photo_views+1) WHERE photo_id='".$_GET['photo_id']."'");

		$pres = dbquery("SELECT photo_id FROM ".DB_PHOTOS." WHERE photo_order='".($data['photo_order']-1)."' AND album_id='".$data['album_id']."'");
		$nres = dbquery("SELECT photo_id FROM ".DB_PHOTOS." WHERE photo_order='".($data['photo_order']+1)."' AND album_id='".$data['album_id']."'");
		if (dbrows($pres)) $prev = dbarray($pres);
		if (dbrows($nres)) $next = dbarray($nres);

		opentable($locale['450']);
		if ($settings['photo_watermark']) {
			if ($settings['photo_watermark_save']) {
				$parts = explode(".", $data['photo_filename']);
				$wm_file1 = $parts[0]."_w1.".$parts[1];
				$wm_file2 = $parts[0]."_w2.".$parts[1];
				if (!file_exists(PHOTODIR.$wm_file1)) {
					if ($data['photo_thumb2']) { $photo_thumb = "photo.php?photo_id=".$data['photo_id']; }
					$photo_file = "photo.php?photo_id=".$data['photo_id'];
				} else {
					if ($data['photo_thumb2']) { $photo_thumb = PHOTODIR.$wm_file1; }
					$photo_file = PHOTODIR.$wm_file2;
				}
			} else {
				if ($data['photo_thumb2']) { $photo_thumb = "photo.php?photo_id=".$data['photo_id']; }
				$photo_file = "photo.php?photo_id=".$data['photo_id'];
			}
			$photo_size = @getimagesize(PHOTODIR.$data['photo_filename']);
		} else {
			$photo_thumb = $data['photo_thumb2'] ? PHOTODIR.$data['photo_thumb2'] : "";
			$photo_file = PHOTODIR.$data['photo_filename'];
			$photo_size = @getimagesize($photo_file);
		}
		add_to_title($locale['global_201'].$data['photo_title']);
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n<td class='tbl2'>\n";
		echo "<a href='".FUSION_SELF."'>".$locale['400']."</a> &gt;\n";
		echo "<a href='".FUSION_SELF."?album_id=".$data['album_id']."'>".$data['album_title']."</a> &gt;\n";
		echo "<a href='".FUSION_SELF."?photo_id=".$_GET['photo_id']."'>".$data['photo_title']."</a>\n</td>\n";
		if ((isset($prev['photo_id']) && isnum($prev['photo_id'])) || (isset($next['photo_id']) && ISnUM($next['photo_id']))) {
			if (isset($prev)) { echo "<td width='1%' class='tbl2'><a href='".FUSION_SELF."?photo_id=".$prev['photo_id']."' title='".$locale['451']."'>&lt;&lt;</a></td>\n"; }
			if (isset($next)) { echo "<td width='1%' class='tbl2'><a href='".FUSION_SELF."?photo_id=".$next['photo_id']."' title='".$locale['452']."'>&gt;&gt;</a></td>\n"; }
		}
		echo "</tr>\n</table>\n";

		echo "<div align='center' style='margin:5px;'>\n";
		echo "<a href=\"javascript:;\" onclick=\"window.open('showphoto.php?photo_id=".$data['photo_id']."','','scrollbars=yes,toolbar=no,status=no,resizable=yes,width=".($photo_size[0]+20).",height=".($photo_size[1]+20)."')\" class='photogallery_photo_link'><!--photogallery_photo_".$data['photo_id']."-->";
		echo "<img src='".(isset($photo_thumb) && !empty($photo_thumb) ? $photo_thumb : $photo_file)."' alt='".$data['photo_filename']."' title='".$locale['453']."' style='border:0px' class='photogallery_photo' /></a>\n</div>\n";
		echo "<div align='center' style='margin:5px 0px 5px 0px' class='photogallery_photo_desc'><!--photogallery_photo_desc-->\n";
		if ($data['photo_description']) {
			echo nl2br(parseubb($data['photo_description'], "b|i|u|center|small|url|mail|img|quote"))."<br /><br />\n";
		}
		echo $locale['433'].showdate("shortdate", $data['photo_datestamp'])."<br />\n";
		echo $locale['434']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br />\n";
		echo $locale['454']."$photo_size[0] x $photo_size[1] ".$locale['455']."<br />\n";
		echo $locale['456'].parsebytesize($settings['photo_watermark'] ? filesize(PHOTODIR.$data['photo_filename']): filesize($photo_file))."<br />\n";
		$photo_comments = dbcount("(comment_id)", DB_COMMENTS, "comment_type='P' AND comment_item_id='".$data['photo_id']."'");
		echo ($photo_comments == 1 ? $locale['436b'] : $locale['436']).$photo_comments."<br />\n";
		echo $locale['437'].($data['count_votes'] > 0 ? str_repeat("<img src='".get_image("star")."' alt='*' style='vertical-align:middle' />", ceil($data['sum_rating'] / $data['count_votes'])) : $locale['438'])."<br />\n";
		echo $locale['457'].$data['photo_views']."\n</div>\n";
		closetable();
		if ($data['photo_allow_comments']) { showcomments("P", DB_PHOTOS, "photo_id", $_GET['photo_id'], FUSION_SELF."?photo_id=".$_GET['photo_id']); }
		if ($data['photo_allow_ratings']) { showratings("P", $_GET['photo_id'], FUSION_SELF."?photo_id=".$_GET['photo_id']); }
	}
} elseif (isset($_GET['album_id']) && isnum($_GET['album_id'])) {
	define("PHOTODIR", PHOTOS.(!SAFEMODE ? "album_".$_GET['album_id']."/" : ""));
	$result = dbquery(
		"SELECT ta.* FROM ".DB_PHOTO_ALBUMS." ta WHERE album_id='".$_GET['album_id']."'"
	);
	if (!dbrows($result)) {
		redirect(FUSION_SELF);
	} else {
		$data = dbarray($result);
		if (!checkgroup($data['album_access'])) {
			redirect(FUSION_SELF);
		} else {
			$rows = dbcount("(photo_id)", DB_PHOTOS, "album_id='".$_GET['album_id']."'");
			add_to_title($locale['global_201'].$data['album_title']);
			opentable($locale['420']);
			echo "<!--pre_album_info-->";
			echo "<table cellpadding='0' cellspacing='0' width='80%' class='center'>\n<tr>\n";
			echo "<td rowspan='2' align='center' class='tbl1 photogallery_album_thumb'><!--photogallery_album_thumb-->";
				if ($data['album_thumb'] && file_exists(PHOTOS.$data['album_thumb'])){
					echo "<img src='".PHOTOS.$data['album_thumb']."' alt='".$data['album_thumb']."' />";
				} else {
					echo $locale['432'];
				}
			echo "</td>\n";
			echo "<td valign='top' width='100%'><div class='tbl2' style='font-weight:bold;vertical-align:top'>".$locale['421'].$data['album_title']."</div>\n";
			echo "<div class='tbl1 photogallery_album_desc' style='vertical-align:middle'><!--photogallery_album_desc-->".nl2br(parseubb($data['album_description']))."</div>\n</td>\n</tr>\n";
			echo "<tr>\n<td valign='bottom' width='100%'>\n<div class='tbl2' style='vertical-align:bottom'>\n";
			if ($rows) {
				$pdata = dbarray(dbquery("
					SELECT tp.*, tu.user_id,user_name FROM ".DB_PHOTOS." tp
					LEFT JOIN ".DB_USERS." tu ON tp.photo_user=tu.user_id
					WHERE album_id='".$_GET['album_id']."' ORDER BY photo_datestamp DESC LIMIT 1"
				));
				echo $locale['422']."$rows<br />\n";
				echo $locale['423']."<a href='".BASEDIR."profile.php?lookup=".$pdata['user_id']."'>".$pdata['user_name']."</a>".$locale['424'].showdate("longdate", $pdata['photo_datestamp'])."\n";
			} else {
				echo $locale['425']."\n";
			}
			echo "</div>\n</td>\n</tr>\n</table>";
			echo "<!--sub_album_info-->";
			closetable();
			if ($rows) {

				opentable($locale['430']);
				if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
				$result = dbquery(
					"SELECT tp.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes
					FROM ".DB_PHOTOS." tp
					LEFT JOIN ".DB_USERS." tu ON tp.photo_user=tu.user_id
					LEFT JOIN ".DB_RATINGS." tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
					WHERE album_id='".$_GET['album_id']."' GROUP BY photo_id ORDER BY photo_order LIMIT ".$_GET['rowstart'].",".$settings['thumbs_per_page']
				);
				$counter = 0;
				echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n<td class='tbl2'>\n";
				echo "<a href='".FUSION_SELF."'>".$locale['400']."</a> &gt;\n";
				echo "<a href='".FUSION_SELF."?album_id=".$data['album_id']."'>".$data['album_title']."</a>\n";
				echo "</td>\n</tr>\n</table>\n";

				echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
				while ($data = dbarray($result)) {
					if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) { echo "</tr>\n<tr>\n"; }
					echo "<td align='center' valign='top' class='tbl'>\n";
					echo "<strong>".$data['photo_title']."</strong><br /><br />\n<a href='".FUSION_SELF."?photo_id=".$data['photo_id']."' class='photogallery_album_photo_link'><!--photogallery_album_photo_".$data['photo_id']."-->";
					if ($data['photo_thumb1'] && file_exists(PHOTODIR.$data['photo_thumb1'])){
						echo "<img src='".PHOTODIR.$data['photo_thumb1']."' alt='".$data['photo_thumb1']."' title='".$locale['431']."' style='border:0px' class='photogallery_album_photo' />";
					} else {
						echo $locale['432'];
					}
					echo "</a><br /><br />\n<span class='small photogallery_album_photo_info'> <!--photogallery_album_photo_info-->\n";
					echo $locale['433'].showdate("shortdate", $data['photo_datestamp'])."<br />\n";
					echo $locale['434']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br />\n";
					$photo_comments = dbcount("(comment_id)", DB_COMMENTS, "comment_type='P' AND comment_item_id='".$data['photo_id']."'");
					echo ($photo_comments == 1 ? $locale['436b'] : $locale['436']).$photo_comments."<br />\n";
					echo $locale['437'].($data['count_votes'] > 0 ? str_repeat("<img src='".IMAGES."star.gif' alt='*' style='vertical-align:middle' />", ceil($data['sum_rating'] / $data['count_votes'])) : $locale['438'])."<br />\n";
					echo $locale['435'].$data['photo_views']."</span><br />\n";
					echo "</td>\n";
					$counter++;
				}
				echo "</tr>\n</table>\n";
				closetable();
			}
			if ($rows > $settings['thumbs_per_page']) { echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], $settings['thumbs_per_page'], $rows, 3, FUSION_SELF."?album_id=".$_GET['album_id']."&amp;")."\n</div>\n"; }
		}
	}
} else {
	opentable($locale['400']);
	$rows = dbcount("(album_id)", DB_PHOTO_ALBUMS, groupaccess('album_access'));
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
	if ($rows) {
		$result = dbquery(
			"SELECT ta.*, tu.user_id,user_name FROM ".DB_PHOTO_ALBUMS." ta
			LEFT JOIN ".DB_USERS." tu ON ta.album_user=tu.user_id
			WHERE ".groupaccess('album_access')." ORDER BY album_order
			LIMIT ".$_GET['rowstart'].",".$settings['thumbs_per_page']
		);
		$counter = 0; $r = 0; $k = 1;
		echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) { echo "</tr>\n<tr>\n"; }
			echo "<td align='center' valign='top' class='tbl'>\n";
			echo "<strong>".$data['album_title']."</strong><br /><br />\n<a href='".FUSION_SELF."?album_id=".$data['album_id']."'>";
			if ($data['album_thumb'] && file_exists(PHOTOS.$data['album_thumb'])){
				echo "<img src='".PHOTOS.$data['album_thumb']."' alt='".$data['album_thumb']."' title='".$locale['401']."' style='border:0px' />";
			} else {
				echo $locale['402'];
			}
			echo "</a><br /><br />\n<span class='small'>\n";
			echo $locale['403'].showdate("shortdate", $data['album_datestamp'])."<br />\n";
			echo $locale['404']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br />\n";
			echo $locale['405'].dbcount("(photo_id)", DB_PHOTOS, "album_id='".$data['album_id']."'")."</span><br />\n";
			echo "</td>\n";
			$counter++; $k++;
		}
		echo "</tr>\n</table>\n";
		closetable();
		if ($rows > $settings['thumbs_per_page']) { echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], $settings['thumbs_per_page'], $rows, 3)."\n</div>\n"; }
	}else{
		echo "<div style='text-align:center'><br />".$locale['406']."<br /><br /></div>\n";
		closetable();
	}
}

require_once THEMES."templates/footer.php";
?>