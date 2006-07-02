<?php
##############################################################################################
#                                                                                            #
#                                right.php
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

if(!strpos($_SERVER['PHP_SELF'],'right.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
if (!empty($show_news) && $show_news === true)
{
$smarty->assign('display_news',true);
//TODO news items in database
$news[0]['news_title'] = "News Title 1";
$news[0]['news_item'] = "You can use 'Auto Notify' to get notified if new ads have been posted in the cat-egories that interest you.";
$news[0]['news_date'] = "July 2, 2006";
$smarty->assign('news',$news);

}
$show_votes = false;
//TODO: right.php - hard coded votes to not show because vote_show and etc needs completely re-done - its an unholy mess right now
if ($show_votes)
{

include ("vote_show.php");

}

?>