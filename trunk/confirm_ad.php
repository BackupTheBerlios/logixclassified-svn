<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: confirm_ad.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Ad Confirmation Reg.
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

if (!$id || !$hash) {
    $a_confirm=$error[14];
} else {
    $query = mysql_query("select * from ".$prefix."ads where id = '$id' AND timeoutnotify = '$hash'");
    $result = mysql_num_rows($query);
    if ($result < 1) {
	$a_confirm=$error[15];
    } else {
	mysql_query("update ".$prefix."ads set timeoutnotify = '',timeoutdays = timeoutdays+$timeoutconfirm where id = '$id'");
	$a_confirm=2;
    }
}


#  The Head-Section
#################################################################################################
include ($HEADER);

#  The Main-Section
#################################################################################################
echo"<p>&nbsp; \n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
        	if ($a_confirm != 2) {
include ("$language_dir/confirm_ad_error.inc");
		} else {
include ("$language_dir/confirm_ad_done.inc");
		}
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo"  </table>\n";

#  The Foot-Section
#################################################################################################
include ($FOOTER);
?>