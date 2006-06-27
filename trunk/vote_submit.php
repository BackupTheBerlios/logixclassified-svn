<?
#################################################################################################
#
#  project           : Logix Classifieds
#  filename          : vote_submit.php
#  last modified by  : 
#  e-mail            : support@phplogix.com
#  purpose           : Submit a Vote
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

if ($_SESSION[suserid] || $votefree) {

    if (!$vote) {
	header(headerstr($source."?status=8"));
    } else {
	if (isbanned($_SESSION[suserid])) {
    	    // IP banned, Do nothing !!!
	    $errormessage=rawurlencode($error[27]);
	    $headerstr="status=9&errormessage=$errormessage";
	} elseif ($vote && ($_SESSION[suserlastvote]+($vote_cookie_time*3600))>$timestamp && $vote_cookie_time) {
    	    // Cookie is set - Already voted, Do nothing !!!
	    $errormessage=rawurlencode($error[25]);
	    $headerstr="status=9&errormessage=$errormessage";
        } elseif ($vote) {
    	    mysql_query("update ".$prefix."votes set votes=votes+1 where id='$vote'") or died("Database Query Error");
	    if ($_SESSION[suserid]) {
    		mysql_query("update ".$prefix."userdata set votes=votes+1,lastvotedate=now(),lastvote='$timestamp' where id='$_SESSION[suserid]'") or died("Database Query Error");
	    }
	    $_SESSION[suserlastvote]=$timestamp;

	    logging("X","$_SESSION[suserid]","$_SESSION[susername]","VOTE: voted","");

	    $headerstr="status=10";
	}
    }

} else {
    $errormessage=rawurlencode($error[28]);
    $headerstr="status=9&errormessage=$errormessage";
}

if (strpos("$source","errormessage")) {
    $source=substr("$source",0,(strpos("$source","errormessage")-1));
}

if (strpos("$source","?")) {
    $source=$source."&";
} else {
    $source=$source."?";
}

header(headerstr($source.$headerstr));
?>