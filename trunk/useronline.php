<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: links.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Link's Area
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");
if (function_exists("sales_checkuser")) {$is_sales_user=sales_checkuser($_SESSION[suserid]);}

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

if (!$_SESSION[suserid] || !$show_useronline_detail) {
	include ("$language_dir/nologin.inc");
    } else {

        echo "<div class=\"mainheader\">$useronl_head</div>\n";
        echo "<div class=\"maintext\"><br>\n";
	echo "<table align=\"center\"  cellspacing=\"1\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";

	echo " <tr>\n";
    	echo "   <td class=\"classcat6\">\n";
	echo "<b>$useronl_uname\n";
	echo "   </td>\n";
    	echo "   <td class=\"classcat5\">\n";
	echo "<b>$useronl_page\n";
	echo "   </td>\n";
    	echo "   <td class=\"classcat6\">\n";
	echo "<b>$useronl_ip\n";
	echo "   </td>\n";
    	echo "   <td class=\"classcat5\">\n";
	echo "<b>$useronl_time\n";
	echo "   </td>\n";
	echo " </tr>\n";

	echo " <tr><td colspan=\"5\"></td></tr>\n";

	$result = mysql_query("SELECT username FROM ".$prefix."useronline WHERE username!='' GROUP by username ORDER by username ASC");
        while ($dbtemp = mysql_fetch_array($result)) {

	    $result2 = mysql_query("SELECT * FROM ".$prefix."useronline WHERE username='$dbtemp[username]' ORDER by timestamp DESC");
    	    $db = mysql_fetch_array($result2);
	    $result3 = mysql_query("SELECT id,icq,homepage FROM ".$prefix."userdata WHERE username='$db[username]'");
    	    $dbu = mysql_fetch_array($result3);

	    echo " <tr>\n";
    	    echo "   <td class=\"classcat6\">\n";
	    if ($db[username]==$_SESSION[susername]) {
		echo "$db[username]\n";
	    } elseif ($db[username]) {
		echo "<a href=\"members.php?choice=details&uid=$dbu[id]&uname=$db[username]\">$db[username]</a>\n";

            	    if ($sales_option && $sales_members && !$is_sales_user) { // check access for user
    			ico_email("","absmiddle");
		    } else {
    			ico_email("username=$db[username]","absmiddle");
		    }

		if ($dbu[icq]) {
            	    if ($sales_option && $sales_members && !$is_sales_user) { // check access for user
			ico_icq("","absmiddle");
		    } else {
			ico_icq("$dbu[icq]","absmiddle");
		    }
		}

		if ($dbu[homepage]) {
            	    if ($sales_option && $sales_members && !$is_sales_user) { // check access for user
			ico_url("","absmiddle");
		    } else {
			ico_url("$dbu[homepage]","absmiddle");
		    }
		}

	    } else {
		echo "$useronl_guest\n";
	    }
	    echo "   </td>\n";
    	    echo "   <td class=\"classcat5\">\n";
	    echo strrchr($db[file],"/")."\n";
	    echo "   </td>\n";
    	    echo "   <td class=\"classcat6\">\n";
	    if ($_SESSION[susermod]) {
		echo "$db[ip]\n";
	    } else {
		echo "***.***.***".strrchr($db[ip],".")."\n";
	    }
	    echo "   </td>\n";
    	    echo "   <td class=\"classcat5\">\n";
	    echo date("H:i:s",$db[timestamp])."\n";
	    echo "   </td>\n";
	    echo " </tr>\n";

	}


	echo " <tr><td colspan=\"5\"></td></tr>\n";

	$result = mysql_query("SELECT * FROM ".$prefix."useronline WHERE username='' GROUP by ip");
        while ($db = mysql_fetch_array($result)) {

	    echo " <tr>\n";
    	    echo "   <td class=\"classcat6\">\n";
	    echo "$useronl_guest\n";
	    echo "   </td>\n";
    	    echo "   <td class=\"classcat5\">\n";
	    echo strrchr($db[file],"/")."\n";
	    echo "   </td>\n";
    	    echo "   <td class=\"classcat6\">\n";
	    if ($_SESSION[susermod]) {
		echo "$db[ip]\n";
	    } else {
		echo "***.***.***".strrchr($db[ip],".")."\n";
	    }
	    echo "   </td>\n";
    	    echo "   <td class=\"classcat5\">\n";
	    echo date("H:i:s",$db[timestamp])."\n";
	    echo "   </td>\n";
	    echo " </tr>\n";

	}


	echo "</table>\n";
        echo "</div>\n";
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

#  The Foot-Section
#################################################################################################
include ($FOOTER);
?>