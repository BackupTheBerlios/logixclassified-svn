<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: favorits.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Add Ad to Favorits
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
window_header("Favorits");

#  The Main-Section
#################################################################################################
if (!$_SESSION[suserid] || (!$adid && !$deladid)) {
    include ("$language_dir/nologin.inc");
} else {

    echo "<div class=\"mainheader\">$favorits_header</div>\n";
    echo "<div class=\"maintext\"><br><center>\n";

    if ($deladid) {
	$query = mysql_query("delete from ".$prefix."favorits where adid='$deladid' AND userid='$_SESSION[suserid]'") or died("Database Query Error");
        echo "$favorits_del<br>\n";
	echo "<br><form action=javascript:window.opener.location.reload();window.close() METHOD=POST>\n";
        echo "<input type=submit value=$close></form>\n";
    } elseif ($adid) {
	$query = mysql_query("SELECT * FROM ".$prefix."favorits WHERE userid=$_SESSION[suserid] AND adid=$adid") or died ("Database Error");
        $result = mysql_num_rows($query);
	if ($result<1) {
    	    $query = mysql_query("INSERT INTO ".$prefix."favorits VALUES('$_SESSION[suserid]','$adid')") or died ("Database Error");
	    echo "$favorits_done<br>\n";
	} else {
	    echo "$favorits_exist<br>\n";
	}
	echo "<br><form action=javascript:window.close() METHOD=POST>\n";
	echo "<input type=submit value=$close></form>\n";
    } else {
	echo "ERROR !!!\n";
    }

    echo "</div>\n";

}

#  The Foot-Section
#################################################################################################
window_footer();
?>