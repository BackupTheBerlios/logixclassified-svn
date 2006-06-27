<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: smiliehelp.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Show's the Smilie DB
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
window_header("Smilies");

#  The Main-Section
#################################################################################################
if (!$_SESSION[suserid]) {

    include ("$language_dir/nologin.inc");

} else {

    echo "<table>\n";
    echo "<tr>\n";
    echo "<td><div class=\"maininputleft\">Code</div></td>\n";
    echo "<td></td>\n";
    echo "<td><div class=\"maintext\"></div></td>\n";
    echo "</tr>\n";

    $result = mysql_query("SELECT * FROM ".$prefix."smilies") or died("Query Error");
    while ($db = mysql_fetch_array($result)) {

	echo "<tr>\n";
        echo "<td><div class=\"maininputleft\">$db[code]</div></td>\n";
	echo "<td>&nbsp&nbsp<img src=".$image_dir."/smilies/".$db[file]."></td>\n";
        echo "<td><div class=\"maintext\">$db[name]</div></td>\n";
	echo "</tr>\n";

    }

    echo "</table>\n";

}

#  The Foot-Section
#################################################################################################
window_footer();
?>
