<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : ./admin/cleanupdb.php
#  last modified by     : 
#  e-mail               : support@phplogix.com
#  purpose              : Database-Cleanup
#
#################################################################################################
$uploadscleanup = false;	// set true if you like to cleanup uploads also (VERY timeconsuming on large sites !!!)


#  Include Configs & Variables
#################################################################################################
require_once (dirname(__FILE__)."/../config.php");

function suppr($file) {
    $delete = @unlink($file);
    if (@file_exists($file)) {
        $filesys = eregi_replace("/","\\",$file);
	$delete = @system("del $filesys");
	if (@file_exists($file)) {
    	    $delete = @chmod ($file, 0777);
            $delete = @unlink($file);
    	    $delete = @system("del $filesys");
    	}
    }
}

#################################################################################################
mysql_connect($db_server, $db_user, $db_pass) or die (mysql_error());
mysql_select_db($db_name) or die (mysql_error());

// table ads - delete ads
mysql_query("DELETE FROM ".$prefix."ads WHERE deleted='1'") or die(mysql_error());
// table adcat - update ads counter
$result = mysql_query("SELECT id,ads FROM ".$prefix."adcat") or die(mysql_error());
while ($db = mysql_fetch_array($result)) {
    $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE catid=$db[id] AND publicview=1") or die(mysql_error());
    $count=mysql_num_rows($query);
    if ($db[ads]!=$count) {
	mysql_query("UPDATE ".$prefix."adcat SET ads='$count' WHERE id=$db[id]") or die(mysql_error());
    }
}

// table adsubcat - update ads counter
$result = mysql_query("SELECT id,ads FROM ".$prefix."adsubcat") or die(mysql_error());
while ($db = mysql_fetch_array($result)) {
    $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE subcatid=$db[id] AND publicview=1") or die(mysql_error());
    $count=mysql_num_rows($query);
    if ($db[ads]!=$count) {
	mysql_query("UPDATE ".$prefix."adsubcat SET ads='$count' WHERE id=$db[id]") or die(mysql_error());
    }
}

// table userdata - update member ads counter
$result = mysql_query("SELECT id FROM ".$prefix."userdata") or die(mysql_error());
while ($db = mysql_fetch_array($result)) {
    $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE userid=$db[id]") or die(mysql_error());
    $count=mysql_num_rows($query);
    if ($db[ads]!=$count) {
	mysql_query("UPDATE ".$prefix."userdata SET ads='$count' WHERE id=$db[id]") or die(mysql_error());
    }
}

// table notify - delete entries with invalid member
$result = mysql_query("SELECT userid FROM ".$prefix."notify GROUP BY userid") or die(mysql_error());
while ($db = mysql_fetch_array($result)) {
    $query = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id='$db[userid]' AND password NOT LIKE 'deleted%'") or die(mysql_error());
    $count=mysql_num_rows($query);
    if (!$count) {
	mysql_query("DELETE FROM ".$prefix."notify WHERE userid='$db[userid]'") or die(mysql_error());
    }
}

// optimize db
$result = mysql_list_tables($db_name) or die(mysql_error());
$i = 0;
while ($i < mysql_num_rows($result)) {
    $tb_names[$i] = mysql_tablename($result, $i);
    $tablename= $tb_names[$i];
    mysql_query("OPTIMIZE TABLE $tablename");
    $i++;
}
echo "         Database cleanup finished\n";


