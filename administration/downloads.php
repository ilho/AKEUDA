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
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
require_once INCLUDES."html_buttons_include.php";
include LOCALE.LOCALESET."admin/downloads.php";

if (!checkrights("D") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

$result = dbquery("SELECT * FROM ".DB_DOWNLOAD_CATS);
if (dbrows($result)) {
	if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['download_id']) && isnum($_GET['download_id']))) {
		$result = dbquery("DELETE FROM ".DB_DOWNLOADS." WHERE download_id='".$_GET['download_id']."'");
		redirect(FUSION_SELF.$aidlink."&download_cat_id=".intval($_GET['download_cat_id']));
	} elseif (isset($_POST['save_download'])) {
		$download_title = stripinput($_POST['download_title']);
		$download_description = addslash($_POST['download_description']);
		$download_url = stripinput($_POST['download_url']);
		$download_cat = intval($_POST['download_cat']);
		$download_license = stripinput($_POST['download_license']);
		$download_os = stripinput($_POST['download_os']);
		$download_version = stripinput($_POST['download_version']);
		$download_filesize = stripinput($_POST['download_filesize']);
		if ($download_title) {
			if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['download_id']) && isnum($_GET['download_id']))) {
				$download_datestamp = isset($_POST['update_datestamp']) ? ", download_datestamp='".time()."'" : "";
				$result = dbquery("UPDATE ".DB_DOWNLOADS." SET download_title='$download_title', download_description='$download_description', download_url='$download_url', download_cat='$download_cat', download_license='$download_license', download_os='$download_os', download_version='$download_version', download_filesize='$download_filesize'".$download_datestamp." WHERE download_id='".$_GET['download_id']."'");
				redirect(FUSION_SELF.$aidlink."&download_cat_id=".$download_cat);
			} else {
				$result = dbquery("INSERT INTO ".DB_DOWNLOADS." (download_title, download_description, download_url, download_cat, download_license, download_os, download_version, download_filesize, download_datestamp, download_count) VALUES ('$download_title', '$download_description', '$download_url', '$download_cat', '$download_license', '$download_os', '$download_version', '$download_filesize', '".time()."', '0')");
				redirect(FUSION_SELF.$aidlink."&download_cat_id=".$download_cat);
			}
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
	if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['download_id']) && isnum($_GET['download_id']))) {
		$result = dbquery("SELECT * FROM ".DB_DOWNLOADS." WHERE download_id='".$_GET['download_id']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$download_title = $data['download_title'];
			$download_description = stripinput($data['download_description']);
			$download_url = $data['download_url'];
			$download_license = $data['download_license'];
			$download_os = $data['download_os'];
			$download_version = $data['download_version'];
			$download_filesize = $data['download_filesize'];
			$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;download_cat_id=".$data['download_cat']."&amp;download_id=".$data['download_id'];
			opentable($locale['401']);
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} else {
		$download_title = "";
		$download_description = "";
		$download_url = "";
		$download_license = "";
		$download_os = "";
		$download_version = "";
		$download_filesize = "";
		$formaction = FUSION_SELF.$aidlink;
		opentable($locale['400']);
	}
	$editlist = ""; $sel = "";
	$result2 = dbquery("SELECT * FROM ".DB_DOWNLOAD_CATS." ORDER BY download_cat_name");
	if (dbrows($result2) != 0) {
		while ($data2 = dbarray($result2)) {
			if (isset($_GET['action']) && $_GET['action'] == "edit") { $sel = ($data['download_cat'] == $data2['download_cat_id'] ? " selected='selected'='selected'" : ""); }
			$editlist .= "<option value='".$data2['download_cat_id']."'$sel>".$data2['download_cat_name']."</option>\n";
		}
	}
	echo "<form id='inputform' method='post' action='".$formaction."'>\n";
	echo "<table cellpadding='0' cellspacing='0' width='460' class='center'>\n<tr>\n";
	echo "<td width='80' class='tbl'>".$locale['420']."</td>\n";
	echo "<td class='tbl'><input type='text' name='download_title' value='".$download_title."' class='textbox' style='width:380px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td valign='top' width='80' class='tbl'>".$locale['421']."</td>\n";
	echo "<td class='tbl'><textarea name='download_description' cols='60' rows='5' class='textbox' style='width:380px;'>".$download_description."</textarea></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'></td><td class='tbl'>\n";
	echo display_html("inputform", "download_description", true)."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='80' class='tbl'>".$locale['422']."</td>\n";
	echo "<td class='tbl'><input type='text' name='download_url' value='".$download_url."' class='textbox' style='width:380px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='80' class='tbl'>".$locale['423']."</td>\n";
	echo "<td class='tbl'><select name='download_cat' class='textbox'>\n".$editlist."</select></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='80' class='tbl'>".$locale['424']."</td>\n";
	echo "<td class='tbl'><input type='text' name='download_license' value='".$download_license."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='80' class='tbl'>".$locale['425']."</td>\n";
	echo "<td class='tbl'><input type='text' name='download_os' value='".$download_os."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='80' class='tbl'>".$locale['426']."</td>\n";
	echo "<td class='tbl'><input type='text' name='download_version' value='".$download_version."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='80' class='tbl'>".$locale['427']."</td>\n";
	echo "<td class='tbl'><input type='text' name='download_filesize' value='".$download_filesize."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'>";
	if (isset($_GET['action']) && $_GET['action'] == "edit") {
		echo "<input type='checkbox' name='update_datestamp' value='1' /> ".$locale['429']."<br /><br />\n";
	}
	echo "<input type='submit' name='save_download' value='".$locale['428']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();

	opentable($locale['402']);
	echo "<table cellpadding='0' cellspacing='0' width='400' class='center'>\n";
	$result = dbquery("SELECT * FROM ".DB_DOWNLOAD_CATS." ORDER BY download_cat_name");
	if (dbrows($result)) {
		echo "<tr>\n";
		echo "<td class='tbl2'>".$locale['440']."</td>\n";
		echo "<td align='right' class='tbl2'>".$locale['441']."</td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td colspan='2' height='1'></td>\n";
		echo "</tr>\n";
		while ($data = dbarray($result)) {
			if (!isset($_GET['download_cat_id']) || !isnum($_GET['download_cat_id'])) { $_GET['download_cat_id'] = 0; }
			if ($data['download_cat_id'] == $_GET['download_cat_id']) { $p_img = "off"; $div = ""; } else { $p_img = "on"; $div = "style='display:none'"; }
			echo "<tr>\n";
			echo "<td class='tbl2'>".$data['download_cat_name']."</td>\n";
			echo "<td class='tbl2' align='right'><img src='".get_image("panel_$p_img")."' name='b_".$data['download_cat_id']."' alt='' onclick=\"javascript:flipBox('".$data['download_cat_id']."')\" /></td>\n";
			echo "</tr>\n";
			$result2 = dbquery("SELECT * FROM ".DB_DOWNLOADS." WHERE download_cat='".$data['download_cat_id']."' ORDER BY download_title");
			if (dbrows($result2) != 0) {
				echo "<tr>\n<td colspan='2'>\n";
				echo "<div id='box_".$data['download_cat_id']."'".$div.">\n";
				echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
				while ($data2 = dbarray($result2)) {
					if (!strstr($data2['download_url'],"http://") && !strstr($data2['download_url'],"../")) {
						$download_url = BASEDIR.$data2['download_url'];
					} else {
						$download_url = $data2['download_url'];
					}
					echo "<tr>\n<td class='tbl'><a href='$download_url' target='_blank'>".$data2['download_title']."</a></td>\n";
					echo "<td align='right' width='100' class='tbl'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;download_cat_id=".$data['download_cat_id']."&amp;download_id=".$data2['download_id']."'>".$locale['442']."</a> -\n";
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;download_cat_id=".$data['download_cat_id']."&amp;download_id=".$data2['download_id']."' onclick=\"return confirm('".$locale['460']."');\">".$locale['443']."</a></td>\n";
					echo "</tr>\n";
				}
				echo "</table>\n</div>\n</td>\n</tr>\n";
			}
		}
		echo "</table>\n";
	} else {
		echo "<tr>\n<td align='center'><br />\n";
		echo $locale['450']."<br /><br /></td>\n";
		echo "</tr>\n</table>\n";
	}
	closetable();
} else {
	opentable($locale['402']);
	echo "<div style='text-align:center'>".$locale['451']."<br />\n".$locale['452']."<br /><br />\n";
	echo "<a href='download_cats.php".$aidlink."'>".$locale['453']."</a>".$locale['454']."</div>\n";
	closetable();
}

require_once THEMES."templates/footer.php";
?>