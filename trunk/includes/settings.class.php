<?php
##############################################################################################
#                                                                                            #
#                                   settings.class.php
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
Class Settings
{
        var $_settings = array();

        function get($var)
        {
            $var = explode('.', $var); //we can have stuff like $db.host perhaps?

            $result = $this->_settings;
            foreach ($var as $key)
            {
                if (!isset($result[$key]))
                {
                    return false;
                }

                $result = $result[$key];
            }

            return $result;
        }

}

Class Settings_array Extends Settings
{
//This will be moved to each its own file based on what type of config we use
    function load ($file)
    {
        if (file_exists($file) == false)
        {
            return false;
        }
            // Include file
            include ($file);

     // Get declared variables
        unset($file);
        $vars = get_defined_vars();

        // Add to settings array
        foreach ($vars as $key => $val)
        {
                if ($key == 'this')
                {
                    continue;
                }

                $this->_settings[$key] = $val;
        }
    }
}

Class Settings_ini Extends Settings
{
    function load ($file)
    {
        if (file_exists($file) == false)
        {
            return false;
        }
        $this->_settings = parse_ini_file ($file, true);
    }
}
Class Settings_post Extends Settings
{
    function load ($data)
    {
        if (empty($data))
        {
            return false;
        }
        foreach($data as $key => $value)
        {
            $this->_settings[$key] = $value;
        }
    }
}
Class Settings_XML Extends Settings
{
    function load ($file)
    {
            if (file_exists($file) == false)
            {
                return false;
            }

            include ('xml_library.php'); /* gotten from   http://keithdevens.com/software/phpxml */
            $xml = file_get_contents($file);
            $data = XML_unserialize($xml);

            $this->_settings = $data['settings'];
    }
/* <?xml version="1.0" encoding="UTF-8"?>
<settings>
        <db>
                <name>test</name>
                <host>localhost</host>
        </db>
</settings>*/

}


?>

