<?php
 ##############################################################################################
#                                                                                            #
#                                   classified.php                                                #
# *                            -------------------                                           #
# *   begin                : Tuesday June 27, 2006                                           #
# *   copyright            : (C) 2006  Logix Classifieds Development Team                    #
# *   email                : support@phplogix.com                                            #
# *   VERSION:             : $Id$
#                                                                                            #
##############################################################################################
#    This program is free software; you can redistribute it and/or modify it under the       #
#    terms of the GNU General Public License as published by the Free Software Foundation;   #
#    either version 2 of the License, or (at your option) any later version.                 #
#                                                                                            #
#    This program is distributed in the hope that it will be useful, but                     #
#    WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS   #
#    FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.          #
#                                                                                            #
#    You should have received a copy of the GNU General Public License along with this       #
#    program; if not, write to:                                                              #
#                                                                                            #
#                        Free Software Foundation, Inc.,                                     #
#                        59 Temple Place, Suite 330,                                         #
#                        Boston, MA 02111-1307 USA                                           #
##############################################################################################
include("includes/timer.class.php");
$BenchmarkTimer = new c_Timer;
$BenchmarkTimer->start(); // Start benchmarking immediately
//$memusage =array();
#  Include Configs & Variables
#################################################################################################
require ("library.php");
//$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
#  The Head-Section
#################################################################################################
include ($HEADER);
//$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
#  The Menu-Section
#################################################################################################
include ("menu.inc.php");
//$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
#  The Left-Side-Section
#################################################################################################
$tmp_width = ($table_width+(2*$table_width_side)+10);
echo"<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$tmp_width\">\n";
echo"<tr>\n";
echo"<td valign=\"top\" align=\"right\">\n";
include ("left.inc.php");
//$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
echo"</td>\n";
#if ($_SESSION[suserid] || $bazarfreeread) {$table_height="";}

#  The Main-Section
#################################################################################################
echo"<td valign=\"top\" align=\"left\">\n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" margin=1 width=\"$table_width\" height=\"$table_height\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";//
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";


