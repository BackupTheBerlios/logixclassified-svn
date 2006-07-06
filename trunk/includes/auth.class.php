<?php
##############################################################################################
#                                                                                            #
#                                   auth.class.php
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
//login/auth functions
class Auth
{
    var $is_auth;
    var $user;
    var $pass;
    var $failuremsg;
    var $access_level;
    var $kick_out;
    var $hash_type;
    var $sessid;
    var $crypt_key;
    var $sessdata;
//this class allows authentication checking by comparing session data contained in database as being current
// also allows all sessions to be destroyed if the same user logs on from different systems. (ip and user-agent both must match for this)
//to take effect.
//since this class only checks data provided, and does no actual database work,other than the provided SESSION table,
// it is scalable for any type of user authentication, and access control.- stores user data encrypted to session db , doesnt actually set
//any data in $_SESSION global, so someone cant just hijack or read the sessid file and get user's login info.
//Yes, it's a bit paranoid, but.. can never be too paranoid when it comes to IT security
//including databases and flat files.
//requires a database object to be passed to teh authenticate and checklogin functions, must use BIND variables, such as ADOdb, E.G.
//not yet implemented, is the kick out feature..

//build the constructor
function Auth()
{
    $this->is_auth = false;//set it false this only constructs the class, does nothing else
    $this->user = ''; //initialize the user
    $this->pass = ''; //initialize password
    $this->failuremsg = '';//is set when any failure occurs here
    $this->access_level = null;//set access level- may or may not be used
    $this->kick_out = false;//set to true to kick out all instances of same user from different locations
    $this->sessid = session_id();//set to current session id
    $this->sessdata = array();
    if(!defined(AUTH_CRYPT_KEY))
    {
         define(AUTH_CRYPT_KEY,"h47l29ifg3m2");
    }
    if(!defined(AUTH_MAX_LIFETIME))
    {
        define(AUTH_MAX_LIFETIME,1440);//defaults to 1440 seconds- php's default
    }
    return true;//return a true value to indicate this has run
}

function Authenticate($username,$password,$postpass,$accesslevel,$db)
{
    //this is the guts of the class  - does all the actual authentication work.  set $is_auth = true if successful
    if($postpass != $password)
    {
        $this->failuremsg = "password";
        $this->is_auth = false;
        return $this->is_auth;
    }
    elseif($postpass == $password)
    {
        $data = array('user'=>$username,'pass'=>$password,'access_level'=>$accesslevel,'sessid'=>$this->sessid);
        $string = serialize($data);
        $info = _encrypt($string);
        $res = $db->execute("insert into auth_sessions (sessid,crypt_data,sess_time) values (?,?,now())",array($this->sessid,$info));
        $is_auth = true;
        $this->sessdata = $data;
        return $this->is_auth;
    }
    else
    {
        $this->failuremsg = "unknown issue";
        $this->is_auth = false;
        return $this->is_auth;
    }
}

function CheckLogin($username,$db)
{
//this queries session table for the actual session and gets user data. similar to ADOdb's cryptsession stuff  set $is_auth = true if successful
    _prunesession($db);  //prune database.
    $res = $db->execute("select * from auth_sessions where sessid=? and sess_time > now()-?",array($this->sessid,AUTH_MAX_LIFETIME));
    if($db->ErrorMsg() == '')
    {
        $data = mysql_fetch_array($res);
    }
    else
    {
        $data = array();
    }
    if(!empty($data))
    {
        $info = _decrypt($data['crypt_data']);
        $stuff = unserialize("::",$info);
        if($stuff['user'] == $username)
        {
            $this->is_auth = true;//we have a session and time, we can be pretty sure its same user
        }
        else
        {
            $this->is_auth = false;
            $this->LogOut($username,$this->sessid,$db);
        }
    }
    else
    {
        $this->is_auth = false;
         return false;
    }

    if($this->is_auth == true)
    {
        $this->sessdata = $stuff;
        return $this->is_auth;
    }
    else
    {
        return false;
    }
}

function LogOut($username,$sessionid,$db)
{
    //logs user out and runs destroy session, for security
    $db->execute("delete * from auth_sessions where sessid = ?",array($sessionid));
    session_destroy();
    return true;
}


function _prunesession($db)
{
   //prune old sessions from database, effectively timing out a user's login even if the session stored is still in the files.
    $db->execute("delete * from auth_sessions where sess_time < now()-?",array(AUTH_MAX_LIFETIME));
}
function _encrypt($data)
{
    //encrypt the data
    $iv_size = mcrypt_get_iv_size('des', 'ecb');
    $iv = mcrypt_create_iv($iv_size, 256);
    $crypttext = mcrypt_encrypt('des', AUTH_CRYPT_KEY, $data, 'ecb', $iv);
    return $crypttext;
}
function _decrypt($data)
{
    //decrypt the data
    $iv_size = mcrypt_get_iv_size('des', 'ecb');  //returns integer  8
    $iv = mcrypt_create_iv($iv_size, 256); //returns a binary value
    $cleartext = mcrypt_decrypt('des', AUTH_CRYPT_KEY, $data, 'ecb', $iv);
    return $cleartext;
}

}//end class
/*
Session database table
create table auth_sessions (
sessid varchar(100) not null default '',
crypt_data blob default null,
sess_time timestamp not null default 0,
primary key sessid(sessid)) type=MyISAM;

//explanation-
Sessid is primary key, as it is always unique- no auto_increment is used, because it is not scalable, auto increment will not "roll over"
crypt_data is teh encrypted session login information (user,pass,acces slevel, whatever we want to store, in any method of encryption including DES
and timestamp is a standard mysql timestamp, we dont need it readable, as it is merely to set session expiration time, so we can prune sessions
every so often.

*/

?>