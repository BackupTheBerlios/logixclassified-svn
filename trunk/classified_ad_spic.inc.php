<?
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_ad_spic.inc.php
#  last modified by     :
#  e-mail               : support@phplogix.com
#  purpose              : IncludeModule show classified ad pictures (detail)
#
#################################################################################################
if(!strpos($_SERVER['PHP_SELF'],'classified_ad_spic.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}

        for ($i=1;$i<=5;$i++) {
        $fieldname="picture".$i;

            if (!$pic_database && $db[$fieldname] && is_file("$pic_path/$db[$fieldname]")) {
        // claculate thumbnail-size
        $picinfo=GetImageSize("$pic_path/$db[$fieldname]");
            $picsize=explode("x",$pic_lowres);
        if ($picinfo[0]>intval($picsize[0]) || $picinfo[1]>intval($picsize[1])) {
            $div[0]=$picinfo[0]/$picsize[0];
            $div[1]=$picinfo[1]/$picsize[1];
            if ($div[0]>$div[1]) {
            $sizestr="width=".intval($picinfo[0]/$div[0])." height=".intval($picinfo[1]/$div[0]);
            } else {
            $sizestr="width=".intval($picinfo[0]/$div[1])." height=".intval($picinfo[1]/$div[1]);
            }
        } else {
            $sizestr=$picinfo[3];
        }
            echo "   <a href=\"pictureviewer.php?pic=$pic_path/$db[$fieldname]\" onClick='enterWindow=window.open(\"pictureviewer.php?".sidstr()."pic=$pic_path/$db[$fieldname]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes,resizable=yes\"); return false'>
            <img src=\"$pic_path/$db[$fieldname]\" $sizestr border=\"0\" alt=\"$ad_enlarge\"></a>\n";
        } elseif ($db[$fieldname]) {
        // claculate thumbnail-size
        $result4 = mysql_query("SELECT * FROM ".$prefix."pictures WHERE picture_name='$db[$fieldname]'") or died("Can NOT find the Picture");
        $dbp = mysql_fetch_array($result4);
        $picsize=explode("x",$pic_lowres);
        if ($dbp[picture_width]>intval($picsize[0]) || $dbp[picture_height]>intval($picsize[1])) {
            $div[0]=$dbp[picture_width]/$picsize[0];
            $div[1]=$dbp[picture_height]/$picsize[1];
            if ($div[0]>$div[1]) {
            $sizestr="width=".intval($dbp[picture_width]/$div[0])." height=".intval($dbp[picture_height]/$div[0]);
            } else {
            $sizestr="width=".intval($dbp[picture_width]/$div[1])." height=".intval($dbp[picture_height]/$div[1]);
            }
        } else {
            $sizestr="width=$dbp[picture_width] height=$dbp[picture_height]";
        }
            echo "   <a href=\"pictureviewer.php?id=$db[$fieldname]\" onClick='enterWindow=window.open(\"pictureviewer.php?".sidstr()."id=$db[$fieldname]\",\"Picture\",\"width=$pic_width,height=$pic_height,top=100,left=100,scrollbars=yes,resizable=yes\"); return false'>
                <img src=\"picturedisplay.php?id=$db[$fieldname]\" $sizestr border=\"0\" alt=\"$ad_enlarge\"></a>\n";
        }

    }

?>