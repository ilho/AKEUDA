<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_offset_include.php
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
	$offset_list = "";
	for ($i = -13; $i < 17; $i++) {
		if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
		$offset_list .= "<option".(isset($user_data['user_offset']) && $user_data['user_offset'] == $offset ? " selected='selected'" : "").">".$offset."</option>\n";
	}
	echo "<tr>\n";
	echo "<td class='tbl'>".$locale['u009'].":</td>\n";
	echo "<td class='tbl'><select name='user_offset' class='textbox' style='width:100px;'>\n".$offset_list."</select></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_offset";
	$db_values .= ", '".(isset($_POST['user_offset']) ? (is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0") : "0")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_offset='".(isset($_POST['user_offset']) ? (is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0") : "0")."'";
}
?>
