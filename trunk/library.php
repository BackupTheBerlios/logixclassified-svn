<?php
##############################################################################################
#                                                                                            #
#                                library.php                                                 #
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
//TODO: sort this shit out into functions and methods, maybe we need some classes?
 if(!strpos($_SERVER['PHP_SELF'],'library.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
#  Include Configs & Variables
#################################################################################################
require("config.php");

if ($db_persistent) {
        $db_connect_id = mysql_pconnect($db_server, $db_user, $db_pass) or die ("Database connect Error - please check your DB configuration / setup");
} else {
        $db_connect_id = mysql_connect($db_server, $db_user, $db_pass) or die ("Database connect Error - please check your DB configuration / setup");
}
if( $db_connect_id && $db_name != "" ) {
    mysql_select_db($db_name, $db_connect_id) or die ("Database select Error - please check your DB configuration / setup");
} else {
    die ("Database select Error - please check your DB configuration / setup");
}

//session_name("PHPSESSID");  redundant. php already sets a default, and we dont really care since we're gonna go with crypted sessions
session_start();
//massive fixes needed to fix php notices - should develop with error_reporting(E_ALL)
//numerous sql injection points also in all files- should have a safe_query wrapper, mimicing BIND variables,
//and lastly - search function and method - trivially explooitable for sql injection. should never allow queries to be passed in GET
//in fact, all form submits should be converted to method="post" and eliminate GET method as much as possible.
if (!empty($_COOKIE['PHPSESSID']) && empty($_SESSION['suserid'])) {  // if lost session
    $query = mysql_query("SELECT id,userid FROM ".$prefix."sessions WHERE id='$_COOKIE[PHPSESSID]' AND expirestamp>'$timestamp'");
    $session = mysql_fetch_array($query);
    if ($session['id']) { setsessionvar($session['userid']); }
}
//TODO:L WTF is this? we dont do this except at checklogin function anyway ..
//KLUDGE: To prevent E_NOTICE on undefined vars, we need to check if values are set
$language_user = $language_default;
(empty($_SESSION['susername']))?$_SESSION['susername'] = "": $_SESSION['susername'] = $_SESSION['susername'];

if (!strpos($_SERVER['PHP_SELF'],"frametop.php"))
{
    mysql_query("INSERT INTO ".$prefix."useronline VALUES ('$timestamp','$ip','$_SERVER[PHP_SELF]','$_SESSION[susername]')");
}

if (!empty($_COOKIE['Language']) && $show_languages)
{
    $language_user=$_COOKIE['Language'];
    $_SESSION['suserlanguage']=$_COOKIE['Language'];
}
elseif (!empty($_SESSION['suserlanguage']) && $show_languages)
{
    $language_user=$_SESSION['suserlanguage'];
}
(empty($language_user))?$language_user = $language_default:$language_user = $language_user;


$language_dir=$languagebase_dir."/".$language_user;
if (!is_file("$language_dir/variables.php")) {$language_dir=$languagebase_dir."/".$language_default;}
require("$language_dir/variables.php");
(empty($_SESSION['susertimezone']))? $_SESSION['susertimezone']="0000":$_SESSION['susertimezone'] = $_SESSION['susertimezone'];
$usertimeoffset = $_SESSION['susertimezone']*3600;
$userdateformat = (!empty($_SESSION['suserdateformat'])) ? $_SESSION['suserdateformat'] : $dateformat ;

if (is_file("sales.php")) {
    include ("sales.php");
    require("$language_dir/sales_variables.php");
}

//OK here, we will query database once, get configuration values, and extract any superglobals submitted,
//they also need to be cleaned and sanitized, but this is better handled in the code
//
//a crude hack for additional fields that dont appear in database-
//I think just at signup time
$add_arr = array('source','vote','sqlquery','sqlquery2','sqlquery3','msgheader','msg','fromid','toemailid','pic1','pic2','pic3','sort1','sort2','subject','systext1','systext2','friend','fromname','fromemail','toname','toemail','text','newrate','confirm','editadid','choice','entry','action','value','username','password','password2','acceptterms','submit','email','hash','nick','choice','catid','subcatid','in','MAX_FILE_SIZE','picture1add','picture2add','picture3add','attachment1add','attachment2add','attachment3add','adid','errormessage','change');
foreach($add_arr as $extract)
{
  if(!empty($extract) && empty($$extract))//if database data is not empty, and variable is not set (if it is empty, it returns empty anyway)
    {
        $$extract = extract_superglobal($extract,$_REQUEST); //assign the data name value to the superglobal value
    }
    //if the variaable is already set, we can do an else, and check it further, for cleanliness, but it's hard to know just what values to check
    //so the code that uses the value needs to check values.
    //OK now do POST, then COOKIE, then Session
    if(!empty($extract) && empty($$extract))//if database data is not empty, and variable is not set (if it is empty, it returns empty anyway)
    {
        $$extract = extract_superglobal($extract,$_COOKIE); //assign the data name value to the superglobal value
    }
    if(!empty($extract) && empty($$extract))//if database data is not empty, and variable is not set (if it is empty, it returns empty anyway)
    {
        $$extract = extract_superglobal($extract,$_SESSION); //assign the data name value to the superglobal value
    }
}
$extraction = mysql_query("select name from ${prefix}config");
while($extract_results = mysql_fetch_array($extraction))
{
    //get the dataname
    $extract = $extract_results['name'];
    //check if it is empty, or check if it is already set
    if(!empty($extract) && empty($$extract))//if database data is not empty, and variable is not set (if it is empty, it returns empty anyway)
    {
        $$extract = extract_superglobal($extract,$_REQUEST); //assign the data name value to the superglobal value
    }
    //if the variaable is already set, we can do an else, and check it further, for cleanliness, but it's hard to know just what values to check
    //so the code that uses the value needs to check values.
    //OK now do POST, then COOKIE, then Session
    if(!empty($extract) && empty($$extract))//if database data is not empty, and variable is not set (if it is empty, it returns empty anyway)
    {
        $$extract = extract_superglobal($extract,$_COOKIE); //assign the data name value to the superglobal value
    }
    if(!empty($extract) && empty($$extract))//if database data is not empty, and variable is not set (if it is empty, it returns empty anyway)
    {
        $$extract = extract_superglobal($extract,$_SESSION); //assign the data name value to the superglobal value
    }

}
#  License Functions
#################################################################################################
//Obsolete- we arent licensing this crap anymore.
function licensekey() {
    $file="license.key";

   /* if (is_readable($file)) {
    $fd = fopen ($file, "r");
        $buffer = fread($fd, 4096);
    fclose ($fd);
        $decbuffer = licensedecrypt($buffer);
    $array= explode("|",$decbuffer);
    $key['fourdigit']     = '';
        $key[number]        = $array[0];
    $key[customername]  = $array[1];
        $key[customeremail] = $array[2];
        $key[domain]        = $array[3];
        $key[purchasedate]  = $array[4];
    $key[expiredate]    = $array[5];
        $key[passback]      = $array[6];
    return $key;
    } else {
    return false;
    }  */
    return true;
}

function ed($t) {
      $r = md5("2fast4MICRO$OFT!");
      $c = 0;
      $v = "";
      for ($i=0;$i<strlen($t);$i++) {
         if ($c==strlen($r)) $c=0;
         $v.= substr($t,$i,1) ^ substr($r,$c,1);
         $c++;
      }
      return $v;
}

#  Functions
#################################################################################################

function window_header($title) {
    global $STYLE,$lang_metatag;

    echo "<!-- Header Start -->\n";
    echo "\n";
    echo "<html>\n";
    echo " <head>\n";
    echo "  <title>$title</title>\n";
    echo "     <link rel=\"stylesheet\" type=\"text/css\" href=\"$STYLE\">\n";
    echo "     $lang_metatag\n";
    echo " </head>\n";
    echo "<body>\n";
    echo "\n";
    echo "<!-- Header End -->\n";
    echo "\n";

}

function window_footer() {
    echo "\n";
    echo "<!-- Footer Start -->\n";
    echo "\n";
    echo"</body>\n";
    echo"</html>\n";
    echo "\n";
    echo "<!-- Footer End -->\n";
    echo "\n";
}

function died($message)
{       //when we die, than with a nice window ;-)
//TODO: Re-do this function died for logging - either sql based or file based, who cares? should work fine
    if(!$message) {
    $message = "There was an unknown error !";
    }

    $errormessage=rawurlencode($message);
    echo "<script language=\"JavaScript\">
    history.back(1);
    var winl = (screen.width - 300) / 2;
    var wint = (screen.height - 150) / 2;
    window.open(\"message.php?".sidstr()."msg=$errormessage&msgheader=Error\",\"Error\",\"width=300,height=150,top=\"+wint+\",left=\"+winl+\",resizeable=no\");
    </script>\n";

    exit;
}

function resizeimage($source,$target,$pic_res="100x100",$pic_quality="80")
{
//TODO: Re-do this function resizeimage, we can use our well developed image handling function , modified to suit

    $picinfo=GetImageSize($source);
    if ($picinfo[2] == "1" || $picinfo[2] == "2" || $picinfo[2] == "3") {
    switch ($picinfo[2]) {
        case 1 : $orig = imagecreatefromgif($source); break;
        case 2 : $orig = imagecreatefromjpeg($source); break;
        case 3 : $orig = imagecreatefrompng($source); break;
    }

        $orix = imagesx($orig);
    $oriy = imagesy($orig);
    $picsize=explode("x",$pic_res);
    if ($orix>intval($picsize[0]) || $oriy>intval($picsize[1])) {
        $div1=$orix/$picsize[0];
        $div2=$oriy/$picsize[1];
            if ($div1>$div2) {
            $desx=intval(floor($orix/$div1));
        $desy=intval(floor($oriy/$div1));
            } else {
            $desx=intval(floor($orix/$div2));
        $dexy=intval(floor($oriy/$div2));
        }
        if (function_exists(imagecreatetruecolor)){
        $dest = imagecreatetruecolor($desx,$desy);
        } else {
            $dest = imagecreate($desx,$desy);
        }
        if (function_exists(imagecopyresampled)){
        imagecopyresampled($dest,$orig,0,0,0,0,$desx,$desy,$orix,$oriy);
        } else {
        imagecopyresized($dest,$orig,0,0,0,0,$desx,$desy,$orix,$oriy);
        }
    } else {
        $dest=$orig;
    }


    $im=imagejpeg($dest,$target,$pic_quality);
    return $im;
    }
    return false;
}

function move_uploaded_file_todb($filename,$picturename,$type) {
    global $prefix;

    if (is_readable($filename)) {
    $picture_size = filesize($filename);
    $picture_bin = addslashes(fread(fopen($filename, "r"), $picture_size));
    $picture_info = GetImageSize($filename);
    $query = mysql_query("INSERT INTO ".$prefix."pictures VALUES ('$picturename','$type','$picture_info[1]','$picture_info[0]','$picture_size','$picture_bin')");
    if ($query) {
        return true;
    }
    }
    return false;
}

function memberfield($signup,$fieldname,$name,$value,$fieldsize="") {
    global $prefix,$language_dir,$image_dir,$ad_no,$ad_yes;

    $retval=false;
    $result=mysql_query("SELECT * FROM ".$prefix."config WHERE type='member' AND name='$fieldname'") or died(mysql_error());
    $field=mysql_fetch_array($result);
    if ($field['value']!="no" && ($signup=="0" || ($signup=="1" && $field['value2']!="no") || ($signup=="2" && $field['value5']!="no")) ) { // if enabled
      if ($signup=="2") {  // show Memberdetails
    if ($field['value3']!="checkbox") {
        if ($fieldname=="homepage") {
                if ($value && substr($value,0,7)!="http://") {$value="http://".$value;}
        $retval="
             <tr>
              <td class=\"gbtable2\"><div class=\"maininputleft\">$name : </div></td>
                  <td class=\"gbtable2\"><div class=\"maininputright\"><a href=\"$value\" target=\"_blank\">$value</a></div></td>
             </tr>
             ";

        } else {
        $retval="
             <tr>
              <td class=\"gbtable2\"><div class=\"maininputleft\">$name : </div></td>
                  <td class=\"gbtable2\"><div class=\"maininputright\">".htmlspecialchars($value)."</div></td>
             </tr>
             ";
        }
    } else {
        $retval="
            <tr>
                <td class=\"gbtable2\"><div class=\"maininputleft\">$name : </div></td>";
            if ($value) {
        $retval.="
                <td class=\"gbtable2\"><img src=\"$image_dir/icons/checked2.gif\" border=\"0\" alt=\"$ad_yes\"
                onmouseover=\"window.status='$ad_yes'; return true;\"
                onmouseout=\"window.status=''; return true;\"></td>\n";
            } else {
            $retval.="
                    <td class=\"gbtable2\"><img src=\"$image_dir/icons/signno.gif\" border=\"0\" alt=\"$ad_no\"
                    onmouseover=\"window.status='$ad_no'; return true;\"
                    onmouseout=\"window.status=''; return true;\"></td>\n";
        }
        $retval.="
            </tr>";

        }

      } else {  // signup
    if ($field['value5']!="no") {$publicinfo="<em id=\"red\">*</em>";} else {$publicinfo="";}
    if ($field['value2']=="req") { $requiredinfo="<em id=\"red\">**</em>"; } else { $requiredinfo=""; }
    if ($field['value3']=="text" || $field['value3']=="") {
    (empty($readonly))?$readonly = "":$readonly=$readonly;//KLUDGE.. more undefined vars
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name $publicinfo: </div></td>
              <td><input type=\"text\" size=\"$fieldsize\" name=\"$field[name]\" value=\"".htmlspecialchars($value)."\"$readonly> $requiredinfo</td>
             </tr>
         ";
    } elseif ($field['value3']=="url") {
        if (!$value) {$value="http://";} elseif ($value && substr($value,0,7)!="http://") {$value="http://".$value;}
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name $publicinfo: </div></td>
              <td><input type=\"text\" size=\"$fieldsize\" name=\"$field[name]\" value=\"".htmlspecialchars($value)."\"$readonly> $requiredinfo</td>
             </tr>
         ";
    } elseif ($field['value3']=="select") {
        if (!$value) {
            $optionstr ="<option value=\"\">--------</option>";
        }
        if (is_file("./$language_dir/$field[value4]")) {
        $filename = "./$language_dir/$field[value4]";
        $fd = fopen ($filename, "r");
        $optionstr.= str_replace("\"$value\"","\"$value\" SELECTED",fread ($fd, filesize ($filename)));
        fclose ($fd);
        } else {
        $options=explode("|",$field['value4']);
        for ($i=0; $i<count($options); $i++) {
            if (!$signup && $options[$i]=="$value") {$selected="SELECTED";} else {$selected="";}
            $optionstr.="<option value=\"".htmlspecialchars($options[$i])."\" $selected>".htmlspecialchars($options[$i])."</option>";
        }
        }
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name $publicinfo: </div></td>
              <td><select name=\"$field[name]\">
          $optionstr
          </select> $requiredinfo</td>
             </tr>
        ";
    } elseif ($field['value3']=="checkbox") {
        if ($signup && $field['value4']) $checked="CHECKED";
        if (!$signup && $value) $checked="CHECKED";
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name $publicinfo: </div></td>
              <td><input type=checkbox name=\"$field[name]\" $checked></td>
             </tr>
        ";
    }
      }
    }

    return $retval;
}

function memberfieldinputcheck ($fieldname,$inputvalue) {
    global $prefix;

    $query=mysql_query("SELECT * FROM ".$prefix."config WHERE type='member' AND name='$fieldname'");
    $db=mysql_fetch_array($query);
    if ($db['value2']=="req" && !$inputvalue) {
    return "$fieldname ";
    } else {
    return "";
    }

}

function adfield($cat,$fieldname,$name="",$value="",$fieldsize="") {
    global $prefix,$language_dir;

    $retval=false;
    $result=mysql_query("SELECT * FROM ".$prefix."config WHERE type='cat' AND name='$fieldname' AND value='$cat'") or died(mysql_error());
    $field=mysql_fetch_array($result);
    if ($field['value2']!="no") { // if enabled
    if ($field['value2']=="req") { $requiredinfo="<em id=\"red\">**</em>"; } else { $requiredinfo=""; }
    if ($field['value3']=="text" || $field['value3']=="url" || $field['value3']=="") {
        if (!$value) {$value=$field['value4'];}
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name : </div></td>
              <td><input type=\"text\" size=\"$fieldsize\" name=\"in[$field[name]]\" value=\"".htmlspecialchars($value)."\"> ".htmlspecialchars($field[value5])." $requiredinfo</td>
             </tr>
         ";
         if ($field['value3']=="url") {$retval.="<!--url-->";}
    } elseif ($field['value3']=="select") {
        if (!$value) {
            $optionstr.="<option value=\"\">--------</option>";
        }
        if (is_file("./$language_dir/$field[value4]")) {
        $filename = "./$language_dir/$field[value4]";
        $fd = fopen ($filename, "r");
        $optionstr.= str_replace("\"$value\"","\"$value\" SELECTED",fread ($fd, filesize ($filename)));
        fclose ($fd);
        } else {
        $options=explode("|",$field['value4']);
        for ($i=0; $i<count($options); $i++) {
            if (!$signup && $options[$i]=="$value") {$selected="SELECTED";} else {$selected="";}
            $optionstr.="<option value=\"".htmlspecialchars($options[$i])."\" $selected>".htmlspecialchars($options[$i])."</option>";
        }
        }
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name : </div></td>
              <td><select name=\"in[$field[name]]\">
          $optionstr
          </select> $field[value5] $requiredinfo</td>
             </tr>
        ";
    } elseif ($field['value3']=="checkbox") {
        if ($signup && $field['value4']) $checked="CHECKED";
        if (!$signup && $value) $checked="CHECKED";
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name : </div></td>
              <td><input type=checkbox name=\"in[$field[name]]\" $checked></td>
             </tr>
        ";
    }
    }
    return $retval;
}

function adfieldunit($cat,$fieldname) {
    global $prefix;
    $retval=false;
    $result=mysql_query("SELECT * FROM ".$prefix."config WHERE type='cat' AND name='$fieldname' AND value='$cat'") or died(mysql_error());
    $field=mysql_fetch_array($result);
    if ($field[value5]) {$retval=$field[value5];}
    return $retval;
}

function adfieldinputcheck ($cat,$fieldname,$inputvalue) {
    global $prefix;

    $query=mysql_query("SELECT * FROM ".$prefix."config WHERE type='cat' AND name='$fieldname' AND value='$cat'");
    $db=mysql_fetch_array($query);
    if ($db[value2]=="req" && $db[value3]!="checkbox" && !$inputvalue) {
    return "$fieldname ";
    } else {
    return "";
    }

}


function searchfield($cat,$fieldname,$name="",$value="",$fieldsize="") {
    global $prefix,$language_dir;

    $retval=false;
    $result=mysql_query("SELECT * FROM ".$prefix."config WHERE type='cat' AND name='$fieldname' AND value='$cat'") or died(mysql_error());
    $field=mysql_fetch_array($result);
    if ($field[value2]=="yes" && $field[value6]!="no") { // if enabled
    if ($field[value3]=="text" || $field[value3]=="") {
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name : </div></td>
              <td><input type=\"text\" name=\"in[$field[name]]\" value=\"".htmlspecialchars($value)."\" size=\"$fieldsize\"> ".htmlspecialchars($field[value5])." </td>
             </tr>
         ";
    } elseif ($field[value3]=="select") {
        if (!$value) {
            $optionstr="<option value=\"\">--------</option>";
        }
        if (is_file("./$language_dir/$field[value4]")) {
        $filename = "./$language_dir/$field[value4]";
        $fd = fopen ($filename, "r");
        $optionstr.= str_replace("\"$value\"","\"$value\" SELECTED",fread ($fd, filesize ($filename)));
        fclose ($fd);
        } else {
        $options=explode("|",$field[value4]);
        for ($i=0; $i<count($options); $i++) {
                if (!$signup && $options[$i]=="$value") {$selected="SELECTED";} else {$selected="";}
                $optionstr.="<option value=\"".htmlspecialchars($options[$i])."\" $selected>".htmlspecialchars($options[$i])."</option>";
        }
        }

        if ($field[value6]=="minmax") {
        $retval="
                <tr>
                    <td><div class=\"maininputleft\">$name : </div></td>
                    <td><select name=\"in[$field[name]]\">
                $optionstr
                </select> - <select name=\"in2[$field[name]]\">
                $optionstr
                </select> $field[value5] </td>
                    </tr>
            ";
        } else {
        $retval="
                <tr>
                    <td><div class=\"maininputleft\">$name : </div></td>
                    <td><select name=\"in[$field[name]]\">
                $optionstr
                </select> $field[value5] </td>
                    </tr>
            ";
        }
    } elseif ($field[value3]=="checkbox") {
        if ($signup && $field[value4]) $checked="CHECKED";
        if (!$signup && $value) $checked="CHECKED";
        $retval="
         <tr>
              <td><div class=\"maininputleft\">$name : </div></td>
              <td><input type=checkbox name=\"in[$field[name]]\" $checked></td>
             </tr>
        ";
    }
    }
    return $retval;
}


function logging($unused,$uid,$username,$event,$ext) {
    global $logging_enable,$prefix,$ip,$client,$timestamp,$REMOTE_HOST;

    if ($logging_enable) {
    mysql_query("INSERT INTO ".$prefix."logging (timestamp,userid,username,ip,ipname,client,event,ext)
                VALUES ('$timestamp','$uid','$username','$ip','$REMOTE_HOST','$client','$event','$ext')") or died(mysql_error());
    }

}

function getfile($filename) {
    $fd = fopen ($filename, "r");
    $contents = fread ($fd, filesize ($filename));
    fclose ($fd);
    return $contents;
}

function addslashesnew($string) {
    if (get_magic_quotes_gpc()==1) {
    return $string;
    } else {
    return addslashes($string);
    }
}

function suppr($file) {
    $delete = @unlink($file);
    if (@file_exists($file)) {
    $filesys = eregi_replace("/","\\",$file);
    $delete = @system("del $filesys");
        if (@file_exists($file)) {
            $delete = @chmod ($file, 0775);
        $delete = @unlink($file);
        $delete = @system("del $filesys");
    }
    }
}

function dateToTime($date) {        //input Format 2000-11-24, output Format: Unixtimestamp
    list($y,$m,$d)=explode("-",substr($date,0,10));
    return mktime(0,0,0,$m,$d,$y);
}

function dateToStr($date) {         //input Format 2000-11-24, output
    global $userdateformat,$timeoffset,$usertimeoffset;

    if ($date!="0000-00-00 00:00:00") {
        $temp=explode(" ",$date);
    list($y,$m,$d)=explode("-",$temp[0]);
        list($hh,$mm,$ss)=explode(":",$temp[1]);
    return date($userdateformat,(mktime($hh,$mm,$ss,$m,$d,$y)+$timeoffset+$usertimeoffset));
    }
}

function str_repeats($input, $mult) {   //str_repeat() - replacement (backward-comp.)
    $ret = "";
    while ($mult > 0) {
    $ret .= $input;
    $mult --;
    }
    return $ret;
}


function isbanned($userid) {
    global $ip,$prefix;

    $ban_query = mysql_query("SELECT * FROM ".$prefix."banned_ips") or died("Database Query Error");

    while ($ips = mysql_fetch_row($ban_query)) {
        if ($ips["0"] == $ip) {
            return 1;
            exit;
        }
    }

    if ($userid) {  // if $userid is empty IGNORE user_banned_check
    $ban_query2 = mysql_query("SELECT * FROM ".$prefix."banned_users") or died("Database Query Error");

    while ($users = mysql_fetch_row($ban_query2)) {
            if ($users["0"] == $userid) {
            return 1;
            exit;
            }
    }
    }

    return 0;
}

function encode_msg ($msg) {
    global $image_dir,$prefix;

    if ($msg) {
        $msg = addslashesnew($msg);   // Add SQL compatibilty
        $msg = str_replace("\r", "", $msg); // Replace return with ""
        $msg = str_replace("\n", "<BR>", $msg); // Replace newline with <br>
    $result = mysql_query("SELECT * FROM ".$prefix."smilies") or died("Query Error");
        while ($db = mysql_fetch_array($result)) {
        $msg = str_replace($db[code], "<img src=".$image_dir."/smilies/".$db[file].">", $msg); // Smilie
        }
    }

    return $msg;
}

function decode_msg ($msg) {
    global $image_dir,$prefix;

    if ($msg) {
#        $msg = stripslashes($msg);   // Remove SQL compatibilty
        $msg = str_replace("<BR>", "\n", $msg); // Replace newline with <br>
    $result = mysql_query("SELECT * FROM ".$prefix."smilies") or died("Query Error");
        while ($db = mysql_fetch_array($result)) {
        $msg = str_replace("<img src=".$image_dir."/smilies/".$db[file].">",$db[code],$msg); // Smilie
        }
    }

    return $msg;
}

function wordwrap_msg($msg, $maxwordlen=40) {  // Looooooong String Break
    $eachword = explode(" " , eregi_replace("<BR>"," ",$msg));          // temp remove <BR>
    for ($i=0; $i<count($eachword); $i++) {
        if (strlen($eachword[$i])>$maxwordlen) {
            $msg = eregi_replace($eachword[$i], chunk_split($eachword[$i],$maxwordlen), $msg); // replace long w
        }
    }
    return $msg;
}

function badwords ($msg,$mod) {
    global $prefix;
    $msg=wordwrap_msg($msg);
    $eachword = explode(" " , eregi_replace("<BR>"," ",$msg));      // temp remove <BR>
    $result = mysql_query("SELECT * FROM ".$prefix."badwords") or died("Query Error");
    while ($db = mysql_fetch_array($result)) {
        for ($i=0; $i<count($eachword); $i++) {
        if (is_int(strpos($eachword[$i],$db['badword']))) {
                if ($mod) {
                $msg = eregi_replace($eachword[$i], "<span class=\"censored\">".$eachword[$i]."</span>", $msg); // Badword
            } else {
                    $msg = eregi_replace($eachword[$i], str_repeats("*", strlen($eachword[$i])), $msg); // Badword
            }
        }
        }
    }
    return $msg;
}

function badwordsmail ($msg) {
    global $prefix;
    $eachword = explode(" ",$msg);
    $result = mysql_query("SELECT * FROM ".$prefix."badwords") or died("Query Error");
    while ($db = mysql_fetch_array($result)) {
        for ($i=0; $i<count($eachword); $i++) {
        if (is_int(strpos($eachword[$i],$db['badword']))) {
                $msg = eregi_replace($eachword[$i], str_repeats("*", strlen($eachword[$i])), $msg); // Badword
        }
        }
    }
    return stripslashes($msg);
}


function strip_array ($in) {  //foreach()-Replacement !!!

    reset($in);
    while ($array=each($in)) {
    $ckey=$array['key'];
    $cvalue=$array['value'];
    $cvalue = str_replace("'", "''", $cvalue);
    $cvalue = stripslashes($cvalue);
    $cvalue = strip_tags($cvalue);
    $out[$ckey] = $cvalue;
    }

    return $out;
}

function open_sales_window($value="") {
    echo "<script language=javascript>
        window.open(\"sales_buy.php?".sidstr()."\",\"Buy_Membership\",\"width=780,height=550,top=10,left=10,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\");
        location.replace('classified.php$value');
        </script>";
}

function ico_email($value,$align="left",$width="600",$height="430",$top="100",$left="100") {
    global $sales_lang_noaccess,$ad_sendemail,$image_dir;

    if (!$value) {

    echo "<a href=\"sales_buy.php\" onClick='enterWindow=window.open(\"sales_buy.php?".sidstr()."\",\"Window\",\"width=780,height=550,top=10,left=10,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'
        onmouseover=\"window.status='$sales_lang_noaccess'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/email.gif\" border=\"0\" alt=\"$sales_lang_noaccess\" align=\"$align\" vspace=\"2\"</a>\n";

    } else {

    echo "<a href=\"sendmail.php?$value\" onClick='enterWindow=window.open(\"sendmail.php?".sidstr()."$value\",\"EMail\",\"width=$width,height=$height,top=$top,left=$left,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'
        onmouseover=\"window.status='$ad_sendemail'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/email.gif\" border=\"0\" alt=\"$ad_sendemail\" align=\"$align\" vspace=\"2\"></a>\n";

    }
}

function ico_icq($value,$align="left") {
    global $sales_lang_noaccess,$ad_icq,$image_dir;

    if (!$value) {

    echo "<a href=\"sales_buy.php\" onClick='enterWindow=window.open(\"sales_buy.php".sidstr()."\",\"Window\",\"width=780,height=550,top=10,left=10,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'
        onmouseover=\"window.status='$sales_lang_noaccess'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/icq.gif\" border=\"0\" alt=\"$sales_lang_noaccess\" align=\"$align\" vspace=\"2\"</a>\n";

    } else {

    echo "<a href=\"http://wwp.icq.com/".$value."\" target=\"_blank\"
        onmouseover=\"window.status='$ad_icq'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/icq.gif\" border=\"0\" alt=\"$ad_icq\" align=\"$align\" vspace=\"2\"></a>\n";

    }
}

function ico_url($value,$align="left") {
    global $sales_lang_noaccess,$ad_gotourl,$image_dir;

    if (!$value) {

    echo "<a href=\"sales_buy.php\" onClick='enterWindow=window.open(\"sales_buy.php".sidstr()."\",\"Window\",\"width=780,height=550,top=10,left=10,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'
        onmouseover=\"window.status='$sales_lang_noaccess'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/home.gif\" border=\"0\" alt=\"$sales_lang_noaccess\" align=\"$align\" vspace=\"2\"</a>\n";

    } else {

    echo "<a href=\"$value\" target=\"_blank\"
        onmouseover=\"window.status='$ad_gotourl ($value)'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/home.gif\" border=\"0\" alt=\"$ad_gotourl\" align=\"$align\" vspace=\"2\"></a>\n";

    }
}

function ico_pic($value,$align="left") {
    global $image_dir;

    echo "<img src=\"$image_dir/icons/checked2.gif\" border=\"0\" alt=\"\" align=\"$align\" vspace=\"2\"></a>\n";

}

function ico_friend($value,$align="left") {
    global $ad_sendlink,$image_dir;

    echo "   <a href=\"sendmail.php?value\" onClick='enterWindow=window.open(\"sendmail.php?".sidstr()."$value\",\"EMail\",\"width=600,height=430,top=100,left=100,scrollbars=yes,resizable=yes,toolbar=no,directories=no,status=no,menubar=no\"); return false'
        onmouseover=\"window.status='$ad_sendlink'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/lightbulb2.gif\" border=\"0\" alt=\"$ad_sendlink\" align=\"$align\" vspace=\"2\"></a>\n";

}

function ico_print($value,$align="left") {
    global $ad_print,$image_dir;

    echo "   <a href=\"javascript:window.print()\"
        onClick='javascript:window.print();'
        onmouseover=\"window.status='$ad_print'; return true;\"
            onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/print.gif\" border=\"0\" alt=\"$ad_print\" align=\"$align\" vspace=\"2\"></a>\n";

}

function ico_favorits($value,$align="left") {
    global $ad_favorits,$image_dir;

    echo "   <a href=\"favorits.php?$value\" onClick='enterWindow=window.open(\"favorits.php?".sidstr()."$value\",\"Window\",\"width=400,height=200,top=200,left=200\"); return false'
            onmouseover=\"window.status='$ad_favorits'; return true;\"
        onmouseout=\"window.status=''; return true;\">
            <img src=\"$image_dir/icons/checked.gif\" border=\"0\" alt=\"$ad_favorits\" align=\"$align\" vspace=\"2\"></a>\n";

}

function ico_adrating($value,$align="left") {
    global $ad_rating,$image_dir;

    echo "   <a href=\"adrating.php?$value\" onClick='enterWindow=window.open(\"adrating.php?".sidstr()."$value\",\"Window\",\"width=180,height=180,top=200,left=200\"); return false'
        onmouseover=\"window.status='$ad_rating'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/handup.gif\" border=\"0\" alt=\"$ad_rating\" align=\"$align\" vspace=\"2\"></a>\n";

}

function ico_info($value,$align="left") {
    global $ad_member,$image_dir;

    echo "   <a href=\"members.php?$value\" onmouseover=\"window.status='$ad_member'; return true;\"
        onmouseout=\"window.status=''; return true;\">
        <img src=\"$image_dir/icons/info.gif\" border=\"0\" alt=\"$ad_member\" align=\"$align\" vspace=\"2\"></a>\n";

}

function sidstr () {
    global $PHPSESSID;

    if (!empty($_COOKIE['PHPSESSID']) || !$PHPSESSID) {
        return "";
    } else {
    return "PHPSESSID=$PHPSESSID&";
    }

}

function requesturi () {
    return substr($_SERVER['REQUEST_URI'],(strrpos($_SERVER['PHP_SELF'],"/")+1));
}

function headerstr ($target) {
    global $url_to_start,$PHPSESSID;

    if (substr($_SERVER['SERVER_SOFTWARE'],0,6)=="Apache") {
    if ($_COOKIE['PHPSESSID']) {
        $headerstr="Location: ".$url_to_start."/".$target;
    } else { // PHPSESSID Var needed
        if (strpos($target,"?") && strpos($target,"PHPSESSID")) {
        $headerstr="Location: ".$url_to_start."/".$target;
        } elseif (strpos($target,"?")) {
        $headerstr="Location: ".$url_to_start."/".$target."&PHPSESSID=$PHPSESSID";
        } else {
        $headerstr="Location: ".$url_to_start."/".$target."?PHPSESSID=$PHPSESSID";
        }
    }
    } else {  // else use Refresh Method
    if ($_COOKIE['PHPSESSID']) {
        $headerstr="Refresh: 0;url=".$url_to_start."/".$target;
    } else { // PHPSESSID Var needed
        if (strpos($target,"?") && strpos($target,"PHPSESSID")) {
        $headerstr="Refresh: 0;url=".$url_to_start."/".$target;
        } elseif (strpos($target,"?")) {
        $headerstr="Refresh: 0;url=".$url_to_start."/".$target."&PHPSESSID=$PHPSESSID";
        } else {
        $headerstr="Refresh: 0;url=".$url_to_start."/".$target."?PHPSESSID=$PHPSESSID";
        }
    }
    }

    #echo $headerstr;exit;
    return $headerstr;

}

function login ($username, $password) {
    global $prefix,$secret,$error,$timestamp,$login_time,$usecryptpass,$usenoalphanum,$webmail_enable,$webmail_notifypopup,$limited;

    if (!$username || !$password) {
        return $error[14];
    } else {
    if (!eregi("^[[:alnum:]_-]+$", $username) && !$usenoalphanum) {
        return $error[3];
    }
    if (!eregi("^[[:alnum:]_-]+$", $password) && !$usenoalphanum) {
        return $error[7];
    }

    if ($limited) {
        $query = mysql_query("SELECT id FROM ".$prefix."sessions");
        $result = mysql_num_rows($query);
        if ($result >= 1) return "Invalid License ! <br>Login limited to 1 User";
    }

    if ($usecryptpass) { $md5query=" OR password = '".md5($password)."'"; }
    $query = mysql_query("SELECT id FROM ".$prefix."userdata WHERE username = '$username' AND (password = '$password'".$md5query.") AND language<>'xc' AND language<>'xd'");
    $result = mysql_num_rows($query);

    if ($result < 1) {
        logging("X","","$username","AUTH: bad login","Password: $password");
        return $error[26]; //Not found
    } else {
        $expirestamp=$timestamp+$login_time;
        list ($id) = mysql_fetch_row($query);
        setsessionvar($id);

        if ($webmail_enable && $webmail_notifypopup) {
        $_SESSION[susernewmails]= checknewmails($id);
        }

        mysql_query("UPDATE ".$prefix."userdata SET lastlogin='$timestamp' WHERE id='$id'");
        mysql_query("DELETE FROM ".$prefix."sessions WHERE (id='".session_id()."' OR userid='$id' OR expirestamp<'$timestamp')");
        mysql_query("INSERT INTO ".$prefix."sessions (id,userid,username,createstamp,expirestamp) VALUES ('".session_id()."','$id','$username','$timestamp','$expirestamp')");

        logging("X","$_SESSION[suserid]","$_SESSION[susername]","AUTH: login","");
        return 2;
    }
    }
}

function logout () {
    global $prefix;

    mysql_query("DELETE FROM ".$prefix."sessions WHERE (userid='$_SESSION[suserid]' OR expirestamp<'$timestamp')");
    logging("X","$_SESSION[suserid]","$_SESSION[susername]","AUTH: logout","");
    // Unset all of the session variables.
    session_unset();
    // Finally, destroy the session.
    session_destroy();

}

function setsessionvar($userid) {
    global $prefix;

    $query = mysql_query("SELECT id,username,password,level,language,lastvotedate,lastvote,lastaddate,lastad,lastlogin,timezone,dateformat FROM ".$prefix."userdata WHERE id='$userid'");
    list ($id,$username,$password,$level,$language,$lastvotedate,$lastvote,$lastaddate,$lastad,$lastlogin,$timezone,$dateformat) = mysql_fetch_row($query);

    $_SESSION[suserid]=$id;
    $_SESSION[susername]=$username;
    $_SESSION[suserpass]=md5($password);
    $_SESSION[suserlevel]=$level;
    $_SESSION[susermod]= ($level>7) ? true : false ;
    $_SESSION[suserlanguage]=$language;
    $_SESSION[suserlastlogin]=$lastlogin;
    $_SESSION[suserlastvote]=$lastvote;
    $_SESSION[suserlastad]=$lastad;
    $_SESSION[susertimezone]=$timezone;
    $_SESSION[suserdateformat]=$dateformat;

}

function checknewads($userid){
    global $prefix,$timestamp;

    if ($_SESSION[suserlastlogin]) {
    $query = mysql_query("SELECT id FROM ".$prefix."ads WHERE userid<>'$userid' AND (UNIX_TIMESTAMP(addate)>$_SESSION[suserlastlogin])");
    $result = mysql_num_rows($query);
    if ($result >= 1) {
            return $result;
    }
    }
    return false;

}

function checknewmails($userid){
    global $prefix,$timestamp;

    if ($_SESSION[suserlastlogin]) {
    $query = mysql_query("SELECT id FROM ".$prefix."webmail WHERE toid='$_SESSION[suserid]' AND (timestamp>$_SESSION[suserlastlogin])");
    $result = mysql_num_rows($query);
    if ($result >= 1) {
            return $result;
    }
    }
    return false;

}

function extract_superglobal($dataname,$inputs)
{
   /*what we do here: we take name (gotten from database), input (from post, get,cookie,session), and see if that data name exists in
    super global array key, if it does, return the value, if not, return null, since it isnt used anyway..
    Because config table may also contain needed vars, we need to check if it is empty also- because we dont want to nullify local vars
    this check needs to be done before this is called.
*/
    (!empty($inputs[$dataname]))?$return_value = $inputs[$dataname]:$return_value="";

    return $return_value;
}

function memory_checkpoint($line,$file,$memory_array)
{
       /* This function checks memory usage at points where called, typically just before and jsut after an include and
stores the info in an array keyed by filename  $memory_array('filename'=>array($line=>$memory_usage,$line=>$memory_usage))
and returns the whole array, which whenever we want, we log to a file using memory log function.
Why dont we store to db or just write results? because this *adds* processing time and will obviously affect benchmarking
We know this will add memory usage, but if we know this function is only loaded once , and we know the array size and etc, we can determine just how much
memory is used by this function and deduct it from benchmarks, although there is little need.
*/
    $memcheck = memory_get_usage();
    if(!array_key_exists($file,$memory_array))
    {
        $memory_array[$file] = array();
    }
    array_push($memory_array[$file],array($line=>$memcheck));
    return $memory_array;
}
function write_memory_log($memory_array,$parse_time = 0)
{
    /* OK for memory log, we take the array we are fed, and iterate through the array by filename, and store this memory information
to our log, started and ended by parse time figures so we know how much time is taken to do this function
and write the log file. because we are gonna be doing benchmarking tests, we will not be using these functions while running apachebench
although I'd love to- but we can get a very good idea of teh state of the current code from these .
log format is :
filename  timestamp
  \t  line number   memory use \n

 */
$stamp = date("Y-m-d H:i:s");

    $hdl = fopen("scratch_dir/memory.log","a+");
    foreach($memory_array as $file => $sizearr)
    {
        $res = fwrite($hdl,"$file -- $stamp\n");
        //var_dump($sizearr);
        foreach($sizearr as $point)
        {
            foreach($point as $line=>$size)
            {
                $sizea = round($size/1024);
                $res2 = fwrite($hdl,"\tLine:$line \tKbytes:$sizea\n");
            }
        }

    }
    $entime = microtime();
    $etime = $entime - $parse_time;
     $res3 = fwrite($hdl,"$stamp ptime:$etime\n");
    fclose($hdl);
    return "Result: $res  $res2";
}
function parse_timer_log($parse_time,$page_name)
{
/*  This function takes the start and end times and the page name and writes a log of page parse time per request.
While this will very likely affect performance and benchmarking, we do want to log this so we can study how page parse times differ, especially
using apachebench- becasue we can get thuosands of entries and monitor how parse time will "ramp up" and differ between page loads.
which will be another excellent performance indicator.
log format is  Y-m-d H:i:s -- page_name -- parse_time \n

*/
$stamp = date("Y-m-d H:i:s");
$hdl = fopen("scratch_dir/time.log","a+");
$res = fwrite($hdl,"$stamp\t$page_name\t $parse_time\n");
fclose($hdl);
return $res;
}

?>