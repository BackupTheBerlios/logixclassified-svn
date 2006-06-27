<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: main.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Main File
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

if ($_COOKIE["checkviewed"] != "1") {
    setcookie("checkcookie", "1", $cookietime, "$cookiepath");
    if ($_COOKIE["checkviewed"] == "1") {
	$cookietime=time()+(24*3600*365);
	setcookie("checkviewed", "1", $cookietime, "$cookiepath");
	$firsttimeuser=true;
    }
    setcookie("checkcookie", "", 0, "$cookiepath");
}

#  The Head-Section
#################################################################################################
include ($HEADER);

#  The Menu-Section
#################################################################################################
include ("menu.inc.php");

#  The Left-Side-Section
#################################################################################################
$tmp_width = ($table_width+(2*$table_width_side)+10);
echo"<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$tmp_width\">\n";
echo"<tr>\n";
echo"<td valign=\"top\" align=\"right\">\n";
include ("left.inc.php");
echo"</td>\n";

#  The Main-Section
#################################################################################################
echo"<td valign=\"top\" align=\"left\">\n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" margin=1 width=\"$table_width\" height=\"$table_height\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
echo"         <div class=\"mainheader\">$main_head</div>\n";
echo"         <div class=\"maintext\">\n";
include ("$language_dir/main.inc");
echo"         </div>\n";
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo" </table>\n";
echo"</td>\n";

#  The Right-Side-Section
#################################################################################################
echo"<td valign=\"top\" align=\"left\">\n";
include ("right.inc.php");
echo"</td>\n";
echo"</tr>\n";
echo"</table>\n";

if ($firsttimeuser && $addfavorits) {
    echo "<script language=javascript>window.external.AddFavorite(\"$url_to_start\",\"$bazar_name\")</script>";
}

#  The Foot-Section
#################################################################################################
include ($FOOTER);
?>