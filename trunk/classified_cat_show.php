<?php
 ##############################################################################################
#                                                                                            #
#                                   classified_cat_show.php
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
if(!strpos($_SERVER['PHP_SELF'],'classified_cat_show.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
#  Main Categories
#################################################################################################
if (!$catid) {  // show the main categories

echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
echo "<tr><td><div class=\"maincatnav\">";
echo "$ad_home\n";
echo "</div>";
echo "</td></tr></table>";

$result = mysql_query("SELECT * FROM ".$prefix."adcat WHERE disabled<>'1' ORDER by sortorder,id") or died("Record NOT Found: ".mysql_error());

echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

while ($db = mysql_fetch_array($result)) {

    echo " <tr>\n";
    echo "   <td class=\"classcat1\">\n";
    if ($db['picture']) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}
    echo "   </td>\n";
    echo "   <td class=\"classcat2\">\n";
    if (!empty($sales_option)) {
    if ($db['sales']) {
        if ($db['sales']==1) {
        $salesalt=$sales_lang_paid1;
        } elseif ($db['sales']==2) {
        $salesalt=$sales_lang_paid2;
        } elseif ($db['sales']==3) {
        $salesalt=$sales_lang_paid3;
        }
        echo "<img src=\"$image_dir/cats/paid".$db['sales'].".gif\" border=\"0\" align=\"right\"
            alt=\"$salesalt\"
            onmouseover=\"window.status='$salesalt'; return true;\"
            onmouseout=\"window.status=''; return true;\">\n";
    }
    }
    echo "   <a href=\"classified.php?catid=$db[id]\" onmouseover=\"window.status='$db[description]';
        return true;\" onmouseout=\"window.status=''; return true;\">$db[name]</a> ($db[ads])<br>\n";
    echo "   <div class=\"smallleft\">\n";
    echo "   $db[description]<br>\n";

    if ($db['passphrase']) {
    echo "<img src=\"$image_dir/icons/key.gif\" alt=\"$cat_pass\" align=\"right\" vspace=\"2\"
            onmouseover=\"window.status='$cat_pass'; return true;\"
            onmouseout=\"window.status=''; return true;\">";
    }

    if ($show_newicon) {
    $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE catid='$db[id]' AND (TO_DAYS(addate)>TO_DAYS(now())-$show_newicon)") or died(mysql_error());
    if (mysql_num_rows($query)) {
        echo "<img src=\"$image_dir/icons/new.gif\" alt=\"$cat_new\" align=\"right\" vspace=\"2\"
                onmouseover=\"window.status='$cat_new'; return true;\"
            onmouseout=\"window.status=''; return true;\">";
    }
    }

    echo "   </div>";
    echo "   </td>\n";

    $db = mysql_fetch_array($result);

    echo "   <td class=\"classcat1\">\n";
    if ($db['picture']) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}
    echo "   </td>\n";
    echo "   <td class=\"classcat2\">\n";
    if (!empty($sales_option)) {
    if ($db['sales']) {
        if ($db['sales']==1) {
        $salesalt=$sales_lang_paid1;
        } elseif ($db['sales']==2) {
        $salesalt=$sales_lang_paid2;
        } elseif ($db['sales']==3) {
        $salesalt=$sales_lang_paid3;
        }
        echo "<img src=\"$image_dir/cats/paid".$db['sales'].".gif\" border=\"0\" align=\"right\"
            alt=\"$salesalt\"
            onmouseover=\"window.status='$salesalt'; return true;\"
            onmouseout=\"window.status=''; return true;\">\n";
    }
    }
    if ($db) {
    echo "   <a href=\"classified.php?catid=$db[id]\" onmouseover=\"window.status='$db[description]';
        return true;\" onmouseout=\"window.status=''; return true;\">$db[name]</a> ($db[ads])<br>\n";
    echo "   <div class=\"smallleft\">\n";
    echo "   $db[description]<br>\n";

    if ($db['passphrase']) {
        echo "<img src=\"$image_dir/icons/key.gif\" alt=\"$cat_pass\" align=\"right\" vspace=\"2\"
            onmouseover=\"window.status='$cat_pass'; return true;\"
            onmouseout=\"window.status=''; return true;\">";
    }

    if ($show_newicon) {
        $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE catid='$db[id]' AND (TO_DAYS(addate)>TO_DAYS(now())-$show_newicon)") or died(mysql_error());
        if (mysql_num_rows($query)) {
        echo "<img src=\"$image_dir/icons/new.gif\" alt=\"$cat_new\" align=\"right\" vspace=\"2\"
                    onmouseover=\"window.status='$cat_new'; return true;\"
                onmouseout=\"window.status=''; return true;\">";
        }
    }
    } else {
    echo "&nbsp;";
    }

    echo "   </div>";
    echo "   </td>\n";
    echo " </tr>\n";
    } //End while

#  Sub Categories
#################################################################################################
} else { // show the subcategories

if ($sales_option) {
    if (!sales_checkaccess(1,$_SESSION['suserid'],$catid)) {  // check access for user and cat
    open_sales_window();
    #echo "<script language=javascript>location.replace('classified.php?textmessage=$sales_lang_noaccess');</script>";
    echo "<script language=javascript>location.replace('classified.php');</script>";

    }
}

$result = mysql_query("SELECT * FROM ".$prefix."adcat WHERE id='$catid' AND disabled<>'1'") or died("Record NOT Found");
$db = mysql_fetch_array($result) or died ("Category NOT Found");

echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
echo "<tr><td><div class=\"maincatnav\">";
echo "<a href=\"classified.php\" onmouseover=\"window.status='$ad_home'; return true;\"
        onmouseout=\"window.status=''; return true;\">$ad_home</a> / $db[name]\n";
echo "</td></tr></table>";
echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";


$result = mysql_query("SELECT * FROM ".$prefix."adsubcat where catid=$catid ORDER by id") or died("Record NOT Found");

while ($db = mysql_fetch_array($result)) {

    $resultc = mysql_query("SELECT * FROM ".$prefix."adcat where id='$catid' AND disabled<>'1'") or died("Record NOT Found");
    $dbc = mysql_fetch_array($resultc);

    echo " <tr>\n";
    echo "   <td class=\"classcat1\">\n";
    if ($db['picture']) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}
    echo "   </td>\n";
    echo "   <td class=\"classcat2\">\n";
    echo "   <a href=\"classified.php?catid=$catid&subcatid=$db[id]\" onmouseover=\"window.status='$db[description]';
        return true;\" onmouseout=\"window.status=''; return true;\">$db[name]</a> ($db[ads])<br>\n";
    echo "   <div class=\"smallleft\">\n";
    echo "   $db[description]<br>\n";
    if ($catnotify && $db[id] && $_SESSION[suserid]) {
    echo "   <a href=\"notify.php?addid=$db[id]\"
            onClick='enterWindow=window.open(\"notify.php?".sidstr()."addid=$db[id]\",\"Notify\",\"width=400,height=200,top=200,left=200\"); return false'
            onmouseover=\"window.status='$notify_add'; return true;\"
            onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/mail.gif\" border=\"0\" alt=\"$notify_add\" align=\"right\" vspace=\"2\"></a>\n";
    }

    if ($dbc[passphrase]) {
    echo "<img src=\"$image_dir/icons/key.gif\" alt=\"$cat_pass\" align=\"right\" vspace=\"2\"
            onmouseover=\"window.status='$cat_pass'; return true;\"
            onmouseout=\"window.status=''; return true;\">";
    }

    if ($show_newicon) {
    $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE subcatid='$db[id]' AND (TO_DAYS(addate)>TO_DAYS(now())-$show_newicon)") or died(mysql_error());
    if (mysql_num_rows($query)) {
        echo "<img src=\"$image_dir/icons/new.gif\" alt=\"$cat_new\" align=\"right\" vspace=\"2\"
            onmouseover=\"window.status='$cat_new'; return true;\"
        onmouseout=\"window.status=''; return true;\">";
    }
    }

    echo "   </div>";
    echo "   </td>\n";

    $db = mysql_fetch_array($result);

    $resultc = mysql_query("SELECT * FROM ".$prefix."adcat where id='$catid' AND disabled<>'1'") or died("Record NOT Found");
    $dbc = mysql_fetch_array($resultc);

    echo "   <td class=\"classcat1\">\n";
    if ($db['picture']) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}
    echo "   </td>\n";
    echo "   <td class=\"classcat2\">\n";
    if ($db) {
    echo "   <a href=\"classified.php?catid=$catid&subcatid=$db[id]\" onmouseover=\"window.status='$db[description]';
        return true;\" onmouseout=\"window.status=''; return true;\">$db[name]</a> ($db[ads])<br>\n";
    }
    echo "   <div class=\"smallleft\">\n";
    echo "   $db[description]<br>\n";
    if ($catnotify && $db[id] && $_SESSION[suserid]) {
    echo "   <a href=\"notify.php?addid=$db[id]\"
            onClick='enterWindow=window.open(\"notify.php?".sidstr()."addid=$db[id]\",\"Notify\",\"width=400,height=200,top=200,left=200\"); return false'
            onmouseover=\"window.status='$notify_add'; return true;\"
            onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/mail.gif\" border=\"0\" alt=\"$notify_add\" align=\"right\" vspace=\"2\"></a>\n";

    if ($dbc[passphrase]) {
        echo "<img src=\"$image_dir/icons/key.gif\" alt=\"$cat_pass\" align=\"right\" vspace=\"2\"
            onmouseover=\"window.status='$cat_pass'; return true;\"
            onmouseout=\"window.status=''; return true;\">";
    }

    if ($show_newicon) {
        $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE subcatid='$db[id]' AND (TO_DAYS(addate)>TO_DAYS(now())-$show_newicon)") or died(mysql_error());
        if (mysql_num_rows($query)) {
        echo "<img src=\"$image_dir/icons/new.gif\" alt=\"$cat_new\" align=\"right\" vspace=\"2\"
                onmouseover=\"window.status='$cat_new'; return true;\"
            onmouseout=\"window.status=''; return true;\">";
        }
    }
    }

    echo "   </div>";
    echo "   </td>\n";
    echo " </tr>\n";

    } //End while


}

# End of Page reached
#################################################################################################
echo "</table>\n";

?>