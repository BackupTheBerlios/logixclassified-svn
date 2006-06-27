<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_right.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : Show the (right side) long description of the classified cat
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'classified_right.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
if ($show_advert3) {$table_height=1;}

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\" height=\"$table_height\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";

if ($catid && $choice!="search") {

    #  Get Entrys for current page
    #################################################################################################

    $result = mysql_query("SELECT * FROM ".$prefix."adcat where id=$catid");
    $db = mysql_fetch_array($result);
    echo "<div class=\"sideheader\">$db[name]</div>\n";
    echo "<div class=\"sidetext\">\n";
    echo "$db[longdescription]\n";
    echo "</div>";

} elseif ($choice=="add") {
    include ("$language_dir/classified_right_add.inc");
} elseif ($choice=="search") {
    include ("$language_dir/classified_right_search.inc");
} elseif ($choice=="my") {
    include ("$language_dir/classified_right_my.inc");
} elseif ($choice=="fav") {
    include ("$language_dir/classified_right_fav.inc");
} elseif ($choice=="notify") {
    include ("$language_dir/classified_right_not.inc");
} else {
    include ("$language_dir/classified_right_main.inc");
}

# End of Page reached
#################################################################################################
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo"  </table>\n";

# Advertising Window 3
#################################################################################################
if ($show_advert3) {

include ("spacer.inc.php");

echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width_side\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";

include ("$language_dir/advert3.inc");

echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo"  </table>\n";

}

?>