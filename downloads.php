<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: downloads.php
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
include LOCALE.LOCALESET."downloads.php";

add_to_title($locale['global_200'].$locale['400']);

if (isset($_GET['download_id']) && isnum($_GET['download_id'])) {
	$res = 0;
	if ($data = dbarray(dbquery("SELECT download_url,download_cat FROM ".DB_DOWNLOADS." WHERE download_id='".$_GET['download_id']."'"))) {
		$cdata = dbarray(dbquery("SELECT * FROM ".DB_DOWNLOAD_CATS." WHERE download_cat_id='".$data['download_cat']."'"));
		if (checkgroup($cdata['download_cat_access'])) {
			$res = 1;
			$result = dbquery("UPDATE ".DB_DOWNLOADS." SET download_count=download_count+1 WHERE download_id='".$_GET['download_id']."'");
			redirect($data['download_url']);
		}
	}
	if ($res == 0) { redirect("downloads.php"); }
}

if (!isset($_GET['cat_id']) || !isnum($_GET['cat_id'])) {
	opentable($locale['400']);
	echo "<!--pre_download_idx-->\n";
	$result = dbquery("SELECT * FROM ".DB_DOWNLOAD_CATS." WHERE ".groupaccess('download_cat_access')." ORDER BY download_cat_name");
	$rows = dbrows($result);
	if ($rows) {
		$counter = 0; $columns = 2;
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
			$num = dbcount("(download_cat)", DB_DOWNLOADS, "download_cat='".$data['download_cat_id']."'");
			echo "<td valign='top' width='50%' class='tbl download_idx_cat_name'><!--download_idx_cat_name--><a href='".FUSION_SELF."?cat_id=".$data['download_cat_id']."'>".$data['download_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data['download_cat_description'] != "") { echo "<br />\n<span class='small'>".$data['download_cat_description']."</span>"; }
			echo "</td>\n" ;
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['430']."<br /><br />\n</div>\n";
	}
	echo "<!--sub_download_idx-->";
	closetable();
} else {
	$res = 0;
	$result = dbquery("SELECT * FROM ".DB_DOWNLOAD_CATS." WHERE download_cat_id='".$_GET['cat_id']."'");
	if (dbrows($result) != 0) {
		$cdata = dbarray($result);
		if (checkgroup($cdata['download_cat_access'])) {
			$res = 1;
			add_to_title($locale['global_201'].$cdata['download_cat_name']);
			opentable($locale['400'].": ".$cdata['download_cat_name']);
			echo "<!--pre_download_cat-->";
			$rows = dbcount("(*)", DB_DOWNLOADS, "download_cat='".$_GET['cat_id']."'");
			if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
			if ($rows != 0) {
				$result = dbquery("SELECT * FROM ".DB_DOWNLOADS." WHERE download_cat='".$_GET['cat_id']."' ORDER BY ".$cdata['download_cat_sorting']." LIMIT ".$_GET['rowstart'].",15");
				$numrows = dbrows($result); $i = 1;
				while ($data = dbarray($result)) {
					if ($data['download_datestamp'] + 604800 > time() + ($settings['timeoffset'] * 3600)) {
						$new = " <span class='small'>".$locale['410']."</span>";
					} else {
						$new = "";
					}
					echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
					echo "<tr>\n<td colspan='3' class='forum-caption'><strong>".$data['download_title']."</strong> $new</td>\n</tr>\n";
					if ($data['download_description']) { echo "<tr>\n<td colspan='3' class='tbl1'>".nl2br(stripslashes($data['download_description']))."</td>\n</tr>\n"; }
					echo "<tr>\n<td width='30%' class='tbl2'><strong>".$locale['411']."</strong> ".$data['download_license']."</td>\n<td width='30%' class='tbl1'><strong>".$locale['412']."</strong> ".$data['download_os']."</td>\n";
					echo "<td width='40%' class='tbl2'><strong>".$locale['413']."</strong> ".$data['download_version']."</td>\n</tr>\n<tr>\n<td width='30%' class='tbl2'><strong>".$locale['414']."</strong> ".showdate("shortdate", $data['download_datestamp'])."</td>\n";
					echo "<td width='30%' class='tbl1'><strong>".$locale['415']."</strong> ".$data['download_count']."</td>\n<td width='40%' class='tbl2'><a href='".FUSION_SELF."?cat_id=".$_GET['cat_id']."&amp;download_id=".$data['download_id']."' target='_blank'>".$locale['416']."</a> (".$data['download_filesize'].")</td>\n</tr>\n";
					echo "</table>\n";
					if ($i != $numrows) { echo "<div style='text-align:center'><img src='".get_image("blank")."' alt='' height='15' width='1' /></div>\n"; $i++; }
				}
				closetable();
				if ($rows > 15) { echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 15, $rows, 3, FUSION_SELF."?cat_id=".$_GET['cat_id']."&amp;")."\n</div>\n"; }
			} else {
				echo "<div style='text-align:center'>".$locale['431']."</div>\n";
				echo "<!--sub_download_cat-->";
				closetable();
			}
		}
	}
	if ($res == 0) { redirect(FUSION_SELF); }
}

require_once THEMES."templates/footer.php";
?>