<?php
##############################################################################################
#                                                                                            #
#                                   webmail.php
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

#  Include Configs & Variables
#################################################################################################
require ("library.php");
$_SESSION['susernewmails']="";

#  The Head-Section
#################################################################################################
include ($HEADER);

#  The Menu-Section
#################################################################################################
include ("menu.inc.php");

#  The Left-Side-Section
#################################################################################################
$tmp_width = ($table_width+(2*$table_width_side)+10);
echo"<table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$tmp_width\">\n";
echo"<tr>\n";
echo"<td valign=\"top\" align=\"right\">\n";
include ("left.inc.php");
echo"</td>\n";

#  The Main-Section
#################################################################################################
echo"<td valign=\"top\" align=\"left\">\n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" margin=1 width=\"$table_width\" height=\"$table_height\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" height=\"$table_height\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";

if (empty($_SESSION['suserid'])) {
    include ("$language_dir/nologin.inc");
} elseif (!$webmail_enable) {
    echo "<br><br><center><b>WebMail is NOT enabled !!!</b></center>";
} else {

echo"           <table align=\"center\">\n";
echo"            <tr>\n";
echo"             <td>\n";
echo"              <div class=\"mainmenu\">\n";
if ($action=="inbox" || !$action || $action=="del") {
    if ($action=="del" && $id) {
    mysql_query("UPDATE ".$prefix."webmail SET deleted='1' WHERE id='$id' AND toid='$_SESSION[suserid]'");
    }
    $sql="SELECT * FROM ".$prefix."webmail WHERE toid='$_SESSION[suserid]' AND deleted='0' ORDER BY timestamp DESC";
    echo"              $webmail_inbox $menusep\n";
} else {
    echo"              <a href=\"webmail.php?action=inbox\" onmouseover=\"window.status='$webmail_head - $webmail_inbox'; return true;\" onmouseout=\"window.status=''; return true;\">$webmail_inbox</a>$menusep\n";
}

if ($action=="sent" || $action=="sentdel") {
    if ($action=="sentdel" && $id) {
    mysql_query("UPDATE ".$prefix."webmail SET answered='1' WHERE id='$id'");
    }
    $action="sent";
    $sql="SELECT * FROM ".$prefix."webmail WHERE fromid='$_SESSION[suserid]' AND answered='0' ORDER BY timestamp DESC";
    echo"              $webmail_sent $menusep\n";
} else {
    echo"              <a href=\"webmail.php?action=sent\" onmouseover=\"window.status='$webmail_head - $webmail_sent'; return true;\" onmouseout=\"window.status=''; return true;\">$webmail_sent</a>$menusep\n";
}

if ($action=="trash" || $action=="trashdel" || $action=="trashundel") {
    if ($action=="trashdel" && $id) {
    mysql_query("UPDATE ".$prefix."webmail SET deleted='2' WHERE id='$id' AND toid='$_SESSION[suserid]'");
    }
    if ($action=="trashundel" && $id) {
    mysql_query("UPDATE ".$prefix."webmail SET deleted='0' WHERE id='$id' AND toid='$_SESSION[suserid]'");
    }
    $action="trash";
    $sql="SELECT * FROM ".$prefix."webmail WHERE toid='$_SESSION[suserid]' AND deleted='1' ORDER BY timestamp DESC";
    echo"              $webmail_trash\n";
} else {
    echo"              <a href=\"webmail.php?action=trash\" onmouseover=\"window.status='$webmail_head - $webmail_trash'; return true;\" onmouseout=\"window.status=''; return true;\">$webmail_trash</a>\n";
}
echo"              </div>\n";
echo"             </td>\n";
echo"             <td width=\"1%\">\n";
echo"          <div class=\"mainheader\">$webmail_head</div>\n";
echo"             </td>\n";
echo"            </tr>\n";


# Calculate Page-Numbers
#################################################################################################
if (empty($perpage)) $perpage = 5;
if (empty($pperpage)) $pperpage = 5;    // !!! ONLY 3,5,7,9,11,13 !!!
if (empty($offset)) $offset = 0;
if (empty($poffset)) $poffset = 0;

$amount = mysql_query($sql) or died(mysql_error());
$amounts = mysql_num_rows($amount);
$pages = ceil($amounts / $perpage);
$actpage = ($offset+$perpage)/$perpage;
$maxpoffset = $pages-$pperpage;
$middlepage=($pperpage-1)/2;
if ($maxpoffset<0) {$maxpoffset=0;}

 if ($amounts) {

  echo "<tr><td colspan=3><div class=\"mainpages\">\n";

  if ($pages) {                                       // print only when pages > 0
    echo "$ad_pages\n";

    if ($offset) {
    $noffset=$offset-$perpage;
    $npoffset = $noffset/$perpage-$middlepage;
    if ($npoffset<0) {$npoffset = 0;}
    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
        echo "[<a href=\"webmail.php?action=$action&offset=$noffset&poffset=$npoffset\" onmouseover=\"window.status='$nav_prev'; return true;\" onmouseout=\"window.status=''; return true;\"><</a>]\n";
    }
    for($i = $poffset; $i< $poffset+$pperpage && $i < $pages; $i++) {
        $noffset = $i * $perpage;
    $npoffset = $noffset/$perpage-$middlepage;
    if ($npoffset<0) {$npoffset = 0;}
    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
    $actual = $i + 1;
        if ($actual==$actpage) {
        echo "(<a href=\"webmail.php?action=$action&offset=$noffset&poffset=$npoffset\" onmouseover=\"window.status='$nav_actpage'; return true;\" onmouseout=\"window.status=''; return true;\">$actual</a>)\n";
        } else {
        echo "[<a href=\"webmail.php?action=$action&offset=$noffset&poffset=$npoffset\" onmouseover=\"window.status='$nav_gopage'; return true;\" onmouseout=\"window.status=''; return true;\">$actual</a>]\n";
        }
    }

    if ($offset+$perpage<$amount_array) {
        $noffset=$offset+$perpage;
    $npoffset = $noffset/$perpage-$middlepage;
    if ($npoffset<0) {$npoffset = 0;}
    if ($npoffset>$maxpoffset) {$npoffset = $maxpoffset;}
        echo "[<a href=\"webmail.php?action=$action&offset=$noffset&poffset=$npoffset\" onmouseover=\"window.status='$nav_next'; return true;\" onmouseout=\"window.status=''; return true;\">></a>]\n";
    }
  }

  echo "</div></td></tr>\n";
  echo "</table>\n";
  $sql.= " LIMIT $offset, $perpage";


  echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

  $result = mysql_query($sql) or die(mysql_error());
  while ($db = mysql_fetch_array($result)) {

    echo " <tr>\n";
    echo "   <td class=\"classcat1\">\n";
    echo "   <div class=\"smallleft\">\n";
    if ($action!="trash" && $action!="sent") {
    echo "   <a href=\"webmail.php?action=del&id=$db[id]\" onmouseover=\"window.status='$webmail_del'; return true;\"
            onmouseout=\"window.status=''; return true;\">
                <img src=\"$image_dir/icons/trash.gif\" border=\"0\" alt=\"$webmail_del\"></a>\n";
    if ($db[fromid]) {
        echo "<a href=\"sendmail.php?username=$db[fromname]\" onClick='enterWindow=window.open(\"sendmail.php?".sidstr()."username=$db[fromname]&newsubject=".rawurlencode("Re:".$db[subject])."&newtext=".rawurlencode("\n\n> ".str_replace("\n","\n> ",$db[text])."\n")."\",\"EMail\",\"width=600,height=430,top=100,left=100\"); return false'
                onmouseover=\"window.status='$webmail_reply'; return true;\"
                onmouseout=\"window.status=''; return true;\">
                <img src=\"$image_dir/icons/email.gif\" border=\"0\" alt=\"$webmail_reply\"></a>\n";
    }

    } elseif ($action=="sent") {
    echo "   <a href=\"webmail.php?action=sentdel&id=$db[id]\" onmouseover=\"window.status='$webmail_sdel'; return true;\"
            onmouseout=\"window.status=''; return true;\">
                <img src=\"$image_dir/icons/trash.gif\" border=\"0\" alt=\"$webmail_sdel\"></a>\n";

    } elseif ($action=="trash") {
    echo "   <a href=\"webmail.php?action=trashdel&id=$db[id]\" onmouseover=\"window.status='$webmail_tdel'; return true;\"
            onmouseout=\"window.status=''; return true;\">
                <img src=\"$image_dir/icons/trash.gif\" border=\"0\" alt=\"$webmail_tdel\"></a>\n";
    echo "   <a href=\"webmail.php?action=trashundel&id=$db[id]\" onmouseover=\"window.status='$webmail_tundel'; return true;\"
            onmouseout=\"window.status=''; return true;\">
                <img src=\"$image_dir/icons/yinyan.gif\" border=\"0\" alt=\"$webmail_tundel\"></a>\n";

    }
    echo "   </div>\n";
    echo "   </td>\n";
    echo "   <td class=\"classcat4\">\n";
    echo "   <div class=\"mainleft\">\n";
    echo "<b>$webmail_from:</b> \n";
    if ($db[fromid]) {
    if ($action!="sent") {
            echo "<a href=\"members.php?choice=details&uid=$db[fromid]&uname=$db[fromname]\">$db[fromname]</a> ";
        echo "<b>$webmail_to:</b> $db[toname]<br>\n";
    } else {
        echo "$db[fromname] ";
        echo "<b>$webmail_to:</b> <a href=\"members.php?choice=details&uid=$db[toid]&uname=$db[toname]\">$db[toname]</a><br>\n";
    }
    } else {
    echo "$db[fromemail] ";
        echo "<b>$webmail_to:</b> $db[toname]<br>\n";
    }
    echo "<b>$webmail_date:</b> ".date($userdateformat,($db[timestamp]+$timeoffset+$usertimeoffset))."<br>\n";
    echo "<b>$webmail_subject:</b>".nl2br(htmlentities($db[subject]));
    echo "<hr><b>$webmail_message:</b> <br>".nl2br(htmlentities($db[text]));

    if ($db[attachment1] || $db[attachment2] || $db[attachment3]) {
    echo "<hr><div class=\"smallleft\"><b>$webmail_attach:</b> ";
    if ($db[attachment1]) echo "<a href=\"$webmail_path/$db[attachment1]\" target=\"_balnk\">$db[attachment1]</a> ";
    if ($db[attachment2]) echo "<a href=\"$webmail_path/$db[attachment2]\" target=\"_balnk\">$db[attachment2]</a> ";
    if ($db[attachment3]) echo "<a href=\"$webmail_path/$db[attachment3]\" target=\"_balnk\">$db[attachment3]</a> ";
    echo "</div>";
    }
    echo "   </div>\n";
    echo "   </td>\n";
    echo " </tr>\n";
  } //End while

 } else {
    echo "<tr><td colspan=3>";
    echo $mess_noentry;
    echo "</td></tr>";
 }
  echo"  </table>\n";
}

echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo" </table>\n";
echo"</td>\n";


#  The Right-Side-Section
#################################################################################################
echo"<td valign=\"top\" align=\"left\">\n";
include ("right.inc.php");
echo"</td>\n";
echo"</tr>\n";
echo"</table>\n";

#  The Footer-Section
#################################################################################################
include ($FOOTER);
?>