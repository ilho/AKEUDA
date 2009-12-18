<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: index.php
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

if (!iADMIN || $userdata['user_rights'] == "" || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }
if (!isset($_GET['pagenum']) || !isnum($_GET['pagenum'])) $_GET['pagenum'] = 1;

$admin_images = true;

// Work out which tab is the active default
if ($page1) { $default = 1; }
elseif ($page2) { $default = 2; }
elseif ($page3) { $default = 3; }
elseif ($page4) { $default = 4; }

// Ensure the admin is allowed to access the selected page
$pageon = true;
if ($_GET['pagenum'] == 1 && !$page1) $pageon = false;
if ($_GET['pagenum'] == 2 && !$page2) $pageon = false;
if ($_GET['pagenum'] == 3 && !$page3) $pageon = false;
if ($_GET['pagenum'] == 4 && !$page4) $pageon = false;
if ($pageon == false) { redirect("index.php".$aidlink."&pagenum=$default"); }

// Display admin panels & pages
opentable($locale['200']." - v".$settings['version']);
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
if ($page1) {
	echo "<td align='center' width='25%' class='".($_GET['pagenum'] == 1 ? "tbl1" : "tbl2")."'><span class='small'>\n";
	echo ($_GET['pagenum'] == 1 ? "<strong>".$locale['ac01']."</strong>" : "<a href='index.php".$aidlink."&amp;pagenum=1'>".$locale['ac01']."</a>")."</span></td>\n";
} else {
	echo "<td align='center' width='25%' class='".($_GET['pagenum'] == 1 ? "tbl1" : "tbl2")."'><span class='small' style='text-decoration:line-through'>\n";
	echo $locale['ac01']."</span></td>\n";
}
if ($page2) {
	echo "<td align='center' width='25%' class='".($_GET['pagenum'] == 2 ? "tbl1" : "tbl2")."'><span class='small'>\n";
	echo ($_GET['pagenum'] == 2 ? "<strong>".$locale['ac02']."</strong>" : "<a href='index.php".$aidlink."&amp;pagenum=2'>".$locale['ac02']."</a>")."</span></td>\n";
} else {
	echo "<td align='center' width='25%' class='".($_GET['pagenum'] == 2 ? "tbl1" : "tbl2")."'><span class='small' style='text-decoration:line-through'>\n";
	echo $locale['ac02']."</span></td>\n";
}
if ($page3) {
	echo "<td align='center' width='25%' class='".($_GET['pagenum'] == 3 ? "tbl1" : "tbl2")."'><span class='small'>\n";
	echo ($_GET['pagenum'] == 3 ? "<strong>".$locale['ac03']."</strong>" : "<a href='index.php".$aidlink."&amp;pagenum=3'>".$locale['ac03']."</a>")."</span></td>\n";
} else {
	echo "<td align='center' width='25%' class='".($_GET['pagenum'] == 3 ? "tbl1" : "tbl2")."'><span class='small' style='text-decoration:line-through'>\n";
	echo $locale['ac03']."</span></td>\n";
}
if ($page4) {
	echo "<td align='center' width='25%' class='".($_GET['pagenum'] == 4 ? "tbl1" : "tbl2")."'><span class='small'>\n";
	echo ($_GET['pagenum'] == 4 ? "<strong>".$locale['ac04']."</strong>" : "<a href='index.php".$aidlink."&amp;pagenum=4'>".$locale['ac04']."</a>")."</span></td>\n";
} else {
	echo "<td align='center' width='25%' class='".($_GET['pagenum'] == 4 ? "tbl1" : "tbl2")."'><span class='small' style='text-decoration:line-through'>\n";
	echo $locale['ac04']."</span></td>\n";
}
echo "</tr>\n<tr>\n<td colspan='4' class='tbl1'>\n";
$result = dbquery("SELECT * FROM ".DB_ADMIN." WHERE admin_page='".$_GET['pagenum']."' ORDER BY admin_title");
$rows = dbrows($result);
if ($rows != 0) {
	$counter = 0; $columns = 4;
	$align = $admin_images ? "center" : "left";
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	while ($data = dbarray($result)) {
		if (checkrights($data['admin_rights']) && $data['admin_link'] != "reserved") {
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			echo "<td align='$align' width='25%' class='tbl'>";
			if ($admin_images) {
				echo "<span class='small'><a href='".$data['admin_link'].$aidlink."'><img src='".get_image("ac_".$data['admin_title'])."' alt='".$data['admin_title']."' style='border:0px;' /><br />\n".$data['admin_title']."</a></span>";
			} else {
				echo "<span class='small'>".THEME_BULLET." <a href='".$data['admin_link'].$aidlink."'>".$data['admin_title']."</a></span>";
			}
			echo "</td>\n";
			$counter++;
		}
	}
	echo "</tr>\n</table>\n";
}
echo "</td>\n</tr>\n</table>\n";
closetable();

opentable($locale['250']);
echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n<td valign='top' width='33%' class='small'>
".(checkrights("M") ? "<a href='".ADMIN."members.php".$aidlink."'>".$locale['251']."</a>" : $locale['251'])." ".dbcount("(user_id)", DB_USERS, "user_status<='1'")."<br />
".(checkrights("M") ? "<a href='".ADMIN."members.php".$aidlink."&amp;sortby=all&amp;status=2'>".$locale['252']."</a>" : $locale['252'])." ".dbcount("(user_id)", DB_USERS, "user_status='2'")."<br />
".(checkrights("M") ? "<a href='".ADMIN."members.php".$aidlink."&amp;sortby=all&amp;status=1'>".$locale['253']."</a>" : $locale['253'])." ".dbcount("(user_id)", DB_USERS, "user_status='1'")."
</td>\n<td valign='top' width='33%' class='small'>
".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#news_submissions'>".$locale['254']."</a>" : $locale['254'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='n'")."<br />
".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#article_submissions'>".$locale['255']."</a>" : $locale['255'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='a'")."<br />
".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#link_submissions'>".$locale['256']."</a>" : $locale['256'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='l'")."<br />
".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#photo_submissions'>".$locale['260']."</a>" : $locale['260'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='p'")."
</td>\n<td valign='top' width='33%' class='small'>
".$locale['257']." ".dbcount("(comment_id)", DB_COMMENTS)."<br />
".$locale['258']." ".dbcount("(shout_id)", DB_SHOUTBOX)."<br />
".$locale['259']." ".dbcount("(post_id)", DB_POSTS)."<br />
".$locale['261']." ".dbcount("(photo_id)", DB_PHOTOS)."
</td>\n</tr>\n</table>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
