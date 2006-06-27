<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_upd.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : Add or edit a classified record
#
#################################################################################################

#  Process
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'classified_upd.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
if (!$catid && !$subcatid && !$editadid) {

$result = mysql_query("SELECT * FROM ".$prefix."adcat WHERE disabled<>'1' ORDER by sortorder,id") or died("Database Query Error");
while ($db = mysql_fetch_array($result)) {
    $optioncat .= "<option value=\"$db[id]\">$db[name]</option>";
}

echo "<br>\n";
echo "<form action=classified.php METHOD=GET>\n";
echo "<table align=\"center\" width=\"100%\">\n";
echo "<input type=\"hidden\" name=\"choice\" value=\"add\">\n";
echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_user </div></td>\n";
echo "<td class=\"classadd2\">$_SESSION[susername]</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_ip</div></td>\n";
echo "<td class=\"classadd2\">$ip</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_cat</div></td>\n";
echo "<td class=\"classadd2\"><select name=\"catid\">\n";
echo "$optioncat</select></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd2\"></td>\n";
echo "<td class=\"classadd2\"><br><input type=submit value=$adadd_submit></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";



} elseif (!$subcatid && !$editadid) {

if ($sales_option) {
    if (!sales_checkaccess(2,$_SESSION[suserid],$catid)) { // check access for user and cat
    open_sales_window();
    echo "<script language=javascript>location.replace('classified.php');</script>";
    }
}

$catresult = mysql_query("SELECT * FROM ".$prefix."adcat WHERE id='$catid' AND disabled<>'1'") or died("Database Query Error");
$dbcat = mysql_fetch_array($catresult) or died ("Category NOT Found");

$result = mysql_query("SELECT * FROM ".$prefix."adsubcat WHERE catid='$catid' ORDER by id") or died("Database Query Error");

while ($db = mysql_fetch_array($result)) {
    $optionsubcat .= "<option value=$db[id]>$db[name]</option>";
}

echo "<br>\n";
echo "<form action=classified.php METHOD=GET>\n";
echo "<table align=\"center\" width=\"100%\">\n";
echo "<input type=\"hidden\" name=\"choice\" value=\"add\">\n";
echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_user </div></td>\n";
echo "<td class=\"classadd2\">$_SESSION[susername]</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_ip</div></td>\n";
echo "<td class=\"classadd2\">$ip</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_cat</div></td>\n";
echo "<td class=\"classadd2\">$dbcat[name]</td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_subcat</div></td>\n";
echo "<td class=\"classadd2\"><select name=\"subcatid\">\n";
echo "$optionsubcat</select></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd2\"></td>\n";
echo "<td class=\"classadd2\"><br><input type=submit value=$adadd_submit></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";

} elseif ($subcatid || $editadid) {

if ($sales_option) {
    if (!sales_checkaccess(2,$_SESSION[suserid],$catid)) { // check access for user and cat
    open_sales_window();
    echo "<script language=javascript>location.replace('classified.php');</script>";
    }
}


if (strpos($client,"MSIE")) { // Internet Explorer Detection
    $field_size="50";
    $text_field_size="30";
    $input_field_size="28";
} else {
    $field_size="28";
    $text_field_size="20";
    $input_field_size="14";
}

unset($db);                     // reset value

$userid=$_SESSION[suserid];
$username=$_SESSION[susername];

if ($editadid) {
    if (!$_SESSION[susermod]) {$userstr="AND userid='$_SESSION[suserid]'";}
    $result = mysql_query("SELECT * FROM ".$prefix."ads WHERE id='$editadid' $userstr") or died("Database Query Error");
    $db = mysql_fetch_array($result);
    $subcatid=$db[subcatid];
    $result2 = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id=$db[userid]") or died("Database Query Error");
    $dbu = mysql_fetch_array($result2);
    $userid=$db[userid];
    $username=$dbu[username];
}

if ($logging_enable && $floodprotect && $floodprotect_ad && $_SESSION[suserid] && !$_SESSION[susermod] && !$editadid) { // check floodprotect
    $checktimestamp = $timestamp-(3600*$floodprotect);
    $result = mysql_query("SELECT timestamp FROM ".$prefix."logging WHERE event='AD: new' AND username='$_SESSION[susername]' AND timestamp>'$checktimestamp'") or died("Database Query Error".mysql_error());
    $count=mysql_num_rows($result);
    if ($floodprotect_ad<=$count) {
        died ("Floodprotect active !!! $count events logged last $floodprotect hour(s)");
    }
}


$subcatresult = mysql_query("SELECT * FROM ".$prefix."adsubcat WHERE id='$subcatid'") or died("Database Query Error");
$dbsubcat = mysql_fetch_array($subcatresult);
$catresult = mysql_query("SELECT * FROM ".$prefix."adcat WHERE id='$dbsubcat[catid]'") or died("Database Query Error");
$dbcat = mysql_fetch_array($catresult);

$cat=$dbcat[name];
$catid=$dbcat[id];
$subcat=$dbsubcat[name];
$subcatid=$dbsubcat[id];

if ($addurations == "week" || $addurations == "day") {
    for ($i=0;$i<count($adduration);$i++) {
    if ($addurations == "week") {
        $ii=$adduration[$i]*7;
    } else {
        $ii=$adduration[$i];
    }
    $optionduration .= "<option value=$ii>$adduration[$i]</option>";
    }
}

echo "<br>\n";
echo "<form enctype=\"multipart/form-data\" action=classified_upd_submit.php METHOD=POST>\n";
echo "<table align=\"center\" width=\"100%\">\n";
echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_user </div></td>\n";
echo "<td class=\"classadd2\">$username</td>\n";
echo "<input type=\"hidden\" name=\"in[userid]\" value=\"$userid\">\n";
echo "<input type=\"hidden\" name=\"in[uname]\" value=\"$username\">\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_ip</div></td>\n";
echo "<td class=\"classadd2\">$ip</td>\n";
echo "<input type=\"hidden\" name=\"in[adipaddr]\" value=\"$ip\">\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_cat</div></td>\n";
echo "<td class=\"classadd2\">$cat</td>\n";
echo "<input type=\"hidden\" name=\"in[catid]\" value=\"$catid\">\n";
echo "<input type=\"hidden\" name=\"in[cat]\" value=\"$cat\">\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_subcat</div></td>\n";
echo "<td class=\"classadd2\">$subcat</td>\n";
echo "<input type=\"hidden\" name=\"in[subcatid]\" value=\"$subcatid\">\n";
echo "<input type=\"hidden\" name=\"in[subcat]\" value=\"$subcat\">\n";
echo "</tr>\n";


if ($addurations == "week" || $addurations == "day") {
    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_dur</div></td>\n";
    echo "<td class=\"classadd2\"><select name=\"in[duration]\">\n";
    echo "$optionduration</select> ";
    if ($addurations == "week") {
    echo "$adadd_durweeks";
    } else {
    echo "$adadd_durdays";
    }

    echo " <em id=\"red\">**</em></td>\n";
    echo "</tr>\n";
} else {
    echo "<input type=\"hidden\" name=\"in[duration]\" value=\"99999\">\n";
}

echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_loc</div></td>\n";
if ($location_text) {
    echo "<td class=\"classadd2\"><input type=text name=\"in[location]\" size=\"$field_size\" maxlength=\"50\" value=\"$db[location]\">\n";
} else {
    echo "<td class=\"classadd2\"><select name=\"in[location]\">\n";
    if ($db[location]) {
    echo "<option value=\"$db[location]\" SELECTED>$db[location]</option>\n";
    } else {
    echo "<option value=\"0\" SELECTED>$location_sel</option>\n";
    }
    include ("$language_dir/location.inc");
    echo "</select> <em id=\"red\">**</em></td>\n";
}
echo "</tr>\n";

// Fields of this Category

$result = mysql_query("select * FROM ".$prefix."config WHERE type='cat' AND value='$catid' AND name NOT LIKE 'icon%' AND name NOT LIKE 'picture%' AND name NOT LIKE 'attach%' ORDER BY id") or die(mysql_error());
while ($dbf = mysql_fetch_array($result)) {
    if ($dbf[value2]=="yes" || $dbf[value2]=="req") {
    $adadd_field=$dbf[name];
    echo adfield($catid,$adadd_field,"$dbcat[$adadd_field]","$db[$adadd_field]",$field_size);
    }
}

// Icon's of this Category

$result = mysql_query("select * FROM ".$prefix."config WHERE type='cat' AND value='$catid' AND name LIKE 'icon%' ORDER BY id") or die(mysql_error());
while ($dbi = mysql_fetch_array($result)) {
    $stricon=$dbi[name];
    $striconalt=$dbi[name]."alt";
    if ($dbi[value2]=="yes" && $dbcat[$stricon]) {
        if (substr(base_convert(substr($dbi[name],4), 10, 2),-1)) {$hspace="3";} else {$hspace="2";}
    $iconstr.= "<img src=\"$dbcat[$stricon]\" alt=\"$dbcat[$striconalt]\" hspace=\"$hspace\">\n";
    $x++;
    }
}

if ($x) {

    echo "<tr><td><div class=\"spaceleft\">&nbsp</div></td></tr>\n";
    echo "<tr>\n";
    echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_selicon</div></td>\n";
    echo "<td class=\"classadd2\" height=\"50\">\n";
    echo "$iconstr";
    echo "<br>\n";

    for ($i=1;$i<=10;$i++) {
    if ($db["icon".$i]) {$checked[$i]="checked";}
    if ($dbcat["icon$i"] && adfield($catid,"icon$i")) {echo "<input type=\"checkbox\" name=\"in[icon$i]\" $checked[$i]>\n";}
    }

    echo "</td></tr>\n";

}

// Text

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_head </div></td>\n";
echo "<td class=\"classadd2\"><input type=text name=\"in[header]\" size=\"$field_size\" maxlength=\"50\" value=\"$db[header]\"> <em id=\"red\">**</em></td>\n";
echo "</tr>\n";

echo "<tr>\n";
echo "<td class=\"classadd1\"><div class=\"maininputleft\">$adadd_text </div><br>\n";
echo "<div class=\"mainpages\"><a href=\"smiliehelp.php\"
      onClick='enterWindow=window.open(\"smiliehelp.php?".sidstr()."display=y\",\"Smilie\",
      \"width=250,height=450,top=100,left=100,scrollbars=yes\"); return false'
      onmouseover=\"window.status='$smiliehelp'; return true;\"
      onmouseout=\"window.status=''; return true;\">$smiliehelp</a>&nbsp&nbsp\n";
echo "</div></td>\n";
$text=decode_msg($db[text]);
echo "<td class=\"classadd2\"><textarea rows=\"8\" name=\"in[text]\" cols=\"$text_field_size\">$text</textarea> <em id=\"red\">**</em></td>\n";
echo "</tr>\n";

if ($convertpath && $pic_enable) {
    $result = mysql_query("select * FROM ".$prefix."config WHERE type='cat' AND value='$catid' AND name LIKE 'picture%' ORDER BY name") or die(mysql_error());
    while ($dbp = mysql_fetch_array($result)) {
    $fieldname=$dbp[name];
    $_fieldname="_".$dbp[name];
    if ($dbp[value2]=="yes") {
        $pcount++;
        $fieldstr = ($pcount==1) ? "$adadd_pic" : "" ;
        if ($db[$fieldname]) {
        echo "<tr>\n";
        echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $fieldstr </div></td>\n";
        echo "<td class=\"classadd2\"><input type=text name=in[$fieldname] value=$db[$fieldname] READONLY>\n";
        echo "<input type=\"checkbox\" name=\"".$fieldname."del\"> $adadd_delatt</div>\n";
        echo "<input type=hidden name=in[$_fieldname] value=$db[$_fieldname]>\n";
        echo "</td>\n";
        echo "</tr>\n";
        } else {
        echo "<tr>\n";
        echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $fieldstr </div></td>\n";
        echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$pic_maxsize\">\n";
        echo "<td class=\"classadd2\"><input type=file name=\"".$fieldname."add\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";
        echo "</td>\n";
        echo "</tr>\n";
        }
    }
    }
    if ($pcount) {
    echo "<tr>\n";
    echo "<td>&nbsp;</td><td><div class=\"mainmenu\">[max. $pic_maxsize Byte, $adadd_picnos]</div></td>\n";
    echo "</tr>\n";
    }
    unset($fieldname);}

if ($att_enable) {
    $result = mysql_query("select * FROM ".$prefix."config WHERE type='cat' AND value='$catid' AND name LIKE 'attach%' ORDER BY name") or die(mysql_error());
    while ($dba = mysql_fetch_array($result)) {
    $fieldname=$dba[name];
    if ($dba[value2]=="yes") {
        $acount++;
        $fieldstr = ($acount==1) ? "$adadd_att" : "" ;
        if ($db[$fieldname]) {
        echo "<tr>\n";
        echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $fieldstr </div></td>\n";
        echo "<td class=\"classadd2\"><input type=text name=in[$fieldname] value=$db[$fieldname] READONLY>\n";
        echo "<input type=\"checkbox\" name=\"".$fieldname."del\"> $adadd_delatt</div>\n";
        echo "</td>\n";
        echo "</tr>\n";
        } else {
        echo "<tr>\n";
        echo "<td class=\"classadd1\"><div class=\"maininputleft\"> $fieldstr </div></td>\n";
        echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$att_maxsize\">\n";
        echo "<td class=\"classadd2\"><input type=file name=\"".$fieldname."add\" size=\"$input_field_size\" maxlength=\"50\" value=\"\"><br>\n";
        echo "</td>\n";
        echo "</tr>\n";
        }
    }
    }
    if ($acount) {
    echo "<tr>\n";
    echo "<td>&nbsp;</td><td><div class=\"mainmenu\">[max. $att_maxsize Byte, $adadd_attnos]</div></td>\n";
    echo "</tr>\n";
    }
    unset($fieldname);
}

if ($editadid) { echo "<input type=\"hidden\" name=\"editadid\" value=\"$editadid\">\n"; }

echo"<tr><td colspan=2><div class=\"smallcenter\">&nbsp;</div></td></tr>\n";
echo"<tr><td align=right><em id=\"red\">**&nbsp;</em></td><td><em id=\"red\">$require</em></td></tr>";

echo "<tr>\n";
echo "<td class=\"classadd1\"></td>\n";
echo "<td class=\"classadd2\"><div class=\"mainmenu\"><br><input type=submit value=$adadd_submit>\n";
echo " $adadd_submitone</div></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";

} else {
died("Fatal ERROR");
}

?>