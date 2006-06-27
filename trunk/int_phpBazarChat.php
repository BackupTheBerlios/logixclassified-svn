<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : int_Logix ClassifiedsChat.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : Logix ClassifiedsChat Interface Add Member
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'int_Logix ClassifiedsChat.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
$chat_db_connect_id=mysql_connect ($chat_db_server,$chat_db_user,$chat_db_pass) or die(mysql_error());

mysql_select_db ($chat_db_name,$chat_db_connect_id) or die(mysql_error());

    $md5password=md5($password);
    $intquery=mysql_query("SELECT * FROM ".$chat_prefix."users WHERE nick='$username'",$chat_db_connect_id) or die(mysql_error());
    $intresult=mysql_fetch_array($intquery);
    if (!$intresult) {
    mysql_query("INSERT INTO ".$chat_prefix."users (nick, pass) VALUES ('$username', '$md5password')",$chat_db_connect_id) or die(mysql_error());
    }

if ($chat_db_connect_id!=$db_connect_id && !$db_persistent) {mysql_close($chat_db_connect_id);}

#################################################################################################
?>