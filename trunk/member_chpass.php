<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: member_chpass.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Change Member's Password
#
#################################################################################################

require("library.php");

if (!$_SESSION[suserid]) {
    header(headerstr("main.php?status=2"));
    exit;
}

if (!$password || !$password2) {
    header(headerstr("members.php?choice=myprofile&change=pass&status=3"));
    exit;
} else {
    $password=trim($password);
    $password2=trim($password2);

    if ($password != $password2) {
	$chpass=$error[0];
    } else {
	if (strlen($password) < 3) {
	    $chpass=$error[5];
	}
	if (strlen($password) > 20) {
	    $chpass=$error[6];
	}
	if (!ereg("^[[:alnum:]_-]+$", $password)) {
	    $chpass=$error[7];
	}

	if (!$chpass) {
	    mysql_query("update ".$prefix."userdata set password = '$password' where id = '$_SESSION[suserid]'");

	    logging("X","$_SESSION[suserid]","$_SESSION[susername]","AUTH: password changed","New Password: $password");

	    $chpass=2;
	}
    }

    if ($chpass != 2) {
        $errormessage=rawurlencode($chpass);
	header(headerstr("members.php?choice=myprofile&change=pass&status=6&errormessage=$errormessage"));
        exit;
    } else {
	$username=$_SESSION[susername];
	logout();
	login($username,$password);
        header(headerstr("members.php?choice=myprofile&status=5"));
        exit;
    }
}
?>