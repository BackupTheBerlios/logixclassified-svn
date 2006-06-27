<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : int_phpBB.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : phpBB V2.0 Interface Add Member
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'int_phpBB-V2.x.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
$USERS_TABLE=$forum_prefix."users";

#################################################################################################
$forum_db_connect_id=mysql_connect ($forum_db_server,$forum_db_user,$forum_db_pass) or die(mysql_error());
mysql_select_db ($forum_db_name,$forum_db_connect_id) or die(mysql_error());

    $intquery=mysql_query("SELECT * FROM $USERS_TABLE WHERE username='$username'",$forum_db_connect_id) or die(mysql_error());
    $intresult=mysql_fetch_array($intquery);
    if (!$intresult) {
    // Get the next ID !!!!
        $query=mysql_query("SELECT user_id,username FROM $USERS_TABLE WHERE user_id <> '-1' ORDER BY user_id DESC LIMIT 1",$forum_db_connect_id) or die(mysql_error());
    $dbt=mysql_fetch_array($query);
        $id=$dbt[user_id]+1;

    // Add new Member
        $md5password=md5($password);
    mysql_query("insert into $USERS_TABLE (user_id, username, user_password, user_email, user_regdate, user_style)
        values ('$id', '$username', '$md5password', '$email', '$timestamp','1')",$forum_db_connect_id) or die(mysql_error());
    }

if ($forum_db_connect_id!=$db_connect_id && !$db_persistent) {mysql_close($forum_db_connect_id);}

#################################################################################################
?>