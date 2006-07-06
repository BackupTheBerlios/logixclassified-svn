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
include_once("includes/timer.class.php");
$BenchmarkTimer = new c_Timer;
$BenchmarkTimer->start(); // Start benchmarking immediately
//Now, include the configuration.
require_once ("library.php");
if($debug === true)
{
    include_once("includes/benchmark.class.php");
    $benchmark = new DebugLib;
    $benchmark->scratch_dir = $scratch_dir;
}
if($debug)
{
    $benchmark->memory_checkpoint(__LINE__,__FILE__);
}
include_once('includes/mysql.class.php');
$db = new DbMysql; //instantiate the database object
$db->connect($db_server,$db_user,$db_pass,$db_name,$db_persistent);
$db->prefix = $prefix;
if($debug)
{
    $benchmark->memory_checkpoint(__LINE__,__FILE__);
}
#  Include Configs & Variables
#################################################################################################
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

if($debug)
{
    $benchmark->memory_checkpoint(__LINE__,__FILE__);
}
#  The Menu-Section
#################################################################################################
include ("menu.php");

if($debug)
{
    $benchmark->memory_checkpoint(__LINE__,__FILE__);
}
#  The Left-Side-Section
#################################################################################################
$tmp_width = ($table_width+(2*$table_width_side)+10);
$smarty->assign('tmp_width',$tmp_width);

include ("left.php");

if($debug)
{
    $benchmark->memory_checkpoint(__LINE__,__FILE__);
}
$smarty->assign('table_width',$table_width);
$smarty->assign('table_height',$table_height);
$smarty->assign('main_head',$main_head);
$main_page_body = "Here, you can place your content";
//TODO main.php page body content needs to be stored to db
$smarty->assign('main_page_body',$main_page_body);


if($debug)
{
    $benchmark->memory_checkpoint(__LINE__,__FILE__);
}

include ("right.php");

if($debug)
{
    $benchmark->memory_checkpoint(__LINE__,__FILE__);
}

//Make this an optional thing admins can add to their template, because we *HATE* intrusive BS like this...
if (!empty($firsttimeuser) && $firsttimeuser===true && $addfavorits)
{
    //echo "<script language=javascript>window.external.AddFavorite(\"$url_to_start\",\"$bazar_name\")</script>";
}

#  The Foot-Section
#################################################################################################
include ('footer.php');

//TODO make sure and remove the .inc files, and add the new files
if($debug)
{
    $benchmark->memory_checkpoint(__LINE__,__FILE__);
}
$BenchmarkTimer->stop();


$parse_time = $BenchmarkTimer->elapsed();
$smarty->assign('page_parse_time',$parse_time);
if($debug)
{
    $benchmark->write_memory_log($parse_time);
    $benchmark->parse_timer_log($parse_time,__FILE__);
}

$smarty->display('main.tpl');

?>