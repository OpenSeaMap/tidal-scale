<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 25.05.11 12:25 Uhr
The object of the file:
Save error and success messages to a file.
*/

//predefined log files
define('LOG_SQL', PATH_LOG.'sql.log');
define('LOG_XML', PATH_LOG.'xml.log');
define('LOG_SOAP', PATH_LOG.'soap.log');
define('LOG_OTHER', PATH_LOG.'other.log');

class Log {

    function __constuct() {
    }
    
    /*
     * Writes an entry in the appropriate log file
	 * @param $logFile
	 * @param $msg
     */
    public static function write($logFile, $msg) {
    	$fp = fopen($logFile, 'a');
    	$log = '### '.date('d.m.Y H:i:s:'.substr(microtime(), 2, 3)).' ##############'."\n".$msg."\n\n";
    	fwrite($fp, $log);
    	fclose($fp);
    }
}
?>