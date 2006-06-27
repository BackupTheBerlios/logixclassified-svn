<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_top.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : Show the classified ads top entry's
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'classified_top.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
#  Ads Overview
#################################################################################################
if ($adapproval && !$_SESSION[susermod]) {$approval=" AND publicview='1'";}
if ($choice=="new" && $_SESSION[suserlastlogin]) {$newads=" AND UNIX_TIMESTAMP(addate)>$_SESSION[suserlastlogin]";}
if (!$sortorder) $sortorder = "answered desc";
if (!$maximum) $maximum = "10";

    $sql="SELECT * FROM ".$prefix."ads WHERE 1=1".$approval.$newads." ORDER by $sortorder LIMIT 0, $maximum";
    $result = mysql_query($sql) or died("Record NOT Found");

    echo "<table align=\"center\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

    while ($db = mysql_fetch_array($result)) {

    $result2 = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id=$db[userid]") or died("Record NOT Found");
    $dbu = mysql_fetch_array($result2);
    $result3 = mysql_query("SELECT * FROM ".$prefix."adcat WHERE id=$db[catid]") or died("Record NOT Found");
    $dbc = mysql_fetch_array($result3);
    include("classified_ads.inc.php");
    }
    echo "</table>\n";
# End of Page reached
#################################################################################################

?>