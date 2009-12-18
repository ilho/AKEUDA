<?php
// Member Management Options
$locale['400'] = "Members";
$locale['401'] = "User";
$locale['402'] = "Add New Member";
$locale['403'] = "User Type";
$locale['404'] = "Options";
$locale['405'] = "View";
$locale['406'] = "Edit";
$locale['407'] = "Activate";
$locale['408'] = "UnBan";
$locale['409'] = "Ban";
$locale['410'] = "Delete";
$locale['411'] = "There are no %s members";
$locale['412'] = " beginning with ";
$locale['413'] = " matching ";
$locale['414'] = "Show All";
$locale['415'] = "Search for member:";
$locale['416'] = "Search";
// Member types
$locale['417'] = "Members";
$locale['418'] = "Unactivated";
$locale['419'] = "Banned";
// Ban/Unban/Delete Member
$locale['420'] = "Ban Imposed";
$locale['421'] = "Ban Removed";
$locale['422'] = "Member Deleted";
$locale['423'] = "Are you sure you wish to delete this member?";
$locale['424'] = "Member Activated";
$locale['425'] = "Account activated at ";
$locale['426'] = "Hello [USER_NAME],\n
Your account at ".$settings['sitename']." has been activated.\n
You can now login using your chosen username and password.\n
Regards,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Edit Member";
$locale['431'] = "Member details updated";
$locale['432'] = "Return to Members Admin";
$locale['433'] = "Return to Admin Index";
$locale['434'] = "Unable to Update Member details:";
// Extra Edit Member Details form options
$locale['440'] = "Save Changes";
// Update Profile Errors
$locale['450'] = "Cannot edit primary administrator.";
$locale['451'] = "You must specify a user name and email address.";
$locale['452'] = "User name contains invalid characters.";
$locale['453'] = "The user name ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." is in use.";
$locale['454'] = "Invalid email address.";
$locale['455'] = "The email address ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." is in use.";
$locale['456'] = "New Passwords do not match.";
$locale['457'] = "Invalid password, use alpha numeric characters only.<br />
Password must be a minimum of 6 characters long.";
$locale['458'] = "<strong>Warning:</strong> unexpected script execution.";
// View Member Profile
$locale['470'] = "Member Profile";
$locale['472'] = "Statistics";
$locale['473'] = "User Groups";
// Add Member Errors
$locale['480'] = "Add Member";
$locale['481'] = "The member account has been created.";
$locale['482'] = "The member account could not be created.";
?>