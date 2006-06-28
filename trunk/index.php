<?php
##############################################################################################
#                                                                                            #
#                                   index.php                                                #
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
#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
include ($HEADER);

#  The Main-Section
#################################################################################################
#echo"<p>&nbsp; \n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$wel_table_width\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
               include ("$language_dir/welcome.inc");
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo" </table>\n";

#  The Foot-Section
#################################################################################################
include ($FOOTER);

#  Browser Detection
#################################################################################################
#include ("$language_dir/browser.inc"); Maybe you want display Message if Browser NOT MSIE
?>