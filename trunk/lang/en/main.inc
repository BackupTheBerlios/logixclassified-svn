<!-- $Id$ -->
<!-- TODO: move this stuff to templates -->
<br>
Welcome to <em id="red">Logix Classifieds</em>!!! The ultimate classified ad & matchmaking Software.<br>
<br>
Here you can place your content.
<br>
<br>
<center><a href="classified.php?choice=top">TOP ADS</a>
<? if ($_SESSION[suserid] && checknewads($_SESSION[suserid])) { echo"<a href=\"classified.php?choice=new&sortorder=addate desc\">NEW ADS</a>\n"; }?>
</center>