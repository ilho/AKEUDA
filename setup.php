<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: setup.php
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
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));

if (isset($_POST['localeset']) && file_exists("locale/".$_POST['localeset']) && is_dir("locale/".$_POST['localeset'])) {
	include "locale/".$_POST['localeset']."/setup.php";
} else {
	$_POST['localeset'] = "English";
	include "locale/English/setup.php";
}

if (isset($_POST['step']) && $_POST['step'] == "7") {
	header("Location: index.php");
}

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<html>\n<head>\n";
echo "<title>".$locale['title']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>\n";
echo "<link rel='stylesheet' href='themes/templates/setup_styles.css' type='text/css' />\n";
echo "</head>\n<body>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td class='full-header'><img src='images/php-fusion-logo.png'></td>\n";
echo "</tr>\n</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td class='sub-header'>".$locale['sub-title']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td colspan='2' style='height:10px;background-color:#f6a504;'></td>\n";
echo "</tr>\n</table>\n";

echo "<br><br>\n";

echo "<form name='setupform' method='post' action='setup.php'>\n";
echo "<table align='center' cellpadding='0' cellspacing='1' width='450' class='tbl-border'>\n<tr>\n";
echo "<td class='tbl2'><strong>";

if (!isset($_POST['step']) || $_POST['step'] == "" || $_POST['step'] == "1") {
	echo $locale['001'];
} elseif (isset($_POST['step']) && $_POST['step'] == "2") {
	echo $locale['002'];
} elseif (isset($_POST['step']) && $_POST['step'] == "3") {
	echo $locale['003'];
} elseif (isset($_POST['step']) && $_POST['step'] == "4") {
	echo $locale['004'];
} elseif (isset($_POST['step']) && $_POST['step'] == "5") {
	echo $locale['005'];
} elseif (isset($_POST['step']) && $_POST['step'] == "6") {
	echo $locale['006'];
}

echo "</strong></td>\n</tr>\n<tr>\n<td class='tbl1'style='text-align:center'>\n";

if (!isset($_POST['step']) || $_POST['step'] == "" || $_POST['step'] == "1") {
	$locale_files = makefilelist("locale/", ".|..", true, "folders");
	$locale_list = makefileopts($locale_files);
	echo $locale['010']."<br><br>";
	echo "<select name='localeset' class='textbox' style='margin-top:5px'>\n";
	echo $locale_list."</select><br><br>\n";
	echo $locale['011']."\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='step' value='2'>\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
}

if (isset($_POST['step']) && $_POST['step'] == "2") {
	if (is_writable("administration/db_backups") && is_writable("images") && is_writable("images/articles") && is_writable("images/avatars") && is_writable("images/news") && is_writable("images/news_cats") && is_writable("images/photoalbum") && is_writable("images/photoalbum/submissions") && is_writable("forum/attachments") && is_writable("config.php")) {
		$write_check = true;
	} else {
		$write_check = false;
	}
	echo $locale['020']."<br><br>\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<tr>\n<td class='tbl1'>administration/db_backups</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("administration/db_backups") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/imagelist.js</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/imagelist.js") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/articles</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/articles") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/avatars</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/avatars") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/news</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/news") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/news_cats</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/news_cats") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/photoalbum</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/photoalbum") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/photoalbum/submissions</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/photoalbum/submissions") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>forum/attachments</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("forum/attachments") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>config.php</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("config.php") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "</table><br><br>\n";
	if ($write_check) {
		echo $locale['021']."\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
		echo "<input type='hidden' name='step' value='3'>\n";
		echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
	} else {
		echo $locale['022']."\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
		echo "<input type='hidden' name='step' value='1'>\n";
		echo "<input type='submit' name='next' value='".$locale['008']."' class='button'>\n";
	}
}

if (isset($_POST['step']) && $_POST['step'] == "3") {
	echo $locale['030']."<br><br>\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='tbl1'>".$locale['031']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='localhost' name='db_host' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['032']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='' name='db_user' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['033']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' value='' name='db_pass' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['034']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='' name='db_name' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['035']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='fusion_' name='db_prefix' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "</table>\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
	echo "<input type='hidden' name='step' value='4'>\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
}

