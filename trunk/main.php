<?php
##############################################################################################
#                                                                                            #
#                                main.php
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
//TODO main.php- template this first ..
include("includes/timer.class.php");
$BenchmarkTimer = new c_Timer;
$BenchmarkTimer->start(); // Start benchmarking immediately
#  Include Configs & Variables
#################################################################################################
$memusage = array();
require ("library.php");
$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);
//TODO: main.php 32 Eliminate this cookie shit, use sessions for logged in users, track what they have viewed or not
//TODO: this is where we would check user login , and if logged in set up their specific page views needs.
//TODO: we want to build a cache of the core, and only need to dynamically write the stuff "around" it.. some pages can be fully cached as html
if (!empty($_COOKIE["checkviewed"]) && $_COOKIE["checkviewed"] != "1")
{
    setcookie("checkcookie", "1", $cookietime, "$cookiepath");
    if ($_COOKIE["checkviewed"] == "1")
    {
        $cookietime=time()+(24*3600*365);
        setcookie("checkviewed", "1", $cookietime, "$cookiepath");
        $firsttimeuser=true;
    }
    setcookie("checkcookie", "", 0, "$cookiepath");
}

#  The Head-Section
#################################################################################################
include ('header.php');
$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);
#  The Menu-Section
#################################################################################################
include ("menu.php");
$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);
#  The Left-Side-Section
#################################################################################################
$tmp_width = ($table_width+(2*$table_width_side)+10);
$smarty->assign('tmp_width',$tmp_width);
##BOOKMARK work location
include ("left.inc.php");
$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);
echo "</td>\n";

#  The Main-Section
#################################################################################################
echo "<td valign=\"top\" align=\"left\">\n";
echo " <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" margin=1 width=\"$table_width\" height=\"$table_height\">\n";
echo "   <tr>\n";
echo "    <td class=\"class1\">\n";
echo "      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";
echo "       <tr>\n";
echo "        <td class=\"class2\">\n";
echo "         <div class=\"mainheader\">$main_head</div>\n";
echo "         <div class=\"maintext\">\n";
include ("./$language_dir/main.inc");

$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);
echo "         </div>\n";
echo "        </td>\n";
echo "       </tr>\n";
echo "      </table>\n";
echo "    </td>\n";
echo "   </tr>\n";
echo " </table>\n";
echo "</td>\n";

#  The Right-Side-Section
#################################################################################################
echo "<td valign=\"top\" align=\"left\">\n";
include ("right.inc.php");
$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

//Make this an optional thing admins can add to their template, because we *HATE* intrusive BS like this...
if (!empty($firsttimeuser) && $firsttimeuser===true && $addfavorits)
{
    //echo "<script language=javascript>window.external.AddFavorite(\"$url_to_start\",\"$bazar_name\")</script>";
}

#  The Foot-Section
#################################################################################################
include ('footer.php');
////$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);
//TODO make sure and remove the .inc files, and add the new files
$BenchmarkTimer->stop();


$parse_time = $BenchmarkTimer->elapsed();
$smarty->assign('page_parse_time',$parse_time);
$smarty->display('main.tpl');
parse_timer_log($parse_time,__FILE__);
write_memory_log($memusage,$parse_time);


?>