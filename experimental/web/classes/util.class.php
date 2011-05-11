<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 11.05.11 12:15 Uhr
*/

/*
alle wichtigen Funktionen in einer hilfsklasse zusammengefasst
*/
class Util {
	
	/*
	 * Format für Datum und Zeitangaben
	 * (wie bei date()-Funktion)
	 */
	const DATETIME_FORMAT = 'd.m.y H:i';
	
	/*
     * Singleton
     */
	private static $singleton = null; 

    public static function getInstance() {

		if (!self::$singleton instanceof Util) {
			self::$singleton = new Util();
		}
		return self::$singleton;
	}

	function __constuct() {
    	
    }

    /*
     * ersetzt alle , durch einen punkt in dem string
	 * @param $string
	 * @retrun $string
     */
 	public static function getDotString($string) {
		$string = str_replace(',','.', $string);
		return $string;
    }
	
    /*
     * entfernt alle . aus einem string
	 * @param $string
	 * @retrun $string
     */
 	public static function removeDotString($string) {
		$string = str_replace('.','', $string);
		return $string;
    }
	
	/*
     * Wandelt die Tendenz in Worte um
     * @param $tendenz zahl
     * @return $tendenz string
     */
	public static function convertTendenz($tendenz){
	
		if($tendenz == 1) { 
		$tendenz = 'Steigend'; 
		}
		elseif($tendenz == -1) {
		$tendenz = 'Fallend'; 
		}
		else {
		$tendenz = 'Gleich'; 
		}	
		return $tendenz;
	}
	
    /*
     * zu utf-8 umwandlung
	 * @param $str
	 * @retrun $str
     */	
	public static function fix_text($str) {
		return htmlspecialchars(utf8_decode($str));
	}
	
    /*
	 * fsock funktion
	 * baut eine verbindung auf und holt den inhalt und speichert diesen in einer variablen
	 * erstellt mithilfe von [ST09]
	 * @param $url
	 * @retrun $content
     */	
	public static function get_document($url) {
    $content = '';
    $is_header = TRUE;
    $base_url = parse_url($url);
    if ($fp = @fsockopen($base_url['host'], 80, $errno, $errstr, 5)) {
        if (!empty($base_url['query'])) {
            $query = '?'.$base_url['query'];
        } else {
            $query = '';
        }
        $data = 'GET '.$base_url['path'].$query." HTTP/1.0\r\n".
                        'Host: '.$base_url['host']."\r\n".
                        "Connection: Close\r\n\r\n";
        stream_set_timeout($fp, 5);
        fputs($fp, $data);
        while(!feof($fp)) {
        $line = fgets($fp, 4096);
        if (!$is_header) {
            $content .= $line;
        } else {
            if (strlen(trim($line)) == 0) {
                $is_header = FALSE;
            }
            }
        }
        fclose($fp);
        return $content;
    } else {
        return FALSE;
		}
	}

    /*
     * wert anzeige, wenn != '' sonst fehler meldung
	 * @param $wert
	 * @retrun $wert oder meldung
     */		
	public static function echo_wert($wert) {
		if($wert != '') {
		echo $wert;
		}else{
		echo 'achtung keine daten vorhanden';
		}
		return;
	}
	
    /*
	 * aus der php doku
	 * in cases when "0" is not intended to be empty, here is a simple function to safely test for an empty string (or mixed variable)
	 * da messwerte und pnp 0 sein können, dann aber nicht leer sind
	 * @param $string
	 * @retrun $string oder bool
     */		
	public static function my_empty($string){
		$string = trim($string);
		if(!is_numeric($string)) return empty($string);
		return FALSE;
	}
	
    /*
     * Liefert die Nanosekunden aus einem microtime() String
     * @param $string - Der String vom microtime aufruf, falls leer aktuelle microtime
     * @return float - aktuelle Zeit mit nanosekunden
     */
    public static function getNanoSeconds($string = '') {
    	if (empty($string)) {
    		$string = microtime();
    	}
    	$zeittemp = explode(" ",$string); 
		$zeitmessung = $zeittemp[0] + $zeittemp[1]; // Timestamp + Nanosek 
		return $zeitmessung;
    }
	
    /*
     * datei entfernen geht so nur bei linux
	 * @param $datei
	 * @retrun meldung
     */		
    public static function rm_datei($datei) {
		if(file_exists($datei)) {
		$escape = escapeshellarg($datei);
		exec("rm " . $escape);
		echo '<p>Die Datei '. $datei .' wurde entfernt</p>';
		} else {
		echo '<p>Die Datei '. $datei .' ist nicht vorhanden</p>';
		}
		return;
	}

	/*
     * fuer linux , fuer windows wget installieren oder eine andere methode nutzen
	 * @param $datei
     */		
    public static function wget_datei($datei) {
		//escapeshellarg — Maskiert eine Zeichenkette (String), um sie als Shell-Argument benutzen zu können
		$escape = escapeshellarg($datei);
		//exec — Führt ein externes Programm aus
		exec("wget " . $escape);
		return;
	}
	
