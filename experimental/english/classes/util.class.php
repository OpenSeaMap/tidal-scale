<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 25.05.11 12:25 Uhr
The object of the file:
All important functions are integrated into a helper class
*/

class Util {
	
	/*
	 * The format for date and time
	 * (as in date () function)
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
     * replaces all, by a point in the string
	 * @param $string
	 * @retrun $string
     */
 	public static function getDotString($string) {
		$string = str_replace(',','.', $string);
		return $string;
    }
	
    /*
     * removes all . from a string
	 * @param $string
	 * @retrun $string
     */
 	public static function removeDotString($string) {
		$string = str_replace('.','', $string);
		return $string;
    }
	
	/*
     * Converts tend into words
     * @param $tendenz number
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
     * to utf-8 conversion
	 * @param $str
	 * @retrun $str
     */	
	public static function fix_text($str) {
		return htmlspecialchars(utf8_decode($str));
	}
	
    /*
	 * fsock function
	 * establishes a connection and gets the content and stores it in a variable
	 * created using [ST09]
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
     * value display, though! ='' else error message
	 * @param $wert
	 * @retrun $wert or message
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
	 * from the php-docu
	 * in cases when "0" is not intended to be empty, here is a simple function to safely test for an empty string (or mixed variable)
	 * as measured values and pnp can be 0, "but not empty"
	 * @param $string
	 * @retrun $string or bool
     */		
	public static function my_empty($string){
		$string = trim($string);
		if(!is_numeric($string)) return empty($string);
		return FALSE;
	}
	
    /*
     * Return the nanosecond from a microtime () as String
     * @param $string - The string of microtime calling, if empty current microtime
     * @return float - current time with nanosecond
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
     * Remove the file is only available from linux
	 * @param $datei
	 * @retrun message
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
     * only for linux, or windows install wget for windows or use a different method
	 * @param $datei
     */		
    public static function wget_datei($datei) {
		//escapeshellarg � Escape a string (String) to use it as a shell argument
		$escape = escapeshellarg($datei);
		//exec � Execute an external program
		exec("wget " . $escape);
		return;
	}
	
	/*
     * convert DATE_W3C to "normal" Date
	 * http://php.net/manual/en/class.datetime.php
	 * compatibility with PHP versions less than 5.3
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
     * Converts a string consisting only of capital letters
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
	 * Converts the trend to arrows
	 * Idea by Markus Baerlocher
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
	 * Converts the trend to arrows for OSM
	 * Idea by Markus Baerlocher
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
	name	number	word	hexwert
	&harr;	&#8596;	gleich \u2194
	&darr;	&#8595;	fallend \u2193
	&uarr;	&#8593;	steigend \u2191
	*/
		return $tendenz;
	}
	
	/*
	 * Provides information about the errors
     * @param $daten_fehler
     * @return $string
     */
	public static function show_daten_fehler($fehler){
	
		// at 0 no bad data
		// on 1 are incomplete / incorrect data
		// on 2 the pnp is not available
		// for 3 missing coordinate information
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
	 * Provides information about the errors adapted for OSM
     * @param $daten_fehler
     * @return $string
     */
	public static function show_daten_fehler_osm($fehler){
	
		// at 0 no bad data
		// on 1 are incomplete / incorrect data
		// on 2 the pnp is not available
		// for 3 missing coordinate information
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
     * Provides an "error free" String
     * @param fstring - Name
     * @return String - "better" Name
     */
    public static function getCleanString($string) {
    	$umlaute = array("Ö","Ä","Ü");
		$replace = array('&Auml;','&Ouml;','&Uuml;');
		//replace "Umlauts"
		$string2 = str_replace($umlaute, $replace, $string);
    	return $string2;
    }
	
	/*
     * Provides an "error free" String adapted for OSM
	 * lower case letters can be used directly, because the php strtolower function ignores the escaped character
     * @param fstring - Name
     * @return String - "better" Name
     */
    public static function getCleanString_osm($string) {
    	$umlaute = array("Ö","Ä","Ü");
		$replace = array('\u00E4','\u00F6','\u00FC');
		//replace "Umlauts"
		$string2 = str_replace($umlaute, $replace, $string);
    	return $string2;
    }
		
}
?>