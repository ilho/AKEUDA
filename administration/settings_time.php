<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings_time.php
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
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("S2") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_POST['savesettings'])) {
	$error = 0;
	$result = dbquery("UPDATE ".DB_SETTINGS." SET
		shortdate='".$_POST['shortdate']."',
		longdate='".$_POST['longdate']."',
		forumdate='".$_POST['forumdate']."',
		subheaderdate='".$_POST['subheaderdate']."',
		timeoffset='".$_POST['timeoffset']."'
	");
	if (!$result) { $error = 1; }
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));
$offsetlist = "";
for ($i=-13;$i<17;$i++) {
	if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
	if ($offset == $settings2['timeoffset']) { $sel = " selected='selected'"; } else { $sel = ""; }
	$offsetlist .= "<option$sel>$offset</option>\n";
}

$timestamp = time()+($settings2['timeoffset']*3600);

$date_opts = "<option value=''>".$locale['455']."</option>\n";
$date_opts .= "<option value='%m/%d/%Y'>".strftime("%m/%d/%Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d/%m/%Y'>".strftime("%d/%m/%Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d-%m-%Y'>".strftime("%d-%m-%Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d.%m.%Y'>".strftime("%d.%m.%Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%m/%d/%Y %H:%M'>".strftime("%m/%d/%Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d/%m/%Y %H:%M'>".strftime("%d/%m/%Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d-%m-%Y %H:%M'>".strftime("%d-%m-%Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d.%m.%Y %H:%M'>".strftime("%d.%m.%Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%m/%d/%Y %H:%M:%S'>".strftime("%m/%d/%Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d/%m/%Y %H:%M:%S'>".strftime("%d/%m/%Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d-%m-%Y %H:%M:%S'>".strftime("%d-%m-%Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d.%m.%Y %H:%M:%S'>".strftime("%d.%m.%Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%B %d %Y'>".strftime("%B %d %Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d. %B %Y'>".strftime("%d. %B %Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d %B %Y'>".strftime("%d %B %Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%B %d %Y %H:%M'>".strftime("%B %d %Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d. %B %Y %H:%M'>".strftime("%d. %B %Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d %B %Y %H:%M'>".strftime("%d %B %Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%B %d %Y %H:%M:%S'>".strftime("%B %d %Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d. %B %Y %H:%M:%S'>".strftime("%d. %B %Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d %B %Y %H:%M:%S'>".strftime("%d %B %Y %H:%M:%S", $timestamp)."</option>\n";

opentable($locale['400']);
require_once ADMIN."settings_links.php";
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['451']."</td>\n";
echo "<td width='50%' class='tbl'><select name='shortdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setshortdate' value='>>' onclick=\"shortdate.value=shortdatetext.options[shortdatetext.selectedIndex].value;shortdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='shortdate' value='".$settings2['shortdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['452']."</td>\n";
echo "<td width='50%' class='tbl'><select name='longdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setlongdate' value='>>' onclick=\"longdate.value=longdatetext.options[longdatetext.selectedIndex].value;longdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='longdate' value='".$settings2['longdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['453']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forumdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setforumdate' value='>>' onclick=\"forumdate.value=forumdatetext.options[forumdatetext.selectedIndex].value;forumdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='forumdate' value='".$settings2['forumdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['454']."</td>\n";
echo "<td width='50%' class='tbl'><select name='subheaderdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setsubheaderdate' value='>>' onclick=\"subheaderdate.value=subheaderdatetext.options[subheaderdatetext.selectedIndex].value;subheaderdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='subheaderdate' value='".$settings2['subheaderdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['456']."</td>\n";
echo "<td width='50%' class='tbl'><select name='timeoffset' class='textbox' style='width:75px;'>\n".$offsetlist."</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
