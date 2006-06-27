<?
#################################################################################################
#
#  project                : Logix Classifieds
#  filename               : classified_upd_submit.php
#  last modified by       :
#  e-mail                 : support@phplogix.com
#  purpose                : Submit Ad-Data
#
#################################################################################################


#  Include Configs & Variables
#################################################################################################
require ("library.php");

if ($logging_enable && $floodprotect && $floodprotect_ad && $_SESSION[suserid] && !$_SESSION[susermod] && !$editadid) { // check floodprotect
    $checktimestamp = $timestamp-(3600*$floodprotect);
    $result = mysql_query("SELECT timestamp FROM ".$prefix."logging WHERE event='AD: new' AND username='$_SESSION[susername]' AND timestamp>'$checktimestamp'") or died("Database Query Error".mysql_error());
    $count=mysql_num_rows($result);
    if ($floodprotect_ad<=$count) {
       died ("Floodprotect active !!! $count events logged last $floodprotect hour(s)");
    }
}

#  Picture Handling
#################################################################################################

    for ($i=1;$i<=5;$i++) {
       if ($_FILES["picture".$i."add"]["name"] && $convertpath && $pic_enable) {

           if ($_FILES["picture".$i."add"]["error"]=="1") { died ($_FILES["picture".$i."add"]["name"]." is larger than PHP upload_max_filesize"); }
           if ($_FILES["picture".$i."add"]["error"]=="2") { died ($_FILES["picture".$i."add"]["name"]." is too large!<br>(max. $pic_maxsize Byte)"); }

           if ($_FILES["picture".$i."add"]["tmp_name"]) {
              //get info
                  $picinfo=GetImageSize($_FILES["picture".$i."add"]["tmp_name"]);
               if ($picinfo[2] == "1" || $picinfo[2] == "2" || $picinfo[2] == "3") {
                   switch ($picinfo[2]) {
                      case 1 : $ext=".gif"; $type="image/gif"; break;
                      case 2 : $ext=".jpg"; $type="image/jpeg"; break;
                      case 3 : $ext=".png"; $type="image/png"; break;
                  }

              } else {
                  died ($_FILES["picture".$i."add"]["name"]." Wrong Filetype! Only .gif, .jpg, .png Files supported");
              }

                  if (strtoupper($convertpath) == "AUTO") {   // simple file handling without convert
                  $picture=$timestamp.$i.$ext;
                  $_picture="";
                  if ($pic_database) {
                     if (!move_uploaded_file_todb($_FILES["picture".$i."add"]["tmp_name"],$picture,$type)) { died ("Could NOT copy the file!"); }
                  } else {
                     if (!move_uploaded_file($_FILES["picture".$i."add"]["tmp_name"], "$bazar_dir/$pic_path/$picture")) { died ("Could NOT copy the file!<br>($bazar_dir/$pic_path/$picture)"); }
                  }
                  } elseif (strtoupper($convertpath) == "GDLIB") {   // advanced file handling with gdlib
                  $picture=$timestamp.$i.".jpg";
                  $_picture="_".$timestamp.$i.".jpg";
                  resizeimage($_FILES["picture".$i."add"]["tmp_name"],"$bazar_dir/$pic_path/$picture", $pic_res, $pic_quality);
                  resizeimage($_FILES["picture".$i."add"]["tmp_name"],"$bazar_dir/$pic_path/$_picture", $pic_lowres, $pic_quality);
                  if ($pic_database) {
                     if (!move_uploaded_file_todb("$bazar_dir/$pic_path/$picture",$picture,$type)) { died ("Could NOT copy the file!"); }
                     suppr("$bazar_dir/$pic_path/$picture");
                     if (!move_uploaded_file_todb("$bazar_dir/$pic_path/$_picture",$_picture,$type)) { died ("Could NOT copy the file!"); }
                     suppr("$bazar_dir/$pic_path/$_picture");
                  }
              } elseif ($convertpath){           // advanced file handling with convert
                  #if (!is_file($convertpath)) { died ("Convertpath is wrong! Check configuration"); }
                  if (!move_uploaded_file($_FILES["picture".$i."add"]["tmp_name"], "$bazar_dir/$pic_path/temp$ext")) { died ("Could NOT copy the file!<br>($bazar_dir/$pic_path/$picture)"); }
                  $picture=$timestamp.$i.$ext;
                    $convertstr=" -scale $pic_res -quality $pic_quality $bazar_dir/$pic_path/temp$ext $bazar_dir/$pic_path/$picture";
                    exec($convertpath.$convertstr);
                  $_picture="_".$timestamp.$i.$ext;
                    $_convertstr=" -scale $pic_lowres -quality $pic_quality $bazar_dir/$pic_path/temp$ext $bazar_dir/$pic_path/$_picture";
                    exec($convertpath.$_convertstr);
                  suppr("$bazar_dir/$pic_path/temp$ext");
                  if ($pic_database) {
                     if (!move_uploaded_file_todb("$bazar_dir/$pic_path/$picture",$picture,$type)) { died ("Could NOT copy the file!"); }
                     suppr("$bazar_dir/$pic_path/$picture");
                     if (!move_uploaded_file_todb("$bazar_dir/$pic_path/$_picture",$_picture,$type)) { died ("Could NOT copy the file!"); }
                     suppr("$bazar_dir/$pic_path/$_picture");
                  }
              }

              $in["picture".$i]=$picture;
              $in["_picture".$i]=$_picture;

           }
       }

       if ($_POST["picture".$i."del"]) {
           $in["picture".$i]="";
           $in["_picture".$i]="";
       }
    }


