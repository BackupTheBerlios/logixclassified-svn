<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: classified_my_del.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Delete AD's
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  HTML Header Start
#################################################################################################
window_header($admy_move);

if (!$_SESSION[suserid]) {
    include ("$language_dir/nologin.inc");
} else {

#  Get Entrys for current page
#################################################################################################
if ($adid && $catid && $subcatid && $confirm && $admovecat) {  		// Move Ad

    if ($sales_option) {
	if (!sales_checkaccess(2,$_SESSION[suserid],$catid)) { // check access for user and cat
            echo "<script language=javascript>window.close();</script>";
            open_sales_window();
	    exit;
        }
    }

    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id=$adid");
    $db = mysql_fetch_array($result);

    if ($db[userid] == $_SESSION[suserid] || $_SESSION[susermod]) {

	$result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id='$adid'") or die("Database Query Error");
	$db = mysql_fetch_array($result);
	$adcatid=$db[catid];
	$adsubcatid=$db[subcatid];
	$result2 = mysql_query("SELECT * FROM ".$prefix."adcat WHERE id='$adcatid'") or die("Database Query Error");
	$result3 = mysql_query("SELECT * FROM ".$prefix."adsubcat WHERE id='$adsubcatid'") or die("Database Query Error");
	$result4 = mysql_query("SELECT * FROM ".$prefix."adsubcat WHERE id='$subcatid'") or die("Database Query Error");

	if ($db && $result2 && $result3 && $result4) {
	    $db4 = mysql_fetch_array($result4);
	    $adcatid2=$db4[catid];
	    $adsubcatid2=$db4[id];
	    if ($adsubcatid2<>$adsubcatid) {

	      // Move Ad in ads-DB
	      mysql_query("update ".$prefix."ads set catid='$adcatid2',subcatid='$adsubcatid2' where id='$adid'") or die("Database Query Error - ad");

	      if ($db[publicview]) {

	        // Subtract Counter in adcat-DB
		mysql_query("update ".$prefix."adcat set ads=ads-1 where id='$adcatid'") or die("Database Query Error - adcat");

		// Subtract Counter in adsubcat-DB
		mysql_query("update ".$prefix."adsubcat set ads=ads-1 where id='$adsubcatid'") or die("Database Query Error - adsubcat");

	        // Add Counter in adcat-DB
		mysql_query("update ".$prefix."adcat set ads=ads+1 where id='$adcatid2'") or die("Database Query Error - adcat");

		// Add Counter in adsubcat-DB
		mysql_query("update ".$prefix."adsubcat set ads=ads+1 where id='$adsubcatid2'") or die("Database Query Error - adsubcat");

	      }

    		echo "<div class=\"mainheader\">$admymove_head</div>\n";
		echo "<br>\n";
		echo "<div class=\"smsubmit\">$admymove_done<br><br>\n";

    		if ($_SESSION[susermod]) {
		    echo "<form action=javascript:window.opener.location.href='classified.php?".sidstr()."status=7';window.close(); METHOD=POST><input type=submit value=$close></form>\n";
		} else {
		    echo "<form action=javascript:window.opener.location.reload();window.close(); METHOD=POST><input type=submit value=$close></form>\n";
		}
		echo "</div>\n";

	    } else {
    		echo "ERROR: cann't move, same SubCat !!!";
	    }
	} else {
    	    echo "ERROR: Record NOT found !!!";
	}


    } else {
	died ("FATAL Error !!!");
    }

} elseif ($adid && $catid && !$subcatid && $admovecat) {          		// Ask for new cat

    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id=$adid");
    $db = mysql_fetch_array($result);

    if ($db[userid] == $_SESSION[suserid] || $_SESSION[susermod]) {

	$result = mysql_query("SELECT * FROM ".$prefix."adsubcat WHERE catid='$catid' AND id<>'$db[subcatid]' ORDER by id") or died("Database Query Error");
	while ($db = mysql_fetch_array($result)) {
	    $optioncat .= "<option value=\"$db[id]\">$db[name]</option>";
	}


        echo "<div class=\"mainheader\">$admymove_head</div>\n";
	echo "<br>\n";
        echo "<form action=\"$_SERVER[PHP_SELF]\" METHOD=\"POST\">\n";
	echo "<div class=\"smsubmit\">$admymove_msg<br><br>$db[header]\n";

	echo "<select name=\"subcatid\">\n";
	echo "$optioncat</select></td>\n";

	echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">\n";
	echo "<input type=\"hidden\" name=\"adid\" value=\"$adid\">\n";
        echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
        echo "<br><br><input type=submit value=$submit>\n";
	echo "</div></form>\n";

    } else {
	died ("FATAL Error !!!");
    }

} elseif ($adid && !$catid && !$subcatid && $admovecat) {          		// Ask for new cat

/*
    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id=$adid");
    $db = mysql_fetch_array($result);
*/
    if ($db[userid] == $_SESSION[suserid] || $_SESSION[susermod]) {

	$result = mysql_query("SELECT * FROM ".$prefix."adcat ORDER by id") or died("Database Query Error");
	while ($db = mysql_fetch_array($result)) {
	    $optioncat .= "<option value=\"$db[id]\">$db[name]</option>";
	}


        echo "<div class=\"mainheader\">$admymove_head</div>\n";
	echo "<br>\n";
        echo "<form action=\"$_SERVER[PHP_SELF]\" METHOD=\"POST\">\n";
	echo "<div class=\"smsubmit\">$admymove_msg<br><br>$db[header]\n";

	echo "<select name=\"catid\">\n";
	echo "$optioncat</select></td>\n";

	echo "<input type=\"hidden\" name=\"adid\" value=\"$adid\">\n";
        echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
        echo "<br><br><input type=submit value=$submit>\n";
	echo "</div></form>\n";



    } else {
	died ("FATAL Error !!!");
    }

} else {				// Error
died ("FATAL Error !!!");
}

}					//

window_footer
?>
