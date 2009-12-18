<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: footer.php
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
require_once INCLUDES."footer_includes.php";

define("CONTENT", ob_get_contents());
ob_end_clean();
render_page(false);

echo "</body>\n</html>\n";

if (iADMIN) {
	$result = dbquery("DELETE FROM ".DB_FLOOD_CONTROL." WHERE flood_timestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_datestamp < '".(time()-1209600)."'");
	$result = dbquery("DELETE FROM ".DB_CAPTCHA." WHERE captcha_datestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_datestamp < '".(time()-86400)."'");
}

$output = ob_get_contents();
ob_end_clean();
echo handle_output($output);

if(ob_get_length () !== FALSE){
	ob_end_flush();
}
mysql_close();
?>