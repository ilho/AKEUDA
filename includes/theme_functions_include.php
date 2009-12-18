<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: theme_functions_include.php
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

function check_panel_status($side) {
	
	global $settings;
	
	$exclude_list = "";
	
	if ($side == "left") {
		if ($settings['exclude_left'] != "") {
			$exclude_list = explode("\r\n", $settings['exclude_left']);
		}
	} elseif ($side == "upper") {
		if ($settings['exclude_upper'] != "") {
			$exclude_list = explode("\r\n", $settings['exclude_upper']);
		}
	} elseif ($side == "lower") {
		if ($settings['exclude_lower'] != "") {
			$exclude_list = explode("\r\n", $settings['exclude_lower']);
		}
	} elseif ($side == "right") {
		if ($settings['exclude_right'] != "") {
			$exclude_list = explode("\r\n", $settings['exclude_right']);
		}
	}
	
	if (is_array($exclude_list)) {
		$script_url = explode("/", $_SERVER['PHP_SELF']);
		$url_count = count($script_url);
		$base_url_count = substr_count(BASEDIR, "/")+1;
		$match_url = "";
		while ($base_url_count != 0) {
			$current = $url_count - $base_url_count;
			$match_url .= "/".$script_url[$current];
			$base_url_count--;
		}
		if (!in_array($match_url, $exclude_list) && !in_array($match_url.(FUSION_QUERY ? "?".FUSION_QUERY : ""), $exclude_list)) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

function showbanners() {
	global $settings;
	ob_start();
	if ($settings['sitebanner2']) {
		eval("?><div style='float: right;'>".stripslashes($settings['sitebanner2'])."</div>\n<?php ");
	}
	if ($settings['sitebanner1']) {
		eval("?>".stripslashes($settings['sitebanner1'])."\n<?php ");
	} elseif ($settings['sitebanner']) {
		echo "<a href='".$settings['siteurl']."'><img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."' style='border: 0;' /></a>\n";
	} else {
		echo "<a href='".$settings['siteurl']."'>".$settings['sitename']."</a>\n";
	}	
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

function showsublinks($sep = "&middot;", $class = "") {
	$sres = dbquery(
		"SELECT link_window, link_visibility, link_url, link_name FROM ".DB_SITE_LINKS."
		WHERE ".groupaccess('link_visibility')." AND link_position>='2' AND link_url!='---' ORDER BY link_order ASC"
	);
	if(dbrows($sres)) {
		$i = 0;
		$res = "<ul>\n";
		while ($sdata = dbarray($sres)) {
			$link_target = $sdata['link_window'] == "1" ? " target='_blank'" : "";
			$li_class = ($i == 0 ? " class='first-link".($class ? " $class" : "")."'" : ($class ? " class='$class'" : ""));
			if (strstr($sdata['link_url'], "http://") || strstr($sdata['link_url'], "https://")) {
				$res .= "<li".$li_class.">".$sep."<a href='".$sdata['link_url']."'$link_target><span>".$sdata['link_name']."</span></a></li>\n";
			} else {
				$res .= "<li".$li_class.">".$sep."<a href='".BASEDIR.$sdata['link_url']."'$link_target><span>".$sdata['link_name']."</span></a></li>\n";
			}
			$i++;
		}
		$res .= "</ul>\n";
		return $res;
	}
}

function showsubdate() {
	global $settings;
	return ucwords(showdate($settings['subheaderdate'], time()));
}

function newsposter($info,$sep = "",$class = "") {
	global $locale;
	$res = "";
	$link_class = $class ? " class='$class' " : "";
	$res = THEME_BULLET." <a href='profile.php?lookup=".$info['user_id']."'".$link_class.">".$info['user_name']."</a> ";
	$res .= $locale['global_071'].showdate("longdate", $info['news_date']);
	$res .= $info['news_ext'] == "y" || $info['news_allow_comments'] ? $sep."\n" : "\n";
	return "<!--news_poster-->".$res;
}

function newsopts($info, $sep, $class = "") {
	global $locale; $res = "";
	$link_class = $class ? " class='$class' " : "";
	if (!isset($_GET['readmore']) && $info['news_ext'] == "y") $res = "<a href='news.php?readmore=".$info['news_id']."'".$link_class.">".$locale['global_072']."</a> ".$sep." ";
	if ($info['news_allow_comments']) $res .= "<a href='news.php?readmore=".$info['news_id']."#comments'".$link_class.">".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> ".$sep." ";
	if ($info['news_ext'] == "y" || $info['news_allow_comments']) $res .= $info['news_reads'].$locale['global_074']."\n";
	$res .= $sep." <a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>\n";
	return "<!--news_opts-->".$res;
}

function articleposter($info, $sep = "", $class = "") {
	global $locale; $res = "";
	$link_class = $class ? " class='$class' " : "";
	$res = THEME_BULLET." ".$locale['global_070']."<a href='profile.php?lookup=".$info['user_id']."'".$link_class.">".$info['user_name']."</a>\n";
	$res .= $locale['global_071'].showdate("longdate", $info['article_date']);
	$res .= $info['article_allow_comments'] ? $sep."\n" : "\n";
	return "<!--article_poster-->".$res;
}

function articleopts($info, $sep) {
	global $locale; $res = "";
	if ($info['article_allow_comments']) { $res = "<a href='articles.php?article_id=".$info['article_id']."#comments'>".$info['article_comments'].($info['article_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> ".$sep."\n"; }
	$res .= $info['article_reads'].$locale['global_074']." ".$sep."\n";
	$res .= "<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>\n";
	return "<!--article_opts-->".$res;
}

function itemoptions($item_type, $item_id) {
	global $locale, $aidlink; $res = "";
	if ($item_type == "N") {
		if (iADMIN && checkrights($item_type)) { $res .= "<!--article_news_opts--> &middot; <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a>\n"; }
	} elseif ($item_type == "A") {
	if (iADMIN && checkrights($item_type)) { $res .= "<!--article_admin_opts--> &middot; <a href='".ADMIN."articles.php".$aidlink."&amp;action=edit&amp;article_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a>\n"; }
	}
	return $res;
}

function showcopyright($class = "") {
	$link_class = $class ? " class='$class' " : "";
	$res = "Powered by <a href='http://www.php-fusion.co.uk'".$link_class.">PHP-Fusion</a> copyright &copy; 2002 - ".date("Y")." by Nick Jones.<br />\n";
	$res .= "Released as free software without warranties under <a href='http://www.fsf.org/licensing/licenses/agpl-3.0.html'".$link_class.">GNU Affero GPL</a> v3.\n";
	return $res;
}

function showcounter() {
	global $locale,$settings;
	return "<!--counter-->".number_format($settings['counter'])." ".($settings['counter'] == 1 ? $locale['global_170'] : $locale['global_171']);
}

function panelbutton($state, $bname) {
   if (isset($_COOKIE["fusion_box_".$bname])) {
      if ($_COOKIE["fusion_box_".$bname] == "none") {
         $state = "off";
      } else {
         $state = "on";
      }
   }
   return "<img src='".get_image("panel_".($state == "on" ? "off" : "on"))."' id='b_$bname' class='panelbutton' alt='' onclick=\"javascript:flipBox('$bname')\" />";
}

function panelstate($state, $bname) {
   if (isset($_COOKIE["fusion_box_".$bname])) {
      if ($_COOKIE["fusion_box_".$bname] == "none") {
         $state = "off";
      } else {
         $state = "on";
      }
   }
   return "<div id='box_$bname'".($state == "off" ? " style='display:none'" : "").">\n";
}

// v6 compatibility
function opensidex($title, $state = "on") {
	
	openside($title, true, $state);

}

function closesidex() {

	closeside();

}

function tablebreak() {
	return true;
}
?>