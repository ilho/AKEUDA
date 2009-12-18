<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

define("THEME_WIDTH", "100%");
define("THEME_BULLET", "<img src='".THEME."images/bullet.gif' alt='' style='border:0' />");

require_once INCLUDES."theme_functions_include.php";

function render_page($license=false) {

	global $settings, $main_style;

	//Header
	echo "<table cellspacing='0' cellpadding='0' width='".THEME_WIDTH."' class='outer-border center'>\n<tr>\n";
	echo "<td>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='full-header'>\n".showbanners()."</td>\n";
	echo "</tr>\n</table>\n";
	echo "</td>\n</tr>\n</table>\n";
	
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='sub-header'>".showsublinks(" <span class='bullet'>&middot;</span> ","white")."</td>\n";
	echo "<td align='right' class='sub-header'>".showsubdate()."</td>\n";
	echo "</tr>\n</table>\n";
	
	//Content
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='$main_style'>\n<tr>\n";
	if (LEFT) { echo "<td class='side-border-left' valign='top'>".LEFT."</td>"; }
	echo "<td class='main-bg' valign='top'>".U_CENTER.CONTENT.L_CENTER."</td>";
	if (RIGHT) { echo "<td class='side-border-right' valign='top'>".RIGHT."</td>"; }
	echo "</tr>\n</table>\n";
	
	//Footer
	echo "</tr>\n</table>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td align='center' class='footer'>".stripslashes($settings['footer'])."<br />\n";
	if (!$license) { echo showcopyright()."<br /><br />\n"; } echo showcounter()."<br /><br />\n";
	echo "</td>\n</tr>\n</table>\n";
	echo "</td>\n</tr>\n</table>\n";

}

function render_news($subject, $news, $info) {

	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='capmain'>".$subject."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='main-body'>".$news."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' class='news-footer'>\n";
	echo newsposter($info," &middot;").newsopts($info,"&middot;").itemoptions("N",$info['news_id']);
	echo "</td>\n</tr>\n</table>\n";

}

function render_article($subject, $article, $info) {
	
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='capmain'>".$subject."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='main-body'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' class='news-footer'>\n";
	echo articleposter($info," &middot;").articleopts($info,"&middot;").itemoptions("A",$info['article_id']);
	echo "</td>\n</tr>\n</table>\n";

}

function opentable($title) {

	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='capmain'>".$title."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='main-body'>\n";

}

function closetable() {

	echo "</td>\n</tr>\n</table>\n";

}

function openside($title, $collapse = false, $state = "on") {
	
	global $panel_collapse; $panel_collapse = $collapse;
	
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='border'>\n<tr>\n";
	echo "<td class='scapmain'>".$title."</td>\n";
	if ($collapse == true) {
		$boxname = str_replace(" ", "", $title);
		echo "<td class='scapmain' align='right'>".panelbutton($state,$boxname)."</td>\n";
	}
	echo "</tr>\n<tr>\n";
	echo "<td".($collapse == true ? " colspan='2'" : "")." class='side-body'>\n";	
	if ($collapse == true) { echo panelstate($state, $boxname); }

}

function closeside($collapse = false) {

	global $panel_collapse;

	if ($panel_collapse == true) { echo "</div>\n"; }	
	echo "</td>\n</tr>\n</table>\n";

}
?>