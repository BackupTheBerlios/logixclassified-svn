<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_ad.inc.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : IncludeModule show classified ad (detail)
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'classified_ad.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
    $iconstring="";
    for ($i=10; $i > 0; $i--) {
        $iconfield="icon".$i;
        $iconalt="icon".$i."alt";
        if ($db[$iconfield] && adfield($db[catid],"$iconfield")) {
            $iconstring.="<img src=\"$dbc[$iconfield]\" alt=\"$dbc[$iconalt]\" align=\"right\" vspace=\"2\"
                onmouseover=\"window.status='$dbc[$iconalt]'; return true;\"
                    onmouseout=\"window.status=''; return true;\">\n";
        }
    }

    if (!$db[location]) {$db[location]=$ad_noloc;}

    echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
    echo " <tr>\n";
    echo "   <td class=\"classcat5\">\n";

    if ($db[_picture1] || $db[_picture2]  || $db[_picture3] || $db[_picture4]  || $db[_picture5]) {     // advanced picture handling

        include ("classified_ad_apic.inc.php");

    } elseif ($db[picture1] || $db[picture2] || $db[picture3] || $db[picture4] || $db[picture5]) {  // simple picture handling

        include ("classified_ad_spic.inc.php");

    } else {

        echo "&nbsp;";
    }

    echo "   </td>\n";
    echo "   <td class=\"classcat6\">\n";
    echo "  <table width=\"100%\"><tr><td>\n";

    if($show_adrating) {
        $per = $db[rating]*10/2;
            echo "<table align=right border=0 cellspacing=4 cellpadding=1 width=\"58\"
                    onmouseover=\"window.status='$ad_rating $db[rating]'; return true;\"
                    onmouseout=\"window.status=''; return true;\">\n";
            echo " <tr>\n";
            echo "  <td class=\"ratebarout\">\n";
        echo "   <img src=\"$image_dir/$adrating_icon\" align=\"left\" border=\"0\" width=\"$per\" height=\"6\"
                alt=\"$ad_rating $db[rating]\" hspace=\"0\"
                    onmouseover=\"window.status='$ad_rating $db[rating]'; return true;\"
                    onmouseout=\"window.status=''; return true;\">\n";
            echo "  </td>\n";
        echo " </tr>\n";
            echo "</table>\n";
    }

    if ($show_newicon && dateToTime($db[addate])>$timestamp-86400*$show_newicon) {
        echo "    <img src=\"$image_dir/icons/new.gif\" align=\"right\" vspace=\"2\" alt=\"$ad_new\"
                    onmouseover=\"window.status='$ad_new'; return true;\"
                    onmouseout=\"window.status=''; return true;\">";
    }

    echo "   <div class=\"whiteleft\">".badwords($db[header],$_SESSION[susermod])."<br></div>\n";
    echo "  </td><td width=\"1%\" valign=\"top\">\n";
    echo "   <div class=\"smallright\">\n";
    echo "  <img src=\"$image_dir/icons/chart.gif\" alt=\"$ad_stat\" align=\"left\" hspace=\"2\"
                onmouseover=\"window.status='$ad_stat'; return true;\"
                    onmouseout=\"window.status=''; return true;\">:$db[viewed]/$db[answered]</div>\n";
    echo "  </td></tr></table>\n";

    echo "   <div class=\"smallleft\">\n";
    echo "   $ad_from $dbu[username] $ad_date ".dateToStr($db[addate])."<br>\n";
        echo "   $iconstring\n";
    echo "   $ad_location$db[location]\n";
    echo "   <br><div class=\"spaceleft\">&nbsp</div><hr>\n";

    if ($db[attachment1] || $db[attachment2] || $db[attachment3] || $db[attachment4] || $db[attachment5] && $att_enable) {

        include ("classified_ad_att.inc.php");

    }

    echo "<table cellspacing=\"0\" cellpading=\"0\">";

    if ($dbc[sfield] && adfield($db[catid],"sfield")) {
        echo "<tr valign=\"top\">
        <td><div class=smallleft>$dbc[sfield]</div></td>
        <td><div class=smallleft>:</div></td>
        <td><div class=smallleft>".badwords($db[sfield],$_SESSION[susermod])."</div></td>
        </tr>";
    }

    for ($i=1;$i<=20;$i++) {
        $fieldi=("field".$i);
        if ($dbc[$fieldi] && adfield($db[catid],"$fieldi")) {
        echo "<tr valign=\"top\">
            <td><div class=smallleft>$dbc[$fieldi]</div></td>
            <td><div class=smallleft>:</div></td>";
        if (ereg("checkbox",adfield($db[catid],"$fieldi")) && $db[$fieldi]=="on") {
                echo "<td><img src=\"$image_dir/icons/checked2.gif\" border=\"0\" alt=\"$ad_yes\"
            onmouseover=\"window.status='$ad_yes'; return true;\"
            onmouseout=\"window.status=''; return true;\"></td>\n";
        } elseif (ereg("checkbox",adfield($db[catid],"$fieldi")) && $db[$fieldi]=="") {
                echo "<td><img src=\"$image_dir/icons/signno.gif\" border=\"0\" alt=\"$ad_no\"
            onmouseover=\"window.status='$ad_no'; return true;\"
            onmouseout=\"window.status=''; return true;\"></td>\n";
        } elseif (ereg("--url--",adfield($db[catid],"$fieldi"))) {
            if ($db[$fieldi] && $db[$fieldi]!="http://") {
            if (substr($db[$fieldi],0,7)!="http://") {$db[$fieldi]="http://".$db[$fieldi];}
                echo "<td><div class=smallleft><a href=\"$db[$fieldi]\" target=\"_blank\">$db[$fieldi]</a></div></td>";
            }
        } else {
                echo "<td><div class=smallleft>".badwords($db[$fieldi],$_SESSION[susermod])." ".adfieldunit($db[catid],"$fieldi")."</div></td>";
        }
        echo "</tr>";
        }
    }

    echo "<tr valign=\"top\">
        <td><div class=smallleft>$ad_text</div></td>
        <td><div class=smallleft>:</div></td>
        <td><div class=smallleft>".badwords($db[text],$_SESSION[susermod])."</div></td>
            </tr>\n";
    echo "</table>";
    echo "<hr>\n";

    if ($sales_option && !sales_checkaccess(3,$_SESSION[suserid],$catid) && ($_SESSION[suserid] || $dbc[sales]==3)) { // check access for user and cat
        ico_email("","left");
    } else {
        ico_email("adid=$adid","left");
    }

    if ($dbu[icq]) {
        if ($sales_option && !sales_checkaccess(3,$_SESSION[suserid],$catid) && ($_SESSION[suserid] || $dbc[sales]==3)) { // check access for user and cat
        ico_icq("","left");
        } else {
        ico_icq("$dbu[icq]","left");
        }
    }

    ico_friend("catid=$catid&subcatid=$subcatid&adid=$adid","left");

    if ($show_url && $dbu[homepage]) {
        if ($sales_option && !sales_checkaccess(3,$_SESSION[suserid],$catid)) { // check access for user and cat
        ico_url("","left");
        } else {
        ico_url("$dbu[homepage]","left");
        }
    }


    ico_print("","left");

    ico_favorits("adid=$adid","left");

    if ($show_adrating && $_SESSION[suserid]) {
        ico_adrating("adid=$adid","left");
    }

    if ($show_members_details && $_SESSION[suserid]) {
        ico_info("choice=details&uid=$dbu[id]&uname=$dbu[username]","left");
    }

    echo "   <div class=\"smallright\">$ad_nr$adid\n";

    if ($_SESSION[susermod]) {
        echo "<a href=\"classified_my_del.php?adid=$db[id]\" onClick='enterWindow=window.open(\"classified_my_del.php?".sidstr()."adid=$db[id]\",\"Delete\",\"width=400,height=200,top=100,left=100\"); return false' onmouseover=\"window.status='MODERATOR $admy_delete'; return true;\" onmouseout=\"window.status=''; return true;\">
             <img src=\"$image_dir/icons/trash.gif\" border=\"0\" alt=\"MODERATOR $admy_delete\" align=\"right\" vspace=\"2\"></a>\n";
        echo "<a href=\"classified.php?editadid=$db[id]\" onmouseover=\"window.status='MODERATOR $admy_edit'; return true;\" onmouseout=\"window.status=''; return true;\">
             <img src=\"$image_dir/icons/reply.gif\" border=\"0\" alt=\"MODERATOR $admy_edit\" align=\"right\" vspace=\"2\"></a>\n";
    }
    echo "   </div>\n";
    echo "   </div>\n";
    echo "   </td>\n";
    echo " </tr>\n";
    echo "</table>\n";

?>