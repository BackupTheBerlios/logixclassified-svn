<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: register_submit.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Submit Member Registration
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
include($HEADER);

#  The Main-Section
#################################################################################################
$username 	= trim($username);
$password 	= trim($password);
$password2 	= trim($password2);
$email		= trim($email);

if (!memberfield("1","sex","","")) {$sex="n";}
if ($homepage=="http://") {$homepage="";}

    $query = mysql_query("select * FROM ".$prefix."config WHERE type='member' AND name<>'picture' AND name<>'newsletter' AND name<>'sex'") or died(mysql_error());
    while ($db = mysql_fetch_array($query)) {
	$fieldname=$db[name];
	$requirederror.= memberfieldinputcheck ("$fieldname",$_POST[$fieldname]);
    }

    if (!$username) $requirederror.="username ";
    if (!$password) $requirederror.="password ";
    if (!$password2) $requirederror.="password2 ";
    if (!$email) $requirederror.="email ";
    if (!$acceptterms) $requirederror.="terms ";

    if ($requirederror) {
	$register=$error[14]."<BR>( ".$requirederror.")<BR>";
    } else {
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$", $email)) {
	# if (!eregi("^([a-z0-9]+)(([a-z0-9._-]+))*[@]([a-z0-9]+)([._-]([a-z0-9]+))*[.]([a-z0-9]){2}([a-z0-9])?$", $email)) {
	    $register=$error[4];
	}
	if ($sex == "") {
	    $register=$error[11];
	}
	if (strlen($username) < 3) {
	    $register=$error[1];
	}
	if (strlen($username) > 20) {
	    $register=$error[2];
	}
	if (!ereg("^[[:alnum:]_-]+$", $username)) {
	    $register=$error[3];
	}
	if ($password != $password2) {
	    $register=$error[0];
	}
	if (strlen($password) < 3) {
	    $register=$error[5];
	}
	if (strlen($password) > 20) {
	    $register=$error[6];
	}
	if (!ereg("^[[:alnum:]_-]+$", $password)) {
	    $register=$error[7];
	}

	if (!$register) {
	    $query = mysql_query("select id from ".$prefix."userdata where username = '$username'");
	    $result = mysql_num_rows($query);

	    if ($result > 0) {
		$register=$error[12];
	    } else {

		$query = mysql_query("select id from ".$prefix."userdata where email = '$email'");
		$result = mysql_num_rows($query);

		if ($result > 0) {
		    $register=$error[13];
		} else {

		    if ($no_confirmation) {

			$is_success = mysql_query("insert into ".$prefix."userdata (username, password, email, sex,
			    newsletter, firstname, lastname, address, zip, city, state, country,
			    phone, cellphone, icq, homepage, hobbys, field1, field2, field3,
			    field4, field5, field6, field7, field8, field9, field10, registered, timezone, dateformat)
			    values ('$username', '$password', '$email', '$sex',
			    '$newsletter', '$firstname', '$lastname', '$address', '$zip', '$city', '$state', '$country',
			    '$phone', '$cellphone', '$icq', '$homepage', '$hobbys', '$field1', '$field2', '$field3',
			    '$field4', '$field5', '$field6', '$field7', '$field8', '$field9', '$field10', '$timestamp',
			    '$timezone','$_POST[dateformat]' )") or died(mysql_error());

			if ($is_success) {
				// only if forum-interface
				if ($forum_db_name && $forum_enable && $forum_interface) {
				    include ("$forum_interface");
				}
		                // only if chat-interface
	                        if ($chat_db_name && $chat_enable && $chat_interface) {
				    include ("$chat_interface");
				}
			}

			$mailto = "$email";
			$subject = "$mail_msg[0]";
			$message = "$mail_msg[1]$username\n\n$mail_msg[2]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$gender[$sex]\n\n$mail_msg[7]";
			$from = "From: $admin_email\r\nReply-to: $admin_email\r\n";

			@mail($mailto, $subject, $message, $from);

			if ($auto_login) {
			    $login=login($username, $password);
			    if ($login!="2") {
				$register="$error[15]";
			    } else {
				$register=3;
			    }
			}

		    } else {

			$hash = substr(md5($secret.$username),0,10);
			$is_success = mysql_query("insert into ".$prefix."userdata (username, password, email, sex,
			    newsletter, firstname, lastname, address, zip, city, state, country,
			    phone, cellphone, icq, homepage, hobbys, field1, field2, field3,
			    field4, field5, field6, field7, field8, field9, field10, registered, timezone, dateformat, language)
			    values ('$username', '$password', '$email', '$sex',
			    '$newsletter', '$firstname', '$lastname', '$address', '$zip', '$city', '$state', '$country',
			    '$phone', '$cellphone', '$icq', '$homepage', '$hobbys', '$field1', '$field2', '$field3',
			    '$field4', '$field5', '$field6', '$field7', '$field8', '$field9', '$field10', '$timestamp',
			    '$timezone','$_POST[dateformat]','xc' )") or died(mysql_error());

			if ($is_success) {

			    $confirmurl = ("$url_to_start" . "/confirm.php?hash=" . "$hash" . "&nick=" . "$username");
			    $aolconfirmurl = ("AOL: <A HREF=\" $url_to_start" . "/confirm.php?hash=" . "$hash" . "&nick=" . "$username \">CLICK HERE</A>");

			    $mailto = "$email";
			    $subject = "$mail_msg[0]";
			    if (strstr($mailto,"aol")) { // For AOL-Users
				$message = "$mail_msg[1]$username\n\n$mail_msg[2]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$gender[$sex]\n\n$mail_msg[6]\n\n$aolconfirmurl\n\n$mail_msg[7]";
			    } else {
			        $message = "$mail_msg[1]$username\n\n$mail_msg[2]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$gender[$sex]\n\n$mail_msg[6]\n\n$confirmurl\n\n$mail_msg[7]";
			    }
			    $from = "From: $admin_email\r\nReply-to: $admin_email\r\n";
			    @mail($mailto, $subject, $message, $from);
			}

		    }

		    logging("X","","$username","AUTH: new registration","Password: $password, EMail: $email, Sex: $sex");

		    if ($reg_notify) {
			    $mailto = "$reg_notify";
 			    $subject = "NOTIFY $mail_msg[0]";
			    $message = "$mail_msg[8]$username\n$mail_msg[3]$password\n$mail_msg[4]$email\n$mail_msg[5]$gender[$sex]\n";
			    $from = "From: $admin_email\r\nReply-to: $admin_email\r\n";

			    @mail($mailto, $subject, $message, $from);
		    }
		    $register=2;
		}
	    }
	}
    }


if ($no_confirmation && $register == 2) {
    if ($force_addad) {
	$cookietime=time()+(3600*24*356);
	setcookie("ForceAddAd", "1", $cookietime, "$cookiepath"); // 1 Year
    }
    header(headerstr("main.php"));
}

echo"<p>&nbsp; \n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
		if ($register == 2) {
        	    include ("$language_dir/register_done.inc");
		} else {
        	    include ("$language_dir/register_error.inc");
		}
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo"  </table>\n";

#  The Foot-Section
#################################################################################################
include($FOOTER);
?>