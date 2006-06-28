<?php
##############################################################################################
#                                                                                            #
#                                admin_functions.php                                         #
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
/*
Purpose: This file contains all functions related to admin controls only. Other functions may be
found in /includes/global_functions.php and/or in specific function files like database functions,
image functions, etc. these will be classed very logically related to what they do.
*/
function check_version($NULL,$check_version_and_query_project_for_latest)
{
    return false;
}

function dateToStr($date)
{
    //formats a date according to localization settings.
    //TODO: Scheduled for Obsolescence. Function will be replaced by a localization set by the user preferences
    global $dateformat,$timeoffset;

    $temp=explode(" ",$date);
    list($y,$m,$d)=explode("-",$temp[0]);
    list($hh,$mm,$ss)=explode(":",$temp[1]);
    if ($y=="00" && $m=="00")
    {
    return "NULL";
    }
    else
    {
    return date($dateformat,(mktime($hh,$mm,$ss,$m,$d,$y)+$timeoffset));
    //this seems silly - what is it needed for?
    }
}

function suppr($file)
{
// Delete a file on all Operating-System's
//TODO: Scheduled for obsolescence. This is exploitable, we will have better controls in the future
    $delete = @unlink($file);
    if (@file_exists($file))
    {
    $filesys = eregi_replace("/","\\",$file);
    $delete = @system("del $filesys");
        if (@file_exists($file))
        {
            $delete = @chmod ($file, 0775);
            $delete = @unlink($file);
            $delete = @system("del $filesys");
        }
    }
}

function admin_level_check($information)
{
//Purpose: This function will be used to double check an admin's ACL level before any administrative action, and after any admin login
//returns the admin's access level rights. The point is to verify data passed to the script by any means is indeed valid
//and matches with the information in the admin_acl table. Makes it possible to have a single login , but multiple admins
//and moderators . This is needed for a really busy site, as a single admin could be overwhelmed with all teh tasks to do
   return false;
}

function selectyesno($name,$value)
{
//TODO: Scheduled for Obsolescence- This is template logic which is better handled by the template system.
    if ($value=="yes")
    {
        $retval="<select name=\"$name\"><option value=\"yes\" SELECTED>Yes</option><option value=\"no\">No</option></select>\n";
    }
    else
    {
        $retval="<select name=\"$name\"><option value=\"yes\">Yes</option><option value=\"no\" SELECTED>No</option></select>\n";
    }
return $retval;
}

function selectyesnoreq($name,$value)
{
//TODO: Scheduled for Obsolescence- This is template logic which is better handled by the template system.
    if ($value=="req")
    {
        $retval="<select name=\"$name\"><option value=\"req\" SELECTED>Req</option><option value=\"yes\">Yes</option><option value=\"no\">No</option></select>\n";
    }
    elseif ($value=="yes")
    {
        $retval="<select name=\"$name\"><option value=\"req\">Req</option><option value=\"yes\" SELECTED>Yes</option><option value=\"no\">No</option></select>\n";
    }
    else
    {
        $retval="<select name=\"$name\"><option value=\"req\">Req</option><option value=\"yes\">Yes</option><option value=\"no\" SELECTED>No</option></select>\n";
    }
return $retval;
}

function selectyesnominmax($name,$value)
{
   //TODO: Scheduled for Obsolescence- This is template logic which is better handled by the template system.
    if ($value=="yes")
    {
        $retval="<select name=\"$name\"><option value=\"yes\" SELECTED>Yes</option><option value=\"minmax\">MinMax</option><option value=\"no\">No</option></select>\n";
    }
    elseif ($value=="minmax")
    {
        $retval="<select name=\"$name\"><option value=\"yes\">Yes</option><option value=\"minmax\" SELECTED>MinMax</option><option value=\"no\">No</option></select>\n";
    }
    else
    {
        $retval="<select name=\"$name\"><option value=\"yes\">Yes</option><option value=\"minmax\">MinMax</option><option value=\"no\" SELECTED>No</option></select>\n";
    }
return $retval;
}

function selecttype($name,$value)
{
 //TODO: Scheduled for Obsolescence- This is template logic which is better handled by the template system.
$seltext = "";
$selurl = "";
$selcheck = "";
$selselect="";
    if ($value=="text") $seltext="SELECTED";
    if ($value=="select") $selselect="SELECTED";
    if ($value=="url") $selurl="SELECTED";
    if ($value=="checkbox") $selcheck="SELECTED";

    $retval="<select name=\"$name\">
    <option value=\"text\" $seltext>Text</option>
    <option value=\"url\" $selurl>URL</option>
    <option value=\"select\" $selselect>Select</option>
    <option value=\"checkbox\" $selcheck>Checkbox</option>
    </select>\n";

return $retval;

}


?>
