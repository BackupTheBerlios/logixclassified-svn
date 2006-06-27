<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: confirm.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Confirmation Reg.
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

    if (!$hash || !$nick) {
	$confirm=$error[14];
    } else {
	$query = mysql_query("SELECT * FROM ".$prefix."userdata WHERE language='xc' AND username='$nick'") or died(mysql_error());
	$db = mysql_fetch_array($query);
	$myhash = substr(md5($secret.$db[username]),0,10);

	if (!$db[id] || $myhash!=$hash) {
	    $confirm=$error[15];
	} else {

	    $is_success_first = mysql_query("UPDATE ".$prefix."userdata SET language='' WHERE id=$db[id]") or died(mysql_error());

	    if (!$is_success_first) {
		$confirm=$error[16];
	    } else {

		$username=$db[username];
		$password=$db[password];
		$email=$db[email];
		$sex=$db[sex];

		logging("X","$db[id]","$username","AUTH: confirmed registration","");

		$confirm=2;
		if ($auto_login) {
		    $login=login($username, $password);
		    if ($login!="2") {
			$confirm=$error[15];
		    } else {
			$confirm=3;
		    }
		}

		// only if forum-interface
		if ($forum_db_name && $forum_enable && $forum_interface) {
		    include ("$forum_interface");
		}
		// only if chat-interface
		if ($chat_db_name && $chat_enable && $chat_interface) {
		    include ("$chat_interface");
		}

		if ($conf_notify) {
		    $mailto = "$conf_notify";
 		    $subject = "NOTIFY $mail_msg[0]";
		    $message = "$mail_msg[8]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$gender[$sex]\n";
		    $from = "From: $admin_email\r\nReply-to: $admin_email\r\n";

	    	    @mail($mailto, $subject, $message, $from);
		}

	    	if ($confirm_mail) {
		    $mailto = "$email";
		    $subject = "NOTIFY $mail_msg[9]";
		    $message = "$mail_msg[10]$username\n\n$mail_msg[11]";
		    $from = "From: $admin_email\r\nReply-to: $admin_email\r\n";

		    @mail($mailto, $subject, $message, $from);
		}

	    }
	}
    }

if ($force_addad && ($confirm==2 || $confirm==3)) {
    $cookietime=time()+(3600*24*356);
    setcookie("ForceAddAd", "1", $cookietime, "$cookiepath"); // 1 Year
}

if ($auto_login && $confirm==3) {
    if ($force_addad && $cookietime){
	header(headerstr("classified.php?status=1"));
    } else {
	header(headerstr("main.php?status=1"));
    }
}

#  The Head-Section
#################################################################################################
include ($HEADER);

#  The Main-Section
#################################################################################################
echo"<p>&nbsp; \n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
        	if ($confirm == 2) {
include ("$language_dir/confirm_done.inc");
		} else {
include ("$language_dir/confirm_error.inc");
		}
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo"  </table>\n";

#  The Foot-Section
#################################################################################################
include ($FOOTER);
?>