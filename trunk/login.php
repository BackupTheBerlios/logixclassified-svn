<?php
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : login.php
#  e-mail               : support@phplogix.com
#  purpose              : Member's login
#$Id$
#License : GPL
#################################################################################################
 #TODO: GPL license header in every file. clean this up a bit, build login function, do away with the header() BS
require ("library.php");

if (strpos("$loginlink","errormessage")) {
    $loginlink=substr("$loginlink",0,(strpos("$loginlink","errormessage")-1));
}

if (strpos("$loginlink","?")) {
    $loginlink=$loginlink."&";
} else {
    $loginlink=$loginlink."?";
}

if (!$username || !$password) {
    header(headerstr($loginlink."status=3"));
} else {
    $login = login($username, $password);

    if ($login!="2") {
    $errormessage=rawurlencode($login);
    header(headerstr($loginlink."status=2&errormessage=$errormessage"));
    exit;
    } else {
    // clear useronline (guest entry)
        mysql_query("DELETE FROM ".$prefix."useronline WHERE ip='$ip' AND username=''");
    header(headerstr($loginlink."status=1"));
    exit;
    }
}
?>