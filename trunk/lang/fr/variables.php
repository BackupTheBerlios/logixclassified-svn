<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: variables.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose	     	: French Language File
#$Id$          	: 2.0.2
#  Translation by      	: Rikew [rikew@wanadoo.fr] (toute remarque est la bienvenue)
#
#################################################################################################

$lang		="Français";

$menusep	=" | ";
# Warning :
# you can use $menusep =" || "; but there are problems with the display of menu and the display of ads menu !!!
# for the ads menu, you can replace : $class_link2 ="Mes annonces"; by : $class_link2 ="Mes ann.";
# --------------------
# Avertissement :
# Vous pouvez utiliser $menusep =" || "; mais il y a des problèmes avec l'affichage du menu et l'affichage du menu des annonces !
# pour le menu des annonces, vous pouvez remplacer : $class_link2 ="Mes annonces"; par : $class_link2 ="Mes ann.";

$lang_metatag	="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">"; // Added in V1.55

$gender[m]	="Homme"; // Added in V1.55
$gender[f]	="Femme"; // Added in V1.55
$gender[a]	="Couple H/F"; // Added in V1.55
$gender[b]	="Couple F/F"; // Added in V1.55
$gender[c]	="Couple H/H"; // Added in V1.55

$userlevel[0]	="Membre Junior";
$userlevel[1]	="Membre";
$userlevel[2]	="Membre Senior";
$userlevel[3]	="Membre ayant payé";
$userlevel[4]	="Membre ayant payé";
$userlevel[5]	="Membre ayant payé";
$userlevel[6]	="Membre ayant payé";
$userlevel[7]	="Membre ayant payé";
$userlevel[8]	="Modérateur";
$userlevel[9]	="Administrateur";

$mail_msg[0] 	="Votre enregistrement sur $bazar_name";
$mail_msg[1] 	="Bonjour ";
$mail_msg[2] 	="Merci pour votre enregistrement sur $bazar_name!\nVoici les informations vous concernant :\n\nPseudo : ";
$mail_msg[3] 	="Mot de passe : ";
$mail_msg[4] 	="E-Mail : ";
$mail_msg[5] 	="Sexe : ";
$mail_msg[6] 	="Vous devez confirmer votre inscription en cliquant sur : ";
$mail_msg[7] 	="Votre Webmaster\n$url_to_start";
$mail_msg[8] 	="Bonjour Webmaster\n\n Il y a un nouveau membre inscrit à $bazar_name :\n\nPseudo : ";
$mail_msg[9] 	="$bazar_name Confirmation d'enregistrement";
$mail_msg[10]	="Bonjour ";
$mail_msg[11]	="Merci d'avoir confirmé votre enregistrement!\n\nDès maintenant vous pouvez vous connecter avec votre pseudo et votre mot de passe.\n\nVotre Webmaster\n$url_to_start";
$mail_msg[12]	="Informations concernant votre compte";
$mail_msg[13]	="Bonjour ";
$mail_msg[14]	="Rappel des informations concernant votre compte : \n\nPseudo : ";
$mail_msg[15] 	="Votre Webmaster\n$url_to_start";
$mail_msg[16]	="Confirmation de changement d'E-Mail";
$mail_msg[17]	="Cher utilisateur \n\nVous avez demandé un changement d'adresse E-Mail.\nVous devez confirmer ce changement en cliquant sur : ";
$mail_msg[18]	="Merci\n\nVotre Webmaster\n$url_to_start";
$mail_msg[19]	="Expiration de votre annonce";
$mail_msg[20]	="Cher utilisateur \n\nVotre annonce va expirer dans $timeoutnotify jours.\n";
$mail_msg[21]	="Pour étendre la durée de validité de $timeoutconfirm jours, cliquez ici :\n\n";
$mail_msg[22]	="Merci\n\nVotre Webmaster\n$url_to_start";
$mail_msg[23]	="Vous avez reçu un nouveau message"; // Added in V1.50
$mail_msg[24]	="Bonjour $toname\n\nVous avez reçu un message depuis le site $bazar_name!\n\nConnectez vous à $url_to_start et cliquez sur \"Messages\".\n\nVotre Webmaster\n$url_to_start"; // Added in V1.50
$mail_new 	="Nouveau(x) message(s)"; // Added in V2.0.2

