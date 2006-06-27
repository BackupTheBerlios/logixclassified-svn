<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: classified_notify_del.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Delete notify categories
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
window_header("Notify");

#  The Main-Section
#################################################################################################

if (!$_SESSION[suserid]) {
  include ("$language_dir/nologin.inc");
} else {

  #  Get Entrys for current page
  #################################################################################################
  if ($delid && $confirm) {  		// Delete

    $result = mysql_query("SELECT * FROM ".$prefix."notify WHERE subcatid='$delid' AND userid='$_SESSION[suserid]'");
    $db = mysql_fetch_array($result);

    if ($db) {

	mysql_query("delete from ".$prefix."notify WHERE subcatid='$delid' AND userid='$_SESSION[suserid]'")
	or died("Database Query Error");

        echo "<div class=\"mainheader\">$notifydel_head</div>\n";
	echo "<br>\n";
	echo "<div class=\"smsubmit\">$notifydel_done<br><br>\n";

	echo "<form action=javascript:window.opener.location.reload();window.close(); METHOD=POST><input type=submit value=$close></form>\n";
	echo "</div>\n";


    } else {
	died ("FATAL Error !!!");
    }
  } elseif ($addid) {
    echo "<div class=\"mainheader\">$notify_head</div>\n";
    echo "<div class=\"maintext\"><br><center>\n";
    $query = mysql_query("SELECT * FROM ".$prefix."notify WHERE userid=$_SESSION[suserid] AND subcatid=$addid") or died ("Database Error");
    $result = mysql_num_rows($query);
    if ($result<1) {
        $query = mysql_query("INSERT INTO ".$prefix."notify VALUES('$_SESSION[suserid]','$addid')") or died ("Database Error");
        echo "$notify_done<br>\n";
    } else {
        echo "$notify_exist<br>\n";
    }
    echo "<br><form action=javascript:window.close() METHOD=POST>\n";
    echo "<input type=submit value=$close></form>\n";
    echo "</center></div>\n";

  } elseif ($delid) {          		// Ask for sure

    $result = mysql_query("SELECT * FROM ".$prefix."notify WHERE subcatid='$delid' AND userid='$_SESSION[suserid]'");
    $db = mysql_fetch_array($result);

    if ($db) {
        echo "<div class=\"mainheader\">$notifydel_head</div>\n";

	echo "<br>\n";
        echo "<form action=\"$SELF_PHP\" METHOD=\"POST\">\n";
	echo "<div class=\"smsubmit\">$notifydel_msg<br>(ID: $db[subcatid])\n";
	echo "<input type=\"hidden\" name=\"delid\" value=\"$delid\">\n";
        echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
        echo "<br><br><input type=submit value=$admydel_submit>\n";
	echo "</div></form>\n";

    } else {
	died ("FATAL Error !!!");
    }


  } else {				// Error
    died ("FATAL Error !!!");
  }

}

#  The Foot-Section
#################################################################################################
window_footer();
?>