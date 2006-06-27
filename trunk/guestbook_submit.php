<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: guestbook_submit.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Submit Guestbook Data
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Main-Section
#################################################################################################
if (!$in && !$delid) {
    header(headerstr("guestbook.php?status=3"));
    exit;
} elseif ($delid && $_SESSION[susermod]) {

    mysql_query("DELETE FROM ".$prefix."guestbook WHERE id='$delid'") or died("Database Query Error");

    header(headerstr("guestbook.php?status=12"));
    exit;

} else {

    if (isbanned($_SESSION[suserid])) {
	header(headerstr("guestbook.php?status=11&errormessage=$error[27]"));
        exit;
    }

    $add_date = $timestamp;

    $in = strip_array($in);

    $in[message] = encode_msg($in[message]);    // Add SQL compatibilty & Smilie Convert

    $in['http']    = str_replace("http://", "", $in['http']);   // Remove http:// from URLs

    if ($in['icq'] != "" && ($in['icq'] < 1000 || $in['icq'] > 999999999)) { died("Non-valid ICQ entry, if you do not have an icq account please leave blank."); }
    if (!eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$",$in['email']) && $in['email'] != "") { died("Non-valid Email entry, please enter your correct e-mail address or if you don't have one leave it blank."); }
    if (strlen($in['message']) < $limit["0"] || strlen($in['message']) > $limit["1"]) { died("Sorry, your message has to be between $limit[0] and $limit[1] characters."); }

    if ($in['email'] == "") { $in['email'] = "none"; }
    if ($in['icq'] == "") { $in['icq'] = 0; }
    if ($in['http'] == "") { $in['http'] = "none"; }
    if ($in['location'] == "0") { $in['location'] = "none"; }
    $in[browser] = $client;

    mysql_query("INSERT INTO ".$prefix."guestbook (name, email, http, icq, message, timestamp, ip, location, browser)
    VALUES('$in[name]', '$in[email]','$in[http]','$in[icq]','$in[message]','$add_date', '$ip','$in[location]','$in[browser]')")
    or died("Database Query Error");

    if ($gb_notify) {
        @mail("$gb_notify","NOTIFY new Guestbook Entry","Name: $in[name]\nLocation: $in[location]\nE-Mail: $in[email]\nICQ: $in[icq]\nWWW: $in[http]\n\n$in[message]","From: $gb_notify");
    }

    logging("X","$_SESSION[suserid]","$_SESSION[susername]","GB: Entry added","Name: $in[name] - Msg: $in[message]");

    header(headerstr("guestbook.php?status=12"));
    exit;
}

?>