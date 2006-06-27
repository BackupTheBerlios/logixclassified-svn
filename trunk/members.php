<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: members.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Members Area
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

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
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";//
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";


if (!$_SESSION[suserid]) {
	include ("$language_dir/nologin.inc");
} else {

	if ($choice=="myprofile") {
	    echo"           <table align=\"center\" width=\"100%\">\n";
	    echo"            <tr>\n";
	    echo"             <td>\n";
	    echo"              <div class=\"mainmenu\">\n";
	    echo"          	   <a href=\"members.php\" onmouseover=\"window.status='$members_link_desc';
				    return true;\" onmouseout=\"window.status=''; return true;\">$members_link</a>\n";
	    echo"              </div>\n";
	    echo"             </td>\n";
	    echo"             <td width=\"30%\">\n";
	    echo "		<div class=\"mainheader\">$myprofile_head</div>\n";
	    echo"             </td>\n";
	    echo"            </tr>\n";
	    echo"           </table>\n";
	    include ("member.php");
	} elseif ($choice=="details") {
	    echo"           <table align=\"center\" width=\"100%\">\n";
	    echo"            <tr>\n";
	    echo"             <td>\n";
	    echo"              <div class=\"mainmenu\">\n";
	    echo"          	   <a href=\"members.php\" onmouseover=\"window.status='$members_link_desc';
				    return true;\" onmouseout=\"window.status=''; return true;\">$members_link</a>\n";
            echo"                  $menusep<a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
	                                        return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
	    echo"              </div>\n";
	    echo"             </td>\n";
	    echo"             <td width=\"30%\">\n";
	    echo "		<div class=\"mainheader\">$memberdet_head</div>\n";
	    echo"             </td>\n";
	    echo"            </tr>\n";
	    echo"           </table>\n";
    	    include ("members_details.php");
	} elseif ($choice=="ads") {
	    echo"           <table align=\"center\" width=\"100%\">\n";
	    echo"            <tr>\n";
	    echo"             <td>\n";
	    echo"              <div class=\"mainmenu\">\n";
	    echo"          	   <a href=\"members.php\" onmouseover=\"window.status='$members_link_desc';
				    return true;\" onmouseout=\"window.status=''; return true;\">$members_link</a>\n";
            echo"                  $menusep<a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
	                                        return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
	    echo"              </div>\n";
	    echo"             </td>\n";
	    echo"             <td width=\"30%\">\n";
	    echo "		<div class=\"mainheader\">$memberads_head</div>\n";
	    echo"             </td>\n";
	    echo"            </tr>\n";
	    echo"           </table>\n";
    	    include ("members_ads.php");
	} else {
	    // Members Main
	    echo"           <table align=\"center\" width=\"100%\">\n";
	    echo"            <tr>\n";
	    echo"             <td>\n";
	    echo"              <div class=\"mainmenu\">\n";
	    echo" 		   <a href=\"members.php?choice=myprofile\" onmouseover=\"window.status='$members_link2desc'; return true;\" onmouseout=\"window.status=''; return true;\">$members_link2</a>\n";
#	    echo" 		   $menusep<a href=\"members.php?choice=search\" onmouseover=\"window.status='$members_link1desc'; return true;\" onmouseout=\"window.status=''; return true;\">$members_link1</a>\n";
	    echo"              </div>\n";
	    echo"             </td>\n";
	    echo"             <td width=\"20%\">\n";
	    echo"              <div class=\"mainheader\">$members_head</div>\n";
	    echo"             </td>\n";
	    echo"            </tr>\n";
	    echo"           </table>\n";
	    include ("members_overview.php");

	}
}
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo" </table>\n";

#  PLEASE DO NOT REMOVE OR EDIT THIS COPYRIGHT-NOTICE !!! THANKS !!! ################################################
echo" <p><div class=\"footer\">phpMembers Ver. $members_#$Id$&copy 2001-".date("Y")." by <a href=\"http://www.smartisoft.com\" target=\"_blank\">SmartISoft</a></div>\n";
#####################################################################################################################

echo"</td>\n";

#  The Right-Side-Section
#################################################################################################
echo"<td valign=\"top\" align=\"left\">\n";
include ("right.inc.php");
echo"</td>\n";
echo"</tr>\n";
echo"</table>\n";

#  The Foot-Section
#################################################################################################
include ($FOOTER);
?>