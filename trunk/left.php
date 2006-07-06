<?php
##############################################################################################
#                                                                                            #
#                                left.php
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
//TODO: this is the left side, let's template the menu and we can simply include this stuff
//TODO locate where left.inc.php is included on all pages, and see if we can unify stuff into a template, or perhaps
//build a function that just does "build left side" and sets the template vars (Smarty)
//Note about smarty- we can use cacheing, which vastly improves performance
if(!strpos($_SERVER['PHP_SELF'],'left.inc.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
if (strpos($client,"MSIE"))
{   // Internet Explorer Detection
    //TODO: Why is this necessary? we need to dig this out..
    $field_size="20";
}
else
{                            // Netscape code
    $field_size="11";
}

# Status Window
#################################################################################################
(empty($status))?$status="":$status=$status;
//TODO: left.inc.php line 48 status array - this stuff needs templated, badly
$smarty->assign('table_width_side',$table_width_side);
$smarty->assign('status_header',$status_header);
$smarty->assign('status_msg',$status_msg);




if (!empty($errormessage))
{
//TODO: Error handling needs to be streamlined, we need to create an error handler function or class.. and allow errors to pass through
//TODO: whatever script they comne in, this is pure bullshit, passing errors in url ..
$smarty->assign('alertmessage',$errormessage);
}

if (!empty($textmessage))
{
#    $textmessage=rawurlencode($textmessage);
$smarty->assign('message',$message);
}


if (empty($_SESSION['suserid']))
{
    $smarty->assign('logged_in',"false");
   //KLUDGE (again- jeezus)
    (empty($login))?$login="":$login=$login;
    if ($login == "lostpass")
    {
        $smarty->assign('lostpw_header',$lostpw_header);
        $smarty->assign('lostpw_email',$lostpw_email);
        $smarty->assign('field_size',$field_size);
        $smarty->assign('lostpw_button',$lostpw_button);

    }
    else
    {
        if ($no_confirmation)
        {
            $target="_self";
        }
        else
        {
            $target="_blank";
        }
        $smarty->assign('field_size',$field_size);
        $smarty->assign('login_header',$login_header);
        $smarty->assign('login_username',$login_username);
        $smarty->assign('login_password',$login_password);
        $langstr = "";
        $smarty->assign('langstr',$langstr);
        $smarty->assign('logi_link1desc',$logi_link1desc);
        $smarty->assign('logi_link1',$logi_link1);
        $smarty->assign('target',$target);
        $smarty->assign('logi_link2desc',$logi_link2desc);
        $smarty->assign('logi_link2',$logi_link2);

    }

}
else
{
    $membernumber=$_SESSION['suserid']+$memberoffset;
    $smarty->assign('membernumber',$membernumber);
    $smarty->assign('login_header',$login_header);
    $smarty->assign('login_username',$login_username);
    $smarty->assign('field_size',$field_size);
    $langstr = "";
    $smarty->assign('langstr',$langstr);
    $smarty->assign('session_username',$_SESSION['susername']);
    $smarty->assign('login_member',$login_member);

    if (!empty($show_useronline) && $show_useronline == true)
    {
        $timeout=$timestamp-300;  // value in seconds
        mysql_query("DELETE FROM ".$prefix."useronline WHERE timestamp<$timeout");
        $result=mysql_query("SELECT username FROM ".$prefix."useronline WHERE username!='' GROUP by username");
        $user =mysql_num_rows($result);
        $result=mysql_query("SELECT ip FROM ".$prefix."useronline WHERE username='' GROUP by ip");
        $user+=mysql_num_rows($result);
        if ($user>1)
        {
            $uostr=$users_online;
        }
        else
        {
            $uostr=$user_online;
        }
        $smarty->assign('user',$user);
        $smarty->assign('uostr',$uostr);
        $smarty->assign('show_useronline_detail',$show_useronline_detail);

    }
    if ($_SESSION['susermod'])
    {      // if Moderator or Administrator
        $smarty->assign('is_moderator',true);
    }
    else
    {
        if ($webmail_enable && $webmail_notifypopup && $_SESSION['susernewmails'])
        {
            $smarty->assign('mail_new',$mail_new);
            $smarty->assign('webmail_head',$webmail_head);
        }

    }
}

# Advertising Window 1
#################################################################################################
if ($show_advert1)   //lets move this shit to db
{
  $smarty->assign('show_advert1',$show_advert1);

}


# Advertising Window 2
#################################################################################################
if (!empty($picadoftheday) || !empty($show_advert2))
{
$smarty->assign('advert2',true);


if ($show_picadday)
{
$smarty->assign('potd',true);
//Man, this is a *shitload* of code for a simple POTD ?!

    $query=mysql_query("SELECT * FROM ".$prefix."config WHERE type='config' AND name='fix_adoftheday'") or die("Database Query Error");
    $db=mysql_fetch_array($query);
    if ($db['value'])   //TODO: left.php potd oh my.. anybody ever heard of a straight join?
    { // Fixed AdoftheDay

    $result=mysql_query("SELECT * FROM ".$prefix."ads WHERE id='$db[value]'") or die("Database Query Error".mysql_error());
        $db=mysql_fetch_array($result);

    }
    else
    {

    $result=mysql_query("SELECT ".$prefix."ads.* FROM ".$prefix."ads LEFT JOIN ".$prefix."adcat ON ".$prefix."ads.catid=".$prefix."adcat.id WHERE ".$prefix."ads.picture1!= '' AND ".$prefix."ads.publicview='1' AND ".$prefix."adcat.passphrase=''") or die("Database Query Error".mysql_error());
    // if you like also pictures from categories with passphrase included in picoftheday rotation use the next line instead of prev line
        // $result=mysql_query("SELECT * FROM ".$prefix."ads WHERE picture1!= '' AND publicview='1'") or die("Database Query Error".mysql_error());
    $count=mysql_num_rows($result);
        $query=mysql_query("SELECT * FROM ".$prefix."favorits WHERE userid>'100000000'") or die("Database Query Error".mysql_error());
    $getad=mysql_fetch_array($query);
        if ($getad<1)
        { //NOT found, calculate NEW ad of the day, and stor it to favorits ;-)
            if ($count>1)
            {
                srand((double)microtime()*1000000);
                $dboffset=rand(0,$count-1);
                if ($dboffset>0)
                {
                    mysql_query("INSERT INTO ".$prefix."favorits VALUES('$timestamp','$dboffset')") or die("Database Query Error".mysql_error());
                }
            }
        }
        else
        {
                $dboffset=$getad['adid'];
                if (!is_int($show_picadday))
                {
                    $show_picadday=24;
                }   // if no value, set to 24 hours
                if ($getad['userid']<($timestamp-3600*$show_picadday))
                {  // if timed out, delete it
                    mysql_query("DELETE FROM ".$prefix."favorits WHERE userid>'100000000'") or die("Database Query Error".mysql_error());
                }
        }

        if (!empty($dboffset) && $dboffset>0 && $count>$dboffset)
        {
            if (mysql_data_seek($result,$dboffset))
            {
                $db=mysql_fetch_array($result);
            }
        }
        else
        {
                $db=mysql_fetch_array($result);
        }

    }

    if ($db['_picture1'])
    {           // Thumbnail exist
//TODO: Screw this- no way are we gonna store pictures in database.
//Picture protection is a pile of BS to begin with. HTTP protocol wasnt designed this way,
//if you dont want people keeping copies of images, DONT POST THEM. Even streamed images can be downloaded and saved and copied.
//so this is an exercise in futility. In the future we may add steganograpgy to this, but otherwise, trying to protect images this way
//is just plain stupid , and hurts performance.

  //  if (!$pic_database)
  //  {
        $smarty->assign('category',$db['catid']);
        $smarty->assign('subcategory',$db['subcatid']);
        $smarty->assign('adid',$db['id']);
        $smarty->assign('img_header',$db['header']);
        $smarty->assign('potd_path',$pic_path);
        $smarty->assign('_picture1',$db['_picture1']);
     //       echo" <div class=\"smallcenter\"><a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\" onmouseover=\"window.status='".addslashes($db[header])."'; return true;\" onmouseout=\"window.status=''; return true;\">
       // <img src=\"$pic_path/$db[_picture1]\" border=\"0\" vspace=\"2\" hspace=\"2\"></a></div>";
   // } else {
    //        echo" <div class=\"smallcenter\"><a href=\"classified.php?catid=$db[catid]&subcatid=$db[subcatid]&adid=$db[id]\" onmouseover=\"window.status='".addslashes($db[header])."'; return true;\" onmouseout=\"window.status=''; return true;\">
   //     <img src=\"picturedisplay.php?id=$db[_picture1]\" border=\"0\" vspace=\"2\" hspace=\"2\"></a></div>";
   // }
    }
    elseif ($db['picture1'])
    {      // Calculate Thumbnail

       //if (!$pic_database) {
//As above- we do not store pics in database anymore.
            $picinfo=GetImageSize("$pic_path/$db[picture1]");
            $picsize=explode("x",$pic_lowres);
            if ($picinfo[0]>intval($picsize[0]) || $picinfo[1]>intval($picsize[1]))
            {
                $div[0]=$picinfo[0]/$picsize[0];
                $div[1]=$picinfo[1]/$picsize[1];
                if ($div[0]>$div[1])
                {
                        $sizestr="width=".intval($picinfo[0]/$div[0])." height=".intval($picinfo[1]/$div[0]);
                }
                else
                {
                        $sizestr="width=".intval($picinfo[0]/$div[1])." height=".intval($picinfo[1]/$div[1]);
                }
            }
            else
            {
                $sizestr=$picinfo[3];
            }
           $smarty->assign('category',$db['catid']);
        $smarty->assign('subcategory',$db['subcatid']);
        $smarty->assign('adid',$db['id']);
        $smarty->assign('img_header',$db['header']);
        $smarty->assign('potd_path',$pic_path);
        $smarty->assign('picture1',$db['picture1']);
        $smarty->assign('size_str',$sizestr);


    }


}


}//end ad2 or POTD
?>