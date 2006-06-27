<?php
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : ./admin/install.php
#  e-mail               : support@phplogix.com
#  purpose              : File to install the Tables for Logix Classifieds
#$Id$
#  License: GPL
#################################################################################################


#  Include Configs & Variables
#################################################################################################
require ("../config.php");

$title      = "Logix Classifieds Install/Update-Tool V2.0.x";


if (is_file("../sales_config.php")) {include ("../sales_config.php");}
if (is_file("../picturelib_config.php")) {include ("../picturelib_config.php");}
if(empty($_POST['action']))
{
    $_POST['action'] = " ";
}
#  Function-Declaration
#################################################################################################

function check_phpversion($version) {
    $testVer=intval(str_replace(".", "",$version));
    $curVer=intval(str_replace(".", "",phpversion()));
    if( $curVer < $testVer ) {
    return false;
    } else {
    return true;
    }
}

function check_optversion($version) {
    ob_start();
    phpinfo();
    $o = ob_get_contents();
    ob_end_clean();

    $testVer=intval(str_replace(".", "",$version));
    $curVer=intval(str_replace(".", "",substr(strstr($o,"Zend Optimizer"),16,5)));
    if( $curVer < $testVer ) {
    return false;
    } else {
    return true;
    }
}

function split_sql($sql) {
    $sql = trim($sql);
    $sql = ereg_replace("#[^\n]*\n", "", $sql);
    $buffer = array();
    $ret = array();
    $in_string = false;

    for($i=0; $i<strlen($sql)-1; $i++) {
    if($sql[$i] == ";" && !$in_string) {
            $ret[] = substr($sql, 0, $i);
            $sql = substr($sql, $i + 1);
            $i = 0;
        }
        if($in_string && ($sql[$i] == $in_string) && $buffer[0] != "\\") {
             $in_string = false;
        } elseif(!$in_string && ($sql[$i] == "\"" || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
             $in_string = $sql[$i];
        }
        if(isset($buffer[1])) {
            $buffer[0] = $buffer[1];
        }
        $buffer[1] = $sql[$i];
     }
    if(!empty($sql)) {
        $ret[] = $sql;
    }
    return($ret);
}

function installdb() {
    global $prefix,$db_name;

    mysql_select_db($db_name) or die ("ERROR: ".mysql_error());

    $sql_query = addslashes(fread(fopen("phpBazar.sql", "r"), filesize("phpBazar.sql")));
    $pieces  = split_sql($sql_query);
    if (count($pieces) == 1 && !empty($pieces[0])) {
    echo "Error !!!";
    }

    for ($i=0; $i<count($pieces); $i++) {
    $pieces[$i] = stripslashes(trim($pieces[$i]));
        if(!empty($pieces[$i]) && $pieces[$i] != "#") {
        $pieces[$i]=str_replace("IF EXISTS ","IF EXISTS ".$prefix,$pieces[$i]);
        $pieces[$i]=str_replace("CREATE TABLE ","CREATE TABLE ".$prefix,$pieces[$i]);
        $pieces[$i]=str_replace("INSERT INTO ","INSERT INTO ".$prefix,$pieces[$i]);
        $result = mysql_query ($pieces[$i]);
        if (!$result) {
        echo "Database: [$db_name] - MYSQL-ERROR: ".mysql_error()."<br>Command: ".stripslashes($pieces[$i])."<br>";
            } else {
        echo "Database: [$db_name] - mySQL-command: <b>OK!</b><br>";
        }
        }
    }
    echo "<br><b>Logix Classifieds Tables installed, Ready ...</b>";
}

function alterindex($v_table,$v_command,$v_field) {
    global $db_name;
    if ($v_command=="ADD") {mysql_query("ALTER TABLE $v_table DROP INDEX $v_field");}
    $sql="ALTER TABLE $v_table $v_command INDEX ($v_field)";
    $result = mysql_query($sql);
    if (!$result) {
    echo "Database: [$db_name] - Alteration of Table: $v_table failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Alteration of Table: $v_table -> Index: $v_field <b>ADDED!</b><br>";
    }
    flush();
}



function altertables($v_table,$v_command,$v_field,$v_type) {
    global $db_name;
    $sql="ALTER TABLE $v_table $v_command $v_field $v_type";
    $result = mysql_query($sql);
    if (!$result) {
    echo "Database: [$db_name] - Alteration of Table: $v_table failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Alteration of Table: $v_table -> Field: $v_field <b>OK!</b><br>";
    }
    flush();
}

function updatetables($v_table,$v_field,$v_type) {
    global $db_name;
    $result = mysql_query("UPDATE $v_table SET $v_field $v_type");
    if (!$result) {
    echo "Database: [$db_name] - Update of Table: $v_table failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Update of Table: $v_table -> Fields: $v_field <b>OK!</b><br>";
    }
    flush();
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

#  Start
#################################################################################################
@set_time_limit(1000);

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");              // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");     // always modified
header ("Cache-Control: no-cache, must-revalidate");            // HTTP/1.1
header ("Pragma: no-cache");                                    // HTTP/1.0

echo "
<html>
<head>
<title>$title</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
          <SCRIPT language=javascript><!--
          function jsconfirm() {
          return confirm (\"This action may run a while !\\nYou really want to do this ?\") }
          // --></SCRIPT>

    <style type=\"text/css\">
    body,td         {
                        font-family: Verdana, Arial, Helvetica, sans-serif;
                        font-size: 10pt;
                        font-weight: normal;
                        background:#FCFCFC;
                        color: #111111;
            }
    input           {
                        color:#000000;
                        background: #E2E2E2;
                        font: 10pt Tahoma, Verdana, Arial, Helvetica, sans-serif;
                        font-weight: normal;
                        font-style: normal;
                        margin-left:5px;
                        margin-right:5px;

                        }
    select          {
                        color:#006699;
                        font: 8pt Tahoma, Verdana, Arial, Helvetica, sans-serif;
                        font-weight: none;
                        text-decoration: none;
                        background: #F5FAFF;
                        border: 1 solid #CCCCDD;
                        }

    .footer     {
                        font: 7.5pt Verdana, Arial, Helvetica, sans-serif;
            text-align: center;
            }
    </style>

</head>
";


echo "
<body>
<table align=center><tr><td>
<center><h3>$title</h3></center>
";




@mysql_connect($db_server, $db_user, $db_pass);
if (mysql_select_db($db_name)) {

    @mysql_close();
    $db_connect_id=mysql_connect($db_server, $db_user, $db_pass) or die ("Database connect Error");
    mysql_select_db($db_name);

    if ($_POST['action']=="up1") {
    echo "<b>Logix Classifieds Table Update from #$Id$1.xx to #$Id$1.20</b><p>";

    $v_table    ="ads";
    $v_command  ="ADD";
    $v_field    ="field11";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field10";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field12";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field11";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field13";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field12";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field14";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field13";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field15";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field14";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field16";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field15";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field17";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field16";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field18";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field17";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field19";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field18";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field20";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field19";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="sfield";
    $v_type     ="VARCHAR(50) NOT NULL AFTER picture";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="publicview";
    $v_type     ="SMALLINT(1) NOT NULL DEFAULT 0";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="CHANGE";
    $v_field    ="text";
    $v_type     ="text TEXT NOT NULL";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="CHANGE";
    $v_field    ="adipaddr";
    $v_type     ="ip VARCHAR(40) NOT NULL";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="adcat";
    $v_command  ="ADD";
    $v_field    ="field11";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field10";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field12";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field11";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field13";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field12";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field14";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field13";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field15";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field14";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field16";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field15";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field17";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field16";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field18";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field17";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field19";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field18";

    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field20";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field19";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="sfield";
    $v_type     ="VARCHAR(50) NOT NULL AFTER picture";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="DROP";
    $v_field    ="field1tbl";
    $v_type     ="";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field2tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field3tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field4tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field5tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field6tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field7tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field8tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field9tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field10tbl";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="banned_ips";
    $v_command  ="CHANGE";
    $v_field    ="banned_ip";
    $v_type     ="ip VARCHAR(40) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="guestbook";
    $v_command  ="CHANGE";
    $v_field    ="ip";
    $v_type     ="ip VARCHAR(40) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="userdata";
    $v_command  ="ADD";
    $v_field    ="homepage";
    $v_type     ="VARCHAR(50) NOT NULL AFTER icq";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="ads";
    $v_field    ="publicview='1'";
    $v_type     ="WHERE publicview=0";
    updatetables($v_table,$v_field,$v_type);

    $v_field    ="adeditdate=addate ";
    $v_type     ="WHERE adeditdate LIKE '0000%'";
    updatetables($v_table,$v_field,$v_type);

    $result = mysql_query("CREATE TABLE favorits (userid INT(11) NOT NULL, adid INT(11) NOT NULL)");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: favorits failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: favorits <b>OK!</b><br>";
    }

    $result = mysql_query("CREATE TABLE useronline (timestamp INT(11) NOT NULL, ip VARCHAR(40) NOT NULL, file VARCHAR(50) NOT NULL)");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: useronline failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: useronline <b>OK!</b><br>";
    }

    echo "<br><b>Logix Classifieds Tables Updated, Ready ...</b>";

    } elseif ($_POST['action']=="up2") {
    echo "<b>Logix Classifieds Table Update from #$Id$1.3x to #$Id$1.40</b><p>";

    $v_table    ="adsubcat";
    $v_command  ="ADD";
    $v_field    ="notify";
    $v_type     ="INT(1) DEFAULT '0' NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="votes";
    $v_command  ="ADD";
    $v_field    ="id";
    $v_type     ="INT(1) DEFAULT '0' NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="ads";
    $v_command  ="ADD";
    $v_field    ="_picture2";
    $v_type     ="VARCHAR(50) NOT NULL AFTER picture";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="picture2";
    $v_type     ="VARCHAR(50) NOT NULL AFTER _picture2";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="_picture3";
    $v_type     ="VARCHAR(50) NOT NULL AFTER picture2";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="picture3";
    $v_type     ="VARCHAR(50) NOT NULL AFTER _picture3";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="viewed";
    $v_type     ="INT(14) DEFAULT '0' NOT NULL AFTER subcatid";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="answered";
    $v_type     ="INT(14) DEFAULT '0' NOT NULL AFTER viewed";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="rating";
    $v_type     ="INT(1) DEFAULT '0' NOT NULL AFTER answered";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="ratingcount";
    $v_type     ="INT(5) DEFAULT '0' NOT NULL AFTER rating";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="durationdays";
    $v_type     ="INT(5) DEFAULT '0' NOT NULL AFTER duration";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="deleted";
    $v_type     ="INT(1) DEFAULT '0' NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="timeoutnotify";
    $v_type     ="VARCHAR(32) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="timeoutdays";
    $v_type     ="INT(10) DEFAULT '0' NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $result = mysql_query("UPDATE ads SET durationdays=duration*7");
    if (!$result) {
        echo "Database: [$db_name] - Durationdays Field : failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Durationdays Field : converted <b>OK!</b><br>";
    }

    $result = mysql_query("UPDATE userdata SET sex='m' WHERE sex='male'");
    if (!$result) {
        echo "Database: [$db_name] - Sex Field male: failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Sex Field male: converted <b>OK!</b><br>";
    }

    $result = mysql_query("UPDATE userdata SET sex='f' WHERE sex='female'");
    if (!$result) {
        echo "Database: [$db_name] - Sex Field female: failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Sex Field female: converted <b>OK!</b><br>";
    }


    $result = mysql_query("CREATE TABLE notify (userid INT(11) NOT NULL, subcatid INT(5) NOT NULL)");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: notify failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: notify <b>OK!</b><br>";
    }


    $result = mysql_query("CREATE TABLE pictures (picture_name VARCHAR(50) NOT NULL, picture_type VARCHAR(10) NOT NULL, picture_height VARCHAR(10) NOT NULL, picture_width VARCHAR(10) NOT NULL, picture_size VARCHAR(10) NOT NULL, picture_bin MEDIUMBLOB NOT NULL)");

    if (!$result) {
        echo "Database: [$db_name] - Creation Table: pictures failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: pictures <b>OK!</b><br>";
    }

    $count=1;
    $result = mysql_query("SELECT * FROM votes");
    while ($db = mysql_fetch_array($result)) {
        $result2 = mysql_query("UPDATE votes SET id='$count' WHERE name='$db[name]'");
        $count++;
    }
    echo "Database: [$db_name] - Table: votes <b>OK!</b><br>";

    echo "<br><b>Logix Classifieds Tables Updated, Ready ...</b>";

    } elseif ($_POST['action']=="up3") {
    echo "<b>Logix Classifieds Table Update from #$Id$1.4x to #$Id$1.5x</b><p>";

    $v_table    ="userdata";
    $v_command  ="ADD";
    $v_field    ="field1";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field2";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field3";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field4";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field5";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field6";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field7";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field8";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field9";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field10";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="language";
    $v_type     ="VARCHAR(2) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="useronline";
    $v_command  ="ADD";
    $v_field    ="username";
    $v_type     ="VARCHAR(25) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="CHANGE";
    $v_field    ="timestamp";
    $v_type     ="timestamp INT(14) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="ads";
    $v_command  ="ADD";
    $v_field    ="attachment1";
    $v_type     ="VARCHAR(50) NOT NULL AFTER picture3";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="attachment2";
    $v_type     ="VARCHAR(50) NOT NULL AFTER attachment1";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="attachment3";
    $v_type     ="VARCHAR(50) NOT NULL AFTER attachment2";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="CHANGE";
    $v_field    ="rating";
    $v_type     ="rating DOUBLE(5,2) DEFAULT '5.0' NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="confirm";
    $v_command  ="ADD";
    $v_field    ="newsletter";
    $v_type     ="VARCHAR(5) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="firstname";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="lastname";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="address";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="zip";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="city";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="state";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="country";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="phone";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="cellphone";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="icq";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="homepage";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="hobbys";
    $v_type     ="VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field1";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field2";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field3";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field4";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field5";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field6";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field7";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field8";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field9";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="field10";
    $v_type     ="VARCHAR(255) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="userdata";
    $v_command  ="ADD";
    $v_field    ="registered";
    $v_type     ="VARCHAR(14) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="CHANGE";
    $v_field    ="adress";
    $v_type     ="address VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="CHANGE";
    $v_field    ="newsletter";
    $v_type     ="newsletter VARCHAR(5) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    ="adcat";
    $v_command  ="ADD";
    $v_field    ="passphrase";
    $v_type     ="VARCHAR(32) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $result = mysql_query("UPDATE ads SET rating='5.0' WHERE rating='0.0'");
    if (!$result) {
        echo "Database: [$db_name] - ads Field rating: failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - ads Field rating: converted <b>OK!</b><br>";
    }

    $result = mysql_query("UPDATE ads SET ratingcount='1' WHERE ratingcount='0'");
    if (!$result) {
        echo "Database: [$db_name] - ads Field ratingcount: failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - ads Field ratingcount: converted <b>OK!</b><br>";
    }

    $result = mysql_query("CREATE TABLE rating (type VARCHAR(3) NOT NULL, id INT(11) NOT NULL, ip VARCHAR(40) NOT NULL, userid INT(11) NOT NULL, ratedate DATETIME NOT NULL)");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: rating failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: rating <b>OK!</b><br>";
    }

    $result = mysql_query("CREATE TABLE logging (timestamp INT(14) NOT NULL, userid INT(11) NOT NULL, username VARCHAR(25) NOT NULL, event VARCHAR(50) NOT NULL, ext VARCHAR(250) NOT NULL,ip VARCHAR(40) NOT NULL, ipname VARCHAR(50) NOT NULL, client VARCHAR(100) NOT NULL)");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: logging failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: logging <b>OK!</b><br>";
    }

    $result = mysql_query("CREATE TABLE sessions (id VARCHAR(32) NOT NULL, userid INT(11) NOT NULL, username VARCHAR(25) NOT NULL,mod INT(1) NOT NULL,sessiondate DATETIME NOT NULL)");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: sessions failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: sessions <b>OK!</b><br>";
    }

    $result = mysql_query("CREATE TABLE webmail (id int(11) NOT NULL auto_increment,fromid int(11) DEFAULT '0' NOT NULL, fromname VARCHAR(25) NOT NULL ,fromemail VARCHAR(50) NOT NULL, toid int(11) DEFAULT '0' NOT NULL, toname VARCHAR(25) NOT NULL , toemail VARCHAR(50) NOT NULL, viewed int(1) DEFAULT '0' NOT NULL,answered int(1) DEFAULT '0' NOT NULL,timestamp int(14) DEFAULT '0' NOT NULL,ip varchar(40) NOT NULL,subject varchar(255) NOT NULL,text text NOT NULL,deleted int(1) DEFAULT '0' NOT NULL,attachment1 varchar(50) NOT NULL,attachment2 varchar(50) NOT NULL,attachment3 varchar(50) NOT NULL,PRIMARY KEY (id))");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: webmail failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: webmail <b>OK!</b><br>";
    }

    $result = mysql_query("CREATE TABLE config (id int(11) NOT NULL auto_increment ,type VARCHAR(32) NOT NULL, name VARCHAR(32) NOT NULL, value VARCHAR(255) NOT NULL, value2 VARCHAR(255) NOT NULL, value3 VARCHAR(255) NOT NULL, value4 VARCHAR(255) NOT NULL, value5 VARCHAR(255) NOT NULL, value6 VARCHAR(255) NOT NULL, PRIMARY KEY (id))");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: config failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: config <b>OK!</b><br>";

        mysql_query("INSERT INTO config VALUES ('', 'member', 'sex', 'yes', 'yes', '', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'newsletter', 'yes', 'yes', '', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'firstname', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'lastname', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'address', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'zip', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'city', 'yes', 'no', 'text', '', '', '')");
            mysql_query("INSERT INTO config VALUES ('', 'member', 'state', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'country', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'phone', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'cellphone', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'icq', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'homepage', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'hobbys', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field1', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field2', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field3', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field4', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field5', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field6', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field7', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field8', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field9', 'yes', 'no', 'text', '', '', '')");
        mysql_query("INSERT INTO config VALUES ('', 'member', 'field10', 'yes', 'no', 'text', '', '', '')");

        echo "Database: [$db_name] - Calculate cat-configuration Table: config ...";

        $result=mysql_query("SELECT * FROM adcat");
        while ($db=mysql_fetch_array($result)) {

        // insert configuration
        if ($db[sfield]) {$enable="yes";} else {$enable="no";}
        $sql="INSERT INTO config (type,name,value,value2) VALUES ('cat','sfield','$db[id]','$enable')";
        mysql_query($sql) or die(mysql_error().$sql);

        for($i = 1; $i<= 20; $i++) {
            if ($db["field".$i]) {$enable="yes";} else {$enable="no";}
            $select="text";
            $option="";
            mysql_query("INSERT INTO config (type,name,value,value2,value3,value4) VALUES
                ('cat','field$i','$db[id]','$enable','$select','$option')") or die(mysql_error());
        }

        for($i = 1; $i<= 10; $i++) {
            if ($db["icon".$i]) {$enable="yes";} else {$enable="no";}
            mysql_query("INSERT INTO config (type,name,value,value2) VALUES
                ('cat','icon$i','$db[id]','$enable')") or die(mysql_error());
        }

        }

        echo "<b>OK</b><br>";

    }

    echo "<br><b>Logix Classifieds Tables Updated, Ready ...</b>";

    } elseif ($_POST['action']=="up4") {

    echo "<b>Logix Classifieds Table Update from #$Id$1.6x to #$Id$2.0.x</b><p>";

    // DB prefix change !!!

    $tables = mysql_list_tables($db_name);

    while (list($table_name) = mysql_fetch_array($tables)) {
        $prefix_table_name = $prefix.$table_name;
        if (!strstr($table_name,$prefix) && !strstr($table_name,$chat_prefix) && !strstr($table_name,$forum_prefix) && $table_name!=$prefix_table_name && $table_name!="users") {
        $sql = "ALTER TABLE `$table_name` RENAME `$prefix_table_name`";
        mysql_query($sql);
        }
        $sql = "OPTIMIZE TABLE $table_name";
        mysql_query($sql);
        echo "Processed Table $table_name <b>OK</b><br>";
        flush();
    }

    $v_table    =$prefix."userdata";
    $v_command  ="ADD";
    $v_field    ="password";
    $v_type     ="VARCHAR(50) BINARY NOT NULL AFTER username";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="lastlogin";
    $v_type     ="INT(14) NOT NULL AFTER registered";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="lastvote";
    $v_type     ="VARCHAR(14) NOT NULL AFTER lastvotedate";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="lastad";
    $v_type     ="VARCHAR(14) NOT NULL AFTER lastaddate";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="picture";
    $v_type     ="VARCHAR(50) NOT NULL AFTER field10";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="_picture";
    $v_type     ="VARCHAR(50) NOT NULL AFTER picture";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="timezone";
    $v_type     ="varchar(3) NOT NULL default '+0'";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="dateformat";
    $v_type     ="varchar(14) NOT NULL default 'M. j Y, g:i a'";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="CHANGE";
    $v_field    ="registered";
    $v_type     ="registered INT(14) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="CHANGE";
    $v_field    ="username";
    $v_type     ="username VARCHAR(50) BINARY NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    =$prefix."userdata";
    $v_command  ="ADD";
    $v_field    ="email";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="username";
    alterindex($v_table,$v_command,$v_field);

    $result=mysql_query("SELECT username FROM ".$prefix."userdata");
    while ($db=mysql_fetch_array($result)) {
        // insert password
        $result2=mysql_query("SELECT * FROM ".$prefix."login WHERE username='$db[username]'");
        $db2=mysql_fetch_array($result2);
        if ($db2[password]) {
        mysql_query("UPDATE ".$prefix."userdata SET password='$db2[password]' WHERE username='$db2[username]'");
        mysql_query("DELETE FROM ".$prefix."login WHERE id='$db2[id]'");
        }
    }

    mysql_query("UPDATE ".$prefix."userdata SET newsletter='on' WHERE newsletter='1'");

    // Change Tables

    mysql_query("UPDATE ".$prefix."config SET value5='no' WHERE type='member' AND value5<>'yes' AND value5<>'req'");
    mysql_query("UPDATE ".$prefix."config SET value2='no' WHERE type='member' AND value2<>'yes' AND value2<>'req'");
    mysql_query("DELETE FROM ".$prefix."config WHERE type='member' AND name='timezone'");
    mysql_query("DELETE FROM ".$prefix."config WHERE type='member' AND name='dateformat'");
    mysql_query("DELETE FROM ".$prefix."config WHERE type='member' AND name='picture'");
    mysql_query("INSERT INTO ".$prefix."config (type,name,value,value2,value3,value4,value5) VALUES ('member','timezone','yes','yes','select','-12|-11|-10|-9|-8|-7|-6|-5|-4|-3|-2|-1|+0|+1|+2|+3|+4|+5|+6|+7|+8|+9|+10|+11|+12','no')");
    mysql_query("INSERT INTO ".$prefix."config (type,name,value,value2,value3,value4,value5) VALUES ('member','dateformat','yes','yes','select','M. j Y, g:i a|d M Y H:i|F j, Y, g:i a|j.n.Y H:i:s','no')");
    mysql_query("INSERT INTO ".$prefix."config (type,name,value,value2,value3,value4,value5) VALUES ('member','picture','yes','','','','')");


    $v_table    =$prefix."ads";
    $v_command  ="CHANGE";
    $v_field    ="picture";
    $v_type     ="picture1 VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="_picture";
    $v_type     ="_picture1 VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="ADD";
    $v_field    ="_picture4";
    $v_type     ="VARCHAR(50) NOT NULL AFTER picture3";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="picture4";
    $v_type     ="VARCHAR(50) NOT NULL AFTER _picture4";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="_picture5";
    $v_type     ="VARCHAR(50) NOT NULL AFTER picture4";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="picture5";
    $v_type     ="VARCHAR(50) NOT NULL AFTER _picture5";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="attachment4";
    $v_type     ="VARCHAR(50) NOT NULL AFTER attachment3";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="attachment5";
    $v_type     ="VARCHAR(50) NOT NULL AFTER attachment4";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    =$prefix."adcat";
    $v_command  ="ADD";
    $v_field    ="sortorder";
    $v_type     ="INT(3) DEFAULT 0 NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="disabled";
    $v_type     ="INT(1) DEFAULT 0 NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    =$prefix."adcat";
    $v_field    ="sortorder";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."ads";
    $v_field    ="userid";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="catid";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="subcatid";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="viewed";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="answered";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="rating";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="addate";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="location";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="header";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="publicview";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."adsubcat";
    $v_command  ="ADD";
    $v_field    ="catid";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."badwords";
    $v_command  ="ADD";
    $v_field    ="badword";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."config";
    $v_command  ="ADD";
    $v_field    ="type";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="name";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."favorits";
    $v_command  ="ADD";
    $v_field    ="userid";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."notify";
    $v_command  ="ADD";
    $v_field    ="userid";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="subcatid";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."pictures";
    $v_command  ="ADD";
    $v_field    ="picture_name";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."rating";
    $v_command  ="ADD";
    $v_field    ="userid";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="id";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="type";
    alterindex($v_table,$v_command,$v_field);

    if ($sales_option) {
    $v_table    =$prefix."sales";
    $v_command  ="ADD";
    $v_field    ="userid";
    alterindex($v_table,$v_command,$v_field);
    }

    $v_table    =$prefix."useronline";
    $v_command  ="ADD";
    $v_field    ="ip";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="timestamp";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="username";
    alterindex($v_table,$v_command,$v_field);

    $v_command  ="CHANGE";
    $v_field    ="username";
    $v_type     ="username VARCHAR(50) BINARY NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    =$prefix."webmail";
    $v_command  ="CHANGE";
    $v_field    ="fromname";
    $v_type     ="fromname VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="toname";
    $v_type     ="toname VARCHAR(50) NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_command  ="ADD";
    $v_field    ="toid";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="viewed";
    alterindex($v_table,$v_command,$v_field);

    $v_field    ="answered";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."banned_users";
    $v_command  ="ADD";
    $v_field    ="userid";
    alterindex($v_table,$v_command,$v_field);

    $v_table    =$prefix."confirm";
    $v_command  ="CHANGE";
    $v_field    ="username";
    $v_type     ="username VARCHAR(50) BINARY NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_field    ="password";
    $v_type     ="password VARCHAR(50) BINARY NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    // session table
    mysql_query("DROP TABLE ".$prefix."sessions");

    $result = mysql_query("CREATE TABLE ".$prefix."sessions (id VARCHAR(32) NOT NULL , userid INT(11) DEFAULT 0, username VARCHAR(50) NOT NULL, createstamp INT(14) NOT NULL, expirestamp INT(14) NOT NULL,  PRIMARY KEY (id), KEY userid (userid), KEY createstamp (createstamp), KEY expirestamp (expirestamp))");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: sessions failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: sessions <b>OK!</b><br>";
    }

    // Add/update DB #$Id$info
        $result = mysql_query("SELECT * FROM ".$prefix."config WHERE type='version' AND name='db'");
        $db = mysql_fetch_array($result);
    if ($db['value']) {
        mysql_query("UPDATE ".$prefix."config SET value='2.0.x' WHERE id='$db[id]'");
    } else {
        mysql_query("INSERT INTO ".$prefix."config (type,name,value) VALUES ('version','db','2.0.x')");
    }

    echo "<br><b>Logix Classifieds Tables Updated, Ready ...</b>";

    } elseif ($_POST['action']=="del") {

    installdb();

    } elseif ($_POST['action']=="sal") {

    echo "<b>Logix ClassifiedsSales Table Update</b><p>";

    $v_table    =$prefix."adcat";
    $v_command  ="ADD";
    $v_field    ="sales";
    $v_type     ="INT(1) NOT NULL DEFAULT '0'";
    altertables($v_table,$v_command,$v_field,$v_type);

    $v_table    =$prefix."adcat";
    $v_command  ="ADD";
    $v_field    ="salesbase";
    $v_type     ="INT(1) DEFAULT '0' NOT NULL";
    altertables($v_table,$v_command,$v_field,$v_type);

    $result = mysql_query("CREATE TABLE ".$prefix."sales (id int(11) DEFAULT '0' NOT NULL auto_increment,
                            userid int(11) DEFAULT '0' NOT NULL,
                            timestamp int(14) DEFAULT '0' NOT NULL,
                        ip varchar(40) NOT NULL,
                            type varchar(10) NOT NULL,
                            count int(6) DEFAULT '0' NOT NULL,
                            amount double(16,4) DEFAULT '0.0000' NOT NULL,
                            ordernumber varchar(25) NOT NULL,
                        PRIMARY KEY (id),
                            UNIQUE id (id)
                            ) ");
    if (!$result) {
        echo "Database: [$db_name] - Creation Table: ".$prefix."sales failed! <b>Error</b>: " .mysql_errno(). ": ".mysql_error(). "<br>";
    } else {
        echo "Database: [$db_name] - Creation Table: ".$prefix."sales <b>OK!</b><br>";
    }

    $v_table    =$prefix."sales";
    $v_command  ="ADD";
    $v_field    ="userid";
    alterindex($v_table,$v_command,$v_field);

    echo "<br><b>Logix ClassifiedsSales Tables successfully installed/updated ...</b>";

    } elseif ($_POST['action']=="chat") {

    echo "<b>Logix ClassifiedsChat Database/Table Update</b><p>";

    $chat_db_connect_id=mysql_connect($chat_db_server,$chat_db_user,$chat_db_pass) or die(mysql_error());
        mysql_create_db($chat_database,$chat_db_connect_id);
        mysql_select_db($chat_database,$chat_db_connect_id);
        $result=mysql_query("CREATE TABLE ".$chat_prefix."users (id int(11) DEFAULT '0' NOT NULL auto_increment, nick varchar(50) NOT NULL, pass varchar(32) NOT NULL, PRIMARY KEY (id), UNIQUE id (id))",$chat_db_connect_id);
    if ($result) {
        echo "Database: [$chat_db_name] - Creation Table: ".$chat_prefix."users <b>OK!</b><br>";
    }
    if ($chat_db_connect_id!=$db_connect_id) {mysql_close($chat_db_connect_id);}

    $result = mysql_query("SELECT * FROM ".$prefix."userdata") or die(mysql_error());
    while ($db = mysql_fetch_array($result)) {
        $username=$db[username];
        $password=$db[password];
        include ("../$chat_interface");
        # echo "$db[username]\n";
        $count++;
    }
        echo "$count Row's processed<br>";
        echo "<br><b>Logix ClassifiedsChat [$chat_db_name] Tables successfully installed/updated ...</b>";

    } elseif ($_POST['action']=="bb") {

    echo "<b>Forum Database/Table Update</b><p>";

    $result = mysql_query("SELECT * FROM ".$prefix."userdata") or die(mysql_error());
    while ($db = mysql_fetch_array($result)) {
            $username=$db[username];
        $password=$db[password];
        $email=$db[email];
        include ("../$forum_interface");
        # echo "$username $email <br>\n";
        $count++;
    }
        echo "$count Row's processed<br>";
        echo "<br><b>Forum [$forum_db_name] Tables successfully updated ...</b>";

    } elseif ($_POST['action']=="pic") {

    echo "<b>Logix Classifieds Picture-to-DB</b><p>";

        if (!$tmp_dir = get_cfg_var('upload_tmp_dir')) {
            $tmp_dir = dirname(tempnam('', ''));
        }

        $result = mysql_query("SELECT * FROM ads WHERE picture!=''") or die(mysql_error());
        while ($db = mysql_fetch_array($result)) {

          $result2 = mysql_query("SELECT * FROM pictures WHERE picture_name ='$db[picture]'") or die(mysql_error());
          $dbp = mysql_fetch_array($result2);
          if ($dbp[picture_name]) {
            echo "WARNING: Picture $db[picture] already exists in DB, nothing done.<br>";
          } else {
            if (is_file("$bazar_dir/$image_dir/userpics/$db[picture]")) {
            $picinfo=GetImageSize("$bazar_dir/$image_dir/userpics/$db[picture]");
                if ($picinfo[2] == "1" || $picinfo[2] == "2" || $picinfo[2] == "3") {
                switch ($picinfo[2]) {
                    case 1 : $ext = ".gif"; $type = "image/gif"; break;
                    case 2 : $ext = ".jpg"; $type = "image/jpeg"; break;
                    case 3 : $ext = ".png"; $type = "image/png"; break;
                }

                        if (strtoupper($convertpath) == "AUTO") {   // simple file handling without convert
                    if (is_file("$bazar_dir/$image_dir/userpics/$db[picture]")) {
                    $picture_size = filesize("$bazar_dir/$image_dir/userpics/$db[picture]");
                    $picture_bin = addslashes(fread(fopen("$bazar_dir/$image_dir/userpics/$db[picture]", "r"), $picture_size));
                    mysql_query("INSERT INTO pictures VALUES ('$db[picture]','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')") or die("DB Update Error $db[picture] ".mysql_error());
                        echo "Picture $db[picture] stored in Database. (Type: $ext, Size: $picture_size)<br>";
                    }

                        } else {                                    // advanced file handling with convert
                            $convertstr=" -geometry $pic_res -quality $pic_quality $bazar_dir/$image_dir/userpics/$db[picture] $tmp_dir/tmp_picture$ext";
                            exec($convertpath.$convertstr);
                    if (is_file("$tmp_dir/tmp_picture$ext")) {
                    $picture_size = filesize("$tmp_dir/tmp_picture$ext");
                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture$ext", "r"), $picture_size));
                    $picinfo=GetImageSize("$tmp_dir/tmp_picture$ext");
                    mysql_query("INSERT INTO pictures VALUES ('$db[picture]','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')") or die("DB Update Error $db[picture] ".mysql_error());
                    suppr("$tmp_dir/tmp_picture$ext");
                        echo "Picture $db[picture] stored in Database. (Type: $ext, Size: $picture_size)<br>";
                    }


                            $_convertstr=" -geometry $pic_lowres -quality $pic_quality $bazar_dir/$image_dir/userpics/$db[picture] $tmp_dir/tmp_picture$ext";
                            exec($convertpath.$_convertstr);
                    if (is_file("$tmp_dir/tmp_picture$ext")) {
                    $picture_size = filesize("$tmp_dir/tmp_picture$ext");
                    $picture_bin = addslashes(fread(fopen("$tmp_dir/tmp_picture$ext", "r"), $picture_size));
                    $picinfo=GetImageSize("$tmp_dir/tmp_picture$ext");
                    mysql_query("INSERT INTO pictures VALUES ('_$db[picture]','$type','$picinfo[1]','$picinfo[0]','$picture_size','$picture_bin')") or die("DB Update Error $db[picture] ".mysql_error());
                    suppr("$tmp_dir/tmp_picture$ext");
                        echo "Picture _$db[picture] stored in Database. (Type: $ext, Size: $picture_size)<br>";
                    }
                        }

                } else {
                echo "WARNING: Picture $db[picture] wrong Filetype, nothing done.<br>";
                }
            } else {
                echo "ERROR: Picture $db[picture] couldn't open.<br>";
            }
          }
        }

    echo "<br><b>Logix Classifieds Convert Pictures, Ready ...</b>";

    } else {

    echo "<b><u>INFOS: </b></u><br>";
        $result = mysql_query("SELECT * FROM ".$prefix."config WHERE type='version' AND name='db'");
if($result)
{
   $db = mysql_fetch_array($result);
}
else
{
$db['value']='';
}
    if ($db['value']) {$dbversion=$db['value'];} else {$dbversion="Prior V2.x.x"; }
    echo "Database [$db_name] <b>exist</b> (Version: $dbversion).<br>";
    if ($chat_enable && $chat_db_name && $chat_interface) echo "Chat [$chat_db_name] <b>enabled</b>.<br>";
    if ($forum_enable && $forum_db_name && $forum_interface) echo "Forum [$forum_db_name] <b>enabled</b>.<br>";
    if ($convertpath=="AUTO") {echo "Converttool [AUTO] <b>not enabled</b>.<br>";}
    elseif ($convertpath=="GDLIB") {echo "Converttool [GDLIB] <b>enabled</b>.<br>";}
    else {echo "Converttool [$convertpath] <b>enabled</b>.<br>";}

    echo "<br><b><u>CHECKS: </b></u><br>";
    if (!$db['value']) $echo ="<b><font color=red>ERROR</font></b> DB Version/ Tables not present - Install/Update Tables";
 if (!is_dir("$bazar_dir/$image_dir")) $echo .= "<b><font color=red>ERROR</font></b> Check config.php <b>\$image_dir</b> is not a directory<br>";
    if (!is_dir("$bazar_dir/$admin_dir")) $echo .= "<b><font color=red>ERROR</font></b> Check config.php <b>\$admin_dir</b> is not a directory<br>";
    if (!is_dir("$bazar_dir/$backup_dir")) $echo .= "<b><font color=red>ERROR</font></b> Check config.php <b>\$backup_dir</b> is not a directory<br>";
    if (!is_dir("$bazar_dir/$languagebase_dir")) $echo .= "<b><font color=red>ERROR</font></b> Check config.php <b>\$languagebase_dir</b> is not a directory<br>";
    if ($pic_enable && $pic_path && !$pic_database && !is_writeable("$bazar_dir/$pic_path")) $echo .= "<b><font color=red>ERROR</font></b> Check <b>\$pic_path</b> - it is not writeable (check permissions - chmod 777)<br>";
    if ($att_enable && $att_path && !is_writeable("$bazar_dir/$att_path")) $echo .="<b><font color=red>ERROR</font></b> Check <b>\$att_path</b> - it is not writeable (check permissions - chmod 777)<br>";
    if ($webmail_enable && $webmail_path && !is_writeable("$bazar_dir/$webmail_path")) $echo .= "<b><font color=red>ERROR</font></b> Check <b>\$webmail_path</b> - it is not writeable (check permissions - chmod 777)<br>";
    if ($fix_tmp_dir && !is_writeable($fix_tmp_dir)) $echo .= "<b><font color=red>ERROR</font></b> Check <b>\$fix_tmp_dir</b> - it is not writeable (check permissions - chmod 777)<br>";
    if ($fix_tmp_dir && !is_dir($fix_tmp_dir)) $echo .= "<b><font color=red>ERROR</font></b> Check <b>\$fix_tmp_dir</b> - it is not a directory<br>";
    if ($convertpath=="GDLIB" && !function_exists("imagegd")) $echo .= "<b><font color=red>ERROR</font></b> GD Library is not installed<br>";
    if ($convertpath && $convertpath!="GDLIB" && $convertpath!="AUTO" && !is_file($convertpath)) $echo .= "<b><font color=red>ERROR</font></b> Convertpath <b>\$convertpath</b> is not a file or executeable<br>";
    if (!check_phpversion("4.1.0")) $echo2 .= "<b><font color=orange>WARNING</font></b> Too old PHP #$Id$<b>".phpversion()."</b>, some functions may not work - Please update PHP (min. 4.1.0)<br>";
    if (!check_optversion("2.1.0")) $echo .= "<b><font color=red>ERROR</font></b> ZEND Optimizer to old #$Id$or not installed - Please install min. ZEND Optimizer 2.1.0<br>";
    if ($convertpath=="GDLIB" && function_exists("imagegd") && !function_exists("imagegd2"))  echo "<b><font color=orange>WARNING</font></b> GD Library 2 is not installed - Please update (suggested > 2.0.1), or set \$convertpath=\"AUTO\"<br>";


    if (!$echo) $echo = "All Checks Passed <b><font color=green>OK</font></b><br>";
    echo $echo;

    echo "<br><br><table width=\"400\" border=\"1\" align=center><tr><td><center><br>";
    echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\"><input type=\"hidden\" name=\"action\" value=\"del\"><input type=\"submit\" onclick=\"return jsconfirm()\" value=\"INSTALL TABLES Version 2.0.x\n!!! this DELETE OLD TABLES if any !!!\"></form><hr>";

    echo "<hr>";

    if ($pic_database) {
        echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\"><input type=\"hidden\" name=\"action\" value=\"pic\"><input type=\"submit\" value=\"UPDATE DB copy Pic's into DB\" ></form>";
    }
    if ($forum_db_name && $forum_enable && $forum_interface) {
        echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\"><input type=\"hidden\" name=\"action\" value=\"bb\"><input type=\"submit\" value=\"UPDATE DB of Forum\" ></form>";
    }
    if (!empty($sales_option) && $sales_option == true) {
        echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\"><input type=\"hidden\" name=\"action\" value=\"sal\"><input type=\"submit\" value=\"INSTALL/UPDATE DB for SalesOption\"></form>";
    }
    if ($chat_db_name && $chat_enable && $chat_interface) {
        echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\"><input type=\"hidden\" name=\"action\" value=\"chat\"><input type=\"submit\" value=\"INSTALL/UPDATE DB for ChatOption\" ></form>";
    }

    echo "</td></tr></table></center>\n";
    }
    echo "<p align=center><input type=\"submit\" value=\"HOME\" onclick=\"javascript:window.location.href='$_SERVER[PHP_SELF]'\">&nbsp;<input type=\"submit\" value=\"ADMIN\" onclick=\"javascript:window.location.href='admin.php'\">&nbsp;<input type=\"submit\" value=\"MEMBERAREA\" onclick=\"javascript:window.location.href='http://www.smartisoft.com/?member'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\"EXIT\" onclick=\"javascript:window.close()\">";
    mysql_close();

} else {

    mysql_close();

    if ($_POST['action']=="inst") {

        mysql_connect($db_server, $db_user, $db_pass);
        echo "<b>Logix Classifieds Database install</b>";
    if (mysql_create_db($db_name)) {
            echo "<b> - OK</b><p>";
    } else {
            echo "<b><font color=red> - FAILED</b></font><p>";
    }
        mysql_close();
#   echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\"><input type=\"hidden\" name=\"action\" value=\"del\"><input type=\"submit\" value=\"INSTALL TABLES #$Id$1.5x\n\"></form><hr>";

    } else {

    echo "Database [$db_name] does NOT exist or is NOT accessable !!!<p>";
        echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">\n";

    echo "<table width=\"400\">\n";
    echo "<tr><td colspan=2><font face=\"verdana\" size=\"2\"><br>Check your mySQL Data (edit config.php)<br><br></font></td></tr>\n";
    echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-Server: </font></td><td><input type=\"text\" name=\"mysqlserver\" value=\"$db_server\" size=\"20\" readonly><br></td></tr>\n";
    echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-User: </font></td><td><input type=\"text\" name=\"mysqluser\" value=\"$db_user\" size=\"20\" readonly><br></td></tr>\n";
    echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-Pass: </font></td><td><input type=\"text\" name=\"mysqlpass\" value=\"$db_pass\" size=\"20\" readonly><br></td></tr>\n";
    echo "<tr><td><font face=\"verdana\" size=\"2\">mySQL-DB: </font></td><td><input type=\"text\" name=\"mysqldb\" value=\"$db_name\" size=\"20\" readonly><br></td></tr>\n";

        echo "<tr><td colspan=2><hr><center><input type=\"hidden\" name=\"action\" value=\"inst\"><input type=\"submit\" value=\"if configuration is OK - try Create DB\"></form>";
    echo "</center></td></tr></table>\n";
    }
    echo "<p align=center><input type=\"submit\" value=\"HOME\" onclick=\"javascript:window.location.href='$_SERVER[PHP_SELF]'\">&nbsp;<input type=\"submit\" value=\"EXIT\" onclick=\"javascript:window.close()\">";

}


#  End
#################################################################################################
echo" <p><div class=\"footer\">Logix Classifieds Ver. $bazar_version &copy 2006-".date("Y")." by  PhpFixer</div>\n";
echo "</td></tr></table></body></html>\n";

?>