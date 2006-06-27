<?
#################################################################################################
#
#  project           	: Logix Classifieds
#  filename          	: member_submit.php
#  last modified by  	: 
#  e-mail            	: support@phplogix.com
#  purpose           	: Update Member's Data
#
#################################################################################################

require("library.php");

if ($_POST[homepage]=="http://") {$_POST[homepage]="";}
if (!memberfield("1","sex","","")) {$_POST[sex]="n";}

if ($_SESSION[suserid]) {

    // Input Content Check
    # if ($firstname && (!eregi("^[a-z ]+$", $firstname))) {
    #	$m_update=$error[8];
    # }
    # if ($lastname && (!eregi("^[[:alnum:]_-]+$", $lastname))) {
    #	$m_update=$error[8];
    # }
    if (ereg("[^0-9]", $icq)) {
	$m_update.=$error[10]."<BR>";
    }
    if ($sex == "") {
        $m_update.=$error[11]."<BR>";
    }

    $query = mysql_query("select * FROM ".$prefix."config WHERE type='member' AND name<>'picture' AND name<>'newsletter' AND name<>'sex'") or died(mysql_error());
    while ($db = mysql_fetch_array($query)) {
	$fieldname=$db[name];
        $requirederror.= memberfieldinputcheck ("$fieldname",$_POST[$fieldname]);
    }
    if ($requirederror) {$m_update.=$error[14]."<BR>( ".$requirederror.")<BR>";}

    // If Check OK - store in DB
    if (!$m_update) {

	// Picture Handling
	if ($_FILES["picture_add"]["name"] && $convertpath && $pic_enable) {

	    if ($_FILES["picture_add"]["error"]=="1") { died ($_FILES["picture_add"]["name"]." is larger than PHP upload_max_filesize"); }
	    if ($_FILES["picture_add"]["error"]=="2") { died ($_FILES["picture_add"]["name"]." is too large!<br>(max. $pic_maxsize Byte)"); }

	    if ($_FILES["picture_add"]["tmp_name"]) {
		//get info
    		$picinfo=GetImageSize($_FILES["picture_add"]["tmp_name"]);
        	if ($picinfo[2] == "1" || $picinfo[2] == "2" || $picinfo[2] == "3") {
	            switch ($picinfo[2]) {
		        case 1 : $ext=".gif"; $type="image/gif"; break;
		        case 2 : $ext=".jpg"; $type="image/jpeg"; break;
		        case 3 : $ext=".png"; $type="image/png"; break;
		    }

		} else {
		    died ($_FILES["picture_add"]["name"]." Wrong Filetype! Only .gif, .jpg, .png Files supported");
		}

    		if (strtoupper($convertpath) == "AUTO") {   // simple file handling without convert
		    $picture = $timestamp.$ext;
		    if ($pic_database) {
			if (!move_uploaded_file_todb($_FILES["picture_add"]["tmp_name"],$picture,$type)) { died ("Could NOT copy the file!"); }
		    } else {
			if (!move_uploaded_file($_FILES["picture_add"]["tmp_name"], "$bazar_dir/$pic_path/$picture")) { died ("Could NOT copy the file!<br>($bazar_dir/$pic_path/$picture)"); }
		    }
    		} elseif (strtoupper($convertpath) == "GDLIB") {   // advanced file handling with gdlib
		    $picture=$timestamp.".jpg";
		    $_picture="_".$timestamp.".jpg";
		    resizeimage($_FILES["picture_add"]["tmp_name"],"$bazar_dir/$pic_path/$picture", $pic_res, $pic_quality);
		    resizeimage($_FILES["picture_add"]["tmp_name"],"$bazar_dir/$pic_path/$_picture", $pic_lowres, $pic_quality);
		    if ($pic_database) {
			if (!move_uploaded_file_todb("$bazar_dir/$pic_path/$picture",$picture,$type)) { died ("Could NOT copy the file!"); }
			suppr("$bazar_dir/$pic_path/$picture");
			if (!move_uploaded_file_todb("$bazar_dir/$pic_path/$_picture",$_picture,$type)) { died ("Could NOT copy the file!"); }
			suppr("$bazar_dir/$pic_path/$_picture");
		    }
		} elseif ($convertpath) { 			// advanced file handling with convert
            	    if (!is_file($convertpath)) { died ("Convertpath is wrong! Check configuration"); }
		    if (!move_uploaded_file($_FILES["picture_add"]["tmp_name"], "$bazar_dir/$pic_path/temp$ext")) { died ("Could NOT copy the file!<br>($bazar_dir/$pic_path/$picture)"); }
		    $picture=$timestamp.$ext;
                    $convertstr=" -scale $pic_res -quality $pic_quality $bazar_dir/$pic_path/temp$ext $bazar_dir/$pic_path/$picture";
                    exec($convertpath.$convertstr);
		    $_picture="_".$timestamp.$ext;
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

	    }
	}

	if ($picture_del) {$picture=""; $_picture="";}
	// Database Update
	$query = mysql_query("update ".$prefix."userdata
						    set sex = '$_POST[sex]',
				                    newsletter = '$_POST[newsletter]',
						    firstname = '$_POST[firstname]',
						    lastname = '$_POST[lastname]',
						    address = '$_POST[address]',
						    zip = '$_POST[zip]',
						    city = '$_POST[city]',
						    state = '$_POST[state]',
						    country = '$_POST[country]',
						    phone = '$_POST[phone]',
						    cellphone = '$_POST[cellphone]',
						    icq = '$_POST[icq]',
						    homepage = '$_POST[homepage]',
						    hobbys = '$_POST[hobbys]',
                                                    picture= '$picture',
                                                    _picture= '$_picture',
						    field1 = '$_POST[field1]',
						    field2 = '$_POST[field2]',
						    field3 = '$_POST[field3]',
						    field4 = '$_POST[field4]',
						    field5 = '$_POST[field5]',
						    field6 = '$_POST[field6]',
						    field7 = '$_POST[field7]',
						    field8 = '$_POST[field8]',
						    field9 = '$_POST[field9]',
						    field10 = '$_POST[field10]',
						    timezone = '$_POST[timezone]',
						    dateformat = '$_POST[dateformat]'
			    where id = '$_SESSION[suserid]'") or died (mysql_error());


	$_SESSION[susertimezone]=$_POST[timezone];
	$_SESSION[suserdateformat]=$_POST[dateformat];
	logging("X","$_SESSION[suserid]","$_SESSION[susername]","AUTH: updated data","");

	if (!$query) {
	    $m_update=$error[20];
	} else {
	    $m_update=2;
	}

    }


    if ($m_update != 2) {
	died ($m_update);
#       $errormessage=rawurlencode($m_update);
#	header(headerstr("members.php?choice=myprofile&status=6&errormessage=$errormessage"));
	exit;
    } else {
	header(headerstr("members.php?choice=myprofile&status=5"));
	exit;
    }
}
?>