<br>
Bienvenue sur <em id="red">Logix Classifieds</em> !<br>L'ultime logiciel d'annonces r�pertori�es et web-rencontres.<br>
<br>
Ce site de d�monstration est �quip� de toutes les options disponibles:<br>
<br>
<ul>
<li> Logix Classifieds
<li> Logix ClassifiedsPicLib
<li> Logix ClassifiedsSales
<li> Logix ClassifiedsChat
<li> phpBB V2.0 Forum (Gratuit)
<li> ImageMagick's Picture "convert" (Gratuit)
</ul>
<br>
<br>
Surfez simplement et d�couvrez l'ensemble des fonctions ...<br>
<br>
L'�quipe SmartISoft<br>
<br>
<br>
<center><a href="classified.php?choice=top">TOP Annonces</a>
<? if ($_SESSION[suserid] && checknewads($_SESSION[suserid])) { echo"<a href=\"classified.php?choice=new&sortorder=addate desc\">Nouvelles annonces</a>\n"; }?>
</center>