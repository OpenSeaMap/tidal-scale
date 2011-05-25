<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 25.05.11 12:25 Uhr
The object of the file:
Sets the MySQL functionality.
*/

class MySQL {

    /*
     * The database connection
     */
	private $dbCon = false;
	private $sumQryTime;
	private $sumQryCount;
	
	/*
	 * Provides a MySQL connection
	 * @param $host
	 * @param $user
	 * @param $pw
	 * @param $db
	 */
	public function __construct($host = MYSQL_HOST, $user = MYSQL_USER, $pw = MYSQL_PW, $db = MYSQL_DB) {
		
		$this->dbCon = @mysql_connect($host, $user, $pw);
		if ($this->dbCon) {
			if (!@mysql_select_db($db, $this->dbCon)) {
				$msqlInfo = 'DB "'.$db.'" not available!';
				Log::write(LOG_SQL, $msqlInfo);
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
     * Executes a sql query when this request is wrong, an error message is displayed
	 * and written to a log file
	 * @param qry - the string to be executed
	 * @param debug = false - if TRUE, the query string appears(echo)
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
	 * Returns the number of rows affected by the last query
	 * @return int - Number of affected rows
	 */
	public function affectedRows() {
		return mysql_affected_rows($this->dbCon);
	}
	
	/*
	 * Return the current Connection
	 * @return Mysql identifier or null
	 */
	public function getConnection() {
		return $this->dbCon;
	}
	
	/*
	 * Returns the number of by this time running data base queries
	 * @return int - number of queries
	 */
	public function getQueryCount() {
		return $this->sumQryCount;
	}
	
	/*
	 * Returns the sum of the duration of the previously executed queries
	 * @return int - querry time
	 */
	public function getQueryTimeSum() {
		return $this->sumQryTime;
	}
}
?>