<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : right.inc
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : Right Side
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'right.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
if ($show_news) {

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
include ("$labguage_dir/news.inc");
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo"  </table>\n";

}

if ($show_votes) {

include ("spacer.inc.php");

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
include ("$language_dir/voting.inc");
include ("vote_show.php");
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo"  </table>\n";

}

?>