<?php
##############################################################################################
#                                                                                            #
#                                   mysql.class.php
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
if(!strpos($_SERVER['PHP_SELF'],'mysql.class.php') === false)
{
  die("YOU MAY NOT ACCESS THIS FILE DIRECTLY");
}
class DbMysql
{
    var $_error = "";
    var $_sqlerror = "";
    var $_obj = "";
    var $_resource = "";
    var $fields = array();
    var $querystring = "";
    var $mode = "";
    var $prefix = "";
    function connect($host,$user,$pass,$dbase,$db_persistent)
    {
        if($db_persistent === true)
        {
            $this->_resource = mysql_pconnect($host,$user,$pass);
        }
        else
        {
            $this->_resource = mysql_connect($host,$user,$pass);
        }
        $this->_obj = mysql_select_db($dbase);
        $this->_sqlErrorMsg($this->_resource);
        return $this->_resource;
    }
    function execute($sql,$items,$link)
    {
        $res = $this->_safe_query($sql,$items,$link);
        while ($row = @mysql_fetch_assoc($res))
        {
            array_push($this->fields,$row);
        }

        if($this->_sqlErrorMsg($res))
        {
           return false;
        }
        else
        {
           return true;
        }

    }
    function get1row($sql,$items,$link)
    {
        $res = $this->_safe_query($sql,$items,$link);
       $this-> _sqlErrorMsg($res);
        return(@mysql_fetch_assoc($res));
    }
    function _safe_query($query,$values,$link)
    {
        $query_parts = preg_split("/\?/", $query);
        $this->querystring = array_shift($query_parts);
        $needed_values = count($query_parts);
        $ii=count($values);
        foreach ($values as $value)
        {
            $value = "'" . mysql_escape_string($value) . "'";
            $this->querystring .= $value.array_shift($query_parts);
        }

        if (count($query_parts))
        {
            $this->_funcErrorMsg('Query "<i>'.$query.'</i>" needs'.
                $needed_values.' values, you only sent '.$ii);
        }
        //print "$safe_query<br>";
        return mysql_query($this->querystring,$link);

    }

    function _mysql_pass ($passstring)
    {
        $nr = 0x50305735 ;
        $nr2 = 0x12345671 ;
        $add = 7 ;
        $charArr = preg_split( "//", $passStr ) ;

        foreach ( $charArr as $char )
        {
                if ( ( $char == '' ) || ( $char == ' ' ) || ( $char == '\t') )
                {
                        continue;
                }

                $charVal = ord( $char ) ;
                $nr ^= ( ( ( $nr & 63 ) + $add ) * $charVal ) + ( $nr << 8 ) ;
                $nr2 += ( $nr2 << 8 ) ^ $nr ;
                $add += $charVal ;
        }

        return sprintf( "%08x%08x", ( $nr & 0x7fffffff ), ( $nr2 & 0x7fffffff ) ) ;
    }

    function _sqlErrorMsg($mysqlresult)
    {
        $this->_sqlerror = mysql_error();
        if(empty($this->_sqlerror))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    function _funcErrorMsg($message)
    {
        $this->_error = $message;
        if(empty($this->_error))
        {
            return false;
        }
        else
        {
            return true;
        }

    }
    function ErrorMsg()
    {
        if($this->_error != "")
        {
            $this->mode = "function";
            $errormsg = $this->_error;
        }
        elseif($this->_sqlerror != "")
        {
            $this->mode="sql";
            $this->querystring;
            $errormsg = $this->_sqlerror;
        }
        else
        {
            $this->mode = "clear";
            $errormsg = "";
        }
        return $errormsg;
    }
}
?>