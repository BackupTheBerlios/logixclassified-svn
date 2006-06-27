<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: passphrase.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Passphrase Function
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  Main-Section
#################################################################################################
if ($enteredsecret && $catid) {
    $md5secret=md5($enteredsecret);
    $cookietime=$timestamp+($passphrase_cookie_time*3600);
    setcookie("Passphrase_$catid", "$md5secret", $cookietime, "$cookiepath");
    setcookie("PassphraseUser_$catid", "$userid", $cookietime, "$cookiepath");

    // close window
    echo "<SCRIPT language=Javascript>opener.location.href=opener.location.href;window.close();</script>";

} elseif ($catid) {

    window_header($pass_head);

    echo "<div class=\"mainheader\">$pass_head</div>\n";
    echo "<br>\n";
    echo "<table align=\"center\" width=\"100%\">\n";
    echo "<form enctype=\"text\" action=$_SERVER[PHP_SELF] METHOD=POST>
	<div class=\"maininputleft\"><center>
        <input type=text name=\"enteredsecret\" value=\"\">
        <input type=hidden name=\"userid\" value=\"$userid\">
	<input type=hidden name=\"catid\" value=\"$catid\"><br><br>
        <input type=submit value=$submit></center></div>\n";
    echo "</form>\n";

    window_footer();
}
?>
