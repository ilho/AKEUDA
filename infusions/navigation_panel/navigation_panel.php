<?php	
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: navigation_panel.php
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

openside($locale['global_001']);
$result = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_position<='2' ORDER BY link_order");
if (dbrows($result)) {
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			if ($data['link_name'] != "---" && $data['link_url'] == "---") {
				echo "<div class='side-label'><strong>".$data['link_name']."</strong></div>\n";
			} else if ($data['link_name'] == "---" && $data['link_url'] == "---") {
				echo "<hr class='side-hr' />\n";
			} else {
				$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
				if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
					echo THEME_BULLET." <a href='".$data['link_url']."'".$link_target." class='side'>".$data['link_name']."</a><br />\n";
				} else {
					echo THEME_BULLET." <a href='".BASEDIR.$data['link_url']."'".$link_target." class='side'>".$data['link_name']."</a><br />\n";
				}
			}
		}
	}
} else {
	echo $locale['global_002'];
}
closeside();
?>