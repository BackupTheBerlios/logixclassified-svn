<?php
##############################################################################################
#                                                                                            #
#                                   register.php
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
#TODO: register.php - brian - form is auto built, but we have shit like date format that people probably wont understand
#TODO:so we need to do some work here.
#TODO: form builder is a great idea, but implementation is inelegant and rudimentary at best
#  Include Configs & Variables
#################################################################################################
require ("library.php");

#  The Head-Section
#################################################################################################
include($HEADER);

#  The Main-Section
#################################################################################################
echo"<p>&nbsp; \n";
echo" <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" width=\"$table_width\">\n";
echo"   <tr>\n";
echo"    <td class=\"class1\">\n";
echo"      <table align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\">\n";
echo"       <tr>\n";
echo"        <td class=\"class2\">\n";
echo"        <div class=\"mainheader\">$newmemb_head</div>\n";
echo"        <div class=\"maintext\">\n";
echo"        <br> \n";
echo"        <table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo"        <FORM ACTION=\"register_submit.php\" METHOD=\"POST\">\n";
echo"         <tr>\n";
echo"          <td width=\"50%\"><div class=\"maininputleft\">$memf_username : </div></td>\n";
echo"          <td><input type=text name=username></td>\n";
echo"         </tr>\n";
echo"         <tr>\n";
echo"          <td>\n";
echo"           <div class=\"maininputleft\">$memf_email <em id=\"red\">$memb_newvalid</em> : </div></td>\n";
echo"          <td><input type=text name=email></td>\n";
echo"         </tr>\n";
echo"         <tr>\n";
echo"          <td><div class=\"maininputleft\">$memf_password : </div></td>\n";
echo"          <td><input type=password name=password></td>\n";
echo"         </tr>\n";
echo"         <tr>\n";
echo"          <td><div class=\"maininputleft\">$memf_password2 : </div></td>\n";
echo"          <td><input type=password name=password2></td>\n";
echo"         </tr>\n";

echo"         <tr>\n";
echo"          <td><div class=\"maininputleft\">&nbsp;</div></td>\n";
echo"         </tr>\n";

$is_sex=memberfield("1","sex","","");
$publicinfo= (strpos($is_sex,"*")) ? "<em id=\"red\">*</em>" : "" ;
if ($is_sex) {
    echo"         <tr>\n";
    echo"          <td><div class=\"maininputleft\">$memf_sex $publicinfo: </div></td>\n";
    echo"          <td><select name=sex>\n";
    for($i = 0; $i<count($genders); $i++) {
    $letter=$genders[$i];
    if ($sex==$letter) {$selected="SELECTED";} else {$selected="";}
    echo "           <option value=\"$letter\" $selected>$gender[$letter]</option>\n";
    }
    echo"           </select></td>\n";
    echo"         </tr>\n";
}

if (memberfield("1","newsletter","","")) {
    echo"         <tr>\n";
    echo"          <td><div class=\"maininputleft\">$memf_newsletter : </div></td>\n";
    echo"          <td><input type=checkbox name=newsletter CHECKED></td>\n";
    echo"         </tr>\n";
}

$result = mysql_query("select * FROM ".$prefix."config WHERE type='member' AND name<>'newsletter' AND name<>'sex' AND name<>'picture' ORDER BY value6,id") or die(mysql_error());
while ($db = mysql_fetch_array($result)) {
    $language="memf_".$db['name'];
    $preselect= ($db['name']=="homepage") ? "http://" : "";
    echo memberfield("1","$db[name]",$$language,$preselect,30);
}

echo"         <tr><td colspan=2><div class=\"smallcenter\">&nbsp;</div></td></tr>\n";
echo"         <tr><td align=right><em id=\"red\">*&nbsp;</em></td><td><em id=\"red\">$memb_newpublic</em></td></tr>";
echo"         <tr><td align=right><em id=\"red\">**&nbsp;</em></td><td><em id=\"red\">$require</em></td></tr>";
echo"         <tr><td colspan=2><div class=\"smallcenter\"><br>\n";
echo"                         <a href=\"termsofuse.php?".sidstr()."\" onClick='enterWindow=window.open(\"termsofuse.php\",\"Fenster\",
                          \"width=750,height=550,top=50,left=50,scrollbars=yes\"); return false'>
                          $memb_newterms</a>
                          <input type=checkbox name=acceptterms CHECKED></div></td>\n";
echo"         </tr>\n";
echo"         <tr>\n";
echo"          <td>&nbsp;</td>\n";
echo"          <td><br><input type=submit value=\"$memb_newsubmit\" name=\"submit\"></td>\n";
echo"         </tr>\n";
echo"        </form>\n";
echo"        </table>\n";
echo"        </div>\n";
echo"        </td>\n";
echo"       </tr>\n";
echo"      </table>\n";
echo"    </td>\n";
echo"   </tr>\n";
echo" </table>\n";

#  The Foot-Section
#################################################################################################
include($FOOTER);
?>