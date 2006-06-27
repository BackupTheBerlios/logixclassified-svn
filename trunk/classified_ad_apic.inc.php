<?php
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_ad_apic.inc.php
#  e-mail               : support@phplogix.com
#  purpose              : IncludeModule show classified ad pictures (detail)
#$Id$
#License: GPL
#################################################################################################
#TODO: clean up and separate template from code.
if(!strpos($_SERVER['PHP_SELF'],'classified_ad_apic.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}

        for ($i=1;$i<=5;$i++) {
            $fieldname="picture".$i;
            $_fieldname="_picture".$i;

        if (!$pic_database && $db[$fieldname] && is_file("$pic_path/$db[$fieldname]") && is_file("$pic_path/$db[$_fieldname]")) {
        echo "   <a href=\"pictureviewer.php?pic=$pic_path/$db[$fieldname]\" onClick='enterWindow=window.open(\"pictureviewer.php?".sidstr()."pic=$pic_path/$db[$fieldname]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>
            <img src=\"$pic_path/$db[$_fieldname]\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";
        } elseif ($db[$fieldname]) {
        echo "   <a href=\"pictureviewer.php?id=$db[$fieldname]\" onClick='enterWindow=window.open(\"pictureviewer.php?".sidstr()."id=$db[$fieldname]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>
                <img src=\"picturedisplay.php?id=$db[$_fieldname]\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";
        }

    }

?>