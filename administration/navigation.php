<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: navigation.php
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

include LOCALE.LOCALESET."admin/main.php";

include INFUSIONS."user_info_panel/user_info_panel.php";

@list($title) = dbarraynum(dbquery("SELECT admin_title FROM ".DB_ADMIN." WHERE admin_link='".FUSION_SELF."'"));

add_to_title($locale['global_200'].$locale['global_123'].($title ? $locale['global_201'].$title : ""));

openside($locale['global_001']);
$page1 = false; $page2 = false; $page3 = false; $page4 = false; $index_link = false;
for ($i = 1; $i < 5; $i++) {
	$admin_nav_opts = "";
	$result = dbquery("SELECT * FROM ".DB_ADMIN." WHERE admin_page='".$i."' AND admin_link!='reserved' ORDER BY admin_title ASC");
	$rows = dbrows($result);
	if ($rows != 0) {
		while ($data = dbarray($result)) {
			if (checkrights($data['admin_rights'])) {
				if ($i == 1) { $page1 = true; } elseif ($i == 2) { $page2 = true; }
				elseif ($i == 3) { $page3 = true; } elseif ($i == 4) { $page4 = true; }
				if (($page1 || $page2 || $page3 || $page4) && !$index_link) {
					echo THEME_BULLET." <a href='".ADMIN."index.php".$aidlink."' class='side'>".$locale['ac00']."</a>\n";
					echo "<hr class='side-hr' />\n";
					$index_link = true;
				}
				$admin_nav_opts .= "<option value='".ADMIN.$data['admin_link'].$aidlink."'>".preg_replace("/&(?!(#\d+|\w+);)/", "&amp;", $data['admin_title'])."</option>\n";
			}
		}
	}
	if ($admin_nav_opts != "") {
		echo "<form action='".FUSION_SELF."'>\n";
		echo "<select onchange='window.location.href=this.value' style='width:100%;' class='textbox'>\n";
		echo "<option value='".FUSION_SELF."' style='font-style:italic;' selected='selected'>".$locale['ac0'.$i]."</option>\n";
		echo $admin_nav_opts."</select>\n</form>\n";
	}
}

if ($page1 || $page2 || $page3 || $page4) { echo "<hr class='side-hr' />\n"; }
echo THEME_BULLET." <a href='".BASEDIR."index.php' class='side'>".$locale['global_181']."</a>\n";
closeside();
?>