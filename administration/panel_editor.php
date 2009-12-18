<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: panel_editor.php
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
include LOCALE.LOCALESET."admin/panels.php";

if (!checkrights("P") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

$temp = opendir(INFUSIONS);
while ($folder = readdir($temp)) {
	if (!in_array($folder, array(".","..")) && strstr($folder, "_panel")) {
		if (is_dir(INFUSIONS.$folder)) $panel_list[] = $folder;
	}
}
closedir($temp); sort($panel_list); array_unshift($panel_list, "none");

if (isset($_POST['save'])) {
	$error = "";
	$panel_name = stripinput($_POST['panel_name']);
	if ($panel_name == "") $error .= $locale['470']."<br />";
	if ($_POST['panel_filename'] == "none") {
		$panel_filename = "";
		$panel_content = addslash($_POST['panel_content']);
		$panel_type = "php";
	} else {
		$panel_filename = stripinput($_POST['panel_filename']);
		$panel_content = "";
		$panel_type = "file";
	}
	$admin_password = isset($_POST['admin_password']) ? $_POST['admin_password'] : "";
	$panel_side = isnum($_POST['panel_side']) ? $_POST['panel_side'] : "1";
	$panel_access = isnum($_POST['panel_access']) ? $_POST['panel_access'] : "0";
	if ($panel_side == "1" || $panel_side == "4") {
		$panel_display = "0";
	} else {
		$panel_display = isset($_POST['panel_display']) ? "1" : "0";
	}
	if (isset($_GET['panel_id']) && isnum($_GET['panel_id'])) {
		if ($panel_name) {
			$data = dbarray(dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_id='".$_GET['panel_id']."'"));
			if ($panel_name != $data['panel_name']) {
				$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_name='$panel_name'");
				if (dbrows($result)) { $error .= $locale['471']."<br />"; }
			}
		}
		if ($panel_type == "php" && $panel_content == "") { $error .= $locale['472']."<br />"; }
		if ((!isset($_COOKIE[COOKIE_PREFIX.'admin']) || md5($_COOKIE[COOKIE_PREFIX.'admin']) != $userdata['user_admin_password']) && md5(md5($admin_password)) != $userdata['user_admin_password']) {
			$error .= $locale['474']."<br />";
		}
		if (!$error) {
			$result = dbquery("UPDATE ".DB_PANELS." SET panel_name='$panel_name', panel_filename='$panel_filename', panel_content='$panel_content', panel_access='$panel_access', panel_display='$panel_display' WHERE panel_id='".$_GET['panel_id']."'");
		}
		opentable($locale['480']);
		echo "<div style='text-align:center'><br />\n";
		if ($error) {
			echo $locale['481']."<br /><br />\n".$error."<br />\n";
		} else {
			echo $locale['482']."<br /><br />\n";
		}
		echo "<a href='panels.php".$aidlink."'>".$locale['486']."</a><br /><br />\n";
		echo "<a href='index.php".$aidlink."'>".$locale['487']."</a><br /><br />\n";
		echo "</div>\n";
		closetable();
		if (!isset($_COOKIE[COOKIE_PREFIX.'admin']) && md5(md5($admin_password)) == $userdata['user_admin_password']) {
			setcookie(COOKIE_PREFIX."admin", md5($admin_password), time() + 3600, "/", "", "0");
		}
	} else {
		if ($panel_name) {
			$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_name='$panel_name'");
			if (dbrows($result) != 0) { $error .= $locale['471']."<br />"; }
		}
		if ($panel_type == "php" && $panel_content == "") { $error .= $locale['472']."<br />"; }
		if ($panel_type == "file" && $panel_filename == "none") { $error .= $locale['473']."<br />"; }
		if ((!isset($_COOKIE[COOKIE_PREFIX.'admin']) || md5($_COOKIE[COOKIE_PREFIX.'admin']) != $userdata['user_admin_password']) && md5(md5($admin_password)) != $userdata['user_admin_password']) {
			$error .= $locale['474']."<br />";
		}
		if (!$error) {
			$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='$panel_side' ORDER BY panel_order DESC LIMIT 1");
			if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
			$result = dbquery("INSERT INTO ".DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('$panel_name', '$panel_filename', '$panel_content', '$panel_side', '$neworder', '$panel_type', '$panel_access', '$panel_display', '0')");
		}
		opentable($locale['483']);
		echo "<div style='text-align:center'><br />\n";
		if ($error) {
			echo $locale['484']."<br /><br />\n".$error."<br />\n";
		} else {
			echo $locale['485']."<br /><br />\n";
		}
		echo "<a href='panels.php".$aidlink."'>".$locale['486']."</a><br /><br />\n";
		echo "<a href='index.php".$aidlink."'>".$locale['487']."</a><br /><br />\n";
		echo "</div>\n";
		closetable();
		if (!isset($_COOKIE[COOKIE_PREFIX.'admin']) && md5(md5($admin_password)) == $userdata['user_admin_password']) {
			setcookie(COOKIE_PREFIX."admin", md5($admin_password), time() + 3600, "/", "", "0");
		}
	}
} else {
	if (isset($_POST['preview'])) {
		$panel_name = stripinput($_POST['panel_name']);
		$panel_filename = $_POST['panel_filename'];
		$panel_content = isset($_POST['panel_content']) ? stripslash($_POST['panel_content']) : "";
		$admin_password = isset($_POST['admin_password']) ? $_POST['admin_password'] : "";
		$panel_side = $_POST['panel_side'];
		$panel_access = $_POST['panel_access'];
		$panelon = isset($_POST['panel_display']) ? " checked='checked'" : "";
		$panelopts = $_POST['panel_side'] == "1" || $_POST['panel_side'] == "4" ? " style='display:none'" : " style='display:block'";
		$panel_type = $panel_filename == "none" ? "php" : "file";
		if ((isset($_COOKIE[COOKIE_PREFIX.'admin']) && md5($_COOKIE[COOKIE_PREFIX.'admin']) == $userdata['user_admin_password']) || md5(md5($admin_password)) == $userdata['user_admin_password']) {
			opentable($panel_name);
			if ($panel_type == "file") {
				@include INFUSIONS.$panel_filename."/".$panel_filename.".php";
			} else {
				eval($panel_content);
			}
			$panel_content = phpentities($panel_content);
			closetable();
			if (!isset($_COOKIE[COOKIE_PREFIX.'admin']) && md5(md5($admin_password)) == $userdata['user_admin_password']) {
				setcookie(COOKIE_PREFIX."admin", md5($admin_password), time() + 3600, "/", "", "0");
			}
		} else {
			echo "<div class='admin-message'>".$locale['global_182']."</div>\n";
			$panel_content = phpentities($panel_content);
		}
	}
	if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
		$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_id='".$_GET['panel_id']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$panel_name = $data['panel_name'];
			$panel_filename = $data['panel_filename'];
			$panel_content = phpentities(stripslashes($data['panel_content']));
			$admin_password = "";
			$panel_type = $data['panel_type'];
			$panel_side = $data['panel_side'];
			$panel_access = $data['panel_access'];
			$panelon = $data['panel_display'] == "1" ? " checked='checked'" : "";
			$panelopts = $panel_side == "1" || $panel_side == "4" ? " style='display:none'" : " style='display:block'";
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
	if (isset($_GET['panel_id']) && isnum($_GET['panel_id'])) {
		$action = FUSION_SELF.$aidlink."&amp;panel_id=".$_GET['panel_id'];
		opentable($locale['450']);
	} else {
		if (!isset($_POST['preview'])) {
			$panel_name = "";
			$panel_filename = "";
			$panel_content = "openside(\"name\");\n"."  echo \"content\";\n"."closeside();";
			$admin_password = "";
			$panel_type = "";
			$panel_side = "";
			$panel_access = "";
			$panelon = "";
			$panelopts = " style='display:none'";
		}
		$action = FUSION_SELF.$aidlink;
		opentable($locale['451']);
	}
	$user_groups = getusergroups(); $access_opts = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($panel_access == $user_group['0'] ? " selected='selected'" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='editform' method='post' action='$action'>\n";
	echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
	echo "<td class='tbl'>".$locale['452']."</td>\n";
	echo "<td class='tbl'><input type='text' name='panel_name' value='$panel_name' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n";
	if (isset($_GET['panel_id']) && isnum($_GET['panel_id'])) {
		if ($panel_type == "file") {
			echo "<tr>\n<td class='tbl'>".$locale['453']."</td>\n";
			echo "<td class='tbl'><select name='panel_filename' class='textbox' style='width:200px;'>\n";
			for ($i=0;$i < count($panel_list);$i++) {
				echo "<option".($panel_filename == $panel_list[$i] ? " selected='selected'" : "").">".$panel_list[$i]."</option>\n";
			}
			echo "</select></td>\n</tr>\n";
		}
	} else {
		echo "<tr>\n<td class='tbl'>".$locale['453']."</td>\n";
		echo "<td class='tbl'><select name='panel_filename' class='textbox' style='width:200px;'>\n";
		for ($i=0;$i < count($panel_list);$i++) {
			echo "<option".($panel_filename == $panel_list[$i] ? " selected='selected'" : "").">".$panel_list[$i]."</option>\n";
		}
		echo "</select>&nbsp;&nbsp;<span class='small2'>".$locale['454']."</span></td>\n</tr>\n";
	}
	if (isset($_GET['panel_id']) && isnum($_GET['panel_id'])) {
		if ($panel_type == "php") {
			echo "<tr>\n<td valign='top' class='tbl'>".$locale['455']."</td>\n";
			echo "<td class='tbl'><textarea name='panel_content' cols='95' rows='15' class='textbox' style='width:98%'>".$panel_content."</textarea></td>\n";
			echo "</tr>\n";
		}
	} else {
		echo "<tr>\n<td valign='top' class='tbl'>".$locale['455']."</td>\n";
		echo "<td class='tbl'><textarea name='panel_content' cols='95' rows='15' class='textbox' style='width:98%'>".$panel_content."</textarea></td>\n";
		echo "</tr>\n";
	}
	if ((!isset($_COOKIE[COOKIE_PREFIX.'admin']) || md5($_COOKIE[COOKIE_PREFIX.'admin']) != $userdata['user_admin_password']) && md5(md5($admin_password)) != $userdata['user_admin_password']) {
		echo "<tr>\n<td class='tbl'>".$locale['456']."</td>\n";
		echo "<td class='tbl'><input type='password' name='admin_password' value='".$admin_password."' class='textbox' style='width:150px;' /></td>\n";
		echo "</tr>\n";
	}
	if (!isset($_GET['panel_id']) || !isnum($_GET['panel_id'])) {
		echo "<tr>\n<td class='tbl'>".$locale['457']."</td>\n";
		echo "<td class='tbl'><select name='panel_side' class='textbox' style='width:150px;' onchange=\"showopts(this.options[this.selectedIndex].value);\">\n";
		echo "<option value='1'".($panel_side == "1" ? " selected='selected'" : "").">".$locale['420']."</option>\n";
		echo "<option value='2'".($panel_side == "2" ? " selected='selected'" : "").">".$locale['421']."</option>\n";
		echo "<option value='3'".($panel_side == "3" ? " selected='selected'" : "").">".$locale['425']."</option>\n";
		echo "<option value='4'".($panel_side == "4" ? " selected='selected'" : "").">".$locale['422']."</option>\n";
		echo "</select></td>\n</tr>\n";
	}
	echo "<tr>\n<td class='tbl'>".$locale['458']."</td>\n";
	echo "<td class='tbl'><select name='panel_access' class='textbox' style='width:150px;'>\n".$access_opts."</select></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'>\n";
	echo "<div id='panelopts'".$panelopts."><input type='checkbox' id='panel_display' name='panel_display' value='1'".$panelon." /> ".$locale['459']."</div>\n";
	echo "<br />\n";
	if (isset($_GET['panel_id']) && isnum($_GET['panel_id'])) {
		if ($panel_type == "php") {
			echo "<input type='hidden' name='panel_filename' value='none' />\n";
		}
		echo "<input type='hidden' name='panel_side' value='".$panel_side."' />\n";
	}
	echo "<input type='submit' name='preview' value='".$locale['460']."' class='button' />\n";
	echo "<input type='submit' name='save' value='".$locale['461']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();
}

echo "<script type='text/javascript'>
	function showopts(panelside) {
		var panelopts = document.getElementById('panelopts');
		var paneldisplay = document.getElementById('panel_display');
		if (panelside == 1 || panelside == 4) {
			panelopts.style.display = 'none';
			paneldisplay.checked = false;
		} else {
			panelopts.style.display = 'block';
		}
	}
</script>\n";

require_once THEMES."templates/footer.php";
?>
