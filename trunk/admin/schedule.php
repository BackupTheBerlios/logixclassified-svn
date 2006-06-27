#!/usr/local/bin/php
<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: ./admin/schedule.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: scheduler-functions for periodic maintanance
#$Id$          	: 1.50
#  last modified     	: 01/01/2002
#
#################################################################################################


#  Start
#################################################################################################
echo "Start: ".date("F j, Y, g:i:s a")."\n\n";

#  Procedure 1
#################################################################################################
echo " Proc 1: NOTIFICATION MAILS\n";
include "notify.php";

#  Procedure 2
#################################################################################################
echo "\n Proc 2: CLEANUP \n";
include "cleanup.php";

#  Procedure 3
#################################################################################################
echo "\n Proc 3: BACKUP \n";
include "backup.php";

#  End
#################################################################################################
echo "\nStop: ".date("F j, Y, g:i:s a")."\n\n";

?>