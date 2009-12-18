<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: panels.php
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
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }

// Calculate current true url
$script_url = explode("/", $_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY : ""));
$url_count = count($script_url);
$base_url_count = substr_count(BASEDIR, "/") + 1;
$start_page = "";
while ($base_url_count != 0) {
	$current = $url_count - $base_url_count;
	$start_page .= "/".$script_url[$current];
	$base_url_count--;
}

define("START_PAGE", substr(preg_replace("#(&amp;|\?)(s_action=edit&amp;shout_id=)([0-9]+)#s", "", $start_page), 1));

// Side & center panels
ob_start();
$plc = 0;

// Render left panels
if(!defined("ADMIN_PANEL")){
	if (check_panel_status("left")) {
		$p_res = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='1' AND panel_status='1' ORDER BY panel_order");
		if (dbrows($p_res)) {
			while ($p_data = dbarray($p_res)) {
				if (checkgroup($p_data['panel_access'])) {
					if ($p_data['panel_type'] == "file") {
						$panel_name = $p_data['panel_filename'];
						include INFUSIONS.$panel_name."/".$panel_name.".php";
					} else {
						eval(stripslashes($p_data['panel_content']));
					}
					$plc++;
				}
			}
		}
	}
}else{
	require_once ADMIN."navigation.php";
}

define("LEFT", ob_get_contents());
ob_end_clean();

// Render right panels
$prc = 0;
ob_start();
if(!defined("ADMIN_PANEL")){
	if (check_panel_status("right")) {
		$p_res = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='4' AND panel_status='1' ORDER BY panel_order");
		if (dbrows($p_res)) {
			while ($p_data = dbarray($p_res)) {
				if (checkgroup($p_data['panel_access'])) {
					if ($p_data['panel_type'] == "file") {
						$panel_name = $p_data['panel_filename'];
						include INFUSIONS.$panel_name."/".$panel_name.".php";
					} else {
						eval(stripslashes($p_data['panel_content']));
					}
					$prc++;
				}
			}
		}
	}
}
define("RIGHT", ob_get_contents());
ob_end_clean();

// Set the require div-width class
if(defined("ADMIN_PANEL")){
	$main_style = "side-left";
}elseif ($plc && $prc) {
	$main_style = "side-both";
} elseif ($plc && !$prc) {
	$main_style = "side-left";
} elseif (!$plc && $prc) {
	$main_style = "side-right";
} elseif (!$plc && !$prc) {
	$main_style = "";
}

// Render upper center panels	
ob_start();

if(!defined("ADMIN_PANEL")){
	echo "<a id='content' name='content'></a>\n";
	if (iADMIN && $settings['maintenance']) {
		echo "<div class='admin-message'>".$locale['global_190']."</div>";
	}
	if (iSUPERADMIN && file_exists(BASEDIR."setup.php")) {
		echo "<div class='admin-message'>".$locale['global_198']."</div>";
	}
	if (iADMIN && !$userdata['user_admin_password']) {
		echo "<div class='admin-message'>".$locale['global_199']."</div>";
	}
	if (check_panel_status("upper")) {
		$p_res = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='2' AND panel_status='1' ORDER BY panel_order");
		if (dbrows($p_res)) {
			while ($p_data = dbarray($p_res)) {
				if (checkgroup($p_data['panel_access'])) {
					if ($p_data['panel_display'] == 1 || $settings['opening_page'] == START_PAGE) {
						if ($p_data['panel_type'] == "file") {
							$panel_name = $p_data['panel_filename'];
							include INFUSIONS.$panel_name."/".$panel_name.".php";
						} else {
							eval(stripslashes($p_data['panel_content']));
						}
					}
				}
			}
		}
	}
}
define("U_CENTER", ob_get_contents());
ob_end_clean();

// Render lower center panels
ob_start();

if(!defined("ADMIN_PANEL")){
	if (check_panel_status("lower")) {
		$p_res = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='3' AND panel_status='1' ORDER BY panel_order");
		if (dbrows($p_res) != 0) {
			while ($p_data = dbarray($p_res)) {
				if (checkgroup($p_data['panel_access'])) {
					if ($p_data['panel_display'] == 1 || $settings['opening_page'] == START_PAGE) {
						if ($p_data['panel_type'] == "file") {
							$panel_name = $p_data['panel_filename'];
							include INFUSIONS.$panel_name."/".$panel_name.".php";
						} else {
							eval(stripslashes($p_data['panel_content']));
						}
					}
				}
			}
		}
	}
}
define("L_CENTER", ob_get_contents());
ob_end_clean();
?>