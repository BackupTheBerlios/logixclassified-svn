<?php
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : adrating.php
#  e-mail               : support@phplogix.com
#  purpose              : Rate Ads
# $Id$
#License: GPL
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");
 #TODO: break this apart into code and template, clean up code.
#  HTML Header Start
#################################################################################################
?>

<DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
 <head>
  <title><?echo $ad_rating;?></title>
  <link rel="stylesheet" type="text/css" href="<?echo $STYLE;?>">
  <? echo "$lang_metatag\n"; ?>
 </head>

<?
echo"<body>\n";

if (!$_SESSION[suserid]) {
    include ("$language_dir/nologin.inc");
} else {

#  Connect to the DB
#################################################################################################

$resultu = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id='$_SESSION[suserid]'");
$dbu = mysql_fetch_array($resultu);
$resulta = mysql_query("SELECT * FROM ".$prefix."ads WHERE id='$adid'");
$dba = mysql_fetch_array($resulta);
$result = mysql_query("SELECT * FROM ".$prefix."rating WHERE (userid='$dbu[id]' OR ip='$ip') AND type='ad' AND id='$adid'");
$israted = mysql_num_rows($result);


#  Get Entrys for current page
#################################################################################################

if ($adid && $newrate && !$israted) {


    // if fraud check OK
    $newcount=$dba[ratingcount]+1;
    $newvalue=round(((($dba[rating]*$dba[ratingcount])+$newrate)/$newcount)*100)/100;

    mysql_query("INSERT INTO ".$prefix."rating (type,id,ip,userid,ratedate) VALUES ('ad','$adid','$ip','$dbu[id]',now())") or died(mysql_error());
    mysql_query("UPDATE ".$prefix."ads SET rating='$newvalue',ratingcount='$newcount' WHERE id='$adid'") or died(mysql_error());

    echo "<SCRIPT language=Javascript>opener.location.href=opener.location.href;window.close();</script>";

} elseif ($adid && !$israted) {

    echo "<div class=\"mainheader\">$ad_rating</div>\n";

    echo "<br>\n";
    echo "<table align=\"center\" width=\"100%\">\n";
    echo "<form enctype=\"text\" action=adrating.php METHOD=POST>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\" nowrap><div class=\"maininputleft\">$ar_adid </div></td>\n";
    echo "<td class=\"classadd2\">$dba[id]</td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$ar_rating </div></td>\n";
    echo "<td class=\"classadd2\">$dba[rating]</td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$ar_ratingcount </div></td>\n";
    echo "<td class=\"classadd2\">$dba[ratingcount]</td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=\"classadd1\"><select name=\"newrate\">\n";
    include ("$language_dir/rating.inc");
    echo "</select></td>\n";

    echo "<input type=hidden name=\"adid\" value=\"$adid\"></td>\n";
    echo "<td class=\"classadd2\"><input type=submit value=$ar_submit></td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";

} elseif ($israted) {

    echo "<div class=\"mainheader\">$ad_rating</div>\n";
    echo "<br><div class=\"maintext\">$ar_already</div>";
    echo "<center>\n";
    echo "<form action=javascript:window.close() METHOD=POST><input type=submit value=$close></form>\n";
    echo "</center>\n";


} else {                // Error
    died("FATAL Error !!!");
}

}                   //

?>
</body>
</html>