if (isset($_POST['step']) && $_POST['step'] == "4") {
	$db_host = stripinput(trim($_POST['db_host']));
	$db_user = stripinput(trim($_POST['db_user']));
	$db_pass = stripinput(trim($_POST['db_pass']));
	$db_name = stripinput(trim($_POST['db_name']));
	$db_prefix = stripinput(trim($_POST['db_prefix']));
	if ($link = dbconnect($db_host, $db_user, $db_pass, $db_name)) {
		$config = "<?php\n";
		$config .= "// database settings\n";
		$config .= "$"."db_host = "."\"".$db_host."\";\n";
		$config .= "$"."db_user = "."\"".$db_user."\";\n";
		$config .= "$"."db_pass = "."\"".$db_pass."\";\n";
		$config .= "$"."db_name = "."\"".$db_name."\";\n";
		$config .= "$"."db_prefix = "."\"".$db_prefix."\";\n";
		$config .= "define("."\""."DB_PREFIX"."\"".", "."\"".$db_prefix."\");\n";
		$config .= "?>";
		$temp = fopen("config.php","w");
		if (fwrite($temp, $config)) {
			fclose($temp);
			$fail = false;
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."admin");
			$result = dbquery("CREATE TABLE ".$db_prefix."admin (
			admin_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			admin_rights CHAR(4) NOT NULL DEFAULT '',
			admin_image VARCHAR(50) NOT NULL DEFAULT '',
			admin_title VARCHAR(50) NOT NULL DEFAULT '',
			admin_link VARCHAR(100) NOT NULL DEFAULT 'reserved',
			admin_page TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			PRIMARY KEY (admin_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."articles");
			$result = dbquery("CREATE TABLE ".$db_prefix."articles (
			article_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			article_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			article_subject VARCHAR(200) NOT NULL DEFAULT '',
			article_snippet TEXT NOT NULL,
			article_article TEXT NOT NULL,
			article_draft TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			article_breaks CHAR(1) NOT NULL DEFAULT '',
			article_name MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
			article_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			article_reads MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			article_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			article_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			PRIMARY KEY (article_id),
			KEY article_datestamp (article_datestamp),
			KEY article_reads (article_reads)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."article_cats");
			$result = dbquery("CREATE TABLE ".$db_prefix."article_cats (
			article_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			article_cat_name VARCHAR(100) NOT NULL DEFAULT '',
			article_cat_description VARCHAR(200) NOT NULL DEFAULT '',
			article_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'article_subject ASC',
			article_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (article_cat_id)
			) TYPE=MyISAM;");
		
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."bbcodes");
			$result = dbquery("CREATE TABLE ".$db_prefix."bbcodes (
			bbcode_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			bbcode_name VARCHAR(20) NOT NULL DEFAULT '',
			bbcode_order SMALLINT(5) UNSIGNED NOT NULL,
			PRIMARY KEY (bbcode_id),
			KEY bbcode_order (bbcode_order)
			) TYPE=MyISAM;");
		
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."blacklist");
			$result = dbquery("CREATE TABLE ".$db_prefix."blacklist (
			blacklist_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			blacklist_ip VARCHAR(20) NOT NULL DEFAULT '',
			blacklist_email VARCHAR(100) NOT NULL DEFAULT '',
			blacklist_reason TEXT NOT NULL,
			PRIMARY KEY (blacklist_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."captcha");
			$result = dbquery("CREATE TABLE ".$db_prefix."captcha (
			captcha_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			captcha_ip VARCHAR(20) NOT NULL,
			captcha_encode VARCHAR(32) NOT NULL DEFAULT '',
			captcha_string VARCHAR(15) NOT NULL DEFAULT '',
			KEY captcha_datestamp (captcha_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."comments");
			$result = dbquery("CREATE TABLE ".$db_prefix."comments (
			comment_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			comment_item_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			comment_type CHAR(2) NOT NULL DEFAULT '',
			comment_name VARCHAR(50) NOT NULL DEFAULT '',
			comment_message TEXT NOT NULL,
			comment_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			comment_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			PRIMARY KEY (comment_id),
			KEY comment_datestamp (comment_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."custom_pages");
			$result = dbquery("CREATE TABLE ".$db_prefix."custom_pages (
			page_id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
			page_title VARCHAR(200) NOT NULL DEFAULT '',
			page_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			page_content TEXT NOT NULL,
			page_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			page_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (page_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."download_cats");
			$result = dbquery("CREATE TABLE ".$db_prefix."download_cats (
			download_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			download_cat_name VARCHAR(100) NOT NULL DEFAULT '',
			download_cat_description TEXT NOT NULL,
			download_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'download_title ASC',
			download_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (download_cat_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."downloads");
			$result = dbquery("CREATE TABLE ".$db_prefix."downloads (
			download_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			download_title VARCHAR(100) NOT NULL DEFAULT '',
			download_description TEXT NOT NULL,
			download_url VARCHAR(200) NOT NULL DEFAULT '',
			download_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			download_license VARCHAR(50) NOT NULL DEFAULT '',
			download_os VARCHAR(50) NOT NULL DEFAULT '',
			download_version VARCHAR(20) NOT NULL DEFAULT '',
			download_filesize VARCHAR(20) NOT NULL DEFAULT '',
			download_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			download_count INT(10) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (download_id),
			KEY download_datestamp (download_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."faq_cats");
			$result = dbquery("CREATE TABLE ".$db_prefix."faq_cats (
			faq_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			faq_cat_name VARCHAR(200) NOT NULL DEFAULT '',
			faq_cat_description VARCHAR(250) NOT NULL DEFAULT '',
			PRIMARY KEY(faq_cat_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."faqs");
			$result = dbquery("CREATE TABLE ".$db_prefix."faqs (
			faq_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			faq_cat_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			faq_question VARCHAR(200) NOT NULL DEFAULT '',
			faq_answer TEXT NOT NULL,
			PRIMARY KEY(faq_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."flood_control");
			$result = dbquery("CREATE TABLE ".$db_prefix."flood_control (
			flood_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			flood_timestamp INT(5) UNSIGNED NOT NULL DEFAULT '0',
			KEY flood_timestamp (flood_timestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_attachments");
			$result = dbquery("CREATE TABLE ".$db_prefix."forum_attachments (
			attach_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			post_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			attach_name VARCHAR(100) NOT NULL DEFAULT '',
			attach_ext VARCHAR(5) NOT NULL DEFAULT '',
			attach_size INT(20) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (attach_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_ranks");
			$result = dbquery("CREATE TABLE ".$db_prefix."forum_ranks (
			rank_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			rank_title VARCHAR(100) NOT NULL DEFAULT '',
			rank_image VARCHAR(100) NOT NULL DEFAULT '',
			rank_posts iNT(10) UNSIGNED NOT NULL DEFAULT '0',
			rank_apply SMALLINT(5) UNSIGNED NOT NULL DEFAULT '101',
			PRIMARY KEY (rank_id)
			) TYPE=MyISAM;");

			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_options");
			$result = dbquery("CREATE TABLE ".$db_prefix."forum_poll_options (
			thread_id MEDIUMINT(8) unsigned NOT NULL,
			forum_poll_option_id SMALLINT(5) UNSIGNED NOT NULL,
			forum_poll_option_text VARCHAR(150) NOT NULL,
			forum_poll_option_votes SMALLINT(5) UNSIGNED NOT NULL,
			KEY thread_id (thread_id)
			) TYPE=MyISAM;");
		
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_voters");
			$result = dbquery("CREATE TABLE ".$db_prefix."forum_poll_voters (
			thread_id MEDIUMINT(8) UNSIGNED NOT NULL,
			forum_vote_user_id MEDIUMINT(8) UNSIGNED NOT NULL,
			forum_vote_user_ip VARCHAR(20) NOT NULL,
			KEY thread_id (thread_id,forum_vote_user_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_polls");
			$result = dbquery("CREATE TABLE ".$db_prefix."forum_polls (
			thread_id MEDIUMINT(8) UNSIGNED NOT NULL,
			forum_poll_title VARCHAR(250) NOT NULL,
			forum_poll_start INT(10) UNSIGNED DEFAULT NULL,
			forum_poll_length iNT(10) UNSIGNED NOT NULL,
			forum_poll_votes SMALLINT(5) unsigned NOT NULL,
			KEY thread_id (thread_id)
			) TYPE=MyISAM;");
				
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forums");
			$result = dbquery("CREATE TABLE ".$db_prefix."forums (
			forum_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			forum_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			forum_name VARCHAR(50) NOT NULL DEFAULT '',
			forum_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			forum_description TEXT NOT NULL,
			forum_moderators TEXT NOT NULL,
			forum_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			forum_post SMALLINT(3) UNSIGNED DEFAULT '101',
			forum_reply SMALLINT(3) UNSIGNED DEFAULT '101',
			forum_poll SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
			forum_vote SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
			forum_attach SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
			forum_lastpost INT(10) UNSIGNED NOT NULL DEFAULT '0',
			forum_postcount MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			forum_threadcount MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			forum_lastuser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (forum_id),
			KEY forum_order (forum_order),
			KEY forum_lastpost (forum_lastpost),
			KEY forum_postcount (forum_postcount),
			KEY forum_threadcount (forum_threadcount)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."infusions");
			$result = dbquery("CREATE TABLE ".$db_prefix."infusions (
			inf_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			inf_title VARCHAR(100) NOT NULL DEFAULT '',
			inf_folder VARCHAR(100) NOT NULL DEFAULT '',
			inf_version VARCHAR(10) NOT NULL DEFAULT '0',
			PRIMARY KEY (inf_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."messages");
			$result = dbquery("CREATE TABLE ".$db_prefix."messages (
			message_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			message_to MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			message_from MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			message_subject VARCHAR(100) NOT NULL DEFAULT '',
			message_message TEXT NOT NULL,
			message_smileys CHAR(1) NOT NULL DEFAULT '',
			message_read TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			message_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			message_folder TINYINT(1) UNSIGNED NOT NULL DEFAULT  '0',
			PRIMARY KEY (message_id),
			KEY message_datestamp (message_datestamp)
			) TYPE=MyISAM;");
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."messages_options");
			$result = dbquery("CREATE TABLE ".$db_prefix."messages_options (
			user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			pm_email_notify tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
			pm_save_sent tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
			pm_inbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			pm_savebox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			pm_sentbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY (user_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news");
			$result = dbquery("CREATE TABLE ".$db_prefix."news (
			news_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			news_subject VARCHAR(200) NOT NULL DEFAULT '',
			news_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			news_news TEXT NOT NULL,
			news_extended TEXT NOT NULL,
			news_breaks CHAR(1) NOT NULL DEFAULT '',
			news_name MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
			news_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			news_start INT(10) UNSIGNED NOT NULL DEFAULT '0',
			news_end INT(10) UNSIGNED NOT NULL DEFAULT '0',
			news_visibility TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			news_reads INT(10) UNSIGNED NOT NULL DEFAULT '0',
			news_draft TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			news_sticky TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			news_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			news_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			PRIMARY KEY (news_id),
			KEY news_datestamp (news_datestamp),
			KEY news_reads (news_reads)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news_cats");
			$result = dbquery("CREATE TABLE ".$db_prefix."news_cats (
			news_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			news_cat_name VARCHAR(100) NOT NULL DEFAULT '',
			news_cat_image VARCHAR(100) NOT NULL DEFAULT '',
			PRIMARY KEY (news_cat_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."new_users");
			$result = dbquery("CREATE TABLE ".$db_prefix."new_users (
			user_code VARCHAR(32) NOT NULL,
			user_email VARCHAR(100) NOT NULL,
			user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
			user_info TEXT NOT NULL,
			KEY user_datestamp (user_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."ratings");
			$result = dbquery("CREATE TABLE ".$db_prefix."ratings (
			rating_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			rating_item_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			rating_type CHAR(1) NOT NULL DEFAULT '',
			rating_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			rating_vote TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			rating_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			rating_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			PRIMARY KEY (rating_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."online");
			$result = dbquery("CREATE TABLE ".$db_prefix."online (
			online_user VARCHAR(50) NOT NULL DEFAULT '',
			online_ip VARCHAR(20) NOT NULL DEFAULT '',
			online_lastactive INT(10) UNSIGNED NOT NULL DEFAULT '0'
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."panels");
			$result = dbquery("CREATE TABLE ".$db_prefix."panels (
			panel_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			panel_name VARCHAR(100) NOT NULL DEFAULT '',
			panel_filename VARCHAR(100) NOT NULL DEFAULT '',
			panel_content TEXT NOT NULL,
			panel_side TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			panel_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			panel_type VARCHAR(20) NOT NULL DEFAULT '',
			panel_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			panel_display TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			panel_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (panel_id),
			KEY panel_order (panel_order)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
						
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."photo_albums");
			$result = dbquery("CREATE TABLE ".$db_prefix."photo_albums (
			album_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			album_title VARCHAR(100) NOT NULL DEFAULT '',
			album_description TEXT NOT NULL,
			album_thumb VARCHAR(100) NOT NULL DEFAULT '',
			album_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			album_access SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			album_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			album_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (album_id),
			KEY album_order (album_order),
			KEY album_datestamp (album_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
						
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."photos");
			$result = dbquery("CREATE TABLE ".$db_prefix."photos (
			photo_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			album_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			photo_title VARCHAR(100) NOT NULL DEFAULT '',
			photo_description TEXT NOT NULL,
			photo_filename VARCHAR(100) NOT NULL DEFAULT '',
			photo_thumb1 VARCHAR(100) NOT NULL DEFAULT '',
			photo_thumb2 VARCHAR(100) NOT NULL DEFAULT '',
			photo_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			photo_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			photo_views INT(10) UNSIGNED NOT NULL DEFAULT '0',
			photo_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			photo_allow_comments tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
			photo_allow_ratings tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
			PRIMARY KEY (photo_id),
			KEY photo_order (photo_order),
			KEY photo_datestamp (photo_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."votes");
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."poll_votes");
			$result = dbquery("CREATE TABLE ".$db_prefix."poll_votes (
			vote_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			vote_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			vote_opt SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
			poll_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (vote_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."polls");
			$result = dbquery("CREATE TABLE ".$db_prefix."polls (
			poll_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			poll_title VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_0 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_1 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_2 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_3 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_4 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_5 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_6 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_7 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_8 VARCHAR(200) NOT NULL DEFAULT '',
			poll_opt_9 VARCHAR(200) NOT NULL DEFAULT '',
			poll_started INT(10) UNSIGNED NOT NULL DEFAULT '0',
			poll_ended INT(10) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (poll_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."posts");
			$result = dbquery("CREATE TABLE ".$db_prefix."posts (
			forum_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			post_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			post_message TEXT NOT NULL,
			post_showsig TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			post_smileys TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			post_author MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			post_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			post_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			post_edituser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			post_edittime INT(10) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (post_id),
			KEY thread_id (thread_id),
			KEY post_datestamp (post_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings");
			$result = dbquery("CREATE TABLE ".$db_prefix."settings (
			sitename VARCHAR(200) NOT NULL DEFAULT '',
			siteurl VARCHAR(200) NOT NULL DEFAULT '',
			sitebanner VARCHAR(200) NOT NULL DEFAULT '',
			sitebanner1 text NOT NULL,
			sitebanner2 text NOT NULL,
			siteemail VARCHAR(100) NOT NULL DEFAULT '',
			siteusername VARCHAR(30) NOT NULL DEFAULT '',
			siteintro TEXT NOT NULL,
			description TEXT NOT NULL,
			keywords TEXT NOT NULL,
			footer TEXT NOT NULL,
			opening_page VARCHAR(100) NOT NULL DEFAULT '',
			news_style TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			locale VARCHAR(20) NOT NULL DEFAULT 'English',
			theme VARCHAR(100) NOT NULL DEFAULT '',
			default_search VARCHAR(100) NOT NULL DEFAULT 'forums',
			exclude_left TEXT NOT NULL,
			exclude_upper TEXT NOT NULL,
			exclude_lower TEXT NOT NULL,
			exclude_right TEXT NOT NULL,
			shortdate VARCHAR(50) NOT NULL DEFAULT '',
			longdate VARCHAR(50) NOT NULL DEFAULT '',
			forumdate VARCHAR(50) NOT NULL DEFAULT '',
			subheaderdate VARCHAR(50) NOT NULL DEFAULT '',
			timeoffset VARCHAR(3) NOT NULL DEFAULT '0',
			numofthreads SMALLINT(2) UNSIGNED NOT NULL DEFAULT '5',
			forum_ips TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			attachmax INT(12) UNSIGNED NOT NULL DEFAULT '150000',
			attachtypes VARCHAR(150) NOT NULL DEFAULT '.gif,.jpg,.png,.zip,.rar,.tar',
			thread_notify TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			forum_ranks TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			forum_edit_lock TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			enable_registration TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
			email_verification TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
			admin_activation TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
			display_validation TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
			validation_method VARCHAR(5) DEFAULT '".(function_exists("gd_info") ? "image" : "text")."' NOT NULL,
			enable_terms TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
			license_agreement TEXT NOT NULL,
			license_lastupdate INT(10) UNSIGNED NOT NULL DEFAULT '0',
			thumb_w SMALLINT(3) UNSIGNED NOT NULL DEFAULT '100',
			thumb_h SMALLINT(3) UNSIGNED NOT NULL DEFAULT '100',
			photo_w SMALLINT(4) UNSIGNED NOT NULL DEFAULT '400',
			photo_h SMALLINT(4) UNSIGNED NOT NULL DEFAULT '300',
			photo_max_w SMALLINT(4) UNSIGNED NOT NULL DEFAULT '1800',
			photo_max_h SMALLINT(4) UNSIGNED NOT NULL DEFAULT '1600',
			photo_max_b INT(10) UNSIGNED NOT NULL DEFAULT '150000',
			thumb_compression CHAR(3) DEFAULT 'gd2' NOT NULL,
			thumbs_per_row SMALLINT(2) UNSIGNED NOT NULL DEFAULT '4',
			thumbs_per_page SMALLINT(2) UNSIGNED NOT NULL DEFAULT '12',
			photo_watermark smallint(1) UNSIGNED NOT NULL DEFAULT '0',
			photo_watermark_image varchar(255) NOT NULL DEFAULT 'images/watermark.png',
			photo_watermark_text smallint(1) UNSIGNED NOT NULL DEFAULT '0',
			photo_watermark_text_color1 varchar(6) NOT NULL DEFAULT 'FF6600',
			photo_watermark_text_color2 varchar(6) NOT NULL DEFAULT 'FFFF00',
			photo_watermark_text_color3 varchar(6) NOT NULL DEFAULT 'FFFFFF',
			photo_watermark_save smallint(1) UNSIGNED NOT NULL DEFAULT '0',
			tinymce_enabled TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			smtp_host VARCHAR(200) NOT NULL DEFAULT '' ,
			smtp_username VARCHAR(100) NOT NULL DEFAULT '',
			smtp_password VARCHAR(100) NOT NULL DEFAULT '',
			bad_words_enabled TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			bad_words TEXT NOT NULL,
			bad_word_replace VARCHAR(20) DEFAULT '[censored]' NOT NULL,
			guestposts TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			userthemes TINYINT(1) UNSIGNED NOT NULL  DEFAULT '1',
			newsperpage TINYINT(2) UNSIGNED NOT NULL DEFAULT '11',
			numofshouts TINYINT(2) UNSIGNED NOT NULL DEFAULT '10',
			flood_interval TINYINT(2) UNSIGNED NOT NULL DEFAULT '15',
			counter BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
			version VARCHAR(10) NOT NULL DEFAULT '7.00',
			maintenance TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			maintenance_message TEXT NOT NULL
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."shoutbox");
			$result = dbquery("CREATE TABLE ".$db_prefix."shoutbox (
			shout_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			shout_name VARCHAR(50) NOT NULL DEFAULT '',
			shout_message VARCHAR(200) NOT NULL DEFAULT '',
			shout_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			shout_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			PRIMARY KEY (shout_id),
			KEY shout_datestamp (shout_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."site_links");
			$result = dbquery("CREATE TABLE ".$db_prefix."site_links (
			link_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			link_name VARCHAR(100) NOT NULL DEFAULT '',
			link_url VARCHAR(200) NOT NULL DEFAULT '',
			link_visibility TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			link_position TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			link_window TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			link_order SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (link_id)
			) TYPE=MyISAM;");
		
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."smileys");
			$result = dbquery("CREATE TABLE ".$db_prefix."smileys (
			smiley_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
			smiley_code VARCHAR(50) NOT NULL,
			smiley_image VARCHAR(100) NOT NULL,
			smiley_text VARCHAR(100) NOT NULL,
			PRIMARY KEY (smiley_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."submissions");
			$result = dbquery("CREATE TABLE ".$db_prefix."submissions (
			submit_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			submit_type CHAR(1) NOT NULL,
			submit_user MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
			submit_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
			submit_criteria TEXT NOT NULL,
			PRIMARY KEY (submit_id) 
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."threads");
			$result = dbquery("CREATE TABLE ".$db_prefix."threads (
			forum_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			thread_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			thread_subject VARCHAR(100) NOT NULL DEFAULT '',
			thread_author MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			thread_views MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			thread_lastpost INT(10) UNSIGNED NOT NULL DEFAULT '0',
			thread_lastpostid MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			thread_lastuser MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			thread_postcount SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			thread_poll TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			thread_sticky TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			thread_locked TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (thread_id),
			KEY thread_postcount (thread_postcount),
			KEY thread_lastpost (thread_lastpost),
			KEY thread_views (thread_views)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."thread_notify");
			$result = dbquery("CREATE TABLE ".$db_prefix."thread_notify (
			thread_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			notify_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			notify_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			notify_status tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
			KEY notify_datestamp (notify_datestamp)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_fields");
			$result = dbquery("CREATE TABLE ".$db_prefix."user_fields (
			field_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			field_name VARCHAR(50) NOT NULL,
			field_group SMALLINT(1) UNSIGNED NOT NULL DEFAULT '1',
			field_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (field_id),
			KEY field_order (field_order)
			) TYPE=MyISAM;");
		
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_groups");
			$result = dbquery("CREATE TABLE ".$db_prefix."user_groups (
			group_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
			group_name VARCHAR(100) NOT NULL,
			group_description VARCHAR(200) NOT NULL,
			PRIMARY KEY (group_id) 
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."users");
			$result = dbquery("CREATE TABLE ".$db_prefix."users (
			user_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			user_name VARCHAR(30) NOT NULL DEFAULT '',
			user_password VARCHAR(32) NOT NULL DEFAULT '',
			user_admin_password VARCHAR(32) NOT NULL DEFAULT '',
			user_email VARCHAR(100) NOT NULL DEFAULT '',
			user_hide_email TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			user_offset CHAR(3) NOT NULL DEFAULT '0',
			user_avatar VARCHAR(100) NOT NULL DEFAULT '',
			user_posts SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			user_threads TEXT NOT NULL,
			user_joined INT(10) UNSIGNED NOT NULL DEFAULT '0',
			user_lastvisit INT(10) UNSIGNED NOT NULL DEFAULT '0',
			user_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			user_rights TEXT NOT NULL,
			user_groups TEXT NOT NULL,
			user_level TINYINT(3) UNSIGNED NOT NULL DEFAULT '101',
			user_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			user_theme VARCHAR(100) NOT NULL DEFAULT 'Default',
			user_location VARCHAR(50) NOT NULL DEFAULT '',
			user_birthdate DATE NOT NULL DEFAULT '0000-00-00',
			user_aim VARCHAR(16) NOT NULL DEFAULT '',
			user_icq VARCHAR(15) NOT NULL DEFAULT '',
			user_msn VARCHAR(100) NOT NULL DEFAULT '',
			user_yahoo VARCHAR(100) NOT NULL DEFAULT '',
			user_web VARCHAR(200) NOT NULL DEFAULT '',
			user_sig TEXT NOT NULL,
			PRIMARY KEY (user_id),
			KEY user_name (user_name),
			KEY user_joined (user_joined),
			KEY user_lastvisit (user_lastvisit)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."weblink_cats");
			$result = dbquery("CREATE TABLE ".$db_prefix."weblink_cats (
			weblink_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			weblink_cat_name VARCHAR(100) NOT NULL DEFAULT '',
			weblink_cat_description TEXT NOT NULL,
			weblink_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'weblink_name ASC',
			weblink_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY(weblink_cat_id)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."weblinks");
			$result = dbquery("CREATE TABLE ".$db_prefix."weblinks (
			weblink_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			weblink_name VARCHAR(100) NOT NULL DEFAULT '',
			weblink_description TEXT NOT NULL,
			weblink_url VARCHAR(200) NOT NULL DEFAULT '',
			weblink_cat MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			weblink_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			weblink_count SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY(weblink_id),
			KEY weblink_datestamp (weblink_datestamp),
			KEY weblink_count (weblink_count)
			) TYPE=MyISAM;");
			
			if (!$result) { $fail = true; }
	
			if (!$fail) {
				echo "<br>\n".$locale['040']."<br><br>\n";
				echo $locale['041']."<br><br>\n";
				echo $locale['042']."<br><br>\n";
				$success = true;
			} else {
				echo "<br>\n".$locale['040']."<br><br>\n";
				echo $locale['041']."<br><br>\n";
				echo "<strong>".$locale['043']."</strong> ".$locale['048']."<br><br>\n";
				$success = false;
			}
		} else {
			echo "<br>\n".$locale['040']."<br><br>\n";
			echo "<strong>".$locale['043']."</strong> ".$locale['046']."<br>\n";
			echo "<span class='small'>".$locale['047']."</span><br><br>\n";
			$success = false;
		}
	} else {
		echo "<br>\n<strong>".$locale['043']."<strong> ".$locale['044']."<br>\n";
		echo "<span class='small'>".$locale['045']."</span><br><br>\n";
		$success = false;
	}
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
	if ($success) {
		echo "<input type='hidden' name='step' value='5'>\n";
		echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
	} else {
		echo "<input type='hidden' name='step' value='3'>\n";
		echo "<input type='submit' name='next' value='".$locale['008']."' class='button'>\n";
	}
}

if (isset($_POST['step']) && $_POST['step'] == "5") {
	echo $locale['060']."<br><br>\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='tbl1'>".$locale['061']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' name='username' maxlength='30' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['062']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['063']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='password2' maxlength='20' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['064']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='admin_password1' maxlength='20' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['065']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='admin_password2' maxlength='20' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['066']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' name='email' maxlength='100' class='textbox' style='width:200px'></td></tr>\n";
	echo "</table>\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
	echo "<input type='hidden' name='step' value='6'>\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
}

if (isset($_POST['step']) && $_POST['step'] == "6") {
	require_once "config.php";
	$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
	
	$error = "";	
	
	$username = stripinput($_POST['username']);
	$password1 = stripinput($_POST['password1']);
	$password2 = stripinput($_POST['password2']);
	$admin_password1 = stripinput($_POST['admin_password1']);
	$admin_password2 = stripinput($_POST['admin_password2']);
	$email = stripinput($_POST['email']);
	
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) {
		$error .= $locale['070']."<br><br>\n";
	}
	
	if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
		if ($password1 != $password2) {
			$error .= $locale['071']."<br><br>\n";
		}
	} else {
		$error .= $locale['072']."<br><br>\n";
	}

	if (preg_match("/^[0-9A-Z@]{6,20}$/i", $admin_password1)) {
		if ($admin_password1 != $admin_password2) {
			$error .= $locale['073']."<br><br>\n";
		} elseif ($admin_password1 == $password1) {
			$error .= $locale['074']."<br><br>\n";
		}
	} else {
		$error .= $locale['075']."<br><br>\n";
	}
	
 	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$error .= $locale['076']."<br><br>\n";
	}


			
	if ($error == "") {
		$result = dbquery("INSERT INTO ".$db_prefix."settings 
		(sitename, siteurl, 
		sitebanner, sitebanner1,	sitebanner2, siteemail,	siteusername,	siteintro, description, keywords,
		footer,	opening_page,	news_style,	locale,	theme, default_search, exclude_left, exclude_upper, exclude_lower, exclude_right,
		shortdate, longdate, forumdate,	subheaderdate, timeoffset,
		numofthreads, forum_ips,	attachmax,	attachtypes, thread_notify,	forum_ranks, forum_edit_lock,
		enable_registration, email_verification,	admin_activation,	display_validation,	validation_method, enable_terms, license_agreement, license_lastupdate,
		thumb_w, thumb_h, photo_w, photo_h, photo_max_w, photo_max_h, photo_max_b, thumb_compression,	thumbs_per_row,	thumbs_per_page, photo_watermark,	photo_watermark_image, photo_watermark_text,	photo_watermark_text_color1, photo_watermark_text_color2,	photo_watermark_text_color3, photo_watermark_save,
		tinymce_enabled, smtp_host, smtp_username, smtp_password, bad_words_enabled, bad_words, bad_word_replace, guestposts, userthemes, newsperpage, numofshouts, flood_interval,
		counter, version, maintenance,	maintenance_message) 
		VALUES 
		('PHP-Fusion Powered Website', 'http://www.yourdomain.com/',
		'images/php-fusion-logo.png', '', '', 'you@yourdomain.com', '$username', '<center>".$locale['210']."</center>', '', '', 
		'<center>Copyright &copy; ".date("Y")."</center>', 'news.php', '0', '".stripinput($_POST['localeset'])."', 'Gillette', 'forums', '', '', '', '',
		'%d/%m/%Y %H:%M', '%B %d %Y %H:%M:%S', '%d-%m-%Y %H:%M', '%B %d %Y %H:%M:%S', '0',
		'5', '0', '150000', '.gif,.jpg,.png,.zip,.rar,.tar', '0', '0', '0',
		'1', '1', '0', '1', 'image', '0', '', '0',
		'100', '100', '400', '300', '1800', '1600', '150000', 'gd2', '4', '12', '0', 'images/watermark.png', '0', 'FF6600', 'FFFF00', 'FFFFFF', '0',
		'0', '', '', '', '0', '', '****', '0', '0', '11', '10', '15',
		'0', '7.00.05', '0', '')");
		
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AD', 'admins.gif', '".$locale['080']."', 'administrators.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AC', 'article_cats.gif', '".$locale['081']."', 'article_cats.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('A', 'articles.gif', '".$locale['082']."', 'articles.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SB', 'banners.gif', '".$locale['083']."', 'banners.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('BB', 'bbcodes.gif', '".$locale['084']."', 'bbcodes.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('B', 'blacklist.gif', '".$locale['085']."', 'blacklist.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('C', '', '".$locale['086']."', 'reserved', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('CP', 'c-pages.gif', '".$locale['087']."', 'custom_pages.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DB', 'db_backup.gif', '".$locale['088']."', 'db_backup.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DC', 'dl_cats.gif', '".$locale['089']."', 'download_cats.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('D', 'dl.gif', '".$locale['090']."', 'downloads.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FQ', 'faq.gif', '".$locale['091']."', 'faq.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('F', 'forums.gif', '".$locale['092']."', 'forums.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IM', 'images.gif', '".$locale['093']."', 'images.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('I', 'infusions.gif', '".$locale['094']."', 'infusions.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IP', '', '".$locale['095']."', 'reserved', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('M', 'members.gif', '".$locale['096']."', 'members.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('NC', 'news_cats.gif', '".$locale['097']."', 'news_cats.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('N', 'news.gif', '".$locale['098']."', 'news.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('P', 'panels.gif', '".$locale['099']."', 'panels.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PH', 'photoalbums.gif', '".$locale['100']."', 'photoalbums.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PI', 'phpinfo.gif', '".$locale['101']."', 'phpinfo.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PO', 'polls.gif', '".$locale['102']."', 'polls.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S', 'shout.gif', '".$locale['103']."', 'shoutbox.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SL', 'site_links.gif', '".$locale['104']."', 'site_links.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SM', 'smileys.gif', '".$locale['105']."', 'smileys.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SU', 'submissions.gif', '".$locale['106']."', 'submissions.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('U', 'upgrade.gif', '".$locale['107']."', 'upgrade.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UG', 'user_groups.gif', '".$locale['108']."', 'user_groups.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('WC', 'wl_cats.gif', '".$locale['109']."', 'weblink_cats.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('W', 'wl.gif', '".$locale['110']."', 'weblinks.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S1', 'settings.gif', '".$locale['111']."', 'settings_main.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S2', 'settings_time.gif', '".$locale['112']."', 'settings_time.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S3', 'settings_forum.gif', '".$locale['113']."', 'settings_forum.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S4', 'registration.gif', '".$locale['114']."', 'settings_registration.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S5', 'photoalbums.gif', '".$locale['115']."', 'settings_photo.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S6', 'settings_misc.gif', '".$locale['116']."', 'settings_misc.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S7', 'settings_pm.gif', '".$locale['117']."', 'settings_messages.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UF', 'user_fields.gif', '".$locale['118']."', 'user_fields.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FR', 'forum_ranks.gif', '".$locale['119']."', 'forum_ranks.php', 2)");

		$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_password, user_admin_password, user_email, user_hide_email, user_offset, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status, user_theme, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_sig) VALUES ('".$username."', '".md5(md5($password1))."', '".md5(md5($admin_password1))."', '".$email."', '1', '0', '', '0', '', '".time()."', '0', '0.0.0.0', 'A.AC.AD.B.BB.C.CP.DB.DC.D.FQ.F.FR.IM.I.IP.M.N.NC.P.PH.PI.PO.S.SL.S1.S2.S3.S4.S5.S6.S7.SB.SM.SU.UF.UG.U.W.WC', '', '103', '0', 'Default', '', '0000-00-00', '', '', '', '', '', '')");

		$result = dbquery("INSERT INTO ".$db_prefix."messages_options (user_id, pm_email_notify, pm_save_sent, pm_inbox, pm_savebox, pm_sentbox) VALUES ('0', '0', '1', '20', '20', '20')");

		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('smiley', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('b', '2')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('i', '3')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('u', '4')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('url', '5')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('mail', '6')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('img', '7')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('center', '8')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('small', '9')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('code', '10')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('quote', '11')");

		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':)', 'smile.gif', 'Smile')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (';)', 'wink.gif', 'Wink')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':(', 'sad.gif', 'Sad')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':|', 'frown.gif', 'Frown')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':o', 'shock.gif', 'Shock')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':P', 'pfft.gif', 'Pfft')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES ('B)', 'cool.gif', 'Cool')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':D', 'grin.gif', 'Grin')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':@', 'angry.gif', 'Angry')");

		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['180']."', 'bugs.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['181']."', 'downloads.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['182']."', 'games.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['183']."', 'graphics.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['184']."', 'hardware.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['185']."', 'journal.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['186']."', 'members.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['187']."', 'mods.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['188']."', 'movies.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['189']."', 'network.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['190']."', 'news.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['191']."', 'php-fusion.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['192']."', 'security.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['193']."', 'software.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['194']."', 'themes.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['195']."', 'windows.gif')");

		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['160']."', 'navigation_panel', '', '1', '1', 'file', '0', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['161']."', 'online_users_panel', '', '1', '2', 'file', '0', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['162']."', 'forum_threads_panel', '', '1', '3', 'file', '0', '0', '0')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['163']."', 'latest_articles_panel', '', '1', '4', 'file', '0', '0', '0')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['164']."', 'welcome_message_panel', '', '2', '1', 'file', '0', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['165']."', 'forum_threads_list_panel', '', '2', '2', 'file', '0', '0', '0')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['166']."', 'user_info_panel', '', '4', 1, 'file', '0', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['167']."', 'member_poll_panel', '', '4', '2', 'file', '0', '0', '0')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['168']."', 'shoutbox_panel', '', '4', '3', 'file', '0', '0', '1')");

		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['130']."', 'index.php', '0', '2', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['131']."', 'articles.php', '0', '2', '0', '2')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['132']."', 'downloads.php', '0', '2', '0', '3')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['133']."', 'faq.php', '0', '1', '0', '4')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['134']."', 'forum/index.php', '0', '2', '0', '5')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['136']."', 'news_cats.php', '0', '2', '0', '7')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['137']."', 'weblinks.php', '0', '2', '0', '6')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['135']."', 'contact.php', '0', '1', '0', '8')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['138']."', 'photogallery.php', '0', '1', '0', '9')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['139']."', 'search.php', '0', '1', '0', '10')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('---', '---', '101', '1', '0', '11')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['140']."', 'submit.php?stype=l', '101', '1', '0', '12')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['141']."', 'submit.php?stype=n', '101', '1', '0', '13')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['142']."', 'submit.php?stype=a', '101', '1', '0', '14')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['143']."', 'submit.php?stype=p', '101', '1', '0', '15')");
		
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_location', '2', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_birthdate', '2', '2')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_aim', '1', '3')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_icq', '1', '4')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_msn', '1', '5')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_yahoo', '1', '6')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_web', '1', '7')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_theme', '3', '8')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_sig', '3', '9')");
		
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (1, '".$locale['200']."', 'rank_super_admin.gif', 0, 103)");
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (2, '".$locale['201']."', 'rank_admin.gif', 0, 102)");
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (3, '".$locale['202']."', 'rank_mod.gif', 0, 104)");
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (4, '".$locale['203']."', 'rank0.gif', 0, 101)");
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (5, '".$locale['204']."', 'rank1.gif', 10, 101)");
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (6, '".$locale['205']."', 'rank2.gif', 50, 101)");
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (7, '".$locale['206']."', 'rank3.gif', 200, 101)");
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (8, '".$locale['207']."', 'rank4.gif', 500, 101)");
		$result = dbquery("INSERT INTO ".$db_prefix."forum_ranks VALUES (9, '".$locale['208']."', 'rank5.gif', 1000, 101)");
   
   	echo "<br>\n".$locale['220']."<br><br>\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
		echo "<input type='hidden' name='step' value='7'>\n";
		echo "<input type='submit' name='next' value='".$locale['009']."' class='button'>\n";
	} else {
		echo "<br>\n".$locale['077']."<br><br>\n".$error;
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
		echo "<input type='hidden' name='step' value='5'>\n";
		echo "<input type='submit' name='back' value='".$locale['008']."' class='button'>\n";
	}
}

echo "</td>\n</tr>\n";
echo "</table>\n</form>\n";

echo "<br>";

echo "</body>\n</html>\n";

// mySQL database functions
function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		return false;
	} else {
		return true;
	}
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files") {
	$res = array();
	$filter = explode("|", $filter); 
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected = "") {
	$res = "";
	for ($i=0; $i < count($files); $i++) {
		$sel = ($selected == $files[$i] ? " selected='selected'" : "");
		$res .= "<option value='".$files[$i]."'$sel>".$files[$i]."</option>\n";
	}
	return $res;
}
?>