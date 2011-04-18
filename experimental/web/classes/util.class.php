<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 18.04.11 17:20 Uhr
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
	
}
?>