$status_msg[0]	="Déconnexion OK";
$status_msg[1]	="Connexion OK";
$status_msg[2]	="Introuvable";
$status_msg[3]	="Champ(s) vide(s)";
$status_msg[4]	="Envoyé";
$status_msg[5]	="Mise à jour OK";
$status_msg[6]	="Erreur";
$status_msg[7]	="Suppression OK";
$status_msg[8]	="Vote vide";
$status_msg[9]	="Vote erreur";
$status_msg[10]	="Vote OK";
$status_msg[11]	="GB Mise à jour Erreur";//gestbook
$status_msg[12]	="GB Mise à jour OK";
$status_msg[13]	="Sauvegarde OK";

$error[0] 	="Les champs \"mot de passe\" doivent être identiques.";
$error[1] 	="Le pseudo est trop court. Le minimum est de 3 caractères.";
$error[2] 	="Le pseudo est trop long. Le maximum est de 11 caractères.";
$error[3] 	="Le pseudo contient des caractères non valides";
$error[4] 	="Le format de l'adresse E-Mail est invalide";
$error[5] 	="Le mot de passe est trop court. Le minimum est de 3 caractères.";
$error[6] 	="Le mot de passe est trop long. Le maximum est de 20 caractères.";
$error[7] 	="Le mot de passe contient des caractères non valides.";
$error[8] 	="Le nom ou le prénom contient des caractères non valides.";
$error[9] 	="Un ou plusieurs champs contiennent des caractères non valides";
$error[10] 	="Le numéro ICQ contient des caractères non valides";
$error[11] 	="Le sexe doit être indiqué"; // Changed in V1.55
$error[12] 	="Le pseudo existe déjà, veuillez s'il vous plait en choisir un autre.";
$error[13] 	="Un membre utilisant cette adresse E-Mail existe déjà.";
$error[14] 	="Un ou plusieurs champs sont vides";
$error[15] 	="Combinaison du mot de passe et du pseudo incorrect, ou compte supprimé";
$error[16] 	="Erreur inconnue de la base de données, s'il-vous-plaît essayez plus tard.";
$error[17] 	="Erreur inconnue, s'il-vous-plaît connectez-vous, supprimez votre compte et ré-enregistrez vous.";
$error[18] 	="Echec dans le processus de nettoyage";
$error[19] 	="Aucun pseudo ne correspond à cette adresse E-Mail";
$error[20] 	="Les détails de votre enregistrement ne peuvent pas être mis à jour à cause d'un défaut dans la base de données";
$error[21] 	="Votre mot de passe ne peut pas être mis à jour à cause d'une erreur dans la base de données";
$error[22] 	="Les E-mails doivent être identiques";
$error[23] 	="Votre nouveau E-Mail est identique à l'ancien";
$error[24] 	="Votre vote ne peut pas être pris en compte à cause d'une erreur dans la base de données";
$error[25] 	="Vous avez déjà voté, votre vote ne peut pas être pris en compte";
$error[26] 	="Mot de passe / Pseudo incorrect, ou compte détruit.";
$error[27]	="Désolé, mais votre compte est inscrit sur la liste noire.";
$error[28]	="Désolé, seuls les membres peuvent voter";
$error[29]	="Aucun résultat<br>Entrez une référence correcte";
$error[30]	="Aucun résultat<br>Essayez une autre recherche";

$text_msg[0]    ="Merci pour votre annonce";
$text_msg[1]    ="Merci pour votre annonce. Après validation de l'administrateur votre annonce sera mis en ligne";
$text_msg[2]    ="Un E-Mail de confirmation a été envoyé à votre nouvelle adresse E-Mail";
$text_msg[3]    ="Un E-Mail de confirmation a été envoyé à votre adresse E-Mail";

#########################################################
# WARNING!! The $menu_link_desc variables below provide javascript messages for the
# browser status bar. DO NOT use any apostrophes in these text messages
# or they may cause an error in the browser !. Tip: Instead of trying to write
# "Reply to this member's ad" why not try "Reply to the ad of this member"
#  or even just "Reply to ad".
##########################################################

