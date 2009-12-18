<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: lostpassword.php
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
require_once "maincore.php";
require_once THEMES."templates/header.php";
require_once INCLUDES."sendmail_include.php";
include LOCALE.LOCALESET."lostpassword.php";

if (iMEMBER) redirect("index.php");

add_to_title($locale['global_200'].$locale['400']);
opentable($locale['400']);
if (isset($_GET['email']) && isset($_GET['account'])) {
	$error = 0;
	if (FUSION_QUERY != "email=".$_GET['email']."&amp;account=".$_GET['account']) { redirect("index.php"); }
	$email = stripinput(trim(preg_replace("/ +/i", "", $_GET['email'])));
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) { $error = 1; }
	if (!preg_match("/^[0-9a-z]{32}$/", $_GET['account'])) { $error = 1; }
	if ($error == 0) {
		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_password='".$_GET['account']."' AND user_email='".$email."'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789@";
			$char_count = strlen($chars) - 1;
			$new_pass = "";
			for ($i = 0; $i < 8; $i++) {
				$new_pass .= substr($chars, mt_rand(0, $char_count), 1);
			}
			$mailbody = str_replace("[NEW_PASS]", $new_pass, $locale['411']);
			$mailbody = str_replace("[USER_NAME]", $data['user_name'], $mailbody);
			sendemail($data['user_name'], $email,$settings['siteusername'], $settings['siteemail'], $locale['409'].$settings['sitename'], $mailbody);
			$result = dbquery("UPDATE ".DB_USERS." SET user_password='".md5(md5($new_pass))."' WHERE user_id='".$data['user_id']."'");
			echo "<div style='text-align:center'><br />\n".$locale['402']."<br /><br />\n<a href='index.php'>".$locale['403']."</a><br /><br />\n</div>\n";
		} else {
			$error = 1;
		}
	}
	if ($error == 1) redirect("index.php");
} elseif (isset($_POST['send_password'])) {
	$email = stripinput(trim(preg_replace("/ +/i", "", $_POST['email'])));
	if (preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_email='$email'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$new_pass_link = $settings['siteurl']."lostpassword.php?email=".$data['user_email']."&account=".$data['user_password'];
			$mailbody = str_replace("[NEW_PASS_LINK]", $new_pass_link, $locale['410']);
			$mailbody = str_replace("[USER_NAME]", $data['user_name'], $mailbody);
			sendemail($data['user_name'], $email,$settings['siteusername'], $settings['siteemail'], $locale['409'].$settings['sitename'], $mailbody);
			echo "<div style='text-align:center'><br />\n".$locale['401']."<br /><br />\n<a href='index.php'>".$locale['403']."</a><br /><br />\n</div>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['404']."<br /><br />\n<a href='".FUSION_SELF."'>".$locale['406']."</a><br /><br />\n</div>\n";
		}
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['405']."<br /><br />\n<a href='".FUSION_SELF."'>".$locale['403']."</a><br /><br /></div>\n";
	}
} else {
	echo "<div style='text-align:center'>\n<form name='passwordform' method='post' action='".FUSION_SELF."'>\n";
	echo $locale['407']."<br /><br />\n";
	echo "<input type='text' name='email' class='textbox' maxlength='100' style='width:200px;' /><br /><br />\n";
	echo "<input type='submit' name='send_password' value='".$locale['408']."' class='button' />\n";
	echo "</form>\n</div>\n";
}
closetable();

require_once THEMES."templates/footer.php";
?>