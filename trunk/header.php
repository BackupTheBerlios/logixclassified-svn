<?php
##############################################################################################
#                                                                                            #
#                                header.php
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

if(!strpos($_SERVER['PHP_SELF'],'header.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
if (empty($charset))
{
    $charset = $default_charset;
}
if(empty($secondary_title))
{
    $secondary_title = " ";
}
if(empty($title_separator))
{
    $title_separator = " :: ";
}
if(empty($favicon))
{
    $favicon = "/favicon.ico";
}

$smarty->assign("title",$title);
$smarty->assign("title_separator",$title_separator);
$smarty->assign("favicon",$favicon);
$smarty->assign("charset",$charset);
$smarty->assign("secondary_title",$secondary_title);

?>