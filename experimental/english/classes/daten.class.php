<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 05.06.11 13:25 Uhr
The object of the file:
all the important functions that deal with the data stored in MySQL in one class
*/

class Daten {
	
    function __construct() {
	    global $db;
    }	

    /*
     * Comparison of different coordinate transformations
	 * has no function for the actual application
	 * designed to test
     */
public static function compare_coord() {

		global $db;
		
	//appropriate number of select query in this case 20
	  $result20Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon FROM ".TABLE_PEGEL2." ORDER BY `pegelnummer` DESC LIMIT 0, 20 ");
	  if ($result20Pegel){
		echo 'successful connection and selection';
		echo '<br><br>';
		//write to logfile 
		$msg = "successful connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'error connection and selection';
		echo '<br><br>';
		//write to logfile 
		$msg = "error connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
	  
	  for ($i = 0; $i < mysql_num_rows($result20Pegel); $i++  )
	  { 
	  	while ($row20Pegel = mysql_fetch_array($result20Pegel))
	      	{ 
			$pegelnummer = $row20Pegel["pegelnummer"];
			$rw = $row20Pegel["Rechtswert_GK"];
			$hw = $row20Pegel["Hochwert_GK"];
			echo $pegelnummer;
			echo '<br><br>Database:<br><br>';
			echo $row20Pegel["lat"];
			echo '<br>';
			echo $row20Pegel["lon"];
		echo '<br><br>Similarity<br><br>';
		$avar = Transformation::GK_geo_6point($hw,$rw);
		echo $avar[0];
		echo '<br>';
		echo $avar[1];
			echo '<br><br>Similarity triangle<br><br>';
			$bvar = Transformation::GK_geo_6point_3eck($hw,$rw);
			echo $bvar[0];
			echo '<br>';
			echo $bvar[1];
			echo '<br><br>';

		} 
	  }
	}

    /*
	 * gk-oordinates converted into lat and lon using an external script
	 * transforms the coordinates only if no prior are set
	 * designed to test
     */	
public static function set_coord_extern() {

		global $db;
		
	//appropriate number of select query in this case 40-80
	  $result5Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon,daten_fehler FROM ".TABLE_PEGEL2." WHERE `lat` = '' AND `lon` = '' AND `Rechtswert_GK` != '0.00' AND `Hochwert_GK` != '0.00' ORDER BY `pegelnummer` DESC LIMIT 0, 40 ");
	  if ($result5Pegel){
		echo 'successful connection and selection';
		//write to logfile 
		$msg = "successful connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'error connection and selection';
		//write to logfile 
		$msg = "error connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
	  
	  for ($i = 0; $i < mysql_num_rows($result5Pegel); $i++  )
	  { 
	  	while ($row5Pegel = mysql_fetch_array($result5Pegel))
	      	{ 
			$pegelnummer = $row5Pegel["pegelnummer"];
			$rw = $row5Pegel["Rechtswert_GK"];
			$hw = $row5Pegel["Hochwert_GK"];
			
		$externeurl = 'http://raumplanung.tobwen.de/OSM/scripts/gk2wgs84.php?LAGE=DE_complete&RW='.$rw.'&HW='.$hw.'';
		$page = Util::get_document($externeurl);

		//change : to ,  result is LAT, LON, 52.6827554958, 9.70345365009
		$page = str_replace(":",",",$page);
		//explode , and write to array
		$avar = explode(",", $page);
		
		//use only field number 3 and 4, index starts by 0
		$lat = $avar[2];
		$lon = $avar[3];
		
	$result = $db->qry(" UPDATE ".TABLE_PEGEL2." SET 
	pegelnummer='$pegelnummer',
	lat='$lat',
	lon='$lon'
	WHERE pegelnummer='$pegelnummer' ");

	if ($result){
	echo 'successful update coordinates';
	echo '<br><br>';
		//write to logfile 
	$msg = "successful update coordinates";
	Log::write(LOG_OTHER, $msg);
	}
	else {
	echo 'error see sql.log';
	}
	 } 
	  }
		
	}

    /*
	 * gk-oordinates converted into lat and lon using [Gro76] and [HKL94]
	 * transforms the coordinates only if no prior are set
     */	
public static function set_coord_formel() {

		global $db;
		
	//appropriate number of select query in this case 500-1000
	  $result5Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon,daten_fehler FROM ".TABLE_PEGEL2." WHERE `lat` = '' AND `lon` = '' AND `Rechtswert_GK` != '0.00' AND `Hochwert_GK` != '0.00' ORDER BY `pegelnummer` DESC LIMIT 0, 500 ");
	  if ($result5Pegel){
		echo 'successful connection and selection';
		//write to logfile 
		$msg = "successful connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'error connection and selection';
		//write to logfile 
		$msg = "error connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
	  
	  for ($i = 0; $i < mysql_num_rows($result5Pegel); $i++  )
	  { 
	  	while ($row5Pegel = mysql_fetch_array($result5Pegel))
	      	{ 
			$pegelnummer = $row5Pegel["pegelnummer"];
			$rw = $row5Pegel["Rechtswert_GK"];
			$hw = $row5Pegel["Hochwert_GK"];
		
		//transformation with formula
		$avar = Transformation::GK_geo($hw,$rw);
		
		//importance: php has only one return value, here is an array that is set appropriately
		$lat = $avar[0];
		$lon = $avar[1];
		
	$result = $db->qry(" UPDATE ".TABLE_PEGEL2." SET 
	pegelnummer='$pegelnummer',
	lat='$lat',
	lon='$lon'
	WHERE pegelnummer='$pegelnummer' ");

	if ($result){
	echo 'successful update coordinates';
	echo '<br><br>';
		//write to logfile 
	$msg = "successful update coordinates";
	Log::write(LOG_OTHER, $msg);
	}
	else {
	echo 'error see sql.log';
	}
		
	} }
		
	}
		
    /*
	 * gk-oordinates converted into lat and lon using constants
	 * transforms the coordinates only if no prior are set
	 * designed to test
     */	
public static function set_coord_aehnlichkeit() {

		global $db;
		
	//appropriate number of select query in this case 1500 - 3000
	  $result5Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon,daten_fehler FROM ".TABLE_PEGEL2." WHERE `lat` = '' AND `lon` = '' AND `Rechtswert_GK` != '0.00' AND `Hochwert_GK` != '0.00' ORDER BY `pegelnummer` DESC LIMIT 0, 1500 ");
	  if ($result5Pegel){
		echo 'successful connection and selection';
		//write to logfile 
		$msg = "successful connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'error connection and selection';
		//write to logfile 
		$msg = "error connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
		
	  
	  for ($i = 0; $i < mysql_num_rows($result5Pegel); $i++  )
	  { 
	  	while ($row5Pegel = mysql_fetch_array($result5Pegel))
	      	{ 
			$pegelnummer = $row5Pegel["pegelnummer"];
			$rw = $row5Pegel["Rechtswert_GK"];
			$hw = $row5Pegel["Hochwert_GK"];
		
		//transformation with constants
		$avar = Transformation::GK_geo_6point($hw,$rw);
		
		//importance: php has only one return value, here is an array that is set appropriately
		$lat = $avar[0];
		$lon = $avar[1];
		
	$result = $db->qry(" UPDATE ".TABLE_PEGEL2." SET 
	pegelnummer='$pegelnummer',
	lat='$lat',
	lon='$lon'
	WHERE pegelnummer='$pegelnummer' ");

	if ($result){
	echo 'successful update coordinates';
	echo '<br><br>';
		//write to logfile 
	$msg = "successful update coordinates";
	Log::write(LOG_OTHER, $msg);
	}
	else {
	echo 'error see sql.log';
	}
		
	} }
		
	}

    /*
	 * gk-oordinates converted into lat and lon using [GJ11]
	 * transforms the coordinates only if no prior are set
	 * Bessel-Ellipsoid
     */	
public static function set_coord_bessel() {

		global $db;
		
	//appropriate number of select query in this case 400 - 600
	  $result5Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon,streifenzone,ellipsoid,daten_fehler,pnp FROM ".TABLE_PEGEL2." WHERE `lat` = '' AND `lon` = '' AND `Rechtswert_GK` != '0.00' AND `Hochwert_GK` != '0.00' AND `ellipsoid` = 'Bessel 1841' ORDER BY `pegelnummer` DESC LIMIT 0, 450 ");
	  if ($result5Pegel){
		//echo 'success';
		//write to logfile 
		$msg = "successful connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'error connection and selection';
		//write to logfile 
		$msg = "error connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
	  
	  for ($i = 0; $i < mysql_num_rows($result5Pegel); $i++  )
	  { 
	  	while ($row5Pegel = mysql_fetch_array($result5Pegel))
	      	{ 
			$pegelnummer = $row5Pegel["pegelnummer"];
			$rw = $row5Pegel["Rechtswert_GK"];
			$hw = $row5Pegel["Hochwert_GK"];
			$streifen = $row5Pegel["streifenzone"];
			$pnp = $row5Pegel["pnp"];

		//"explode by space" and wirte to array
		$avar = explode(" ", $streifen);
		
		//transformation with formula
		$bvar = Transformation::GK_geo_bessel($hw,$rw,$avar[1]);
		//var_dump($bvar);
		
		$cvar = Transformation::geo_bessel_kart($bvar[0],$bvar[1],$pnp);
		//var_dump($cvar);
		
		$dvar = Transformation::rotation_translation_bessel_wgs84($cvar[0],$cvar[1],$cvar[2]);
		//var_dump($dvar);
		
		$evar = Transformation::kart_wgs84_geo($dvar[0],$dvar[1],$dvar[2]);
		//var_dump($evar);
		
		//importance: php has only one return value, here is an array that is set appropriately
		$lat = $evar[0];
		$lon = $evar[1];
		
	$result = $db->qry(" UPDATE ".TABLE_PEGEL2." SET 
	pegelnummer='$pegelnummer',
	lat='$lat',
	lon='$lon'
	WHERE pegelnummer='$pegelnummer' ");

	if ($result){
	//echo '<br><br>';
	//echo 'success update';
		//write to logfile 
	$msg = "successful update coordinates pegelnummer '. $pegelnummer .'";
	Log::write(LOG_OTHER, $msg);
	}
	else {
	echo 'error see sql.log';
	}
		
	} }
		
}

    /*
	 * gk-oordinates converted into lat and lon using [GJ11]
	 * transforms the coordinates only if no prior are set
	 * Krassowsky-Ellipsoid
     */	
public static function set_coord_krass() {

		global $db;
		
	//appropriate number of select query in this case 400 - 600
	  $result5Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon,streifenzone,ellipsoid,daten_fehler,pnp FROM ".TABLE_PEGEL2." WHERE `lat` = '' AND `lon` = '' AND `Rechtswert_GK` != '0.00' AND `Hochwert_GK` != '0.00' AND `ellipsoid` = 'Krassovski' ORDER BY `pegelnummer` DESC LIMIT 0, 450 ");
	  if ($result5Pegel){
		//echo 'erfolg verbindung und auswahl';
		//write to logfile 
		$msg = "successful connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'error connection and selection';
		//write to logfile 
		$msg = "error connection and selection";
		Log::write(LOG_OTHER, $msg);
		}
	  
	  for ($i = 0; $i < mysql_num_rows($result5Pegel); $i++  )
	  { 
	  	while ($row5Pegel = mysql_fetch_array($result5Pegel))
	      	{ 
			$pegelnummer = $row5Pegel["pegelnummer"];
			$rw = $row5Pegel["Rechtswert_GK"];
			$hw = $row5Pegel["Hochwert_GK"];
			$streifen = $row5Pegel["streifenzone"];
			$pnp = $row5Pegel["pnp"];

		//"explode by space" and wirte to array
		$avar = explode(" ", $streifen);
		
		//transformation with formula
		$bvar = Transformation::GK_geo_krass($hw,$rw,$avar[1]);
		//var_dump($bvar);
		
		$cvar = Transformation::geo_krass_kart($bvar[0],$bvar[1],$pnp);
		//var_dump($cvar);
		
		$dvar = Transformation::rotation_translation_krass_wgs84($cvar[0],$cvar[1],$cvar[2]);
		//var_dump($dvar);
		
		$evar = Transformation::kart_wgs84_geo($dvar[0],$dvar[1],$dvar[2]);
		//var_dump($evar);
		
		//importance: php has only one return value, here is an array that is set appropriately
		$lat = $evar[0];
		$lon = $evar[1];
		
	$result = $db->qry(" UPDATE ".TABLE_PEGEL2." SET 
	pegelnummer='$pegelnummer',
	lat='$lat',
	lon='$lon'
	WHERE pegelnummer='$pegelnummer' ");

	if ($result){
	//echo '<br><br>';
	//echo 'success update';
		//write to logfile 
	$msg = "successful update coordinates pegelnummer '. $pegelnummer .'";
	Log::write(LOG_OTHER, $msg);
	}
	else {
	echo 'error see sql.log';
	}
		
	} }
		
}

		
	/**
	 * Saves of SimpleXML objects passed to the database
	 * here for the static XML document
	 * @param XML-Objekte
	 */
public static function save_update_xml($pegelnummer, $pegelname, $km, $messwert, $datum, $uhrzeit, $pnp, $tendenz) {

		global $db;
	
		/*
		//check all values if these are empty
		foreach (func_get_args() as $arg) {
			if(Util::my_empty($arg)==true) {
			$daten_fehler = 1;
			}
		}
		*/
		
		//better (see util.class.php for more details)
		
		// at 0 no bad data
		// on 1 are incomplete / incorrect data
		// on 2 the pnp is not available
		
		$daten_fehler = 0;
		
	    $array = func_get_args ();
		//sind messwert, pegelnummer, pegelname, name und namegebiet (see translate.txt for translation)
		if(Util::my_empty($array[0])==true || Util::my_empty($array[1])==true || Util::my_empty($array[3])==true) {
		$daten_fehler = 1;
		}
		//pnp (see translate.txt for translation)
		if(Util::my_empty($array[6])==true) {
		$daten_fehler = 2;
		}
		
		//change , to .
		$messwert = Util::getDotString($messwert);
		$km = Util::getDotString($km);
		$pnp = Util::getDotString($pnp);
		
		if ($pegelnummer != '' && $messwert != '') {
		
			$result = $db->qry(" INSERT INTO ".TABLE_PEGEL." (pegelnummer,pegelname,km,messwert,datum,uhrzeit,pnp,tendenz,daten_fehler) VALUES ('$pegelnummer','$pegelname','$km','$messwert','$datum','$uhrzeit','$pnp','$tendenz','$daten_fehler') ON DUPLICATE KEY UPDATE pegelname=VALUES(pegelname), km=VALUES(km), messwert=VALUES(messwert), datum=VALUES(datum), uhrzeit=VALUES(uhrzeit), pnp=VALUES(pnp), tendenz=VALUES(tendenz), daten_fehler=VALUES(daten_fehler); ");
			if ($result)
			{
			echo 'update: success';
		//write to logfile 
			$msg = "update: success";
			Log::write(LOG_OTHER, $msg);
			}
			else
			{
			echo 'update: error see sql.log';
			}
		}
		else
		{
		echo 'update: error pegelnummer or messwert missing';
		}
		return;
		}
		
	/**
	 * Saves of SimpleXML objects passed to the database
	 * here for the SOAP Response XML document
	 * @param XML-Objekte
	 */
public static function save_update_soap($pegelnummer, $pegelname, $km, $messwert, $datum, $uhrzeit, $pnp, $tendenz, $namegebiet, $name, $Rechtswert_GK, $Hochwert_GK, $streifenzone, $bezugssystem, $ellipsoid, $epsgCode) {

		global $db;
		
		/*
		//check all values if these are empty
		foreach (func_get_args() as $arg) {
			if(Util::my_empty($arg)==true) {
			$daten_fehler = 1;
			}
		}
		*/
		
		//better (see util.class.php for more details)
		
		// at 0 no bad data
		// on 1 are incomplete / incorrect data
		// on 2 the pnp is not available
		// for 3 missing coordinate information
		
		$daten_fehler = 0;
		
	    $array = func_get_args ();
		//sind messwert, pegelnummer, pegelname, name und namegebiet (see translate.txt for translation)
		if(Util::my_empty($array[0])==true || Util::my_empty($array[1])==true || Util::my_empty($array[3])==true || Util::my_empty($array[8])==true || Util::my_empty($array[9])==true) {
		$daten_fehler = 1;
		}
		//pnp (see translate.txt for translation)
		if(Util::my_empty($array[6])==true) {
		$daten_fehler = 2;
		}
		//rechtswert, hochwert, streifenzone und bezugssystem (see translate.txt for translation)
		if(Util::my_empty($array[10])==true || Util::my_empty($array[11])==true || Util::my_empty($array[12])==true || Util::my_empty($array[13])==true || Util::my_empty($array[14])==true) {
		$daten_fehler = 3;
		}
		
		// apply convert the string as a float
		// floatval
		// else goes also through . to replace sql will do the rest
		$messwert = Util::getDotString($messwert);
		$km = Util::getDotString($km);
		$pnp = Util::getDotString($pnp);


		//remove dot, convert , to .
		$Rechtswert_GK = Util::removeDotString($Rechtswert_GK);
		$Rechtswert_GK = Util::getDotString($Rechtswert_GK);
		$Hochwert_GK = Util::removeDotString($Hochwert_GK);
		$Hochwert_GK = Util::getDotString($Hochwert_GK);

		//(see util.class.php for more details)
		$tendenz = 	Util::convertTendenz($tendenz);
		/*
		if($tendenz == 1) { 
		$tendenz = 'Steigend'; 
		}
		elseif($tendenz == -1) {
		$tendenz = 'Fallend'; 
		}
		else {
		$tendenz = 'Gleich'; 
		}		
		*/
		
		if ($pegelnummer != '' && $messwert != '') {
		$result = $db->qry(" INSERT INTO ".TABLE_PEGEL2." (pegelnummer,pegelname,km,messwert,datum,uhrzeit,pnp,tendenz,namegebiet,name,Rechtswert_GK,Hochwert_GK,streifenzone,bezugssystem,ellipsoid,epsgCode,daten_fehler) VALUES ('$pegelnummer','$pegelname','$km','$messwert','$datum','$uhrzeit','$pnp','$tendenz','$namegebiet','$name','$Rechtswert_GK','$Hochwert_GK','$streifenzone','$bezugssystem','$ellipsoid','$epsgCode','$daten_fehler') ON DUPLICATE KEY UPDATE pegelname=VALUES(pegelname), km=VALUES(km), messwert=VALUES(messwert), datum=VALUES(datum), uhrzeit=VALUES(uhrzeit), pnp=VALUES(pnp), tendenz=VALUES(tendenz), daten_fehler=VALUES(daten_fehler); ");
		
		if ($result)
		{
		/*
		echo 'update: success';
		//write to logfile 
			$msg = "update: success";
			Log::write(LOG_OTHER, $msg);
		*/
		}
		else
		{
		echo 'update: error see sql.log';
		}
	}
	else
	{
	echo 'update: error pegelnummer or messwert missing';
	}
	return;
	}	


	/**
	 * Saves of SimpleXML objects passed to the database
	 * here for the static XML document
	 * Function adapted to use it in a shell (removed some echos)
	 * @param XML-Objekte
	 */
public static function save_update_xml_shell($pegelnummer, $pegelname, $km, $messwert, $datum, $uhrzeit, $pnp, $tendenz) {

		global $db;
		
		/*
		//check all values if these are empty
		foreach (func_get_args() as $arg) {
			if(Util::my_empty($arg)==true) {
			$daten_fehler = 1;
			}
		}
		*/
		
		//better (see util.class.php for more details)
		
		// at 0 no bad data
		// on 1 are incomplete / incorrect data
		// on 2 the pnp is not available
		
		$daten_fehler = 0;
		
	    $array = func_get_args ();
		//sind messwert, pegelnummer, pegelname, name und namegebiet (see translate.txt for translation)
		if(Util::my_empty($array[0])==true || Util::my_empty($array[1])==true || Util::my_empty($array[3])==true) {
		$daten_fehler = 1;
		}
		//pnp (see translate.txt for translation)
		if(Util::my_empty($array[6])==true) {
		$daten_fehler = 2;
		}
		
		//change , to .
		$messwert = Util::getDotString($messwert);
		$km = Util::getDotString($km);
		$pnp = Util::getDotString($pnp);
		
		if ($pegelnummer != '' && $messwert != '') {
		
			$result = $db->qry(" INSERT INTO ".TABLE_PEGEL2." (pegelnummer,pegelname,km,messwert,datum,uhrzeit,pnp,tendenz,daten_fehler) VALUES ('$pegelnummer','$pegelname','$km','$messwert','$datum','$uhrzeit','$pnp','$tendenz','$daten_fehler') ON DUPLICATE KEY UPDATE pegelname=VALUES(pegelname), km=VALUES(km), messwert=VALUES(messwert), datum=VALUES(datum), uhrzeit=VALUES(uhrzeit), pnp=VALUES(pnp), tendenz=VALUES(tendenz), daten_fehler=VALUES(daten_fehler); ");
			if ($result)
			{
			//echo 'update: success';
			}
			else
			{
			echo 'update: error see sql.log';
			}
		}
		else
		{
		echo 'update: error pegelnummer or messwert missing';
		}
		return;
		}
	
	}
?>