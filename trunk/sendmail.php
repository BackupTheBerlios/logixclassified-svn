<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: sendmail.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Sending Mail's
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
window_header("SendMail");

#  The Main-Section
#################################################################################################
if (strpos($client,"MSIE")) { // Internet Explorer Detection
    $field_height2="6";
    $field_size="50";
} else {
    $field_height2="4";
    $field_size="30";
}

if(!$floodprotect2 && $floodprotect) {$floodprotect2=$floodprotect;}

if ($logging_enable && $floodprotect2 && $floodprotect_mail && $_SESSION[suserid] && !$_SESSION[susermod]) { // check floodprotect
    $checktimestamp = $timestamp-(3600*$floodprotect2);
    $result = mysql_query("SELECT timestamp FROM ".$prefix."logging WHERE event='MAIL: sent' AND username='$_SESSION[susername]' AND timestamp>'$checktimestamp'");
    $count=mysql_num_rows($result);
    if ($floodprotect_mail<=$count) {
	echo "<br><br><br><br><center>ERROR: Floodprotect active !!! $count events logged last $floodprotect2 hour(s)</center>";
	exit;
    }

}

if ((($catid && $subcatid && $adid) || $friend) && !$action) {  	// Send Link to a Friend

    if (!$_SESSION[suserid]) {
	$dbu[username]="$sm_afriend";
	$dbu[email]="$sm_anonym";
    } else {
	$resultu = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id='$_SESSION[suserid]'");
	$dbu = mysql_fetch_array($resultu);
    }

    $fromname="$bazar_name Webmaster";
    $fromemail="$admin_email";
    $subject="$bazar_name $sm_friendref";

    if ($friend && !$action) {
	$systext1="$dbu[username] [mailto:$dbu[email]] $sm_friendrefx";
	$systext2="$url_to_start";
	echo "<div class=\"mainheader\">$sm_friendhead</div>\n";
    } else {
	$systext1="$dbu[username] [mailto:$dbu[email]] $sm_systext";
	$systext2="$url_to_start/classified.php?catid=$catid&subcatid=$subcatid&adid=$adid";
	echo "<div class=\"mainheader\">$sm_linkhead</div>\n";
    }


    echo "<br>\n";
    echo "<table align=\"center\" width=\"100%\">\n";
    echo "<form enctype=\"text\" action=$_SERVER[PHP_SELF] METHOD=POST>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_fromname </div></td>\n";
    echo "<td class=\"classadd2\"><input type=\"text\" name=\"fromname\" value=\"$fromname\" size=\"$field_size\" maxlength=\"50\" readonly></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_fromemail</div></td>\n";
    echo "<td class=\"classadd2\"><input type=\"text\" name=\"fromemail\" value=\"$fromemail\" size=\"$field_size\" maxlength=\"50\" readonly></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_toname </div></td>\n";
    echo "<td class=\"classadd2\"><input type=text name=\"toname\" size=\"$field_size\" maxlength=\"50\" value=\"\"></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_toemail </div></td>\n";
    echo "<td class=\"classadd2\"><input type=text name=\"toemail\" size=\"$field_size\" maxlength=\"50\" value=\"\"></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_subject</div></td>\n";
    echo "<td class=\"classadd2\">$subject</td>\n";
    echo "<input type=\"hidden\" name=\"subject\" value=\"$subject\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_text </div></td>\n";
    echo "<td class=\"classadd2\">$systext1<br><div class=\"smallleft\">$systext2<br></div><textarea rows=\"6\" name=\"text\" cols=\"$field_size\"></textarea></td>\n";
    echo "<input type=\"hidden\" name=\"systext1\" value=\"$systext1\">\n";
    echo "<input type=\"hidden\" name=\"systext2\" value=\"$systext2\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"></td>\n";
    echo "<input type=hidden name=friend value=1>\n";
    echo "<input type=hidden name=action value=submit>\n";
    echo "<td class=\"classadd2\"><br><input type=submit value=$sm_submit></td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";

} elseif (($_SESSION[suserid] || $anonymous_mail) && $adid && !$action) { // Send E-Mail

    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id=$adid");
    $db = mysql_fetch_array($result);
    $resultu = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id=$db[userid]");
    $dbu = mysql_fetch_array($resultu);
    $toname	=$dbu[username];
    $toemail=$dbu[email];
    $toemailid=$dbu[id];


    if ($_SESSION[suserid]) {
	$resultu = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id='$_SESSION[suserid]'");
	$dbu = mysql_fetch_array($resultu);
        $fromname=$dbu[username];
	$fromemail=$dbu[email];
	$readonly="READONLY";
    } else {
        $fromname="";
	$fromemail="";
	$readonly="";
    }

    echo "<div class=\"mainheader\">$sm_mailhead</div>\n";

    $subject="$bazar_name $sm_answer$adid ($db[header])";

    echo "<br>\n";
    echo "<table align=\"center\" width=\"100%\">\n";
    echo "<form enctype=\"multipart/form-data\" name=\"doit\" action=$_SERVER[PHP_SELF] METHOD=POST>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_fromname </div></td>\n";
    echo "<td class=\"classadd2\"><input type=\"text\" name=\"fromname\" value=\"$fromname\" size=\"$field_size\" maxlength=\"50\" $readonly></td>\n";
    echo "<input type=\"hidden\" name=\"fromid\" value=\"$dbu[id]\">\n";
    echo "<input type=\"hidden\" name=\"catid\" value=\"$db[catid]\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_fromemail</div></td>\n";
    echo "<td class=\"classadd2\"><input type=\"text\" name=\"fromemail\" value=\"$fromemail\" size=\"$field_size\" maxlength=\"50\" $readonly>\n";
    if (!$webmail_enable) echo "<input type=\"checkbox\" name=\"cc\">$sm_cc\n";
    echo "</td></tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_toname </div></td>\n";
    echo "<td class=\"classadd2\">$toname</td>\n";
    echo "<input type=\"hidden\" name=\"toname\" value=\"$toname\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_toemail </div></td>\n";
    echo "<td class=\"classadd2\">************</td>\n";
    echo "<input type=\"hidden\" name=\"toemailid\" value=\"$toemailid\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_subject</div></td>\n";
    echo "<td class=\"classadd2\">$subject</td>\n";
    echo "<input type=\"hidden\" name=\"subject\" value=\"$subject\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_text</div></td>\n";
    echo "<td class=\"classadd2\"><textarea rows=\"$field_height2\" name=\"text\" cols=\"$field_size\"></textarea></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";
    echo "<td class=\"classadd2\"><input type=file name=\"pic1\" size=\"30\" maxlength=\"50\" value=\"\"><br>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";
    echo "<td class=\"classadd2\"><input type=file name=\"pic2\" size=\"30\" maxlength=\"50\" value=\"\"><br>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";
    echo "<td class=\"classadd2\"><input type=file name=\"pic3\" size=\"30\" maxlength=\"50\" value=\"\"><br>\n";
    echo "<div class=\"mainmenu\">[max. $pic_maxsize Byte, $adadd_picnos]</div></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"></td>\n";
    echo "<input type=hidden name=adid value=$adid>\n";
    echo "<input type=hidden name=action value=submit>\n";
    echo "<td class=\"classadd2\"><div class=\"mainmenu\"><br><input type=submit value=$sm_submit> $adadd_submitone</div></td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";

} elseif (($_SESSION[suserid] || $anonymous_mail) && $username && !$action) { // Send E-Mail to username (useronline)

    $resultu = mysql_query("SELECT * FROM ".$prefix."userdata WHERE username='$username'");
    $dbu = mysql_fetch_array($resultu);
    $toname	=$dbu[username];
    $toemail=$dbu[email];
    $toemailid=$dbu[id];

    if ($_SESSION[suserid]) {
	$resultu = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id='$_SESSION[suserid]'");
	$dbu = mysql_fetch_array($resultu);
        $fromname=$dbu[username];
	$fromemail=$dbu[email];
	$readonly="READONLY";
    } else {
        $fromname="";
	$fromemail="";
	$readonly="";
    }

    echo "<div class=\"mainheader\">$sm_mailhead</div>\n";

    $subject="$bazar_name $sm_answer$adid ($db[header])";

    echo "<br>\n";
    echo "<table align=\"center\" width=\"100%\">\n";
    echo "<form enctype=\"multipart/form-data\" name=\"doit\" action=$_SERVER[PHP_SELF] METHOD=POST>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_fromname </div></td>\n";
    echo "<td class=\"classadd2\"><input type=\"text\" name=\"fromname\" value=\"$fromname\" size=\"$field_size\" maxlength=\"50\" $readonly></td>\n";
    echo "<input type=\"hidden\" name=\"fromid\" value=\"$dbu[id]\">\n";
    echo "<input type=\"hidden\" name=\"catid\" value=\"$db[catid]\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_fromemail</div></td>\n";
    echo "<td class=\"classadd2\"><input type=\"text\" name=\"fromemail\" value=\"$fromemail\" size=\"$field_size\" maxlength=\"50\" $readonly>\n";
    if (!$webmail_enable) echo "<input type=\"checkbox\" name=\"cc\">$sm_cc\n";
    echo "</td></tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_toname </div></td>\n";
    echo "<td class=\"classadd2\">$toname</td>\n";
    echo "<input type=\"hidden\" name=\"toname\" value=\"$toname\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_toemail </div></td>\n";
    echo "<td class=\"classadd2\">************</td>\n";
    echo "<input type=\"hidden\" name=\"toemailid\" value=\"$toemailid\">\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_subject</div></td>\n";
    echo "<td class=\"classadd2\"><input type=\"text\" name=\"subject\" value=\"$newsubject\" size=\"$field_size\" maxlength=\"50\"></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$sm_text</div></td>\n";
    echo "<td class=\"classadd2\"><textarea rows=\"$field_height2\" name=\"text\" cols=\"$field_size\">$newtext</textarea></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";
    echo "<td class=\"classadd2\"><input type=file name=\"pic1\" size=\"30\" maxlength=\"50\" value=\"\"><br>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";
    echo "<td class=\"classadd2\"><input type=file name=\"pic2\" size=\"30\" maxlength=\"50\" value=\"\"><br>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $adadd_pic </div></td>\n";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";
    echo "<td class=\"classadd2\"><input type=file name=\"pic3\" size=\"30\" maxlength=\"50\" value=\"\"><br>\n";
    echo "<div class=\"mainmenu\">[max. $pic_maxsize Byte, $adadd_picnos]</div></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"></td>\n";
    echo "<input type=hidden name=adid value=$adid>\n";
    echo "<input type=hidden name=action value=submit>\n";
    echo "<td class=\"classadd2\"><div class=\"mainmenu\"><br><input type=submit value=$sm_submit> $adadd_submitone</div></td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";

} elseif (($_SESSION[suserid] || $anonymous_mail || $friend) && $action=="submit") { // Do it NOW !!!!!!!!!!!

    if ($webmail_enable && !$friend) { //WebMail or NOT ???
        require ("library_webmail.php");
	unset($sm_emailheader);
	unset($sm_emailfooter);
    } else {
        require ("library_mail.php");
    }
    $mail = new phpmailer;

    if ($toemailid && !$toemail) {
	$resultu = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id=$toemailid");
	$dbu = mysql_fetch_array($resultu);
	$toemail = $dbu[email];
    }

    if ($fromname && $fromemail && $toname && $toemail && $subject && $text) {	// Send E-Mail

	if ($systext1) {
	    $body=$systext1."\n".$systext2."\n\n".$sm_emailheader.badwordsmail($text).$sm_emailfooter;
	} else {
	    $body=$sm_emailheader.badwordsmail($text).$sm_emailfooter;
	}

	$mail->From = "$fromemail";
	$mail->FromName = "$fromname";
	$mail->WordWrap = 75;
	$mail->UseMSMailHeaders = true;
	$mail->AddCustomHeader("X-Mailer: $bazar_name $bazar_#$Id$- Email Interface");
	$mail->AddAddress("$toemail", "$toname");
	if ($cc) { $mail->AddBCC($fromemail, $fromname); }
	$mail->Subject = stripslashes($subject);
	if ($webmail_enable) {
	    $mail->Body = addslashes($body);
	    $mail->Subject = addslashes($subject);
	} else {
	    $mail->Body = stripslashes($body);
	    $mail->Subject = stripslashes($subject);
	}
	if ($pic1 != "none" && $pic1) { $mail->AddAttachment("$pic1", "$pic1_name"); }
	if ($pic2 != "none" && $pic2) { $mail->AddAttachment("$pic2", "$pic2_name"); }
	if ($pic3 != "none" && $pic3) { $mail->AddAttachment("$pic3", "$pic3_name"); }

	if(!$mail->Send()) {
	    echo "There was an error sending the message";
    	    exit;
	}

	if ($mail_notify) {
            $subject_notify = "NOTIFY E-Mail from $fromname<$fromemail> to $toname<$toemail>";

	    if ($webmail_enable && !$friend) { //WebMail or NOT ???
		mail($mail_notify, $subject_notify, stripslashes($body), "From:$admin_email");
	    } else {
        	$mail->ClearAllRecipients();
		$mail->AddAddress($mail_notify);
		$mail->From     = "$admin_email";
		$mail->Subject  = "$subject";
		$mail->Send();
	    }
	}

	if ($webmail_notify && $webmail_enable && !$friend) { //WebMail AND Notify or NOT ???
	    mail($toemail, $mail_msg[23], $mail_msg[24], "From:$admin_email");
	}

	if ($sales_option && $fromid) {
	    sales_countevent(3,$fromid,$catid);
	}

	if ($adid) {// Stat Answered Counter
	    mysql_query("update ".$prefix."ads set answered=answered+1 where id=$adid") or died(mysql_error());
	}

	logging("X","$_SESSION[suserid]","$_SESSION[susername]","MAIL: sent","E-Mail from $fromname<$fromemail> to $toname<$toemail>, Subject: $subject");
	include ("$language_dir/sendmail_done.inc");

    } else {									// Empty Field

	logging("X","$_SESSION[suserid]","$_SESSION[susername]","MAIL: error","E-Mail from $fromname<$fromemail> to $toname<$toemail>, Subject: $subject");
	include ("$language_dir/sendmail_error.inc");

    }

    echo "<center><table><tr><td>\n";
    echo "<form action=javascript:history.back(1) METHOD=POST><input type=submit value=$back></form>\n";
    echo "</td><td>\n";
    echo "<form action=javascript:window.close() METHOD=POST><input type=submit value=$close></form>\n";
    echo "</td></tr></table></center>\n";

} elseif (!($_SESSION[suserid] || $anonymous_mail)) {

    include ("$language_dir/nologin.inc");

} else {				// Error

    died("FATAL Error !!!");

}

#  The Foot-Section
#################################################################################################
window_footer();
?>