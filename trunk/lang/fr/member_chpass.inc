<div class="mainheader">Modifier le mot de passe</div>
<div class="maintext">
<br>
  	<table align="center">
	<form action=member_chpass.php METHOD=POST>
	<tr>
	<td width="50%"><div class="maininputleft">Nouveau mot de passe : </div></td>
        <td><input type=password name=password value="<? echo $password; ?>"></td>
    	</tr>

  	<tr>
    	<td width="50%"><div class="maininputleft">Répéter le mot de passe : </div></td>
        <td><input type=password name=password2 value="<? echo $password2; ?>"></td>
    	</tr>

	<tr>
	<td></td>
	<td><br><input type=submit value=Sauvegarder></td>
	</tr>
	</form>
  	</table>
</div>