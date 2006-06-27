<?php
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : footer.inc
#  e-mail               : support@phplogix.com
#  purpose              : Bazar Footer File
#$Id$
#License: GPL
#################################################################################################
#TODO: break this down into footer.php and header template.
if(!strpos($_SERVER['PHP_SELF'],'footer.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
?>

<!-- Footer Start -->
<p>
<div class="footer">

 <script>

 // (C) 2000 www.CodeLifter.com
 // http://www.codelifter.com
 // Free for all users, but leave in this  header

 // message to show in non-IE browsers
 var txt = "<? echo $footer_fav;?>"

 // url you wish to have bookmarked
 var url = "<? echo $url_to_start;?>";

 // caption to appear with bookmark
 var who = "<? echo $bazar_name;?>"

 // do not edit below this line
 // ===========================

var ver = navigator.appName
var num = parseInt(navigator.appVersion)
if ((ver == "Microsoft Internet Explorer")&&(num >= 4)) {
  document.write('<A HREF="javascript:window.external.AddFavorite(url,who);" ');
  document.write('onMouseOver=" window.status=')
  document.write("txt; return true ")
  document.write('"onMouseOut=" window.status=')
  document.write("' '; return true ")
  document.write('">'+ txt + '</a>')
}else{
  txt += " (Ctrl+D)"
  document.write(txt)
}
</script>

 || <a href="termsofuse.php"
onClick='enterWindow=window.open("termsofuse.php","Terms",
"width=750,height=550,top=50,left=50,scrollbars=yes"); return false'><? echo $footer_terms;?></a>
|| &copy; <? echo date("Y",$timestamp);?> by <a href="mailto:<? echo $admin_email;?>"><? echo $bazar_copyright;?></a>
<br>
</div>
<div class="smallcenter">
<?
if ($show_proctime) {
    list($usec, $sec) = explode(" ",$proctime_start);
    $proctime_start = $usec+$sec;

    list($usec, $sec) = explode(" ",microtime());
    $proctime_end = $usec+$sec;
    $proctime = $proctime_end-$proctime_start;
    echo "<br>Processing Time: ".substr($proctime,0,7)." sec.";
}

if ($supportpwd && $support=="$supportpwd") {echo "<p>"; phpinfo();}
?>
</div>

</body>
</html>

<!-- Footer End -->