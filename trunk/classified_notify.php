<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_notify.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : Member's can view & delete there notify-cat entry's
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'classified_notify.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
#  Get Entrys for current page
#################################################################################################
if ($_SESSION[suserid]) {  // show the ads list

 $result = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id='$_SESSION[suserid]'") or died("Record NOT Found");
 $db = mysql_fetch_array($result);
 $amount = mysql_query("SELECT count(userid) FROM ".$prefix."notify WHERE userid='$_SESSION[suserid]'") or died("Record NOT Found");
 $amount_array = mysql_fetch_array($amount);

 if ($amount_array[0]) {

  echo "<table align=\"center\"  cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
  echo "<tr><td><div class=\"smallright\">\n";
  echo "$admy_member$db[username]\n";
  echo "</div></td>\n";
  echo "</table>\n";

  echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
  $result = mysql_query("SELECT * FROM ".$prefix."notify WHERE userid='$_SESSION[suserid]'") or died("Favorits - Record NOT Found");
  while ($tempdb = mysql_fetch_array($result)) {
    $query = mysql_query("SELECT * FROM ".$prefix."adsubcat WHERE id='$tempdb[subcatid]'") or died("Ads - Record NOT Found");
    $db = mysql_fetch_array($query);

    if ($db) {
    $query2 = mysql_query("SELECT id,name FROM ".$prefix."adcat WHERE id='$db[catid]'") or died("Ads - Record NOT Found");
    $dbc = mysql_fetch_array($query2);
    echo " <tr>\n";
    echo "   <td class=\"classcat1\">\n";
    if ($db[picture]) { echo "<img src=\"$db[picture] \">\n";} else {echo "&nbsp;";}
    echo "   </td>\n";
    echo "   <td class=\"classcat2\">\n";
    echo "   <a href=\"classified.php?catid=$db[catid]&subcatid=$db[id]\" onmouseover=\"window.status='$db[description]';
                 return true;\" onmouseout=\"window.status=''; return true;\">$dbc[name]/$db[name]</a> ($db[ads])<br>\n";
    echo "   <div class=\"smallleft\">\n";
    echo "   <a href=\"notify.php?delid=$db[id]\"
          onClick='enterWindow=window.open(\"notify.php?".sidstr()."delid=$db[id]\",\"Delete\",\"width=400,height=200,top=100,left=100\"); return false'
          onmouseover=\"window.status='$adnot_delete'; return true;\"
          onmouseout=\"window.status=''; return true;\">
         <img src=\"$image_dir/icons/trash.gif\" border=\"0\" alt=\"$adnot_delete\" align=\"right\" vspace=\"2\"></a>\n";
    echo "   $db[description]\n";
    echo "   </div>";
    echo "   </td>\n";

    }
  } //End while
 echo "</table>\n";
 } else {
    echo $mess_noentry;
 }
} else { // NO Login

}

# End of Page reached
#################################################################################################
?>