<?php
##############################################################################################
#                                                                                            #
#                                vote_show.php
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

if(!strpos($_SERVER['PHP_SELF'],'vote_show.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
$raw_url=rawurlencode(requesturi());

echo "<form action=\"vote_submit.php?source=$raw_url\" method=\"POST\">";
    $result=mysql_query("select sum(votes) as sum from ".$prefix."votes");
    if($result) {
        $sum = (int) mysql_result($result,0,"sum");
        mysql_free_result($result);
    }

    $result=mysql_query("select * from ".$prefix."votes order by votes DESC");
    echo "<table border=0 cellspacing=1 cellpadding=1 height=183><tr><td class=\"votetext\">$vote_vote</td><td class=\"votetext\">$vote_answer</td><td class=\"votetext\">%</td></tr>\n";
    while($db=mysql_fetch_array($result)) {
    $id=$db['id'];
    if (!$voteanswer[$id]) {$voteanswer[$id]=$db['name'];}
    echo "<tr><td align=center><input type=radio name=vote value=\"$id\"></td>";
        echo "<td class=\"votetext\" colspan=\"2\">" .$voteanswer[$id]."</td></tr><tr>
    <td class=\"votetext\" align=\"center\">".$db['votes']."</td><td class=\"votetext\">";

        if($sum && (int)$db['votes']) {
            $per = (int)(100 * $db['votes']/$sum);

        echo "<table align=center border=0 cellspacing=0 cellpadding=1 width=\"$votebar_width\" height=\"$votebar_height\">\n";
            echo " <tr>\n";
        echo "  <td class=\"votebarout\">\n";
            echo "   <table align=left border=0 cellspacing=0 cellpadding=0 width=\"$per%\" height=\"100%\">\n";
            echo "    <tr>\n";
        echo "     <td class=\"votebarin\">\n";
            echo "        <div class=\"votespace\">&nbsp;</div>\n";
        echo "     </td>\n";
        echo "    </tr>\n";
        echo "   </table>\n";
        echo "  </td>\n";
        echo " </tr>\n";
            echo "</table>\n";

        echo"</td><td class=\"votetext\">$per</td>";
            }
        echo "</tr>\n";
        }
echo "</table>\n";
echo "<br><input type=submit value=\"$vote_button\"></form>";
?>