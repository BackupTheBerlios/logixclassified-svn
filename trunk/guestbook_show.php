<?php
##############################################################################################
#                                                                                            #
#                                   guestbook_show.php
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
if(!strpos($_SERVER['PHP_SELF'],'guestbook_show.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
#  The Main-Section
#################################################################################################
echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
echo "<tr><td><div class=\"maincatnav\">\n";
echo "&nbsp<br>\n";
echo "</div></td>\n";

#  Calculate Page-Numbers
#################################################################################################
if (empty($perpage)) $perpage = 5;
if (empty($pperpage)) $pperpage = 9;    //!!! ONLY 5,7,9,11,13 !!!!
if (empty($sort)) $sort = "desc";
if (empty($offset)) $offset = 0;
if (empty($poffset)) $poffset = 0;

$amount = mysql_query("SELECT count(id) FROM ".$prefix."guestbook");
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
        if ($npoffset<0) {$npoffset=0;}
        if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
    echo "[<a href=\"guestbook.php?offset=$noffset&poffset=$npoffset\" onmouseover=\"window.status='$nav_prev'; return true;\" onmouseout=\"window.status=''; return true;\"><</a>] ";
    }

    for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {
        $noffset = $i * $perpage;
        $npoffset = $noffset/$perpage-$middlepage;
        if ($npoffset<0) {$npoffset = 0;}
        if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
    $actual = $i + 1;
        if ($actual==$actpage) {
        echo "(<a href=\"guestbook.php?offset=$noffset&poffset=$npoffset\" onmouseover=\"window.status='$nav_actpage'; return true;\" onmouseout=\"window.status=''; return true;\">$actual</a>) ";
            } else {
        echo "[<a href=\"guestbook.php?offset=$noffset&poffset=$npoffset\" onmouseover=\"window.status='$nav_gopage'; return true;\" onmouseout=\"window.status=''; return true;\">$actual</a>] ";
        }
    }

    if ($offset+$perpage<$amount_array["0"]) {
        $noffset=$offset+$perpage;
        $npoffset = $noffset/$perpage-$middlepage;
        if ($npoffset<0) {$npoffset=0;}
        if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
    echo "[<a href=\"guestbook.php?offset=$noffset&poffset=$npoffset\" onmouseover=\"window.status='$nav_next'; return true;\" onmouseout=\"window.status=''; return true;\">></a>] ";
        }
    }

echo "</div></td></tr>\n";
echo "</table>\n";

#  Start the Page
#################################################################################################

echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
echo "   <tr>\n";
echo "     <td class=\"gbheader\">$gb_name</td>\n";
echo "     <td class=\"gbheader\">$gb_comments</td>\n";
echo "   </tr>\n";

#  Get Entrys for current page
#################################################################################################

$result = mysql_query("SELECT * FROM ".$prefix."guestbook ORDER by id $sort LIMIT $offset, $perpage");

while ($db = mysql_fetch_array($result)) {
    $when = date($userdateformat, ($db["timestamp"]+$timeoffset+$usertimeoffset));

    if ($db['email']   != "none") {
    $email = "<a href=\"mailto: $db[email]\"><img src=\"$image_dir/icons/email.gif\" alt=\" Send E-Mail\" border=\"0\" align=\"right\"></a>";
    } else {
    $email = "";
    }
    if ($db['icq']     != 0)      {
    $icq = "<a href=\"http://wwp.icq.com/scripts/contact.dll?msgto=$db[icq]\"><img src=\"http://wwp.icq.com/scripts/online.dll?icq=" . $db[icq] . "&img=5\" alt=\"Send ICQ Message\" border=\"0\" align=\"right\" height=\"17\"></a>";
    } else {
    $icq = "";
    }
    if ($db['http']    != "none") {
    $http = "<a href=\"http://$db[http]\" target=\"_blank\"><img src=\"$image_dir/icons/home.gif\" alt=\"View Web Page\" border=\"0\" align=\"right\"></a>";
    } else {
    $http = "";
    }
    if ($db['ip']      != "none") {
    $ips = "<img src=\"$image_dir/icons/ip.gif\" alt=\"IP logged\" align=\"left\">";
    } else {
    $ips = "";
    }
    if ($db['location']!= "none") {
    $location = "$gb_location<br>$db[location]<br>";
    } else {
    $location = "<br><br>";
    }
    if ($db['browser']      != "") {
    $browser = "<img src=\"$image_dir/icons/browser.gif\" alt=\"$db[browser]\" align=\"left\">";
    } else {
    $browser = "";
    }
(empty($_SESSION['susermod']))?$_SESSION['susermod']="": $_SESSION['susermod']= $_SESSION['susermod'];
    echo "  <tr>\n";
    echo "     <td class=\"gbtable1\">\n";
    echo "        <em id=\"red\">".badwords($db['name'],$_SESSION['susermod'])."</em><br>\n";
    echo "        <div class=\"smallleft\">$location<br></div>\n";
    echo "        <br>$icq $http $email $ips $browser\n";
    echo "     </td>\n";
    echo "        <td class=\"gbtable2\"><div class=\"smallleft\">\n";
    if ($_SESSION['susermod']) {
    echo "<a href=\"guestbook_submit.php?delid=$db[id]\"><img src=\"$image_dir/icons/trash.gif\" alt=\"MODERATOR Delete Entry\" border=\"0\" align=\"right\"></a>";
    echo "<div class=\"spaceleft\">&nbsp;</div>\n";
    }
    echo "        $gb_posted $when</div><hr>".badwords($db['message'],$_SESSION['susermod'])."</td>\n";
    echo "  </tr>\n";
    }

# End of Page reached
#################################################################################################
echo "</table>\n";
?>