	/*
     * Wandelt DATE_W3C in ein "normales" Datum um
	 * http://php.net/manual/en/class.datetime.php
	 * Die if abfrage ist um kompatiblität mit php versionen kleiner 5.3 herzustellen,
	 * allerdings so nur abwärtskompatibel bis 5.1
     * @param $datew3c
     * @return $date
     */
	public static function convertTime($datew3c){
		$localTimezone = new DateTimeZone("Europe/Berlin");
		$date = new DateTime($datew3c, $localTimezone);
		//http://de3.php.net/manual/de/function.phpversion.php
		 if (strnatcmp(phpversion(),'5.2.0') >= 0)
		{
		$date->setTimezone($localTimezone);
		}
		else
		{
		//$date->setTimezone($localTimezone);
		}
		return $date;
	}
	
	/*
     * Wandelt einen nur aus GROSSBUCHSTABEN bestehenden String um
	 * http://php.net/manual/de/function.ucfirst.php
     * @param $string
     * @return $string
     */
	public static function convertUpperString($string){
	//$string = ucfirst(strtolower($string));
	$string = ucwords(strtolower($string));
		return $string;
	}
	
	/*
     * Wandelt die Tendenz in Pfeile um
	 * Idee von Markus Bärlocher
     * @param $tendenz
     * @return $tendenz
     */
	public static function convertArrow($tendenz){
	
		if($tendenz == 'Steigend') { 
		$tendenz = '&uarr;'; 
		}
		elseif($tendenz == 'Fallend') {
		$tendenz = '&darr;'; 
		}
		else {
		$tendenz = '&harr;'; 
		}	
		
	/*
	&harr;	&#8596;	gleich
	&darr;	&#8595;	fallend
	&uarr;	&#8593;	steigend
	*/
		
		return $tendenz;
	}

	/*
     * Wandelt die Tendenz in Pfeile um für OSM angepasst
	 * Idee von Markus Bärlocher
     * @param $tendenz
     * @return $tendenz
     */
	public static function convertArrow_osm($tendenz){
	
		if($tendenz == 'Steigend') { 
		$tendenz = '\u2191'; 
		}
		elseif($tendenz == 'Fallend') {
		$tendenz = '\u2193'; 
		}
		else {
		$tendenz = '\u2194'; 
		}	
		
	/*
	name	nummer	wort	hexwert
	&harr;	&#8596;	gleich \u2194
	&darr;	&#8595;	fallend \u2193
	&uarr;	&#8593;	steigend \u2191
	*/
		return $tendenz;
	}
	
	/*
     * Stellt Informationen zu den Fehlern dar
     * @param $daten_fehler
     * @return $string
     */
	public static function show_daten_fehler($fehler){
	
		//bei 0 gibt es keine fehlerhaften daten
		//bei 1 liegen unvollstaendige / fehlerhafte daten vor
		//bei 2 ist der pnp nicht vorhanden
		//bei 3 fehlen koordinaten informationen
		if($fehler == 0) { 
		$fehler = '<br>'; 
		}
		elseif($fehler == 1) {
		$fehler = '<br><font color="red"><b>Ausser Betrieb</b></font><br>'; 
		}
		elseif($fehler == 2) {
		$fehler = '<br><font color="red">Kein PnP Wert vorhanden</font><br>'; 
		}
		else {
		$fehler = '<br>'; 
		}	
		return $fehler;
	}
	
	/*
     * Stellt Informationen zu den Fehlern dar für OSM angepasst
     * @param $daten_fehler
     * @return $string
     */
	public static function show_daten_fehler_osm($fehler){
	
		//bei 0 gibt es keine fehlerhaften daten
		//bei 1 liegen unvollstaendige / fehlerhafte daten vor
		//bei 2 ist der pnp nicht vorhanden
		//bei 3 fehlen koordinaten informationen
		if($fehler == 0) { 
		$fehler = ''; 
		}
		elseif($fehler == 1) {
		$fehler = '<font color="red"><b>Ausser Betrieb</b></font>'; 
		}
		elseif($fehler == 2) {
		$fehler = '<font color="red">Kein PnP Wert vorhanden</font>'; 
		}
		else {
		$fehler = ''; 
		}	
		return $fehler;
	}
	
	/*
     * Liefert einen "fehlerfreien" String
     * @param fstring - Name
     * @return String - verbesserter Name
     */
    public static function getCleanString($string) {
    	$umlaute = array("Ã–","Ã„","Ãœ");
		$replace = array('&Auml;','&Ouml;','&Uuml;');
		//Umlaute ersetzen
		$string2 = str_replace($umlaute, $replace, $string);
    	return $string2;
    }
	
	/*
     * Liefert einen "fehlerfreien" String für OSM angepasst
	 * es werden direkt kleinbuchstaben eingesetzt da die php strtolower funktion die escaped zeichen ignoriert
     * @param fstring - Name
     * @return String - verbesserter Name
     */
    public static function getCleanString_osm($string) {
    	$umlaute = array("Ã–","Ã„","Ãœ");
		$replace = array('\u00E4','\u00F6','\u00FC');
		//Umlaute ersetzen
		$string2 = str_replace($umlaute, $replace, $string);
    	return $string2;
    }
		
}
?>