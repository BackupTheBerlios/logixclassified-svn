<?
################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: picturedisplay.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Copy Pictures from mySQL-DB to Files
#
#################################################################################################

       ####################################################################################
       ### !!! COPY THIS FILE TO YOUR BAZAR-DIR - AFTER SUCCESSFULL RUN - DELETE IT !!! ###
       ####################################################################################

#  Include Configs & Variables
#################################################################################################
require ("config.php");
#$filepath="/tmp/pics";			// set FULL Path to your pictures dir
$filepath="$bazar_dir/$pic_path";	// or use config.php settings

mysql_connect($db_server, $db_user, $db_pass) or die (mysql_error());
mysql_select_db($db_name) or die (mysql_error());

$result = mysql_query("SELECT * FROM ".$prefix."pictures") or die(mysql_error());
while ($db=mysql_fetch_array($result)) {
    $query = mysql_query("SELECT * FROM ".$prefix."pictures WHERE picture_name='$db[picture_name]'") or die(mysql_error());
    $data = @MYSQL_RESULT($query,0,"picture_bin");
    $type = @MYSQL_RESULT($query,0,"picture_type");
#Header( "Content-type: $type");
#echo $data;
    $filename = "$filepath/$db[picture_name]";
    $fd = fopen ($filename, "w");
    fwrite ($fd, $data);
    fclose ($fd);
    echo "Picture: $db[picture_name] converted to file <br>\n";
}

#  End
#################################################################################################
?>