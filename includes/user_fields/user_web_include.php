<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_web_include.php
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
	echo "<tr>\n";
	echo "<td class='tbl'>".$locale['uf_web'].":</td>\n";
	echo "<td class='tbl'><input type='text' name='user_web' value='".(isset($user_data['user_web']) ? $user_data['user_web'] : "")."' maxlength='50' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	if ($user_data['user_web']) {
		echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_web']."</td>\n";
		echo "<td align='right' class='tbl1'>";
		$urlprefix = !strstr($user_data['user_web'], "http://") ? "http://" : "";
		echo "<a href='".$urlprefix.$user_data['user_web']."' title='".$urlprefix.$user_data['user_web']."' target='_blank'>".$locale['uf_web_001']."</a>\n";
		echo "</td>\n</tr>\n";
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_web";
	$db_values .= ", '".(isset($_POST['user_web']) ? stripinput(trim($_POST['user_web'])) : "")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_web='".(isset($_POST['user_web']) ? stripinput(trim($_POST['user_web'])) : "")."'";
}
?>