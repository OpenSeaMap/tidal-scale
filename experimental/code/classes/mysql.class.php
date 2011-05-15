<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 23.04.11 15:20 Uhr
*/

class MySQL {

    /*
     * Die Datenbank Verbindung
     */
	private $dbCon = false;
	private $sumQryTime;
	private $sumQryCount;
	
	/*
	 * Stellt eine MySQL Verbindung her
	 * @param $host
	 * @param $user
	 * @param $pw
	 * @param $db
	 */
	public function __construct($host = MYSQL_HOST, $user = MYSQL_USER, $pw = MYSQL_PW, $db = MYSQL_DB) {
		
		$this->dbCon = @mysql_connect($host, $user, $pw);
		if ($this->dbCon) {
			if (!@mysql_select_db($db, $this->dbCon)) {
				Util::error('DB "'.$db.'" not available!');
			}
		} else {
			$msqlInfo = 'DB Connection to "'.$host.'" (User:"'.$user.' failed") '; 
			Log::write(LOG_SQL, $msqlInfo);
		}
		
		$this->sumQryTime = 0;
		$this->sumQryCount = 0;

	}
	
	public function __destruct() {
		@mysql_close($this->dbCon);
	}
	
    /*
     * Führt ein sql query aus, wenn diese anfange falsch ist wird eine fehlermeldung ausgegeben
	 * und diese in ein log File geschrieben
	 * @param qry - der auszuführende string
	 * @param debug = false - wenn TRUE, wird der query string angezeigt(echo)
	 * @return das mysql result 
     */
	public function qry($qry, $debug = false) {
		
		//$debug = true;	
    	if ($debug) {
			echo '<br/><br/>'."\n\n".$qry.'<br/><br/>'."\n\n";
		}
		$bef = Util::getNanoSeconds();
		$result = mysql_query($qry, $this->dbCon);
		$aft = Util::getNanoSeconds();
		
	    if (!$result) {
	    	$msg = 'Falsches SQL Query:'."\n\n";
	    	$msg.= $qry."\n\n";
	    	$msg.= mysql_error($this->dbCon)."\n\n";
	    	Log::write(LOG_SQL, $msg);
			//die();
	    }
    	
    	$this->sumQryCount++;
    	//get qry time
    	$this->sumQryTime+= ($aft-$bef);
    	
    	if ($debug) {
    		if (strtolower(substr(trim($qry), 0, 6)) == 'select') {
				echo '<br/>size:'.mysql_num_rows($result);
			} else {
				echo '<br/>affected rows:' . $this->affectedRows();
			}
			echo '<br/>duration: '.($aft-$bef);
    	}
    	
		return $result;
	}
	
	/*
	 * Gibt die Anzahl der betroffenen Zeilen der letzten Abfrage zurück
	 * @return int - Anzahl betroffener Zeilen
	 */
	public function affectedRows() {
		return mysql_affected_rows($this->dbCon);
	}
	
	/*
	 * Liefert die aktuelle Connection
	 * @return Mysql identifier oder null
	 */
	public function getConnection() {
		return $this->dbCon;
	}
	
	/*
	 * Liefert die Anzahl der bis zu diesem Zeitpunkt ausgeführten Datenbankabfragen
	 * @return int - Anzahl Abfragen
	 */
	public function getQueryCount() {
		return $this->sumQryCount;
	}
	
	/*
	 * Liefert die Summe der Dauer der bisher ausgeführten Abfragen
	 * @return int - Abfrage Zeit
	 */
	public function getQueryTimeSum() {
		return $this->sumQryTime;
	}
}
?>