<?php
/*
English Language Fileset
Produced by Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk
*/

// Locale Settings
setlocale(LC_TIME, "en","GB"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-1";
$locale['xml_lang'] = "en";
$locale['tinymce'] = "en";
$locale['phpmailer'] = "en";

// Full & Short Months
$locale['months'] = "&nbsp|January|February|March|April|May|June|July|August|September|October|November|December";
$locale['shortmonths'] = "&nbsp|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sept|Oct|Nov|Dec";

// Standard User Levels
$locale['user0'] = "Public";
$locale['user1'] = "Member";
$locale['user2'] = "Administrator";
$locale['user3'] = "Super Administrator";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['global_001'] = "Navigation";
$locale['global_002'] = "No links defined\n";
// Users Online
$locale['global_010'] = "Users Online";
$locale['global_011'] = "Guests Online";
$locale['global_012'] = "Members Online";
$locale['global_013'] = "No Members Online";
$locale['global_014'] = "Total Members";
$locale['global_015'] = "Unactivated Members";
$locale['global_016'] = "Newest Member";
// Forum Side panel
$locale['global_020'] = "Forum Threads";
$locale['global_021'] = "Newest Threads";
$locale['global_022'] = "Hottest Threads";
$locale['global_023'] = "No Threads created";
// Articles Side panel
$locale['global_030'] = "Latest Articles";
$locale['global_031'] = "No Articles available";
// Welcome panel
$locale['global_035'] = "Welcome";
// Latest Active Forum Threads panel
$locale['global_040'] = "Latest Active Forum Threads";
$locale['global_041'] = "My Recent Threads";
$locale['global_042'] = "My Recent Posts";
$locale['global_043'] = "New Posts";
$locale['global_044'] = "Thread";
$locale['global_045'] = "Views";
$locale['global_046'] = "Replies";
$locale['global_047'] = "Last Post";
$locale['global_048'] = "Forum";
$locale['global_049'] = "Posted";
$locale['global_050'] = "Author";
$locale['global_051'] = "Poll";
$locale['global_052'] = "Moved";
$locale['global_053'] = "You have not started any forum threads yet.";
$locale['global_054'] = "You have not posted any forum messages yet.";
$locale['global_055'] = "There are %u new posts since your last visit.";
$locale['global_056'] = "My Tracked Threads";
$locale['global_057'] = "Options";
$locale['global_058'] = "Stop";
$locale['global_059'] = "You're not tracking any threads.";
$locale['global_060'] = "Stop tracking this thread?";
// News & Articles
$locale['global_070'] = "Posted by ";
$locale['global_071'] = "on ";
$locale['global_072'] = "Read More";
$locale['global_073'] = " Comments";
$locale['global_073b'] = " Comment";
$locale['global_074'] = " Reads";
$locale['global_075'] = "Print";
$locale['global_076'] = "Edit";
$locale['global_077'] = "News";
$locale['global_078'] = "No News has been posted yet";
// Page Navigation
$locale['global_090'] = "Prev";
$locale['global_091'] = "Next";
$locale['global_092'] = "Page ";
$locale['global_093'] = " of ";
// Guest User Menu
$locale['global_100'] = "Login";
$locale['global_101'] = "Username";
$locale['global_102'] = "Password";
$locale['global_103'] = "Remember Me";
$locale['global_104'] = "Login";
$locale['global_105'] = "Not a member yet?<br /><a href='".BASEDIR."register.php' class='side'>Click here</a> to register.";
$locale['global_106'] = "Forgotten your password?<br />Request a new one <a href='".BASEDIR."lostpassword.php' class='side'>here</a>.";
$locale['global_107'] = "Register";
$locale['global_108'] = "Lost password";
// Member User Menu
$locale['global_120'] = "Edit Profile";
$locale['global_121'] = "Private Messages";
$locale['global_122'] = "Members List";
$locale['global_123'] = "Admin Panel";
$locale['global_124'] = "Logout";
$locale['global_125'] = "You have %u new ";
$locale['global_126'] = "message";
$locale['global_127'] = "messages";
// Poll
$locale['global_130'] = "Member Poll";
$locale['global_131'] = "Submit Vote";
$locale['global_132'] = "You must login to vote.";
$locale['global_133'] = "Vote";
$locale['global_134'] = "Votes";
$locale['global_135'] = "Votes: ";
$locale['global_136'] = "Started: ";
$locale['global_137'] = "Ended: ";
$locale['global_138'] = "Polls Archive";
$locale['global_139'] = "Select a Poll to view from the list:";
$locale['global_140'] = "View";
$locale['global_141'] = "View Poll";
$locale['global_142'] = "There are no polls defined.";
// Shoutbox
$locale['global_150'] = "Shoutbox";
$locale['global_151'] = "Name:";
$locale['global_152'] = "Message:";
$locale['global_153'] = "Shout";
$locale['global_154'] = "You must login to post a message.";
$locale['global_155'] = "Shoutbox Archive";
$locale['global_156'] = "No messages have been posted.";
$locale['global_157'] = "Delete";
$locale['global_158'] = "Validation Code:";
$locale['global_159'] = "Enter Validation Code:";
// Footer Counter
$locale['global_170'] = "unique visit";
$locale['global_171'] = "unique visits";
$locale['global_172'] = "Render time: %s seconds";
// Admin Navigation
$locale['global_180'] = "Admin Home";
$locale['global_181'] = "Return to Site";
$locale['global_182'] = "<strong>Notice:</strong> Admin Password not entered or incorrect.";
// Miscellaneous
$locale['global_190'] = "Maintenance Mode Activated";
$locale['global_191'] = "Your IP address is currently blacklisted.";
$locale['global_192'] = "Logging out as ";
$locale['global_193'] = "Logging in as ";
$locale['global_194'] = "This account is currently suspended.";
$locale['global_195'] = "This account has not been activated.";
$locale['global_196'] = "Invalid username or password.";
$locale['global_197'] = "Please wait while we transfer you...<br /><br />
[ <a href='index.php'>Or click here if you do not wish to wait</a> ]";
$locale['global_198'] = "<strong>Warning:</strong> setup.php detected, please delete it immediately.";
$locale['global_199'] = "<strong>Warning:</strong> admin password not set, click <a href='".BASEDIR."edit_profile.php'>Edit Profile</a> to set it.";
//Titles
$locale['global_200'] = " - ";
$locale['global_201'] = ": ";
$locale['global_202'] = $locale['global_200']."Search";
$locale['global_203'] = $locale['global_200']."FAQ";
$locale['global_204'] = $locale['global_200']."Forum";
//Themes
$locale['global_210'] = "Skip to content";
// No themes found
$locale['global_300'] = "no theme found";
$locale['global_301'] = "We are really sorry but this page cannot be displayed. Due to some circumstances no site theme can be found. If you are a Site Administrator, please use your FTP client to upload any theme designed for <em>PHP-Fusion v7</em> to the <em>themes/</em> folder. After upload check in <em>Main Settings</em> to see if the selected theme was correctly uploaded to your <em>themes/</em> directory. Please note that the uploaded theme folder has to have the exact same name (including character case, which is important on Unix based servers) as chosen in <em>Main Settings</em> page.<br /><br />If you are regular member of this site, please contact the site\'s administrator via ".hide_email($settings['siteemail'])." e-mail and report this issue.";
$locale['global_302'] = "The Theme chosen in Main Settings does not exists or is incomplete!";
?>
