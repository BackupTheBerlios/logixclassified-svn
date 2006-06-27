<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: member_chemail.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Change Member's E-Mail
#
#################################################################################################


require("library.php");


if (!$_SESSION[suserid]) {
    header(headerstr("main.php?status=2"));
    exit;
}

if (!$email || !$email2) {
    header(headerstr("members.php?status=3"));
    exit;
} else {
    $email=trim($email);
    $email2=trim($email2);

    if ($email != $email2) {
	$chemail=$error[14];
    } else {
	if (!eregi("^([a-z0-9]+)([._-]([a-z0-9]+))*[@]([a-z0-9]+)([._-]([a-z0-9]+))*[.]([a-z0-9]){2}([a-z0-9])?$", $email)) {
	    $chemail=$error[4];
	} else {
	    $query = mysql_query("select id from ".$prefix."userdata where email = '$email'");
	    $result = mysql_num_rows($query);
	    if ($result > 0) {

		list($uid_from_db) = mysql_fetch_row($query);
		if ($uid_from_db != $_SESSION[suserid]) {
		    $chemail=$error[13];
		} else {
		    $chemail=$error[23];
		}

	    } else {

	        $mdhash = substr(md5($_SESSION[suserid].$email.$secret),0,10);
		$query = mysql_query("insert into ".$prefix."confirm_email values ('$_SESSION[suserid]', '$email', '$mdhash', now())");
		if (!$query) {
		    $chemail=$error[20];
	        } else {

		    $confirmurl = ("$url_to_start" . "/confirm_email.php?mdhash=" . "$mdhash" . "&id=" . "$_SESSION[suserid]" . "&email=" . "$email");

		    $mailto = "$email";
		    $subject = "$mail_msg[16]";
		    $message = "$mail_msg[17]\n\n$confirmurl\n\n$mail_msg[18]";
		    $from = "From: $admin_email\r\nReply-to: $admin_email\r\n";
		    @mail($mailto, $subject, $message, $from);

		    logging("X","$_SESSION[suserid]","$_SESSION[susername]","AUTH: new email change","");

		    $chemail=2;
		}
	    }
	}
    }

    if ($chemail != 2) {
        $errormessage=rawurlencode($chemail);
	header(headerstr("members.php?status=6&errormessage=$errormessage"));
	exit;
    } else {
        $textmessage=rawurlencode($text_msg[2]);
        header(headerstr("members.php?status=4&textmessage=$textmessage"));
	exit;
    }

}

?>