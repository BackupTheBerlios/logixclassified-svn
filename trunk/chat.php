<?php
##############################################################################################
#                                                                                            #
#                                   chat.php
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
 #TODO: clean up and move to templates.
#  Include Configs & Variables
#################################################################################################
require ("library.php");

if (empty($_SESSION['suserid']) || !$chat_enable) {

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
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";

if (!$chat_enable && $_SESSION['suserid']) {
    echo "<br><br><center><b>Chat is NOT enabled !!!</b></center>";
} else {
    include ("$language_dir/nologin.inc");
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
include ("right.inc.php");
echo"</td>\n";
echo"</tr>\n";
echo"</table>\n";

#  The Foot-Section
#################################################################################################
include ($FOOTER);

} else {

$table_width_menu+=20;
if (strpos($client,"MSIE")) {   // Internet Explorer Detection
    $frameheight="107";
} else {
    $frameheight="119";
}

echo"
<html>
 <head>
 <title>$bazar_name - Chat</title>
 <link rel=\"stylesheet\" type=\"text/css\" href=\"$STYLE\">
 <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
 <meta name=\"generator\" content=\"Manual\">
 </head>


<frameset rows=\"$frameheight,*\" frameborder=\"0\" border=\"0\" framespacing=\"0\">
<frame name=\"topFrame\" scrolling=\"NO\" noresize src=\"frametop.php\" target=\"_top\" >
<frameset cols=\"*,$table_width_menu,*\" frameborder=\"0\" border=\"0\" framespacing=\"0\">
<frame name=\"mainFrameLeft\" src=\"frameblank.php\" scrolling=\"NO\" target=\"mainFrame\">
<frame name=\"mainFrame\" src=\"chat/index.php?u=$_SESSION[susername]&p=$_SESSION[suserpass]\" scrolling=\"auto\" target=\"mainFrame\">
<frame name=\"mainFrameRight\" src=\"frameblank.php\" scrolling=\"NO\" target=\"mainFrame\">
<noframes>
<body bgcolor=\"#000000\" text=\"#FFFFFF\" link=\"#FFFFFF\" vlink=\"#C0C0C0\" alink=\"#FF0000\">
<p align=\"center\"><font face=\"Verdana\" size=\"2\"><b>This Site's uses Frames, update your Browser !!!</b></font></p>
<p align=\"center\">&nbsp;</p>
</body>
</noframes>
</frameset>
</frameset>
";
}

?>