<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: member_poll_panel.php
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

openside($locale['global_130']);
if (isset($_POST['cast_vote']) && (isset($_POST['poll_id']) && isnum($_POST['poll_id']))) {
	$result = dbquery("SELECT * FROM ".DB_POLL_VOTES." WHERE vote_user='".$userdata['user_id']."' AND poll_id='".$_POST['poll_id']."'");
	if (!dbrows($result)) {
		if (isset($_POST['voteoption']) && isnum($_POST['voteoption'])) {
			$result = dbquery("INSERT INTO ".DB_POLL_VOTES." (vote_user, vote_opt, poll_id) VALUES ('".$userdata['user_id']."', '".$_POST['voteoption']."', '".$_POST['poll_id']."')");
		}
		redirect(FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
	}
}
$result = dbquery("SELECT * FROM ".DB_POLLS." ORDER BY poll_started DESC LIMIT 1");
if (dbrows($result)) {
	$data = dbarray($result);
	$poll_title = $data['poll_title'];
	$poll_option = array();
	for ($i = 0; $i <= 9; $i++) {
		if ($data["poll_opt_".$i]) $poll_option[$i] = $data["poll_opt_".$i];
	}
	if (iMEMBER) $result2 = dbquery("SELECT * FROM ".DB_POLL_VOTES." WHERE vote_user='".$userdata['user_id']."' AND poll_id='".$data['poll_id']."'");
	if ((!iMEMBER || !dbrows($result2)) && $data['poll_ended'] == 0) {
		$poll = ""; $i = 0; $num_opts = count($poll_option);
		while ($i < $num_opts) {
			$poll .= "<label><input type='radio' name='voteoption' value='$i' /> $poll_option[$i]</label><br /><br />\n";
			$i++;
		}
		echo "<form name='voteform' method='post' action='".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>\n";
		echo "<strong>".$poll_title."</strong><br /><br />\n".$poll;
		echo "<div style='text-align:center'><input type='hidden' name='poll_id' value='".$data['poll_id']."' />\n";
		if (iMEMBER) {
			echo "<input type='submit' name='cast_vote' value='".$locale['global_131']."' class='button' />";
		} else {
			echo $locale['global_132'];
		}
		echo "</div>\n</form>\n";
	} else {
		$poll =  ""; $i = 0; $num_opts = count($poll_option);
		$poll_votes = dbcount("(vote_opt)", DB_POLL_VOTES, "poll_id='".$data['poll_id']."'");
		while ($i < $num_opts) {
			$num_votes = dbcount("(vote_opt)", DB_POLL_VOTES, "vote_opt='$i' AND poll_id='".$data['poll_id']."'");
			$opt_votes = ($poll_votes ? number_format(100 / $poll_votes * $num_votes) : 0);
			$poll .= "<div>".$poll_option[$i]."</div>\n";
			$poll .= "<div><img src='".get_image("pollbar")."' alt='".$poll_option[$i]."' height='12' width='".$opt_votes."' class='poll' /></div>\n";
			$poll .= "<div>".$opt_votes."% [".$num_votes." ".($num_votes == 1 ? $locale['global_133'] : $locale['global_134'])."]</div><br />\n";
			$i++;
		}
		echo "<strong>".$poll_title."</strong><br /><br />\n".$poll;
		echo "<div style='text-align:center'>".$locale['global_135'].$poll_votes."<br />\n";
		echo $locale['global_136'].showdate("shortdate", $data['poll_started']);
		if ($data['poll_ended'] > 0) {
			echo "<br />\n".$locale['global_137'].showdate("shortdate", $data['poll_ended'])."\n";
		}
		$result = dbquery("SELECT * FROM ".DB_POLLS);
		if (dbrows($result) > 1) {
			echo "<br /><br /><a href='".INFUSIONS."member_poll_panel/polls_archive.php' class='side'>".$locale['global_138']."</a>\n";
		}
		echo "</div>\n";
	}
} else {
	echo "<div style='text-align:center'>".$locale['global_142']."</div>\n";
}
closeside();
?>
