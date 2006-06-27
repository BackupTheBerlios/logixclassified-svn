<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: logout.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Member's logout
#
#################################################################################################

require ("library.php");
logout();

// clear useronline
mysql_query("DELETE FROM ".$prefix."useronline WHERE ip='$ip'");

header(headerstr("main.php?status=0"));
?>