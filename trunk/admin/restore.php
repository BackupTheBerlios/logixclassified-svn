<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: ./admin/restore.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Restore latest Backup
#
#################################################################################################


#  Include Configs & Variables
#################################################################################################
require (dirname(__FILE__)."/../config.php");

$file_path              = "$bazar_dir/$backup_dir";     // backup dir under admin-path with write-permission
$unpacker               = "/usr/bin/gunzip";      	// path to unpacker
$unpackeroptions        = "-f";              		// set the packer options
$mysql			= "/usr/bin/mysql";  		// path to mysql-client
$mysqloptions		= "-u".$db_user." -p".$db_pass." $db_name";
#$setrestoredate	= "01-01-2002";			// set if you wish to set the DB Date, if not latest will be used (default)

#################################################################################################

if (ini_get('safe_mode')) { echo "<b>ATTENTION:</b> PHP safe_mode=on <br>\n the backupfiles must have the same fileowner/group as the running script !<br>\n<br>\n"; }
if (ini_get('safe_mode_exec_dir')) { echo "<b>ATTENTION:</b> PHP safe_mode_exec_dir set <br>\n the mysql executable must be within the safe_mode_exec_path !<br>\n<br>\n"; }

if ($setrestoredate) {
    $restoredate=$setrestoredate;
} else {
    // find last backup date
    for($i=0; $i<365; $i++) {
	$datestr=date("d-m-Y",mktime (0,0,0,date("m") ,date("d")-$i,date("Y")));
        $testfile=$file_path."/".$prefix."ads_".$datestr.".sql";
        if (is_file($testfile) || is_file($testfile.".gz")) {$restoredate=$datestr;break;}
    }
}

$handle=opendir($file_path);
while (false!==($file = readdir($handle))) {
    $ext=substr($file,-17,17);
    $ext2=substr($file,-14,14);
    if ($ext==$restoredate.".sql.gz") {
	exec("$unpacker $unpackeroptions $file_path/$file");
	$output=exec("$mysql $mysqloptions < ".$file_path."/".substr($file,0,-3));
	if ($outout) {echo $output;}
    } elseif ($ext2==$restoredate.".sql") {
	#echo "$mysql $mysqloptions < ".$file_path."/".$file."<br>";
	$output=exec("$mysql $mysqloptions < ".$file_path."/".$file);
	if ($outout) {echo $output;}
    }
}
closedir($handle);

echo "         Database ($restoredate) restore finished\n";
?>