<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: lostpass.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Send lost pwd to Member
#
#################################################################################################

require ("library.php");

if (!$email) {
    $lostpass=$error[14];
} else {

    $query = mysql_query("select id,username,password from ".$prefix."userdata where email='$email'");
    $result = mysql_num_rows($query);

    if ($result < 1) {
	$lostpass=$error[19];
    } else {
	list($id,$username,$password) = mysql_fetch_row($query);

	$confirmurl = ("$url_to_start" . "/confirm.php?hash=" . "$hash" . "&username=" . "$username");

	$mailto = "$email";
	$subject = "$mail_msg[12]";
	$message = "$mail_msg[13]$username\n\n$mail_msg[14]$username\n$mail_msg[3]$password\n\n$mail_msg[15]";
	$from = "From: $admin_email\r\nReply-to: $admin_email\r\n";

	@mail($mailto, $subject, $message, $from);
	logging("X","$id","$username","AUTH: lost password sent","");

	$lostpass=2;
    }

}

if ($lostpass == 2) {
    header(headerstr("main.php?status=4"));
    exit;
} else {
    header(headerstr("main.php?status=2"));
    exit;
}
?>