$menu_link1	="Accueil";
$menu_link1desc	="Retour à l\'accueil";
$menu_link1url	="main.php";
$menu_link2	="Annonces";
$menu_link2desc	="Consulter les annonces";
$menu_link2url	="classified.php";
$menu_link3	="Photos";
$menu_link3desc	="Consulter les photos";
$menu_link3url	="pictures.php"; // "picturelib.php" for PicLib Option else use "pictures.php"
$menu_link4	="Liens";
$menu_link4desc	="Visiter nos sites partenaires";
$menu_link4url	="links.php";
$menu_link5	="Forum";
$menu_link5desc	="Aller sur le forum";
$menu_link5url	="forum.php";
$menu_link6	="Chat";
$menu_link6desc	="Chatter avec les autres membres";
$menu_link6url	="chat.php";
$menu_link7	="Messages"; // Changed in V1.50
$menu_link7desc	="Consulter vos messages"; // Changed in V1.50
$menu_link7url	="webmail.php"; // Changed in V1.50
$menu_link8	="Membres"; // Changed in V1.55
$menu_link8desc	="Listes des membres, visualiser et modifier votre profil."; // Changed in V1.55
$menu_link8url	="members.php";
$menu_link9	="Livre d'or";
$menu_link9desc	="Ajouter votre témoignage";
$menu_link9url	="guestbook.php";
$menu_link10	="Contact";
$menu_link10desc="Nous contacter";
$menu_link10url	="contact.php";

$main_head	="Accueil";
$classified_head="Annonces";
$classadd_head	="Ajouter&nbsp;une&nbsp;annonce";
$classedit_head	="Modifier&nbsp;votre&nbsp;annonce";
$classseek_head	="Rechercher";
$classmy_head	="Mes annonces";
$classfav_head	="Ma sélection";
$classnot_head	="Mes alertes";
$forum_head	="Forum";
$stories_head	="Histoires";
$pictures_head	="Photos";
$links_head	="Liens";
$members_head	="Membres"; // Changed in V1.55
$guestbook_head	="Livre&nbsp;d'or";
$contact_head	="Contact";
$status_header	="Statut";
$useronl_head	="Membres connectés"; // Added in V1.50
$newmemb_head	="Inscription"; // Added in V1.50
$webmail_head	="Messages"; // Added in V1.50
$classtop_head	="Top"; // Added in V1.55
$classnew_head	="Nouvelles&nbsp;annonces"; // Added in V2.0.0

$lostpw_header	="Mot de passe";
$lostpw_email 	="E-Mail";
$lostpw_button	="Envoyer";

$login_header	="Connexion";
$login_username	="Pseudo";
$login_password	="Mot de passe";
$login_member	="Membre n°";

$user_online	="connecté"; // Added in V1.50
$users_online	="connectés"; // Added in V1.50
$useronl_uname	="Pseudo"; // Added in V1.50
$useronl_ip	="Adr. IP"; // Added in V1.50
$useronl_time	="Heure"; // Added in V1.50
$useronl_page	="Page"; // Added in V1.50
$useronl_guest	="Visiteur"; // Added in V1.50

$nav_prev	="Page précédente"; // Added in V1.50
$nav_next	="Page suivante"; // Added in V1.50
$nav_gopage	="Aller à cette page"; // Added in V1.50
$nav_actpage	="Cette page"; // Added in V1.50

$memf_username	="Pseudo"; // Added in V1.50
$memf_email	="E-Mail"; // Added in V1.50
$memf_level	="Niveau"; // Added in V1.50
$memf_votes	="Votes"; // Added in V1.50
$memf_lastvote	="Date du dernier vote"; // Added in V1.50
$memf_ads	="Annonce(s)"; // Added in V1.50
$memf_lastad	="Date de la dernière annonce"; // Added in V1.50
$memf_password	="Mot de passe"; // Added in V1.50
$memf_password2	="Confirmation du mot de passe"; // Added in V1.50
$memf_sex	="Sexe"; // Added in V1.50
$memf_newsletter="Newsletter"; // Added in V1.50
$memf_firstname ="Prénom"; // Added in V1.50
$memf_lastname	="Nom"; // Added in V1.50
$memf_address	="Adresse"; // Added in V1.50
$memf_zip	="Code postal"; // Added in V1.50
$memf_city	="Ville"; // Added in V1.50
$memf_state	="Région"; // Added in V1.50
$memf_country	="Pays"; // Added in V1.50
$memf_phone	="Téléphone"; // Added in V1.50
$memf_cellphone	="Mobile"; // Added in V1.50
$memf_icq	="ICQ"; // Added in V1.50
$memf_homepage	="Site Internet"; // Added in V1.50
$memf_hobbys	="Loisirs"; // Added in V1.50
$memf_field1	="Field1"; // Added in V1.50
$memf_field2	="Field2"; // Added in V1.50
$memf_field3	="Field3"; // Added in V1.50
$memf_field4	="Field4"; // Added in V1.50
$memf_field5	="Field5"; // Added in V1.50
$memf_field6	="Field6"; // Added in V1.50
$memf_field7	="Field7"; // Added in V1.50
$memf_field8	="Field8"; // Added in V1.50
$memf_field9	="Field9"; // Added in V1.50
$memf_field10	="Field10"; // Added in V1.50
$memf_timezone	="GMT"; // Added in V2.0.0
$memf_dateformat="Format des dates"; // Added in V2.0.0

