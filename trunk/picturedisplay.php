<?
################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: picturedisplay.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Display Picture
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  Main-Section
#################################################################################################
$query = mysql_query("SELECT * FROM ".$prefix."pictures WHERE picture_name='$id'") or die(mysql_error());

$data = @MYSQL_RESULT($query,0,"picture_bin");
$type = @MYSQL_RESULT($query,0,"picture_type");

Header( "Content-type: $type");
echo $data;
?>