<div class="mainheader">Changer d'E-Mail</div>
<div class="maintext">
<br>
  	<table align="center">
	<form action=member_chemail.php METHOD=POST>
	<tr>
	<td width="50%"><div class="maininputleft">Nouvelle adresse E-Mail : </div></td>
        <td><input type=text name=email value="<? echo $email; ?>"></td>
    	</tr>

  	<tr>
    	<td width="50%"><div class="maininputleft">Répéter votre adresse E-Mail : </div></td>
        <td><input type=text name=email2 value="<? echo $email2; ?>"></td>
    	</tr>

	<tr>
	<td></td>
	<td><br><input type=submit value=Sauvegarder></td>
	</tr>
	</form>
  	</table>
</div>