$webmail_inbox	="Boîte de réception"; // Added in V1.50
$webmail_sent	="Eléments envoyés"; // Added in V1.50
$webmail_trash	="Supprimés"; // Added in V1.50
$webmail_from	="De"; // Added in V1.50
$webmail_to	="à"; // Added in V1.50
$webmail_date	="Date"; // Added in V1.50
$webmail_subject="Objet"; // Added in V1.50
$webmail_message="Message"; // Added in V1.50
$webmail_attach	="Fichier(s)"; // Added in V1.50
$webmail_reply	="Répondre"; // Added in V1.50
$webmail_del	="Supprimer ce message"; // Added in V1.50
$webmail_tdel	="Supprimer ce message des éléments supprimés"; // Added in V1.50
$webmail_tundel	="Annuler la suppression du message"; // Added in V1.50
$webmail_sdel	="Supprimer ce message des éléments envoyés"; // Added in V1.55


#########################################################
# WARNING!! The $xxxx_link_desc variables below provide javascript messages for the
# browser status bar. DO NOT use any apostrophes in these text messages
# or they may cause an error in the browser !. Tip: Instead of trying to write
# "Reply to this member's ad" why not try "Reply to the ad of this member"
#  or even just "Reply to ad".
##########################################################

$logi_link1	="<center>Mot de passe perdu</center>";
$logi_link1desc	="Envoyer le mot de passe par E-Mail";
$logi_link2	="<center>Inscription</center>";
$logi_link2desc	="Inscription comme nouveau membre";

$gb_link1	="Ajouter un témoignage";
$gb_link1desc	="Ajouter un témoignage";
$gb_link1head	="Ajouter un témoignage";
$gb_pages	="Pages:";
$gb_name	="Nom";
$gb_comments	="Messages";
$gb_location	="Localisation: ";
$gb_posted	="Date : ";

$gbadd_name	="Nom :";
$gbadd_location	="Localisation :";
$gbadd_email	="E-Mail :";
$gbadd_url	="Site Internet :";
$gbadd_icq	="ICQ :";
$gbadd_msg	="Message :";

$vote_vote	="Nb";
$vote_answer	="Résultats";
$vote_button	="Vote";

$memb_link1	="Changer d'E-Mail";
$memb_link1desc	="Changer mon adresse E-Mail";
$memb_link2	="Modifier mot de passe";
$memb_link2desc	="Modifier mon mot de passe";
$memb_link3	="Supprimer mon compte";
$memb_link3desc	="Supprimer mon compte";

$memb_newvalid	="(Doit être valide)"; // Added in V1.50
$memb_newterms	="J'ai lu les conditions d'utilisation et je les accepte"; // Added in V1.50
$memb_newsubmit	="Enregitrement"; // Added in V1.50
$memb_newpublic	="(Visible sur le site)"; // Added in V1.55

$memb_detdeleted="supprimé"; // Added in V1.55
$memb_detdetails="Détails"; // Added in V1.55
$memb_detuser   ="Pseudo"; // Added in V1.55
$memb_detonl    ="Pseudo"; // Added in V1.55
$memb_detads    ="Ann"; // Added in V1.55
$memb_detvot    ="Vote"; // Added in V1.55
$memb_detmail   ="Mail"; // Added in V1.55
$memb_deticq    ="ICQ"; // Added in V1.55
$memb_deturl    ="Site"; // Added in V1.55
$memb_detpic    ="Phot"; // Added in V2.0.0

