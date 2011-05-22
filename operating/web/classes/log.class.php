<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 18.04.11 16:02 Uhr
Aufgabe der Datei:
Fehler- und Erfolgesmeldungen in einer Datei speichern.
*/

//vordefinierte logdateien
define('LOG_SQL', PATH_LOG.'sql.log');
define('LOG_XML', PATH_LOG.'xml.log');
define('LOG_SOAP', PATH_LOG.'soap.log');
define('LOG_OTHER', PATH_LOG.'other.log');

class Log {

    function __constuct() {
    }
    
    /*
     * Schreibt einen Eintrag in die entsprechende Log Datei
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