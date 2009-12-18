<?php
$locale['400'] = "Register";
$locale['401'] = "Activate Account";
// Registration Errors
$locale['402'] = "You must specify a user name, password & email address.";
$locale['403'] = "User name contains invalid characters.";
$locale['404'] = "Your two Passwords do not match.";
$locale['405'] = "Invalid password, use alpha numeric characters only.<br />
Password must be a minimum of 6 characters long.";
$locale['406'] = "Your email address does not appear to be valid.";
$locale['407'] = "Sorry, the user name ".(isset($_POST['username']) ? $_POST['username'] : "")." is in use.";
$locale['408'] = "Sorry, the email address ".(isset($_POST['email']) ? $_POST['email'] : "")." is in use.";
$locale['409'] = "An inactive account has been registered with the email address.";
$locale['410'] = "Incorrect validation code.";
$locale['411'] = "Your email address or email domain is blacklisted.";
// Email Message
$locale['449'] = "Welcome to ".$settings['sitename'];
$locale['450'] = "Hello ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Welcome to ".$settings['sitename'].". Here are your login details:\n
Username: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Password: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Please activate your account via the following link:\n";
// Registration Success/Fail
$locale['451'] = "Registration complete";
$locale['452'] = "You can now log in.";
$locale['453'] = "An administrator will activate your account shortly.";
$locale['454'] = "Your registration is almost complete, you will receive an email containing your login details along with a link to verify your account.";
$locale['455'] = "Your account has been verified.";
$locale['456'] = "Registration Failed";
$locale['457'] = "Send mail failed, please contact the <a href='mailto:".$settings['siteemail']."'>Site Administrator</a>.";
$locale['458'] = "Registration failed for the following reason(s):";
$locale['459'] = "Please Try Again";
// Register Form
$locale['500'] = "Please enter your details below. ";
$locale['501'] = "A verification email will be sent to your specified email address. ";
$locale['502'] = "Fields marked <span style='color:#ff0000;'>*</span> must be completed.
Your user name and password is case-sensitive.";
$locale['503'] = " You can enter additional information by going to Edit Profile once you are logged in.";
$locale['504'] = "Validation Code:";
$locale['505'] = "Enter Validation Code:";
$locale['506'] = "Register";
$locale['507'] = "The registration system is currently disabled.";
$locale['508'] = "Terms of Agreement";
$locale['509'] = "I have read the <a href='".BASEDIR."print.php?type=T' target='_blank'>Terms of Agreement</a> and I agree with them.";
// Validation Errors
$locale['550'] = "Please specify a user name.";
$locale['551'] = "Please specify a password.";
$locale['552'] = "Please specify an email address.";
?>