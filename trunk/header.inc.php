<?php
#################################################################################################
#
#  project           : Logix Classifieds
#  filename          : header.inc
#  e-mail            : support@phplogix.com
#  purpose           : Header File
#$Id$
#License: GPL
#################################################################################################
#TODO: break this out, have a header.php that sets headers, and a header template separately.
if(!strpos($_SERVER['PHP_SELF'],'header.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
#  Processing Time Start
#################################################################################################
$proctime_start=microtime();

#  HTML Header Start
#################################################################################################
?>
<!-- Header Start -->

<html>
 <head>
  <title>Logix Classifieds</title>
  <link rel="stylesheet" type="text/css" href="<? echo $STYLE;?>">
  <? echo "$lang_metatag\n"; ?>
  <meta http-equiv="Author" content="SmartISoft">
  <meta name="robots" content="index, follow">
  <meta name="revisit-after" content="20 days">
 </head>

<body>
<?php
/*//temporary debug table
echo '<table align="center"><tr><th>SUPERGLOBALS DATA TO FIX NO GLOBALS</th></tr><tr><td>';
echo "GET <br /><pre>";
var_dump($_GET);
echo "</pre></td></tr><tr><td>POST<br /><pre>";
var_dump($_POST);
echo "</pre></td></tr><tr><td>COOKIES<br /><pre>";
var_dump($_COOKIE);
echo "</pre></td></tr><tr><td>SESSIONS<br /><pre>";
var_dump($_SESSION);
echo  "</pre></td></tr></table>";    */
?>
 <div align="center">
  <table border="0" cellpadding="0" cellspacing="0" width="738" height="67">
   <tr>
    <td width="504">
      <img src="images/pb_banner01.jpg" border=1>
    </td>
    <td width="230">
      <img src="images/pb_logo01.jpg" border=1>
    </td>
   </tr>
  </table>
 </div>

<!-- Header End -->

