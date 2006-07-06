<?php
##############################################################################################
#                                                                                            #
#                                   benchmark.class.php
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
class DebugLib extends c_Timer
{

    var $scratch_dir = "";
    var $memory_array = array();
    function parse_timer_log($parse_time,$page_name)
    {
        /*  This function takes the start and end times and the page name and writes a log of page parse time per request.
        While this will very likely affect performance and benchmarking, we do want to log this so we can study how page parse times differ, especially
        using apachebench- becasue we can get thuosands of entries and monitor how parse time will "ramp up" and differ between page loads.
        which will be another excellent performance indicator.
        log format is  Y-m-d H:i:s -- page_name -- parse_time \n
        */
        $stamp = date('Y-m-d H:i:s');
        $hdl = fopen($this->scratch_dir.'/time.log','a+');
        $res = fwrite($hdl,"$stamp\t$page_name\t $parse_time\n");
        fclose($hdl);
        return $res;
    }
    function write_memory_log($parse_time = 0)
    {
            /* OK for memory log, we take the array we are fed, and iterate through the array by filename, and store this memory information
        to our log, started and ended by parse time figures so we know how much time is taken to do this function
        and write the log file. because we are gonna be doing benchmarking tests, we will not be using these functions while running apachebench
        although I'd love to- but we can get a very good idea of teh state of the current code from these .
        log format is :
        filename  timestamp
        \t  line number   memory use \n

        */
        $stamp = date("Y-m-d H:i:s");

        $hdl = fopen($this->scratch_dir.'/memory.log','a+');
        foreach($this->memory_array as $file => $sizearr)
        {
            $res = fwrite($hdl,"$file -- $stamp\n");
            //var_dump($sizearr);
            foreach($sizearr as $point)
            {
                foreach($point as $line=>$size)
                {
                    $sizea = round($size/1024);
                    $res2 = fwrite($hdl,"\tLine:$line \tKbytes:$sizea\n");
                }
            }

        }
        $entime = microtime();
        $etime = $entime - $parse_time;
        $res3 = fwrite($hdl,"$stamp ptime:$etime\n");
        fclose($hdl);
        return "Result: $res  $res2";
    }
    function memory_checkpoint($line,$file)
    {
            /* This function checks memory usage at points where called, typically just before and just after an include and
        stores the info in an array keyed by filename  $memory_array('filename'=>array($line=>$memory_usage,$line=>$memory_usage))
        and returns the whole array, which whenever we want, we log to a file using memory log function.
        Why dont we store to db or just write results? because this *adds* processing time and will obviously affect benchmarking
        We know this will add memory usage, but if we know this function is only loaded once , and we know the array size and etc, we can determine just how much
        memory is used by this function and deduct it from benchmarks, although there is little need.
        */
        $memcheck = memory_get_usage();
        if(!array_key_exists($file,$this->memory_array))
        {
            $this->memory_array[$file] = array();
        }
        array_push($this->memory_array[$file],array($line=>$memcheck));
        return $this->memory_array;
    }


}

?>