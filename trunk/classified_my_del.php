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
window_header($admy_delete);

if (!$_SESSION[suserid]) {
    include ("$language_dir/nologin.inc");
} else {


#  Get Entrys for current page
#################################################################################################
if ($adid && $confirm) {  		// Delete AD

    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id=$adid");
    $db = mysql_fetch_array($result);

    if ($db[userid] == $_SESSION[suserid] || $_SESSION[susermod]) {

	// Subtract Counter in userdata-DB
	mysql_query("update ".$prefix."userdata set ads=ads-1 where id='$db[userid]'")
	or died("Database Query Error");

	// Subtract Counter in adcat-DB
	mysql_query("update ".$prefix."adcat set ads=ads-1 where id='$db[catid]'")
	or died("Database Query Error");

	// Subtract Counter in adsubcat-DB
	mysql_query("update ".$prefix."adsubcat set ads=ads-1 where id='$db[subcatid]'")
	or died("Database Query Error");

        // Delete Pictures if any ...
        if (!$pic_database && $db[picture] && is_file("$bazar_dir/$pic_path/$db[picture]")) {
            suppr("$bazar_dir/$pic_path/$db[picture]");
        } elseif ($db[picture]) {
    	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[picture]'") or died("Database Query Error");
	}
	if (!$pic_database && $db[_picture] && is_file("$bazar_dir/$pic_path/$db[_picture]")) {
	    suppr("$bazar_dir/$pic_path/$db[_picture]");
	} elseif ($db[_picture]) {
	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[_picture]'") or died("Database Query Error");
	}

        if (!$pic_database && $db[picture2] && is_file("$bazar_dir/$pic_path/$db[picture2]")) {
            suppr("$bazar_dir/$pic_path/$db[picture2]");
        } elseif ($db[picture2]) {
    	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[picture2]'") or died("Database Query Error");
	}
	if (!$pic_database && $db[_picture2] && is_file("$bazar_dir/$pic_path/$db[_picture2]")) {
	    suppr("$bazar_dir/$pic_path/$db[_picture2]");
	} elseif ($db[_picture2]) {
	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[_picture2]'") or died("Database Query Error");
	}

        if (!$pic_database && $db[picture3] && is_file("$bazar_dir/$pic_path/$db[picture3]")) {
            suppr("$bazar_dir/$pic_path/$db[picture3]");
        } elseif ($db[picture3]) {
    	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[picture3]'") or died("Database Query Error");
	}
	if (!$pic_database && $db[_picture3] && is_file("$bazar_dir/$pic_path/$db[_picture3]")) {
	    suppr("$bazar_dir/$pic_path/$db[_picture3]");
	} elseif ($db[_picture3]) {
	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[_picture3]'") or died("Database Query Error");
	}

        // Delete Attachments if any ...
        if ($db[attachment1] && is_file("$bazar_dir/$att_path/$db[attachment1]")) {
    	    suppr("$bazar_dir/$att_path/$db[attachment1]");
        }
        if ($db[attachment2] && is_file("$bazar_dir/$att_path/$db[attachment2]")) {
    	    suppr("$bazar_dir/$att_path/$db[attachment2]");
        }
        if ($db[attachment3] && is_file("$bazar_dir/$att_path/$db[attachment3]")) {
    	    suppr("$bazar_dir/$att_path/$db[attachment3]");
        }

	// Delete Entry from favorits-DB
	mysql_query("delete from ".$prefix."favorits where adid = '$adid'")
	or died("Database Query Error");

	// Delete Entry from ads-DB
	mysql_query("delete from ".$prefix."ads where id = '$adid'")
	or died("Database Query Error");

        echo "<div class=\"mainheader\">$admydel_head</div>\n";
	echo "<br>\n";
	echo "<div class=\"smsubmit\">$admydel_done<br><br>\n";

    	if ($_SESSION[susermod]) {
		echo "<form action=javascript:window.opener.location.href='classified.php?".sidstr()."status=7';window.close(); METHOD=POST><input type=submit value=$close></form>\n";
	} else {
		echo "<form action=javascript:window.opener.location.reload();window.close(); METHOD=POST><input type=submit value=$close></form>\n";
	}
	echo "</div>\n";


    } else {
	died ("FATAL Error !!!");
    }


} elseif ($adid) {          		// Ask for sure

    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id=$adid");
    $db = mysql_fetch_array($result);

    if ($db[userid] == $_SESSION[suserid] || $_SESSION[susermod]) {
        echo "<div class=\"mainheader\">$admydel_head</div>\n";
	echo "<br>\n";
        echo "<form action=\"classified_my_del.php\" METHOD=\"POST\">\n";
	echo "<div class=\"smsubmit\">$admydel_msg<br><br>$db[header]\n";
	echo "<input type=\"hidden\" name=\"adid\" value=\"$adid\">\n";
        echo "<input type=\"hidden\" name=\"confirm\" value=\"true\">\n";
        echo "<br><br><input type=submit value=$admydel_submit>\n";
	echo "</div></form>\n";

    } else {
	died ("FATAL Error !!!");
    }


} else {				// Error
died ("FATAL Error !!!");
}

}					//

window_footer();
?>