<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_ads_show.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : Show the classified ads entry's
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'classified_ads_show.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
#  Ads Overview
#################################################################################################
if ($adapproval && !$_SESSION[susermod]) {$approval="AND publicview='1'";}
if (empty($ad_sort)) $ad_sort = "addate desc";

$result = mysql_query("SELECT * FROM ".$prefix."adcat WHERE id='$catid' AND disabled<>'1'") or died("Record NOT Found<br>".mysql_error());
$db = mysql_fetch_array($result) or died ("Category NOT Found");
$result2 = mysql_query("SELECT * FROM ".$prefix."adsubcat WHERE id='$subcatid'") or died("Record NOT Found<br>".mysql_error());
$db2 = mysql_fetch_array($result2) or died ("SubCategory NOT Found");

if (!$adid) {  // show the ads list

    if (!$db[passphrase] || md5($db[passphrase])==$_COOKIE["Passphrase_$db[id]"] && $_SESSION[suserid]==$_COOKIE["PassphraseUser_$db[id]"]) { // Passphrase

    if ($sales_option) {
        if (!sales_checkaccess(1,$_SESSION[suserid],$catid)) { // check access for user and cat
        open_sales_window();
            echo "<script language=javascript>location.replace('classified.php');</script>";
        }
    }

    echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
    echo "<tr><td><div class=\"maincatnav\">\n";
    echo "<a href=\"classified.php\" onmouseover=\"window.status='$ad_home'; return true;\"
    onmouseout=\"window.status=''; return true;\">$ad_home</a> /
        <a href=\"classified.php?catid=$catid\" onmouseover=\"window.status='$db[name]'; return true;\"
        onmouseout=\"window.status=''; return true;\">$db[name]</a> / $db2[name]<br>\n";
    echo "</div></td>\n";


    # Calculate Page-Numbers
    #################################################################################################
    if (empty($perpage)) $perpage = 5;
    if (empty($pperpage)) $pperpage = 5;    // !!! ONLY 3,5,7,9,11,13 !!!
    if (empty($offset)) $offset = 0;
    if (empty($poffset)) $poffset = 0;

    $amount = mysql_query("SELECT count(id) FROM ".$prefix."ads WHERE subcatid='$subcatid' $approval") or died("Record NOT Found<br>".mysql_error());
    $amount_array = mysql_fetch_array($amount);
    $pages = ceil($amount_array["0"] / $perpage);
    $actpage = ($offset+$perpage)/$perpage;
    $maxpoffset = $pages-$pperpage;
    $middlepage=($pperpage-1)/2;
    if ($maxpoffset<0) {$maxpoffset=0;}

    echo "<td><div class=\"mainpages\">\n";

    if ($pages) {                                       // print only when pages > 0
        echo "$ad_pages\n";

        if ($offset) {
            $noffset=$offset-$perpage;
            $npoffset = $noffset/$perpage-$middlepage;
            if ($npoffset<0) {$npoffset = 0;}
            if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
                echo "[<a href=\"classified.php?catid=$catid&subcatid=$subcatid&offset=$noffset&poffset=$npoffset&sort1=$sort1&sort2=$sort2\" onmouseover=\"window.status='$nav_prev'; return true;\" onmouseout=\"window.status=''; return true;\"><</a>]\n";
        }
        for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {
            $noffset = $i * $perpage;
            $npoffset = $noffset/$perpage-$middlepage;
        if ($npoffset<0) {$npoffset = 0;}
        if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
        $actual = $i + 1;
            if ($actual==$actpage) {
        echo "(<a href=\"classified.php?catid=$catid&subcatid=$subcatid&offset=$noffset&poffset=$npoffset&sort1=$sort1&sort2=$sort2\" onmouseover=\"window.status='$nav_actpage'; return true;\" onmouseout=\"window.status=''; return true;\">$actual</a>)\n";
        } else {
        echo "[<a href=\"classified.php?catid=$catid&subcatid=$subcatid&offset=$noffset&poffset=$npoffset&sort1=$sort1&sort2=$sort2\" onmouseover=\"window.status='$nav_gopage'; return true;\" onmouseout=\"window.status=''; return true;\">$actual</a>]\n";
        }
    }

    if ($offset+$perpage<$amount_array["0"]) {
            $noffset=$offset+$perpage;
        $npoffset = $noffset/$perpage-$middlepage;
        if ($npoffset<0) {$npoffset = 0;}
        if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
            echo "[<a href=\"classified.php?catid=$catid&subcatid=$subcatid&offset=$noffset&poffset=$npoffset&sort1=$sort1&sort2=$sort2\" onmouseover=\"window.status='$nav_next'; return true;\" onmouseout=\"window.status=''; return true;\">></a>]\n";
    }
    }

    echo "</div></td></tr>\n";
    echo "</table>\n";


    if ($sort1 && !$sort2) {
    $ad_sort=$sort1." desc";
    } elseif (!$sort1 && $sort2) {
    $ad_sort="addate ".$sort2;
    } elseif ($sort1 && $sort2) {
    $ad_sort=$sort1." ".$sort2;
    }

    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE subcatid='$subcatid' $approval ORDER by $ad_sort LIMIT $offset, $perpage") or died(mysql_error());

    echo "<table align=\"center\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

    while ($db = mysql_fetch_array($result)) {

    $result2 = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id=$db[userid]") or died("Record NOT Found<br>".mysql_error());
    $dbu = mysql_fetch_array($result2);
    $result3 = mysql_query("SELECT * FROM ".$prefix."adcat WHERE id='$db[catid]' AND disabled<>'1'") or died("Record NOT Found<br>".mysql_error());
    $dbc = mysql_fetch_array($result3);

    if ($sales_option && $sqlquery && !sales_checkaccess(1,$_SESSION[suserid],$db[catid])) { // check access for user and cat
        // if no acces to cat, do nothing :-)
    } else {
        include("classified_ads.inc.php");
    }
    } //End while
    echo "</table>\n";

    if ($show_adsortorder) {

        echo"
        <SCRIPT LANGUAGE=\"JavaScript\">
        <!--
            function changesort1(newvalue) {
            site = \"classified.php?catid=$catid&subcatid=$subcatid&sort1=\"+(newvalue)+\"&sort2=$sort2\";
            if (newvalue!=0) {top.location.href=site;}
        }
            function changesort2(newvalue) {
            site = \"classified.php?catid=$catid&subcatid=$subcatid&sort1=$sort1&sort2=\"+(newvalue);
            if (newvalue!=0) {top.location.href=site;}
            }
        -->
        </SCRIPT>";

    echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
    echo "<form method=\"post\" name=\"\">\n";
    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"mainpages\">$adseek_sort\n";
    echo "<select name=\"in[search_sort]\" onchange=\"changesort1(this.options[this.selectedIndex].value)\">\n";
    echo str_replace("\"$sort1\"","\"$sort1\" SELECTED",getfile("$bazar_dir/$language_dir/sortoption1.inc"));
    echo "</select>\n";
    echo "<select name=\"in[search_sort2]\" onchange=\"changesort2(this.options[this.selectedIndex].value)\">\n";
    echo str_replace("\"$sort2\"","\"$sort2\" SELECTED",getfile("$bazar_dir/$language_dir/sortoption2.inc"));
    echo "</select>\n";
    echo "</div></td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";
    }

 } else { // NO or no valid passphrase
    echo "$pass_text";
    echo "<script language=javascript>
    PASS=window.open(\"passphrase.php?".sidstr()."catid=$catid&userid=$_SESSION[suserid]\",\"Passphrase\",\"width=180,height=120,top=200,left=200\")
    PASS.focus();
    </script>\n";
 } // End Passphrase

#  Ads Detail
#################################################################################################

} else { // show the ads detail

 if (!$db[passphrase] || md5($db[passphrase])==$_COOKIE["Passphrase_$db[id]"] && $_SESSION[suserid]==$_COOKIE["PassphraseUser_$db[id]"]) { // Passphrase

    if ($sales_option) {
    if (!sales_checkaccess(1,$_SESSION[suserid],$catid)) { // check access for user and cat
        open_sales_window();
        #echo "<script language=javascript>location.replace('classified.php?errormessage=$sales_lang_noaccess');</script>";
        echo "<script language=javascript>location.replace('classified.php');</script>";
    }
    }

    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE subcatid='$subcatid' $approval ORDER by $ad_sort") or died(mysql_error());

    // due there is no subselect in mysql, we must build a tmp-array :(
    $i=0;
    while ($dbt = mysql_fetch_array($result)) {
    $tmp[$i]=$dbt[id];
    if ($dbt[id]==$adid) {$target=$i;}
    $i++;
    } //End while

    $prevtarget=$target-1;
    $nexttarget=$target+1;
    $prevadid=$tmp[$prevtarget];
    $nextadid=$tmp[$nexttarget];

    echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
    echo "<tr><td><div class=\"maincatnav\">";
    echo "<a href=\"classified.php\" onmouseover=\"window.status='$ad_home'; return true;\"
    onmouseout=\"window.status=''; return true;\">$ad_home</a> /
      <a href=\"classified.php?catid=$catid\" onmouseover=\"window.status='$db[name]'; return true;\"
    onmouseout=\"window.status=''; return true;\">$db[name]</a> /
      <a href=\"classified.php?catid=$catid&subcatid=$subcatid\" onmouseover=\"window.status='$db2[name]'; return true;\"
    onmouseout=\"window.status=''; return true;\">$db2[name]</a><br>\n";
    echo "</div></td>";

    echo "<td><div class=\"mainpages\">";
    echo "[<a href=\"classified.php?catid=$catid&subcatid=$subcatid&adid=$nextadid\" onmouseover=\"window.status='$nav_prev'; return true;\" onmouseout=\"window.status=''; return true;\"><</a>]\n";
    echo "[<a href=\"classified.php?catid=$catid&subcatid=$subcatid&adid=$prevadid\" onmouseover=\"window.status='$nav_next'; return true;\" onmouseout=\"window.status=''; return true;\">></a>]\n";
    echo "</div></td></tr>";
    echo "</table>";

    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id=$adid $approval") or died("Record NOT Found<br>".mysql_error());
    $db = mysql_fetch_array($result) or died ("Ad NOT Found");
    $result2 = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id=$db[userid]") or died("Record NOT Found<br>".mysql_error());
    $dbu = mysql_fetch_array($result2) or died ("User NOT Found");
    $result3 = mysql_query("SELECT * FROM ".$prefix."adcat WHERE id='$db[catid]' AND disabled<>'1'") or died("Record NOT Found<br>".mysql_error());
    $dbc = mysql_fetch_array($result3) or died ("Category NOT Found");

    // Stat Viewed Counter
    mysql_query("update ".$prefix."ads set viewed=viewed+1 where id=$adid") or died("Database Query Error");

    include ("classified_ad.inc.php");

 } else { // NO or no valid passphrase
    echo "$pass_text";
    echo "<script language=javascript>
    PASS=window.open(\"passphrase.php?".sidstr()."catid=$catid&userid=$_SESSION[suserid]\",\"Passphrase\",\"width=180,height=120,top=200,left=200\")
    PASS.focus();
    </script>\n";
 } // End Passphrase

}
# End of Page reached
#################################################################################################

?>