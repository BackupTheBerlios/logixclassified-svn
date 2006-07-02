<!-- $Id$ -->
{if $display_news}


  <table align="center" border="0" cellspacing="0" cellpadding="1" width="{$table_width_side}">
    <tr>
     <td class="class1">
       <table align="center" border="0" cellspacing="0" cellpadding="3" width="100%">
        <tr>
         <td class="class2">
<div class="sideheader">News</div>
<div class="sidetext">
{section name=news loop=$news}
<div align="center"><strong>{$news[news].news_title} - {$news[news].news_date}</strong></div>
{$news[news].news_item}
<p />
{/section}
</div>

         </td>
        </tr>
       </table>
     </td>
    </tr>
   </table>

{/if}

{if $show_votes}
{include file="spacer.tpl"}

  <table align="center" border="0" cellspacing="0" cellpadding="1" width="$table_width_side">
    <tr>
     <td class="class1">
       <table align="center" border="0" cellspacing="0" cellpadding="3" width="100%">
        <tr>
         <td class="class2">
 <div class="sideheader">Voting</div>
<div class="sidetext">
{$poll_question}
<p />
</div>
<!-- Vote answer options -->
<form action="vote_submit.php?source=main.php" method="POST">
<table border="0" cellspacing="1" cellpadding="1" height="1"83>
<tr>
<td class="votetext">
{$vote_vote}
</td>
<td class="votetext">
{$vote_answer}
</td>
<td class="votetext">
%
</td>
</tr>
<!--
while($db=mysql_fetch_array($result))
    $id=$db['id'];
    if (!$voteanswer[$id]) $voteanswer[$id]=$db['name'];
    echo "<tr><td align=center><input type=radio name=vote value=\"$id\"></td>";
        echo "<td class=\"votetext\" colspan=\"2\">" .$voteanswer[$id]."</td></tr><tr>
    <td class=\"votetext\" align=\"center\">".$db['votes']."</td><td class=\"votetext\">";

        if($sum && (int)$db['votes'])
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

        echo "</tr>\n";

</table>       -->
<br><input type=submit value=\"$vote_button\"></form>
 {/if} <!-- end show votes -->
         </td>
        </tr>
       </table>
     </td>
    </tr>
   </table>











