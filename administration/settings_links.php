<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings_links.php
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

echo "<table cellpadding='0' cellspacing='1' class='tbl-border center'>\n<tr>\n";
echo "<td class='".(preg_match("/settings_main.php/i", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_main.php".$aidlink."'>".$locale['401']."</a></span></td>\n";
echo "<td class='".(preg_match("/settings_time.php/i", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_time.php".$aidlink."'>".$locale['450']."</a></span></td>\n";
echo "<td class='".(preg_match("/settings_forum.php/i", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_forum.php".$aidlink."'>".$locale['500']."</a></span></td>\n";
echo "<td class='".(preg_match("/settings_registration.php/i", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_registration.php".$aidlink."'>".$locale['550']."</a></span></td>\n";
echo "<td class='".(preg_match("/settings_photo.php/i", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_photo.php".$aidlink."'>".$locale['600']."</a></span></td>\n";
echo "<td class='".(preg_match("/settings_misc.php/i", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_misc.php".$aidlink."'>".$locale['650']."</a></span></td>\n";
echo "<td class='".(preg_match("/settings_messages.php/i", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_messages.php".$aidlink."'>".$locale['700']."</a></span></td>\n";
echo "</tr>\n";
if (isset($_GET['error']) && isNum($_GET['error'])) { 
	echo "<tr><td class='tbl1' colspan='7'><div class='admin-message'>".(!$_GET['error'] ? $locale['900'] : $locale['901'])."</div></td></tr>\n";
}
echo "</table>\n<br />\n";
?>
