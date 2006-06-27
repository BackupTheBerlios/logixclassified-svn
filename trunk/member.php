<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : member.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : My Profile Area
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'member.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
if ($change=="email") {
    include ("$language_dir/member_chemail.inc");
} elseif ($change=="pass") {
    include ("$language_dir/member_chpass.inc");
} elseif ($change=="delete") {
    include ("$language_dir/member_delete.inc");
} elseif ($change=="sales" && $sales_option) {
    include ("sales_member.php");
} else {

    $query = mysql_query("select * from ".$prefix."userdata where id = '$_SESSION[suserid]'") or died (mysql_error());
    list ($id, $username, $password, $email, $sex, $newsletter, $level, $votes, $lastvotedate, $lastvote, $ads,
    $lastaddate, $lastad, $firstname, $lastname, $address, $zip, $city, $state, $country,
    $phone, $cellphone, $icq, $homepage, $hobbys, $field1, $field2, $field3,
    $field4, $field5, $field6, $field7, $field8, $field9, $field10,$picture,$_picture,$language,$registered,$lastlogin,
    $timezone,$dateformat) = mysql_fetch_row($query);

    if (!$homepage) $homepage = "http://";

    if (strpos($client,"MSIE")) { // Internet Explorer Detection
    $field_size="50";
    $text_field_size="31";
    $input_field_size="20";
    } else {
    $field_size="28";
    $text_field_size="20";
    $input_field_size="10";
    }

    echo"           <table align=\"center\">\n";
    echo"            <tr>\n";
    echo"             <td>\n";
    echo"              <div class=\"mainmenu\">\n";
    echo"          <a href=\"members.php?choice=myprofile&change=email\" onmouseover=\"window.status='$memb_link1desc'; return true;\" onmouseout=\"window.status=''; return true;\">$memb_link1</a>$menusep\n";
    echo"          <a href=\"members.php?choice=myprofile&change=pass\" onmouseover=\"window.status='$memb_link2desc'; return true;\" onmouseout=\"window.status=''; return true;\">$memb_link2</a>$menusep\n";
    echo"          <a href=\"members.php?choice=myprofile&change=delete\" onmouseover=\"window.status='$memb_link3desc'; return true;\" onmouseout=\"window.status=''; return true;\">$memb_link3</a>\n";
    if ($sales_option) {
    echo"          $menusep<a href=\"members.php?choice=myprofile&change=sales\" onmouseover=\"window.status='$sales_lang_linkdesc'; return true;\" onmouseout=\"window.status=''; return true;\">$sales_lang_link</a>\n";
    }
    echo"              </div>\n";
    echo"             </td>\n";
    echo"            </tr>\n";
    echo"           </table>\n";

    echo"           <div class=\"maintext\">\n";
    echo"           <br>\n";
    echo"           <table align=center>\n";
    echo"           <form enctype=\"multipart/form-data\" action=member_submit.php METHOD=POST>\n";
    echo"           <tr>\n";
    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_username : </div></td>\n";
    echo"            <td>$_SESSION[susername]</td>\n";
    echo"           </tr>\n";
    echo"           <tr>\n";
    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_email : </div></td>\n";
    echo"            <td>$email</td>\n";
    echo"           </tr>\n";
    echo"           <tr>\n";
    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_level : </div></td>\n";
    echo"            <td>$level ($userlevel[$level])</td>\n";
    echo"           </tr>\n";
    echo"           <tr>\n";
    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_votes : </div></td>\n";
    echo"            <td>$votes</td>\n";
    echo"           </tr>\n";
    if ($votes) {
        echo"           <tr>\n";
    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_lastvote : </div></td>\n";
        echo"            <td>".dateToStr($lastvotedate)."</td>\n";
    echo"           </tr>\n";
    }
    echo"           <tr>\n";
    echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_ads : </div></td>\n";
    echo"            <td>$ads</td>\n";
    echo"           </tr>\n";
    if ($ads) {
    echo"           <tr>\n";
        echo"            <td width=\"50%\"><div class=\"maininputleft\">$memf_lastad : </div></td>\n";
    echo"            <td>".dateToStr($lastaddate)."</td>\n";
        echo"           </tr>\n";
    }

    $is_sex=memberfield("1","sex","","");
    $publicinfo = (strpos($is_sex,"*")) ? "<em id=\"red\">*</em>" : "" ;
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

    if (memberfield("0","newsletter","","")) {
    if ($newsletter) $newschecked = "CHECKED";
    echo"         <tr>\n";
    echo"          <td><div class=\"maininputleft\">$memf_newsletter : </div></td>\n";
    echo"          <td><input type=checkbox name=newsletter $newschecked></td>\n";
    echo"         </tr>\n";
    }


    $result = mysql_query("select * FROM ".$prefix."config WHERE type='member' AND name<>'picture' AND name<>'newsletter' AND name<>'sex' ORDER BY value6,id") or die(mysql_error());
    while ($db = mysql_fetch_array($result)) {
        $language="memf_".$db[name];
        echo memberfield("0","$db[name]",$$language,$$db[name],$text_field_size);
    }

    if (memberfield("0","picture","","")) {
    if ($picture) {
            echo "<tr>\n";
            echo "<td><div class=\"maininputleft\"> $adadd_pic </div></td>\n";
            echo "<td><input type=text name=picture value=$picture READONLY>\n";
            echo "<input type=hidden name=_picture value=$_picture>\n";
            echo "<input type=\"checkbox\" name=\"picture_del\"> $adadd_delatt</div>\n";
            echo "</td>\n";
            echo "</tr>\n";

        if ($_picture) {      // advanced picture handling

            echo "<tr>\n";
            echo "<td>&nbsp;</td><td>\n";
            include ("member_apic.inc.php");
            echo "</td>\n";
            echo "</tr>\n";

        } elseif ($picture) {    // simple picture handling

            echo "<tr>\n";
            echo "<td>&nbsp;</td><td>\n";
        include ("member_spic.inc.php");
            echo "</td>\n";
            echo "</tr>\n";

        }

            echo "</td>\n";
            echo "</tr>\n";
        } else {
            echo "<tr>\n";
            echo "<td><div class=\"maininputleft\"> $adadd_pic </div></td>\n";
            echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";
            echo "<td><input type=file name=\"picture_add\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";
            echo "</td>\n";
            echo "</tr>\n";
        }
    }

    echo"         <tr><td colspan=2><div class=\"smallcenter\">&nbsp;</div></td></tr>\n";
    echo"         <tr><td align=right><em id=\"red\">*&nbsp;</em></td><td><em id=\"red\">$memb_newpublic</em></td></tr>";
    echo"         <tr><td align=right><em id=\"red\">**&nbsp;</em></td><td><em id=\"red\">$require</em></td></tr>";
    echo"         <tr><td colspan=2><div class=\"smallcenter\"><br>\n";
    echo"            <tr>\n";
    echo"            <td>&nbsp;</td>\n";
    echo"            <td><input type=submit value=$update></td>\n";
    echo"           </tr>\n";
    echo"           </table>\n";
    echo"           </form>\n";
    echo"           </div>\n";
}

?>