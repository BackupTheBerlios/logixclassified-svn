<!-- $Id$ -->
{include file="header.tpl"}
{include file="menu.tpl"}
<table align="center" border="0" cellspacing="0" cellpadding="0" width="{$tmp_width}">
<tr>
<td valign="top" align="right">
{include file="left.tpl"}

echo "</td>\n";

#  The Main-Section
#################################################################################################
echo "<td valign=\"top\" align=\"left\">\n";
echo " <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" margin=1 width=\"$table_width\" height=\"$table_height\">\n";
echo "   <tr>\n";
echo "    <td class=\"class1\">\n";
echo "      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";
echo "       <tr>\n";
echo "        <td class=\"class2\">\n";
echo "         <div class=\"mainheader\">$main_head</div>\n";
echo "         <div class=\"maintext\">\n";
 include main.inc from lang dir- what do it do?
 echo "         </div>\n";
echo "        </td>\n";
echo "       </tr>\n";
echo "      </table>\n";
echo "    </td>\n";
echo "   </tr>\n";
echo " </table>\n";
echo "</td>\n";

echo "<td valign=\"top\" align=\"left\">\n";
include ("right.inc.php");

echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

{include file="footer.tpl"}