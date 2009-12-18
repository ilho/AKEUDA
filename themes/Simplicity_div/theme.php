<?php
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }
require_once INCLUDES."theme_functions_include.php";

define("THEME_WIDTH", "100%");
define("THEME_BULLET", "<img src='".THEME."images/bullet.gif' alt='' style='border:0' />");

function render_page($license=false) {
	
	global $settings, $main_style;

	echo "<div style='width:".THEME_WIDTH.";' class='$main_style'>\n";
	
	//Header
	echo "<div class='full-header'>\n".showbanners()."\n</div>\n";
	echo "<div class='floatfix'>\n";
	echo "<div class='white-header' style='float:left;'>".showsublinks(" <span class='bullet'>&middot;</span> ")."</div>\n";
	echo "<div class='white-header' style='float:right;'>".showsubdate()."</div>\n";
	echo "</div>\n";
	
	//Content
	if (LEFT) { echo "<div id='side-border-left'>".LEFT."</div>\n"; }
	if (RIGHT) { echo "<div id='side-border-right'>".RIGHT."</div>\n"; }
	echo "<div id='main-bg'><div id='container'>".U_CENTER.CONTENT.L_CENTER."</div></div>\n";
	
	//Footer
	echo "<div class='full-header clear'>".stripslashes($settings['footer'])."</div>\n";
	echo "<div class='white-header' style='text-align:center;'><br/>\n";
	echo showcounter()."<br /><br />\n";
	if ($license == false) {
		echo showcopyright()."<br /><br />\n";
	}
	echo "</div>\n</div>\n";

}

function render_news($subject, $news, $info) {

	echo "<div class='capmain'>$subject</div>\n";
	echo "<div class='main-body floatfix'>".	$news."</div>\n";
	echo "<div class='news-footer'>\n";
	echo newsposter($info,"&middot;").newsopts($info,"&middot;").itemoptions("N",$info['news_id']);
	echo "</div>\n";

}

function render_article($subject, $article, $info) {

	echo "<div class='capmain'>$subject</div>\n";
	echo "<div class='main-body floatfix'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div>\n";
	echo "<div class='news-footer'>\n";
	echo articleposter($info,"&middot;").articleopts($info,"&middot;").itemoptions("A",$info['article_id']);
	echo "</div>\n";

}

function opentable($title) {

	echo "<div class='capmain'>$title</div>\n";
	echo "<div class='main-body floatfix'>\n";

}

function closetable() {

	echo "</div>\n";

}

function openside($title, $collapse = false, $state = "on") {
	
	global $panel_collapse; $panel_collapse = $collapse;

	echo "<div class='border'>\n";
	if ($collapse == true) {
		$boxname = str_replace(" ", "", $title);
		echo "<div class='scapmain' style='float:right;'>".panelbutton($state,$boxname)."</div>\n";
	}
	echo "<div class='scapmain'>".$title."</div>\n";
	echo "<div class='side-body floatfix'>\n";
	if ($collapse == true) { echo panelstate($state, $boxname); }

}

function closeside($collapse = false) {

	global $panel_collapse;

	if ($panel_collapse == true) { echo "</div>\n"; }
	echo "</div>\n</div>\n";

}
?>