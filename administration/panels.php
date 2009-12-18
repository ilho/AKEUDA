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
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/panels.php";

if (!checkrights("P") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['action']) && $_GET['action'] == "refresh") {
	$i = 1;
	$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='1' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".DB_PANELS." SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='2' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".DB_PANELS." SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='3' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".DB_PANELS." SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='4' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".DB_PANELS." SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
}
if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_id='".$_GET['panel_id']."'"));
	$result = dbquery("DELETE FROM ".DB_PANELS." WHERE panel_id='".$_GET['panel_id']."'");
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order-1 WHERE panel_side='$panel_side' AND panel_order>='".$data['panel_order']."'");
	redirect(FUSION_SELF.$aidlink);
}
if ((isset($_GET['action']) && $_GET['action'] == "setstatus") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_status='".intval($_GET['status'])."' WHERE panel_id='".$_GET['panel_id']."'");
}
if ((isset($_GET['action']) && $_GET['action'] == "mup") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='".intval($_GET['panel_side'])."' AND panel_order='".intval($_GET['order'])."'"));
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order+1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order-1 WHERE panel_id='".$_GET['panel_id']."'");
	redirect(FUSION_SELF.$aidlink);
}
if ((isset($_GET['action']) && $_GET['action'] == "mdown") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='".intval($_GET['panel_side'])."' AND panel_order='".intval($_GET['order'])."'"));
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order-1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order+1 WHERE panel_id='".$_GET['panel_id']."'");
	redirect(FUSION_SELF.$aidlink);
}
if ((isset($_GET['action']) && $_GET['action'] == "mleft") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
	$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='1' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_side='1', panel_order='$neworder' WHERE panel_id='".$_GET['panel_id']."'");
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order-1 WHERE panel_side='4' AND panel_order>='".intval($_GET['order'])."'");
	redirect(FUSION_SELF.$aidlink);
}
if ((isset($_GET['action']) && $_GET['action'] == "mright") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
	$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='4' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_side='4', panel_order='$neworder' WHERE panel_id='".$_GET['panel_id']."'");
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order-1 WHERE panel_side='1' AND panel_order>='".intval($_GET['order'])."'");
	redirect(FUSION_SELF.$aidlink);
}
if ((isset($_GET['action']) && $_GET['action'] == "mupper") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
	$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='2' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_side='2', panel_order='$neworder' WHERE panel_id='".$_GET['panel_id']."'");
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order-1 WHERE panel_side='3' AND panel_order>='".intval($_GET['order'])."'");
	redirect(FUSION_SELF.$aidlink);
}
if ((isset($_GET['action']) && $_GET['action'] == "mlower") && (isset($_GET['panel_id']) && isnum($_GET['panel_id']))) {
	$result = dbquery("SELECT * FROM ".DB_PANELS." WHERE panel_side='3' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_side='3', panel_order='$neworder' WHERE panel_id='".$_GET['panel_id']."'");
	$result = dbquery("UPDATE ".DB_PANELS." SET panel_order=panel_order-1 WHERE panel_side='2' AND panel_order>='".intval($_GET['order'])."'");
	redirect(FUSION_SELF.$aidlink);
}
opentable($locale['400']);
echo "<table cellpadding='0' cellspacing='1' width='80%' class='tbl-border center'>\n<tr>\n";
echo "<td class='tbl2'><strong>".$locale['401']."</strong></td>\n";
echo "<td align='center' width='1%' class='tbl2' colspan='2' style='white-space:nowrap'><strong>".$locale['402']."</strong></td>\n";
echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['403']."</strong></td>\n";
echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['404']."</strong></td>\n";
echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['405']."</strong></td>\n";
echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['406']."</strong></td>\n";
echo "</tr>\n";
$ps = 1; $i = 1; $k = 0;
$result = dbquery("SELECT * FROM ".DB_PANELS." ORDER BY panel_side,panel_order");
while ($data = dbarray($result)) {
	$row_color = ($k % 2 == 0 ? "tbl1" : "tbl2");
	$numrows = dbcount("(panel_id)", DB_PANELS, "panel_side='".$data['panel_side']."'");
	if ($ps != $data['panel_side']) { $ps = $data['panel_side']; $i = 1; }
	if ($numrows != 1) {
		$up = $data['panel_order'] - 1;
		$down = $data['panel_order'] + 1;
		if ($i == 1) {
			$up_down = " <a href='".FUSION_SELF.$aidlink."&amp;action=mdown&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."&amp;order=$down'><img src='".get_image("down")."' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;' /></a>";
		} else if ($i < $numrows) {
			$up_down = " <a href='".FUSION_SELF.$aidlink."&amp;action=mup&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."&amp;order=$up'><img src='".get_image("up")."' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;' /></a>\n";
			$up_down .= " <a href='".FUSION_SELF.$aidlink."&amp;action=mdown&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."&amp;order=$down'><img src='".get_image("down")."' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;' /></a>";
		} else {
			$up_down = " <a href='".FUSION_SELF.$aidlink."&amp;action=mup&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."&amp;order=$up'><img src='".get_image("up")."' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;' /></a>";
		}
	} else {
		$up_down = "";
	}
	echo "<tr>\n<td class='".$row_color."'>".$data['panel_name']."</td>\n";
	echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>";
	if ($data['panel_side'] == 1) { echo $locale['420'];
	} elseif ($data['panel_side'] == 2) { echo $locale['421'];
	} elseif ($data['panel_side'] == 3) { echo $locale['425'];
	} elseif ($data['panel_side'] == 4) { echo $locale['422']; }
	echo "</td>\n<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>";
	if ($data['panel_side'] == 1) {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=mright&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".get_image("right")."' alt='".$locale['442']."' title='".$locale['431']."' style='border:0px;' /></a>";
	} elseif ($data['panel_side'] == 2) {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=mlower&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".get_image("down")."' alt='".$locale['444']."' title='".$locale['446']."' style='border:0px;' /></a>";
	} elseif ($data['panel_side'] == 3) {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=mupper&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".get_image("up")."' alt='".$locale['443']."' title='".$locale['445']."' style='border:0px;' /></a>";
	} elseif ($data['panel_side'] == 4) {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=mleft&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".get_image("left")."' alt='".$locale['441']."' title='".$locale['430']."' style='border:0px;' /></a>";
	}
	echo "</td>\n<td width='1%' class='".$row_color."' style='white-space:nowrap'>".$data['panel_order']."$up_down</td>\n";
	echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>".($data['panel_type'] == "file" ? $locale['423'] : $locale['424'])."</td>\n";
	echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>".getgroupname($data['panel_access'])."</td>\n";
	echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>\n";
	echo "[<a href='panel_editor.php".$aidlink."&amp;action=edit&amp;panel_id=".$data['panel_id']."&amp;panel_side=1'>".$locale['434']."</a>]\n";
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF.$aidlink."&amp;action=setstatus&amp;status=1&amp;panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF.$aidlink."&amp;action=setstatus&amp;status=0&amp;panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	echo "[<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."' onclick=\"return confirm('".$locale['440']."');\">".$locale['437']."</a>]\n";
	echo "</td>\n</tr>\n";
	$i++; $k++;
}
echo "</table>\n";

echo "<div style='text-align:center;margin-top:5px'>[ <a href='panel_editor.php".$aidlink."'>".$locale['438']."</a> ]\n";
echo "[ <a href='".FUSION_SELF.$aidlink."&amp;action=refresh'>".$locale['439']."</a> ]</div>\n";

closetable();

require_once THEMES."templates/footer.php";
?>