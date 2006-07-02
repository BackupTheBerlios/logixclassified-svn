<?php
##############################################################################################
#                                                                                            #
#                                footer.php                                                  #
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

#TODO: break this down into footer.php and header template.
if(!strpos($_SERVER['PHP_SELF'],'footer.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
$smarty->assign('copyright',$bazar_copyright);
$datestart = "2006";
$curdate = date("Y");
if($curdate > $datestart)
{
   $cdate = $datestart." - ".$curdate;
}
else
{
    $cdate = $datestart;
}
$smarty->assign('copyright_date',$cdate);

?>