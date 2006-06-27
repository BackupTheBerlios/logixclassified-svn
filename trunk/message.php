<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: message.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Show clear Text ErrorMessage in a popup-window
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
window_header($msgheader);

#  The Main-Section
#################################################################################################
echo "<body bgcolor=\"$bgcolor\">\n";
echo "<div class=\"mainheader\">".strtoupper($msgheader)."</div>\n";
echo "<div class=\"maintext\"><br><center>\n";
echo "$msgheader : ".stripslashes($msg)."<br>\n";
echo "<br><form action=javascript:window.close() METHOD=POST>\n";
echo "<input type=submit value=$close></form></center>\n";
echo "</div>\n";

#  The Foot-Section
#################################################################################################
window_footer();
?>