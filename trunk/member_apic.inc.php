<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : member_apic.inc.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : IncludeModule show member picture
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'member_apic.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}

        if (!$pic_database && $picture && is_file("$pic_path/$picture") && is_file("$pic_path/$_picture")) {
        echo "   <a href=\"pictureviewer.php?pic=$pic_path/$picture\" onClick='enterWindow=window.open(\"pictureviewer.php?".sidstr()."pic=$pic_path/$picture\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>
            <img src=\"$pic_path/$_picture\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";
        } elseif ($picture) {
        echo "   <a href=\"pictureviewer.php?id=$picture\" onClick='enterWindow=window.open(\"pictureviewer.php?".sidstr()."id=$picture\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes\"); return false'>
                <img src=\"picturedisplay.php?id=$_picture\" border=\"0\" alt=\"$ad_enlarge\"></a>\n";
        }


?>