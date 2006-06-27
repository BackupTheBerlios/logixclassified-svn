<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : lang.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : Change the Language
#
#################################################################################################
include ("library.php");
$cookietime=time()+(3600*24*356);
setcookie("Language", $_REQUEST['lng'], $cookietime, $cookiepath); // 1 Year

//include ("library.php");

if (strstr("$url","errormessage")) {
    $url=substr("$url",0,(strpos("$url","errormessage")-1));
}

if (strstr("$url","?")) {
    $url=$url."&";
} else {
    $url=$url."?";
}

$_SESSION['suserlanguage']=$lng;
if ($_SESSION['suserid']) { mysql_query("UPDATE ".$prefix."userdata SET language='$lng' WHERE id='$_SESSION[suserid]'"); }

header(headerstr($url."status=5"));
?>