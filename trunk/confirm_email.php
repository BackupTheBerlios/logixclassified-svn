<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: confirm_email.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: E-Mail Confirmation Reg.
#
#################################################################################################

#  Include Configs & Variables
#################################################################################################
require ("library.php");

if (!$id || !$email || !$mdhash) {
    $confirm=$error[14];
} else {
    $query = mysql_query("select * from ".$prefix."confirm_email where id = '$id' AND email = '$email' AND mdhash = '$mdhash'");
    $result = mysql_num_rows($query);
    if ($result < 1) {
	$confirm=$error[15];
    } else {
	mysql_query("update ".$prefix."userdata set email = '$email' where id = '$id'");
	mysql_query("delete from ".$prefix."confirm_email where email = '$email'");

	logging("X","$id","$email","AUTH: confirmed email change","");

	$confirm=2;
    }
}



#  The Head-Section
#################################################################################################
include ($HEADER);

#  The Main-Section
#################################################################################################
echo"<p>&nbsp; \n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
        	if ($confirm != 2) {
include ("$language_dir/confirm_email_error.inc");
		} else {
include ("$language_dir/confirm_email_done.inc");
		}
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo"  </table>\n";

#  The Foot-Section
#################################################################################################
include ($FOOTER);
?>