#  Attachment Handling
#################################################################################################


    for ($i=1;$i<=5;$i++) {

       if ($_FILES["attachment".$i."add"]["name"] && $att_enable) {

           if ($_FILES["attachment".$i."add"]["error"]=="1") { died ($_FILES["attachment".$i."add"]["name"]." is larger than PHP upload_max_filesize"); }
           if ($_FILES["attachment".$i."add"]["error"]=="2") { died ($_FILES["attachment".$i."add"]["name"]." is too large!<br>(max. $att_maxsize Byte)"); }

           if ($_FILES["attachment".$i."add"]["tmp_name"]) {
              $temp=explode(".",$_FILES["attachment".$i."add"]["name"]);
              $attinfo=$temp[(count($temp)-1)];
              if ($attinfo == "pdf" || $attinfo == "doc" || $attinfo == "txt") {
                      switch ($attinfo) {
                         case "pdf" : $ext=".pdf"; $type="application/pdf"; break;
                         case "doc" : $ext=".doc"; $type="application/doc"; break;
                     case "txt" : $ext=".txt"; $type="text/txt"; break;
                  }
              } else {
                  died ("Wrong Filetype! Only .pdf, .doc, .txt Files supported");
              }

              $attachment=$timestamp.$i.$ext;
              if (!move_uploaded_file($_FILES["attachment".$i."add"]["tmp_name"], "$bazar_dir/$att_path/$attachment")) { died ("Could NOT copy the file!<br>($bazar_dir/$att_path/$attachment)"); }
              $in["attachment".$i]=$attachment;

           }

       }

       if ($_POST["attachment".$i."del"]) {
           $in["attachment".$i]="";
       }
    }

#  Text Handling
#################################################################################################
$query = mysql_query("select * FROM ".$prefix."config WHERE type='cat' AND value='$in[catid]'");
while ($db = mysql_fetch_array($query)) {
    $fieldname=$db[name];
    $requirederror.= adfieldinputcheck ($in[catid],"$fieldname",$in[$fieldname]);
}

