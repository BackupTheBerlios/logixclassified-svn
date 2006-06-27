<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : int_vBulletin-V2.2.x.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : vBulletin-V2.2.x Interface Add Member
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'int_vBulletin-V2.2.x.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
$USERS_TABLE=$forum_prefix."user";
$USERS_FIELD=$prefix."userfield";

#################################################################################################
$forum_db_connect_id=mysql_connect ($forum_db_server,$forum_db_user,$forum_db_pass) or die(mysql_error());
mysql_select_db ($forum_db_name,$forum_db_connect_id) or die(mysql_error());

    $intquery=mysql_query("SELECT * FROM $USERS_TABLE WHERE username='$username'",$forum_db_connect_id) or die(mysql_error());
    $intresult=mysql_fetch_array($intquery);
    if (!$intresult) {
    // Add new Member
        $md5password=md5($password);
        mysql_query("insert into $USERS_TABLE (userid, usergroupid, username, password, email, joindate , styleid)
                values (NULL, '2', '$username', '$md5password', '$email', '$timestamp','1')",$forum_db_connect_id) or die(mysql_error());
    $userid=mysql_insert_id($forum_db_connect_id);
        // insert custom user fields
    mysql_query("INSERT INTO $USERS_FIELD (userid) VALUES ($userid)",$forum_db_connect_id);
    }

if ($forum_db_connect_id!=$db_connect_id && !$db_persistent) {mysql_close($forum_db_connect_id);}

#################################################################################################
?>