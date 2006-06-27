<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: ./admin/notify.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: notify members
#
#################################################################################################


#  Include Configs & Variables
#################################################################################################
require_once (dirname(__FILE__)."/../config.php");

#  Procedure 1
#################################################################################################
mysql_connect($db_server, $db_user, $db_pass) or die (mysql_error());
mysql_select_db($db_name) or die (mysql_error());

$result = mysql_query("SELECT * FROM ".$prefix."notify GROUP BY userid") or die(mysql_error());
while ($db = mysql_fetch_array($result)) {

    $result2 = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id ='$db[userid]'") or die(mysql_error());
    $dbu = mysql_fetch_array($result2);
    echo "         ID:$db[userid] $dbu[username][$dbu[email]]: CatNotify ";
    $link="";
    $result3 = mysql_query("SELECT * FROM ".$prefix."notify WHERE userid ='$db[userid]' ORDER BY subcatid asc") or die(mysql_error());
    while ($dbn = mysql_fetch_array($result3)) {
	$result4 = mysql_query("SELECT * FROM ".$prefix."adsubcat WHERE id ='$dbn[subcatid]' AND notify='1'") or die(mysql_error());
	$dbs = mysql_fetch_array($result4);
	if ($dbs) {
	    $result5 = mysql_query("SELECT id,name FROM ".$prefix."adcat WHERE id ='$dbs[catid]'") or die(mysql_error());
	    $dbc = mysql_fetch_array($result5);
    	    echo "|$dbs[id]";
	    $link.="$dbc[name]/$dbs[name]: $url_to_start/classified.php?catid=$dbs[catid]&subcatid=$dbs[id]\n";
	}
    }
    $message[1]="Hello $dbu[username]\n\nNEW ads have been posted within your selected categories at $bazar_name.\nFollow this link to check them out:\n\n";
    $message[2]="\nThis message is generated automatically. To change your settings go to\nthe Auto-Notify section in our system:\n$url_to_start/classified.php?choice=notify\n\n Your Webmaster";
    $subj = "AUTO-NOTIFY - NEW Ads within selected Categories";

    $msg=$message[1].$link.$message[2];
    $from = "From: $admin_email";
    if ($link) {
    	@mail($dbu[email], $subj, $msg, $from);
	echo "| EMail sent\n";
    } else {
	echo "| Nothing to send\n";
    }
}
mysql_query("UPDATE ".$prefix."adsubcat SET notify='0'") or die("Update Error");
?>