if ((empty($_SESSION['suserid']) && !$bazarfreeread) || (empty($_SESSION['suserid']) && $choice=="notify" || empty($_SESSION['suserid']) && $editadid || empty($_SESSION['suserid']) && $choice=="add" || empty($_SESSION['suserid']) && $choice=="my" || empty($_SESSION['suserid']) && $choice=="fav"))
{
    include ("$language_dir/nologin.inc");
    //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
}
else
{
    if ($force_addad && $_COOKIE['ForceAddAd']==1)
    {
        $choice="add";$editadid="";
    }

    if ($choice=="add" || $editadid) {
        echo"           <table align=\"center\" width=\"100%\">\n";
        echo"            <tr>\n";
        echo"             <td>\n";
        echo"              <div class=\"mainmenu\">\n";
        echo"              <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';
                    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";
        echo"          <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
                    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
        echo"              </div>\n";
        echo"             </td>\n";
        echo"             <td width=\"30%\">\n";
        if ($editadid) {
        echo "      <div class=\"mainheader\">$classedit_head</div>\n";
        } else {
        echo "      <div class=\"mainheader\">$classadd_head</div>\n";
        }
        echo"             </td>\n";
        echo"            </tr>\n";
        echo"           </table>\n";
        if ($force_addad && $_COOKIE["ForceAddAd"]==1) {
        echo $adadd_forceadd;
        } elseif (!$editadid) {
        echo $adadd_pretext;
        }
        include ("classified_upd.php");
        //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
    } elseif ($choice=="my") {
        echo"           <table align=\"center\" width=\"100%\">\n";
        echo"            <tr>\n";
        echo"             <td>\n";
        echo"              <div class=\"mainmenu\">\n";
        echo"              <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';
                    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";
        echo"          <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
                    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
        echo"              </div>\n";
        echo"             </td>\n";
        echo"             <td width=\"30%\">\n";
        echo "<div class=\"mainheader\">$classmy_head</div>\n";
        echo"             </td>\n";
        echo"            </tr>\n";
        echo"           </table>\n";
        include ("classified_my.php");
       //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
    } elseif ($choice=="fav") {
        echo"           <table align=\"center\" width=\"100%\">\n";
        echo"            <tr>\n";
        echo"             <td>\n";
        echo"              <div class=\"mainmenu\">\n";
        echo"              <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';
                    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";
        echo"          <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
                    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
        echo"              </div>\n";
        echo"             </td>\n";
        echo"             <td width=\"30%\">\n";
        echo "<div class=\"mainheader\">$classfav_head</div>\n";
        echo"             </td>\n";
        echo"            </tr>\n";
        echo"           </table>\n";
        include ("classified_my.php");
        //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
    } elseif ($choice=="notify") {
        echo"           <table align=\"center\" width=\"100%\">\n";
        echo"            <tr>\n";
        echo"             <td>\n";
        echo"              <div class=\"mainmenu\">\n";
        echo"              <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';
                    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";
        echo"          <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
                    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
        echo"              </div>\n";
        echo"             </td>\n";
        echo"             <td width=\"30%\">\n";
        echo "<div class=\"mainheader\">$classnot_head</div>\n";
        echo"             </td>\n";
        echo"            </tr>\n";
        echo"           </table>\n";
        include ("classified_notify.php");
        //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
    } elseif ($choice=="search") {
        echo"           <table align=\"center\" width=\"100%\">\n";
        echo"            <tr>\n";
        echo"             <td>\n";
        echo"              <div class=\"mainmenu\">\n";
        echo"              <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';
                    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>$menusep\n";
        echo"          <a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
                    return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
        echo"              </div>\n";
        echo"             </td>\n";
        echo"             <td width=\"30%\">\n";
        echo "<div class=\"mainheader\">$classseek_head</div>\n";
        echo"             </td>\n";
        echo"            </tr>\n";
        echo"           </table>\n";
        include ("classified_search.php");
       //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
    } elseif ($choice=="top") {
        echo"           <table align=\"center\" width=\"100%\">\n";
        echo"            <tr>\n";
        echo"             <td>\n";
        echo"              <div class=\"mainmenu\">\n";
        echo"              <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';
                    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>\n";
#       echo"          $menusep<a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
#                   return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
        echo"              </div>\n";
        echo"             </td>\n";
        echo"             <td width=\"30%\">\n";
        echo "<div class=\"mainheader\">$classtop_head $top_maximum</div>\n";
        echo"             </td>\n";
        echo"            </tr>\n";
        echo"           </table>\n";
        $maximum=$top_maximum;
        include ("classified_top.php");
       //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
    } elseif ($choice=="new") {
        echo"           <table align=\"center\" width=\"100%\">\n";
        echo"            <tr>\n";
        echo"             <td>\n";
        echo"              <div class=\"mainmenu\">\n";
        echo"              <a href=\"classified.php\" onmouseover=\"window.status='$class_link_desc';
                    return true;\" onmouseout=\"window.status=''; return true;\">$class_link</a>\n";
#       echo"          $menusep<a href=\"javascript:history.back(1)\" onmouseover=\"window.status='$back';
#                   return true;\" onmouseout=\"window.status=''; return true;\">$back</a>\n";
        echo"              </div>\n";
        echo"             </td>\n";
        echo"             <td width=\"30%\">\n";
        echo "<div class=\"mainheader\">$classnew_head</div>\n";
        echo"             </td>\n";
        echo"            </tr>\n";
        echo"           </table>\n";
        include ("classified_top.php");
        //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
    } else {

        // Classified Main
        echo"           <table align=\"center\" width=\"100%\">\n";
        echo"            <tr>\n";
        echo"             <td>\n";
        echo"              <div class=\"mainmenu\">\n";
        echo"          <a href=\"classified.php?choice=add&catid=$catid&subcatid=$subcatid\" onmouseover=\"window.status='$class_link1desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link1</a>$menusep\n";
        echo"          <a href=\"classified.php?choice=search&catid=$catid&subcatid=$subcatid\" onmouseover=\"window.status='$class_link3desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link3</a>$menusep\n";
        echo"          <a href=\"classified.php?choice=my\" onmouseover=\"window.status='$class_link2desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link2</a>$menusep\n";
        echo"          <a href=\"classified.php?choice=fav\" onmouseover=\"window.status='$class_link4desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link4</a>\n";
        if ($catnotify) {
        echo"          $menusep<a href=\"classified.php?choice=notify\" onmouseover=\"window.status='$class_link5desc'; return true;\" onmouseout=\"window.status=''; return true;\">$class_link5</a>\n";
        }
        echo"              </div>\n";
        echo"             </td>\n";
        echo"             <td width=\"20%\">\n";
        echo"              <div class=\"mainheader\">$classified_head</div>\n";
        echo"             </td>\n";
        echo"            </tr>\n";
        echo"           </table>\n";

        //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
        if ($catid && $subcatid) {
        include ("classified_ads_show.php");
        //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
        } elseif ($sqlquery) {
        include ("classified_results.php");
         //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
        } else {
        include ("classified_cat_show.php");
         //$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
        }

    }
}
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
include ("classified_right.php");
//$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
echo"</td>\n";
echo"</tr>\n";
echo"</table>\n";

#  The Foot-Section
#################################################################################################
include ($FOOTER);
//$memusage =memory_checkpoint(__LINE__,__FILE__,$memusage);
$BenchmarkTimer->stop();

$parse_time = $BenchmarkTimer->elapsed();
parse_timer_log($parse_time,__FILE__);
//write_memory_log($memusage,$parse_time);

?>