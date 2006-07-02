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


    $result=mysql_query("select sum(votes) as sum from ".$prefix."votes");
    if($result) {
        $sum = (int) mysql_result($result,0,"sum");
        mysql_free_result($result);
    }


//where do these come from? language file somewhere... $vote_vote
//as above...   $vote_answer
(empty($vote_vote))?$vote_vote="votes":$vote_vote=$vote_vote;
(empty($vote_answer))?$vote_answer="answer":$vote_answer=$vote_answer;
$smarty->assign('vote_vote',$vote_vote);
$smarty->assign('vote_answer',$vote_answer);


$poll_question = "How often do you enjoy yourself at home ???";
$smarty->assign('poll_question',$poll_question);
//TODO - overhaul this thing voting - we need to normalize the database.
//
$voteanswer[1]="Often";
$voteanswer[2]="Sometimes";
$voteanswer[3]="2-3 times week";
$voteanswer[4]="Never";

$smarty->assign('voteanswer',$voteanswer);
//TODO: vote_show.php - redo poll system this is totally b0rked
/*
//dear god. What happens if there are dozens of polls , over time , this is gonna get *HUGE*
    $result=mysql_query("select * from ".$prefix."votes where id in ('1','2','3','4') order by votes DESC");
    while($db=mysql_fetch_array($result))
    {
    $id=$db['id'];
    if (empty($voteanswer[$id]))//Why?? why dont we maintain previous polls? ignore this shit.
    {
        //$voteanswer[$id]=$db['name'];
    }
    else
    {
       $voteres[$id] = $db['votes'];
       $vote_id[$id] = $db['id'];
    }

echo "<tr><td align=center><input type=radio name=vote value=\"$id\"></td>";
        echo "<td class=\"votetext\" colspan=\"2\">" .$voteanswer[$id]."</td></tr><tr>
    <td class=\"votetext\" align=\"center\">".$db['votes']."</td><td class=\"votetext\">";

        if($sum && (int)$db['votes'])
        {
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
*/
?>