$members_link	="Membres"; // Added in V1.55
$members_link_desc="Retour à la zone membre"; // Added in V1.55
$members_link1	="Chercher"; // Added in V1.55
$members_link1desc="Chercher un membre"; // Added in V1.55
$members_link2	="Mon profil"; // Added in V1.55
$members_link2desc="Voir et éditer votre profil"; // Added in V1.55

$myprofile_head	="Mon profil"; // Added in V1.55
$memberseek_head="Recherche de membre"; // Added in V1.55
$memberdet_head	="Détails&nbsp;du&nbsp;membre"; // Added in V1.55
$memberads_head	="Annonces&nbsp;du&nbsp;membre"; // Added in V1.55


$class_link1	="Ajouter";
$class_link1desc="Ajouter une annonce";
$class_link2	="Mes annonces";
$class_link2desc="Visualiser, modifier, supprimer vos annonces";
$class_link3	="Chercher";
$class_link3desc="Rechercher des annonces";
$class_link4	="Sélection";
$class_link4desc="Ma sélection d\'annonces";
$class_link5	="Alertes";
$class_link5desc="Mes alertes";
$class_link	="Annonces";
$class_link_desc="Parcourir les annonces";

$ad_pages	="Pages:";
$ad_from	="Par : ";
$ad_date	="le";
$ad_home	="Annonces";
$ad_sendemail	="Envoyer un message à ce membre";
$ad_sendlink	="Envoyer cette annonce à un(e) ami(e)";
$ad_icq		="Envoyer un message ICQ à ce membre";
$ad_location	="Localisation : ";
$ad_noloc	="Non indiqué";
$ad_text	="Texte";
$ad_picwin	=""; //?
$ad_enlarge	="Zoom";
$ad_print	="Imprimer";
$ad_favorits	="Ajouter à ma sélection";
$ad_nr		="Référence : ";
$ad_gotourl	="Visiter le site du membre";
$ad_stat	="Statistiques : vues/réponses";
$ad_att		="Documents"; // Added in V1.50
$ad_new		="Nouvelle annonce (moins de $show_newicon jours)"; // Added in V1.50
$ad_rating	="Voter pour cette annonce"; // Added in V1.50
$ad_yes		="Oui"; // Added in V1.50
$ad_no		="Non"; // Added in V1.50
$ad_member	="Détails du membre"; // Added in V1.55

$adadd_submit	="Envoyer";
$adadd_user	="Pseudo :";
$adadd_ip  	="Adresse IP :";
$adadd_cat 	="Catégorie :";
$adadd_subcat	="Sous catégorie :";
$adadd_dur	="Durée de parution :";
$adadd_durweeks	="Semaines";
$adadd_durdays	="Jours";
$adadd_loc	="Localisation :";
$adadd_head	="Titre :";
$adadd_text	="Texte :";
$adadd_selicon	="Sélectionnez :";
$adadd_fieldend =" :";
$adadd_pic	="Photo :";
$adadd_picnos	="sans caractères spéciaux";
$adadd_submitone="[cliquer une fois]";
$adadd_att	="Document :"; // Added in V1.50
$adadd_delatt	="(supprimer)"; // Added in V1.50
$adadd_attnos	="uniquement .pdf .doc .txt"; // Added in V1.50
$adadd_forceadd	="<center><br><br>Merci de vous être connecté.<br><br>
		    Maintenant ajoutez votre annonce.<br>
		    Sélectionnez la catégorie et cliquez 'Envoyer'.</center>"; // Added in V1.57
$adadd_pretext	="<center><br><br>Pour ajouter votre annonce :<br>
		    Sélectionnez une catégorie et cliquez 'Envoyer'.</center>"; // Added in V1.59
			
$adseek_adnr	="Référence : ";
$adseek_submit	="Rechercher";
$adseek_cat 	="Catégorie :";
$adseek_subcat	="Sous catégorie :";
$adseek_all	="Tous";
$adseek_loc	="Localisation :";
$adseek_text	="Rechercher le texte :";
$adseek_submitone="[cliquer une fois]";
$adseek_simple	="Recherche par référence";
$adseek_adv	="Recherche avancée";
$adseek_result	="Résultats de la recherche";
$adseek_sort    ="Classement :";
$adseek_pic	="Avec photo :"; // Added in V1.50
$adseek_att	="Avec document :"; // Added in V1.50