if (!$in[location] || !$in[header] || !$in[text] || $requirederror) {
    died($error[14]);
} else {

    if (isbanned($_SESSION[suserid])) {
           $error=rawurlencode($error[27]);
        header(headerstr("classified.php?status=6&errormessage=$error"));
        exit;
    }

    if (strlen($in['text']) < $limit["0"] || strlen($in['text']) > $limit["1"]) {
       died("Sorry, your text has to be between $limit[0] and $limit[1] characters.");
    }

    $in = strip_array($in);
    $in[text]=encode_msg($in[text]);

    if ($in[icon1]=="on")  {$in[icon1]=1;}  else {$in[icon1]=0;}
    if ($in[icon2]=="on")  {$in[icon2]=1;}  else {$in[icon2]=0;}
    if ($in[icon3]=="on")  {$in[icon3]=1;}  else {$in[icon3]=0;}
    if ($in[icon4]=="on")  {$in[icon4]=1;}  else {$in[icon4]=0;}
    if ($in[icon5]=="on")  {$in[icon5]=1;}  else {$in[icon5]=0;}
    if ($in[icon6]=="on")  {$in[icon6]=1;}  else {$in[icon6]=0;}
    if ($in[icon7]=="on")  {$in[icon7]=1;}  else {$in[icon7]=0;}
    if ($in[icon8]=="on")  {$in[icon8]=1;}  else {$in[icon8]=0;}
    if ($in[icon9]=="on")  {$in[icon9]=1;}  else {$in[icon9]=0;}
    if ($in[icon10]=="on") {$in[icon10]=1;} else {$in[icon10]=0;}

    if ($editadid) {

       if ($adeditapproval) {$publicview=-1; $textmessage=$text_msg[1]; } else {$publicview=1;}

       if ($pic1del) {$in[picture]=""; $in[_picture]="";}
       if ($pic2del) {$in[picture2]=""; $in[_picture2]="";}
       if ($pic3del) {$in[picture3]=""; $in[_picture3]="";}
       if ($att1del) {$in[att1]="";}
       if ($att2del) {$in[att2]="";}
       if ($att3del) {$in[att3]="";}

        mysql_query("UPDATE ".$prefix."ads SET userid='$in[userid]',
                                              catid='$in[catid]',
                                              subcatid='$in[subcatid]',
                                              adeditdate=now(),
                                              ip='$ip',
                                              durationdays='$in[duration]',
                                              location='$in[location]',
                                              header='$in[header]',
                                              text='$in[text]',
                                              _picture1='$in[_picture1]',
                                              picture1='$in[picture1]',
                                              _picture2='$in[_picture2]',
                                              picture2='$in[picture2]',
                                              _picture3='$in[_picture3]',
                                              picture3='$in[picture3]',
                                              _picture4='$in[_picture4]',
                                              picture4='$in[picture4]',
                                              _picture5='$in[_picture5]',
                                              picture5='$in[picture5]',
                                              attachment1='$in[attachment1]',
                                              attachment2='$in[attachment2]',
                                              attachment3='$in[attachment3]',
                                              attachment4='$in[attachment4]',
                                              attachment5='$in[attachment5]',
                                              sfield='$in[sfield]',
                                              field1='$in[field1]',
                                              field2='$in[field2]',
                                              field3='$in[field3]',
                                              field4='$in[field4]',
                                              field5='$in[field5]',
                                              field6='$in[field6]',
                                              field7='$in[field7]',
                                              field8='$in[field8]',
                                              field9='$in[field9]',
                                              field10='$in[field10]',
                                              field11='$in[field11]',
                                              field12='$in[field12]',
                                              field13='$in[field13]',
                                              field14='$in[field14]',
                                              field15='$in[field15]',
                                              field16='$in[field16]',
                                              field17='$in[field17]',
                                              field18='$in[field18]',
                                              field19='$in[field19]',
                                              field20='$in[field20]',
                                              icon1='$in[icon1]',
                                              icon2='$in[icon2]',
                                              icon3='$in[icon3]',
                                              icon4='$in[icon4]',
                                              icon5='$in[icon5]',
                                              icon6='$in[icon6]',
                                              icon7='$in[icon7]',
                                              icon8='$in[icon8]',
                                              icon9='$in[icon9]',
                                              icon10='$in[icon10]',
                                              publicview='$publicview'
                                              WHERE id=$editadid") or died("Database Query Error");

        logging("X","$_SESSION[suserid]","$_SESSION[susername]","AD: changed","Cat: $in[catid] ($in[cat]) - Subcat: $in[subcatid] ($in[subcat]) - Ad: $editadid, Header: $in[header]");

        if ($aded_notify) {
           $mailto = "$aded_notify";
            $from = "From: $admin_email";
           $subject = "NOTIFY edited AD from $in[uname]";
            $message = "User: $in[userid] ($in[uname])\nCat: $in[catid] ($in[cat])\nSubcat: $in[subcatid] ($in[subcat])\nLocation: $in[location]\nHeader: $in[header]\n\nText: $in[text]";
           @mail($mailto, $subject, $message, $from);
       }


    } else {
       if ($adapproval) {$publicview=0;} else {$publicview=1;}
        mysql_query("INSERT INTO ".$prefix."ads (userid, catid, subcatid, addate, adeditdate, ip, durationdays, location,
        header, text, _picture1, picture1, _picture2, picture2, _picture3, picture3, _picture4, picture4, _picture5, picture5,
       attachment1, attachment2, attachment3, attachment4, attachment5, sfield, field1, field2, field3, field4, field5, field6,
       field7, field8, field9, field10, field11, field12, field13, field14, field15, field16, field17, field18, field19, field20,
       icon1, icon2, icon3, icon4, icon5, icon6, icon7, icon8, icon9, icon10, publicview)
        VALUES('$in[userid]', '$in[catid]','$in[subcatid]',now(),now(),'$ip','$in[duration]','$in[location]',
        '$in[header]','$in[text]','$in[_picture1]','$in[picture1]','$in[_picture2]','$in[picture2]','$in[_picture3]','$in[picture3]',
       '$in[_picture4]','$in[picture4]','$in[_picture5]','$in[picture5]','$in[attachment1]','$in[attachment2]','$in[attachment3]',
       '$in[attachment4]','$in[attachment5]','$in[sfield]','$in[field1]','$in[field2]','$in[field3]',
        '$in[field4]','$in[field5]','$in[field6]','$in[field7]','$in[field8]','$in[field9]','$in[field10]',
       '$in[field11]','$in[field12]','$in[field13]',
        '$in[field14]','$in[field15]','$in[field16]','$in[field17]','$in[field18]','$in[field19]','$in[field20]',
        '$in[icon1]','$in[icon2]','$in[icon3]','$in[icon4]','$in[icon5]','$in[icon6]','$in[icon7]','$in[icon8]',
        '$in[icon9]','$in[icon10]','$publicview')") or died("Database Query Error");

       $newadid=mysql_insert_id();
       $newcatid=$in[catid];
       $newsubcatid=$in[subcatid];

        if (!$adapproval) {
           $textmessage="";
           mysql_query("update ".$prefix."adcat set ads=ads+1 where id='$in[catid]'") or died("Database Query Error");
            mysql_query("update ".$prefix."adsubcat set ads=ads+1,notify='1' where id='$in[subcatid]'") or died("Database Query Error");
           mysql_query("update ".$prefix."userdata set ads=ads+1,lastaddate=now(),lastad=$timestamp where id='$in[userid]'") or died("Database Query Error");
           $_SESSION[suserlastad]=$timestamp;
       } else {
           $textmessage=$text_msg[1];
       }

        if ($ad_notify) {
           $mailto = "$ad_notify";
            $from = "From: $admin_email";
           $subject = "NOTIFY new AD from $in[uname]";
            $message = "User: $in[userid] ($in[uname])\nCat: $in[catid] ($in[cat])\nSubcat: $in[subcatid] ($in[subcat])\nLocation: $in[location]\nHeader: $in[header]\n\nText: $in[text]";
               @mail($mailto, $subject, $message, $from);
       }

       if ($sales_option) {
            sales_countevent(2,$in[userid],$in[catid]);
        }

        logging("X","$_SESSION[suserid]","$_SESSION[susername]","AD: new","Cat: $in[catid] ($in[cat]) - Subcat: $in[subcatid] ($in[subcat]) - Header: $in[header]");

    }


    #  some functions at update time
    #################################################################################################

    if ($timeoutnotify>0) {
       $sql = "select * FROM ".$prefix."ads WHERE (TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays-'$timeoutnotify')) AND timeoutdays<'$timeoutmax' AND deleted!='1' AND publicview='1' AND timeoutnotify=''";
       $result = mysql_query($sql) or died("Database Query Error");
       while ($db = mysql_fetch_array($result)) {
           $result2 = mysql_query("SELECT * FROM ".$prefix."userdata WHERE id=$db[userid]") or died("Record NOT Found:".mysql_error());
           $dbu = mysql_fetch_array($result2);
           $mdhash=md5($timestamp.$db[header].$db[id].$secret);
           $mailto = "$dbu[email]";
            $from = "From: $admin_email";
           $subject = "$mail_msg[19]";
            $message = "$mail_msg[20]\nID: $db[id] ($db[header])\n\n$mail_msg[21]$url_to_start/confirm_ad.php?id=$db[id]&hash=$mdhash\n\n$mail_msg[22]";
               @mail($mailto, $subject, $message, $from);
           mysql_query("UPDATE ".$prefix."ads SET timeoutnotify='$mdhash' WHERE id='$db[id]'")
           or died("Database Query Error - userdata");
       }
    }

    if ($adautoflush) {
       if ($really_del_memb) {
           $result = mysql_query("select * FROM ".$prefix."ads WHERE (TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays)) OR deleted='1'")
                         or died("Database Query Error");
       } else {
           $result = mysql_query("select * FROM ".$prefix."ads WHERE TO_DAYS(addate)<TO_DAYS(now())-(durationdays+timeoutdays)")
                         or died("Database Query Error");
       }
       while ($db = mysql_fetch_array($result)) {

           // Subtract Counter in userdata-DB
           mysql_query("update ".$prefix."userdata set ads=ads-1 where id='$db[userid]'")
           or died("Database Query Error - userdata");

           // Subtract Counter in adcat-DB
           mysql_query("update ".$prefix."adcat set ads=ads-1 where id='$db[catid]'")
           or died("Database Query Error - adcat");

           // Subtract Counter in adsubcat-DB
           mysql_query("update ".$prefix."
           adsubcat set ads=ads-1 where id='$db[subcatid]'")
           or died("Database Query Error - adsubcat");

           // Delete Pictures if any ...
               for ($i=1;$i<=5;$i++) {
               $fieldname="picture".$i;
               $_fieldname="_picture".$i;

                  if (!$pic_database && $db[$fieldname] && is_file("$bazar_dir/$pic_path/$db[$fieldname]")) {
                   suppr("$bazar_dir/$pic_path/$db[$fieldname]");
                  } elseif ($db[$fieldname]) {
                  mysql_query("delete from ".$prefix."pictures where picture_name = '$db[$fieldname]'") or died("Database Query Error");
              }
                  if (!$pic_database && $db[$_fieldname] && is_file("$bazar_dir/$pic_path/$db[$_fieldname]")) {
                   suppr("$bazar_dir/$pic_path/$db[$_fieldname]");
                  } elseif ($db[$_fieldname]) {
                  mysql_query("delete from ".$prefix."pictures where picture_name = '$db[$_fieldname]'") or died("Database Query Error");
                  }
           }

           // Delete Attachments if any ...
               for ($i=1;$i<=5;$i++) {
               $fieldname="attachment".$i;
                  if ($db[$fieldname] && is_file("$bazar_dir/$att_path/$db[$fieldname]")) {
                   suppr("$bazar_dir/$att_path/$db[$fieldname]");
              }
           }

               // Delete Entry from favorits-DB
           mysql_query("delete from ".$prefix."favorits where adid = '$db[id]'")
           or died("Database Query Error");

           // Delete Entry from ads-DB
           mysql_query("delete from ".$prefix."ads where id = '$db[id]'")
           or died("Database Query Error - ads");

       }
    }

    if ($editadid && !$_SESSION[susermod]) {
       if ($adeditapproval) {
               $locvar="choice=my&status=13&textmessage=".rawurlencode($text_msg[1]);
       } else {
#             $locvar="choice=my&status=13&textmessage=".rawurlencode($text_msg[0]);
               $locvar="choice=my&status=13";
       }
    } else {
       if ($adapproval) {
               $locvar="choice=my&status=13&textmessage=".rawurlencode($text_msg[1]);
       } else {
#             $locvar="catid=$newcatid&subcatid=$newsubcatid&adid=$newadid&status=13&textmessage=".rawurlencode($text_msg[0]);
               $locvar="catid=$newcatid&subcatid=$newsubcatid&adid=$newadid&status=13";
#             $locvar="catid=$newcatid&subcatid=$newsubcatid&status=13";
       }
       if ($force_addad && $HTTP_COOKIE_VARS["ForceAddAd"]==1){
           setcookie("ForceAddAd", "", 0, "$cookiepath"); // delete cookie
       }
    }
    header(headerstr("classified.php?$locvar"));
    exit;
}

?>