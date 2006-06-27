<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : member_details.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : show details of a member
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'members_details.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
if (function_exists("sales_checkuser")) {$is_sales_user=sales_checkuser($userid);}

if ($uid && $uname && $show_members_details && !($sales_option && $sales_members && !$is_sales_user)) {

  $query = mysql_query("select * from ".$prefix."userdata where id = '$uid'") or died (mysql_error());
    list ($id, $username, $password, $email, $sex, $newsletter, $level, $votes, $lastvotedate, $lastvote, $ads,
        $lastaddate, $lastad, $firstname, $lastname, $address, $zip, $city, $state, $country,
        $phone, $cellphone, $icq, $homepage, $hobbys, $field1, $field2, $field3,
        $field4, $field5, $field6, $field7, $field8, $field9, $field10,$picture,$_picture,$language,$registered,$lastlogin,
        $timezone,$dateformat) = mysql_fetch_row($query);

  if ($email) {
    echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\" border=\"0\">\n";
    echo "<tr><td><div class=\"smallleft\">\n";
    echo "$admy_member$uname&nbsp;&nbsp;\n";

        if ($email) {
            if ($sales_option && $sales_members && !$is_sales_user) { // check access for user
                ico_email("","absmiddle");
        } else {
                ico_email("username=$uname","absmiddle");
        }
        }
        if ($icq) {
            if ($sales_option && $sales_members && !$is_sales_user) { // check access for user
            ico_icq("","absmiddle");
        } else {
            ico_icq("$icq","absmiddle");
        }
        }
        if ($homepage) {
            if ($sales_option && $sales_members && !$is_sales_user) { // check access for user
                ico_url("","absmiddle");
        } else {
                ico_url("$homepage","absmiddle");
        }
        }

    echo "</div></td>\n";
    echo "</tr></table>\n";

    echo"           <div class=\"maintext\">\n";
    echo"           <br>\n";
    echo"           <table align=center width=\"100%\">\n";
    echo"           <tr>\n";
    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_username : </div></td>\n";
    echo"            <td class=\"gbtable2\">$uname</td>\n";
    echo"           </tr>\n";
    echo"           <tr>\n";
    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_level : </div></td>\n";
    echo"            <td class=\"gbtable2\">$level ($userlevel[$level])</td>\n";
    echo"           </tr>\n";
    echo"           <tr>\n";
    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_votes : </div></td>\n";
    echo"            <td class=\"gbtable2\">$votes</td>\n";
    echo"           </tr>\n";
    if ($votes) {
        echo"           <tr>\n";
    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_lastvote : </div></td>\n";
        echo"            <td class=\"gbtable2\">".dateToStr($lastvotedate)."</td>\n";
        echo"           </tr>\n";
    }
    echo"           <tr>\n";
    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">";
    if ($ads) {
    echo"                <a href=\"$_SERVER[PHP_SELF]?choice=ads&uid=$uid&uname=$uname\">$memf_ads</a> :";
    } else {
    echo"                $memf_ads :";
    }
    echo"                </div></td>\n";
    echo"            <td class=\"gbtable2\">$ads</td>\n";
    echo"           </tr>\n";
    if ($ads) {
        echo"       <tr>\n";
    echo"            <td class=\"gbtable2\" width=\"50%\"><div class=\"maininputleft\">$memf_lastad : </div></td>\n";
        echo"            <td class=\"gbtable2\">".dateToStr($lastaddate)."</td>\n";
    echo"           </tr>\n";
    }


    $sex=$gender[$sex];

    $result = mysql_query("select * FROM ".$prefix."config WHERE type='member' AND name<>'picture' ORDER BY value6,id") or die(mysql_error());
    while ($db = mysql_fetch_array($result)) {
    $language="memf_".$db[name];
    echo memberfield("2","$db[name]",$$language,$$db[name]);
    }

    if ($_picture) {      // advanced picture handling

    echo "<tr>\n";
        echo "<td colspan=2 align=center>\n";
        include ("member_apic.inc.php");
        echo "</td>\n";
        echo "</tr>\n";

    } elseif ($picture) {    // simple picture handling

        echo "<tr>\n";
        echo "<td colspan=2 align=center>\n";
        include ("member_spic.inc.php");
        echo "</td>\n";
        echo "</tr>\n";

    }


    echo"           </table>\n";
    echo"           </div><br><br>\n";

  } else {
    echo $mess_noentry;
  }

} else {
    echo "$memb_notenabled";
}
?>