$admy_edit	="Modifier cette annonce";
$admy_delete	="Supprimer cette annonce";
$admy_move	="Déplacer cette annonce";
$admy_member	="Membre: ";


$admydel_head	="Suppression";
$admydel_msg	="Souhaitez-vous réellement procéder à une suppression ?";
$admydel_submit ="Supprimer";
$admydel_done	="Suppression confirmée";

$admymove_head	="Déplacer cette annonce";
 // Added in V2.0.0
$admymove_msg	="Sélectionner une sous catégorie";
 // Added in V2.0.0
$admymove_submit="Déplacer l'annonce";
 // Added in V2.0.0
$admymove_done	="Votre annonce a été déplacée";
 // Added in V2.0.0

$adfav_delete	="Supprimer cette annonce de ma sélection";
$adnot_delete	="Supprimer cette catégorie de mes alertes";

$notifydel_head	="Supprimer une catégorie de mes alertes.";
$notifydel_msg	="Etes-vous sûr de vouloir faire cela ?";
$notifydel_done	="La catégorie a été supprimée de vos alertes";
$notify_done	="Cette catégorie a été ajoutée a vos alertes";
$notify_exist	="Cette catégorie est déjà présente dans vos alertes";
$notify_add	    ="Ajouter cette catégorie à mes alertes";
$notify_head	="Mes alertes";

$sm_mailhead	="Envoyer un message à ce membre";
$sm_linkhead	="Envoyer cette annonce à un(e) ami(e)";
$sm_friendhead	="Recommander ce site à un(e) ami(e)";
$sm_friendrefx	="vous a envoyé ce lien :";
$sm_fromname	="De :";
$sm_fromemail	="E-Mail :";
$sm_toname	="à :";
$sm_toemail	="Son E-Mail :";
$sm_text	="Message :";
$sm_subject	="Objet :";
$sm_submit	="Envoyer";
$sm_cc		="CC";
$sm_afriend     ="Un(e) ami(e)";
$sm_anonym      ="Anonyme";
$sm_friendref	="- Recommandation";
$sm_answer	="- Réponse Réf. ";
$sm_systext	="vous a envoyé ce lien : ";
$sm_emailheader	="";
$sm_emailfooter	="\n\n-----------------------------\nEnvoyé depuis $bazar_name sur $HTTP_HOST";

$ar_adid	="Référence :"; // Added in V1.50
$ar_rating	="Moyenne :"; // Added in V1.50
$ar_ratingcount	="Votes :"; // Added in V1.50
$ar_submit	="Voter"; // Added in V1.50
$ar_already	="Désolé, vous avez déjà voté pour cette annonce."; // Added in V1.50

$msghead_error	="Erreur";
$msghead_message="Message";

$favorits_header="Ma sélection";
$favorits_done	="L'annonce a été ajoutée à votre sélection";
$favorits_exist	="L'annonce est déjà dans votre sélection";
$favorits_del	="L'annonce a été supprimée de votre sélection";

$location_sel	="Sélectionner";
$back		="Retour";
$done		="Fermer";
$close		="Fermer";
$submit		="Ok";
$update		="Sauvegarder"; //Added in V1.50
$smiliehelp	="Insérer un smiley";
$require	="(Requis)"; // Added in V2.0.0

$footer_fav	="Ajouter à vos favoris";
$footer_terms	="Conditions d'utilisation";

$cat_new	="Nouvelle(s) annonce(s) : moins de $show_newicon jours"; // Added in V1.55
$cat_pass	="Accessible uniquement avec un mot de passe valide"; // Added in V1.55
$mess_noentry	="<br><br><br><br><br><center>Aucun enregistrement</center>"; // Added in V1.55
$pass_text	="<br><br><br><br><br><center>Cette page est visible seulement si le mot de passe est valide</center>"; // Added in V1.55
$memb_notenabled="<br><br><br><br><br><center>Cette page est désactivée</center>"; // Added in V1.55
$pass_head	="Mot de passe"; // Added in V1.55

#################################################################################################
#
# End
#
#################################################################################################
?>