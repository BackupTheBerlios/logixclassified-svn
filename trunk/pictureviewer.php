<?
################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: pictureviewer.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: View Picture
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
window_header("Picture");

#  The Main-Section
#################################################################################################
if (!$_SESSION[suserid] && !$bazarfreeread) {
    include ("$language_dir/nologin.inc");
} else {

    echo "<center>";
    if ($_GET[pic] && !$_GET[id]) {
	echo "<img src=\"$_GET[pic]\" border=\"0\">";
    } elseif(!$_GET[pic] && $_GET[id]) {
	echo "<img src=\"picturedisplay.php?id=$_GET[id]\" border=\"0\">";
    } else {
	echo "NO PICTURE AVAILIABLE";
    }
    echo "</center>";

}

#  The Foot-Section
#################################################################################################
window_footer();
?>