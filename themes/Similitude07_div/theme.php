<?php
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }
require_once INCLUDES."theme_functions_include.php";

define("THEME_WIDTH", "100%");
define("THEME_BULLET", "<strong>&middot;</strong>");

function render_page($license=false) {

global $settings, $main_style;

	//Header
	echo "<div class='outer-border $main_style' style='background-color:#fff;width:".THEME_WIDTH.";'>\n";
	echo "<div class='floatfix'>\n";
	echo "<div class='full-header floatfix'>\n\n".showbanners()."</div>\n";
	echo "<div class='sub-header floatfix'>\n";
	echo "<div style='float:left;'>".showsublinks(" <span class='bullet'>&middot;</span> ","white")."</div>\n";
	echo "<div style='float:right;'>".showsubdate()."</div>\n";
	echo "</div></div>\n";
	
	//Content
	if (LEFT) { echo "<div id='side-border-left'>".LEFT."</div>\n"; }
	if (RIGHT) { echo "<div id='side-border-right'>".RIGHT."</div>\n"; }
	echo "<div id='main-bg' class='clearfix'><div id='container'>".U_CENTER.CONTENT.L_CENTER."</div></div>\n";
	
	//Footer
	echo "<div class='footer clear' style='text-align:center;'>".stripslashes($settings['footer'])."<br />\n";
	if ($license == false) { echo showcopyright()."<br /><br />\n"; } echo showcounter()."<br /><br />\n";
	echo "</div>\n</div>\n";

}

function render_news($subject, $news, $info) {

	echo "<div class='capmain'>$subject</div>\n";
	echo "<div class='main-body floatfix'>".$news."</div>\n";
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
	echo "<div class='scapmain'>";
	if ($collapse == true) {
		$boxname = str_replace(" ", "", $title);
		echo "<div style='float:right;'>".panelbutton($state,$boxname)."</div>";
	}
	echo $title."</div>\n<div class='side-body floatfix'>\n";
	if ($collapse == true) { echo panelstate($state, $boxname); }

}

function closeside($collapse = false) {

	global $panel_collapse;

	if ($panel_collapse == true) { echo "</div>\n"; }
	echo "</div>\n</div>\n";

}
?>