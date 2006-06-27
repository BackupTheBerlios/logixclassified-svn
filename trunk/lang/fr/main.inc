<br>
Bienvenue sur <em id="red">Logix Classifieds</em> !<br>L'ultime logiciel d'annonces répertoriées et web-rencontres.<br>
<br>
Ici vous pouvez placer votre contenu.
<br>
<br>
<center><a href="classified.php?choice=top">TOP Annonces</a>
<? if ($_SESSION[suserid] && checknewads($_SESSION[suserid])) { echo"<a href=\"classified.php?choice=new&sortorder=addate desc\">Nouvelles annonces</a>\n"; }?>
</center>