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
include ("left.php");
$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);
$smarty->assign('table_width',$table_width);
$smarty->assign('table_height',$table_height);
$smarty->assign('main_head',$main_head);
$main_page_body = "Here, you can place your content";
//TODO main.php page body content needs to be stored to db
$smarty->assign('main_page_body',$main_page_body);

$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);


include ("right.php");
$memusage = memory_checkpoint(__LINE__,__FILE__,$memusage);


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
parse_timer_log($parse_time,__FILE__);
write_memory_log($memusage,$parse_time);
$smarty->display('main.tpl');

?>