// remove unused pictures
if ($pic_database && $pic_enable && $uploadscleanup) {

  $result = mysql_query("SELECT picture_name FROM ".$prefix."pictures") or die(mysql_error());
  while ($db = mysql_fetch_array($result)) {
    if (!strstr($db[picture_name],"_")) {
	$query = mysql_query("SELECT id FROM ".$prefix."ads WHERE picture1='$db[picture_name]' OR picture2='$db[picture_name]' OR picture3='$db[picture_name]' OR picture4='$db[picture_name]' OR picture5='$db[picture_name]'") or die(mysql_error());
	$count=mysql_num_rows($query);
	if (!$count) {
	  $query = mysql_query("SELECT id FROM ".$prefix."userdata WHERE picture='$db[picture_name]'") or die(mysql_error());
	  $count=mysql_num_rows($query);
	  if (!$count) {
	    mysql_query("DELETE FROM ".$prefix."pictures WHERE picture_name='$db[picture_name]' OR picture_name='_$db[picture_name]'") or die(mysql_error());
	    echo "         Picture $db[picture_name] deleted.\n";
	  }
	}
    }
  }
  echo "         Picture cleanup finished\n";

} elseif (!$pic_database && $pic_enable && $uploadscleanup) {

  unset($retVal);
  $handle=opendir("$bazar_dir/$pic_path");
  while ($file = readdir($handle)) {
    if (eregi(".+\.png$|.+\.gif$|.+\.jpg$",$file) && !eregi("_+|-+",$file)) {
        $retVal[count($retVal)+1] = $file;
    }
  }
  closedir($handle);

  if (is_array($retVal)) {
    sort($retVal);
    while (list($key, $val) = each($retVal)) {
	if ($val != "." && $val != "..") {
	    $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE picture1='$val' OR picture2='$val' OR picture3='$val' OR picture4='$val' OR picture5='$val'") or die(mysql_error());
	    $count=mysql_num_rows($query);
	    if (!$count) {
	      $query = mysql_query("SELECT id FROM ".$prefix."userdata WHERE picture='$val'") or die(mysql_error());
	      $count=mysql_num_rows($query);
	      if (!$count) {
		suppr("$bazar_dir/$pic_path/".$val);
		echo "         File $val deleted.\n";
		if (is_file("$bazar_dir/$pic_path/_".$val)) {
		    suppr("$bazar_dir/$pic_path/_".$val);
		    echo "         File _$val deleted.\n";
		}
	      }
	    }
	}
    }
  }
  echo "         Picture cleanup finished\n";
}

// remove unused attachments
if ($att_enable && $uploadscleanup) {
  unset($retVal);
  $handle=opendir("$bazar_dir/$att_path");
  while ($file = readdir($handle)) {
    if (eregi(".+\.pdf$|.+\.doc$|.+\.txt$",$file) && !eregi("_+|-+",$file) ) {
        $retVal[count($retVal)] = $file;

    }
  }
  closedir($handle);

  if (is_array($retVal)) {
    sort($retVal);
    while (list($key, $val) = each($retVal)) {
	if ($val != "." && $val != "..") {
	    $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE attachment1='$val' OR attachment2='$val' OR attachment3='$val' OR attachment3='$val' OR attachment4='$val'") or die(mysql_error());
	    $count=mysql_num_rows($query);
	    if (!$count) {
		suppr("$bazar_dir/$att_path/".$val);
		echo "         File $val deleted.\n";
	    }
	}
    }
  }
  echo "         Attachment cleanup finished\n";
}


// remove old webmails & attachments
if ($webmail_enable && $webmail_storedays) {
    $deletestamp=$timestamp-($webmail_storedays*3600*24);
    $result=mysql_query("SELECT * FROM ".$prefix."webmail WHERE timestamp<'$deletestamp'") or die(mysql_error());
    while ($db = mysql_fetch_array($result)) {
	if ($db[attachment1] && is_file("$bazar_dir/$webmail_path/$db[attachment1]")) {suppr("$bazar_dir/$webmail_path/$db[attachment1]");}
	if ($db[attachment2] && is_file("$bazar_dir/$webmail_path/$db[attachment2]")) {suppr("$bazar_dir/$webmail_path/$db[attachment2]");}
	if ($db[attachment3] && is_file("$bazar_dir/$webmail_path/$db[attachment3]")) {suppr("$bazar_dir/$webmail_path/$db[attachment3]");}
	mysql_query("DELETE FROM ".$prefix."webmail WHERE id='$db[id]'") or die(mysql_error());
    }
    echo "         WebMail prune old mails & attachments finished\n";

}


// remove old logevents
if ($logging_enable && $logging_days) {
    $deletestamp=$timestamp-($logging_days*3600*24);
    mysql_query("DELETE FROM ".$prefix."logging WHERE timestamp<'$deletestamp'") or die(mysql_error());
    echo "         Logging prune old events finished\n";
}


?>
