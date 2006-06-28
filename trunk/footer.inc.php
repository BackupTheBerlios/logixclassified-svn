<?php
##############################################################################################
#                                                                                            #
#                                footer.php                                                  #
# *                            -------------------                                           #
# *   begin                : Tuesday June 27, 2006                                           #
# *   copyright            : (C) 2006  Logix Classifieds Development Team                    #
# *   email                : support@phplogix.com                                            #
# *   VERSION:             : $Id$
#                                                                                            #
##############################################################################################
#    This program is free software; you can redistribute it and/or modify it under the       #
#    terms of the GNU General Public License as published by the Free Software Foundation;   #
#    either version 2 of the License, or (at your option) any later version.                 #
#                                                                                            #
#    This program is distributed in the hope that it will be useful, but                     #
#    WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS   #
#    FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.          #
#                                                                                            #
#    You should have received a copy of the GNU General Public License along with this       #
#    program; if not, write to:                                                              #
#                                                                                            #
#                        Free Software Foundation, Inc.,                                     #
#                        59 Temple Place, Suite 330,                                         #
#                        Boston, MA 02111-1307 USA                                           #
##############################################################################################

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
|| &copy; <? echo date("Y",$timestamp);?> by <a href="credits.html"><? echo $bazar_copyright;?></a>
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


?>
</div>

</body>
</html>

<!-- Footer End -->