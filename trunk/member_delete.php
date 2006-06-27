<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: member_update.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Update Member's Data
#
#################################################################################################

require ("library.php");

    $delstring="deleted_".$timestamp;
    if ($really_del_memb) { //if set really delete it
	mysql_query("update ".$prefix."ads set deleted='1' where userid = '$_SESSION[suserid]'");
	mysql_query("delete from ".$prefix."userdata where id = '$_SESSION[suserid]'");
        mysql_query("DELETE FROM ".$prefix."notify WHERE userid='$_SESSION[suserid]'") or died(mysql_error());

        // delete all Ads from this User

        $query = mysql_query("SELECT * FROM ".$prefix."ads WHERE userid=$_SESSION[suserid]");
        while ($db = mysql_fetch_array($query)) {

	// Subtract Counter in userdata-DB
	mysql_query("update ".$prefix."userdata set ads=ads-1 where id='$db[userid]'") or died(mysql_error());

	// Subtract Counter in adcat-DB
	mysql_query("update ".$prefix."adcat set ads=ads-1 where id='$db[catid]'") or died(mysql_error());

	// Subtract Counter in adsubcat-DB
	mysql_query("update ".$prefix."adsubcat set ads=ads-1 where id='$db[subcatid]'") or died(mysql_error());

        // Delete Pictures if any ...
        if (!$pic_database && $db[picture] && is_file("$bazar_dir/$pic_path/$db[picture]")) {
            suppr("$bazar_dir/$pic_path/$db[picture]");
        } elseif ($db[picture]) {
    	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[picture]'") or died(mysql_error());
	}
	if (!$pic_database && $db[_picture] && is_file("$bazar_dir/$pic_path/$db[_picture]")) {
	    suppr("$bazar_dir/$pic_path/$db[_picture]");
	} elseif ($db[_picture]) {
	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[_picture]'") or died(mysql_error());
	}

        if (!$pic_database && $db[picture2] && is_file("$bazar_dir/$pic_path/$db[picture2]")) {
            suppr("$bazar_dir/$pic_path/$db[picture2]");
        } elseif ($db[picture2]) {
    	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[picture2]'") or died(mysql_error());
	}
	if (!$pic_database && $db[_picture2] && is_file("$bazar_dir/$pic_path/$db[_picture2]")) {
	    suppr("$bazar_dir/$pic_path/$db[_picture2]");
	} elseif ($db[_picture2]) {
	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[_picture2]'") or died(mysql_error());
	}

        if (!$pic_database && $db[picture3] && is_file("$bazar_dir/$pic_path/$db[picture3]")) {
            suppr("$bazar_dir/$pic_path/$db[picture3]");
        } elseif ($db[picture3]) {
    	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[picture3]'") or died(mysql_error());
	}
	if (!$pic_database && $db[_picture3] && is_file("$bazar_dir/$pic_path/$db[_picture3]")) {
	    suppr("$bazar_dir/$pic_path/$db[_picture3]");
	} elseif ($db[_picture3]) {
	    mysql_query("delete from ".$prefix."pictures where picture_name = '$db[_picture3]'") or died(mysql_error());
	}

	// Delete Entry from favorits-DB
	mysql_query("delete from ".$prefix."favorits where adid = '$db[id]'") or died(mysql_error());

	// Delete Entry from ads-DB
	mysql_query("delete from ".$prefix."ads where id = '$db[id]'") or died(mysql_error());

        }

    } else {		// or only overwrite the password :-) better
        mysql_query("update ".$prefix."ads set deleted='1' where userid = '$_SESSION[suserid]'") or died(mysql_error());
        mysql_query("update ".$prefix."userdata set password='$delstring',language='xd' where id = '$_SESSION[suserid]'") or died(mysql_error());
    }

logging("X","$_SESSION[suserid]","$_SESSION[susername]","AUTH: deleted","");

logout();
header(headerstr("main.php?status=7"));

?>