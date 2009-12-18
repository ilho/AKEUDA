<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_sig_include.php
| Author: Digitanium
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

if ($profile_method == "input") {
	require_once INCLUDES."bbcode_include.php";
	echo "<tr>\n";
	echo "<td valign='top' class='tbl'>".$locale['uf_sig']."</td>\n";
	echo "<td class='tbl'><textarea name='user_sig' cols='60' rows='5' class='textbox' style='width:295px'>".(isset($user_data['user_sig']) ? $user_data['user_sig'] : "")."</textarea><br />\n";
	echo display_bbcodes("300px", "user_sig", "inputform", "smiley|b|i|u||center|small|url|mail|img|color")."</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	// Not shown in profile
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_sig";
	$db_values .= ", '".(isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_sig='".(isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "")."'";
}
?>