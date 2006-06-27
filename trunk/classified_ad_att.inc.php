<?php
#################################################################################################
#
#  project              : Logix Classifieds
#  filename             : classified_adatt.inc.php
#  e-mail               : support@phplogix.com
#  purpose              : IncludeModule show classified ad attachments (detail)
#$Id$
#License: GPL
#################################################################################################
#TODO: clean up, move to templates..
if(!strpos($_SERVER['PHP_SELF'],'classified_ad_att.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}

    for ($i=5;$i>=1;$i--) {
        $fieldname="attachment".$i;
        if ($db[$fieldname]) {
            echo "<a href=\"$att_path/$db[$fieldname]\" onClick='enterWindow=window.open(\"$att_path/$db[$fieldname]\",\"Picture\",\"top=10,left=10,scrollbars=yes,resizable=yes\"); return false'>
                <img src=\"$image_dir/$att_icon\" alt=\"$db[$fieldname]\" align=\"right\" vspace=\"2\" border=\"0\"
                onmouseover=\"window.status='$db[$fieldname]'; return true;\"
                onmouseout=\"window.status=''; return true;\"></a>\n";
        }
    }

    echo " $ad_att:";
    echo "   <br><div class=\"spaceleft\">&nbsp</div><br><div class=\"spaceleft\">&nbsp</div><hr>\n";

?>