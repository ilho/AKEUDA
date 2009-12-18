<?php
$locale['400'] = "Edit Profile";
// Edit Profile Messages
$locale['410'] = "In order to change your password or email address<br />you must enter your current password.";
$locale['411'] = "Profile successfully updated";
$locale['412'] = "Unable to update Profile:";
// Edit Profile Form
$locale['420'] = "Current Password";
$locale['421'] = "Current Admin Password";
$locale['422'] = "New Admin Password";
$locale['423'] = "Confirm Admin Password";
$locale['424'] = "Update Profile";
// Update Profile Errors
$locale['430'] = "You must specify a user name and email address.";
$locale['431'] = "User name contains invalid characters.";
$locale['432'] = "The user name ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." is in use.";
$locale['433'] = "Invalid email address.";
$locale['434'] = "The email address ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." is in use.";
$locale['435'] = "New passwords do not match.";
$locale['436'] = "Invalid password, use alpha numeric characters only.<br />Password must be a minimum of 6 characters long.";
$locale['437'] = "You must specify your current password to change your password or email address.";
$locale['438'] = "New admin passwords do not match.";
$locale['439'] = "Your user password and admin password must be different.";
$locale['440'] = "Invalid admin password, use alpha numeric characters only.<br />Admin password must be a minimum of 6 characters long.";
$locale['441'] = "You must specify your current admin password to change your admin password.";
?>