<?php
//test ausgaben entfernen
//echos entfernen


/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 01.05.11 15:25 Uhr
*/

/*
* alle wichtigen Funktionen die mit der Datenspeicherung in MySQL etwas zu tun haben,
* in einer Klasse zusammengefasst
*/
class Daten {
	
    function __construct() {
	    global $db;
    }	

    /*
     * vergleich der verscheidenen Koordinaten-Transformationen
	 * hat für die eigentliche Anwendung keine Funktion
	 * zum testen gedacht
     */
public static function compare_coord() {

		global $db;
		
	//geeignete Anzahl der Abfrage wählen hier 20
	  $result20Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon FROM ".TABLE_PEGEL2." ORDER BY `pegelnummer` DESC LIMIT 0, 20 ");
	  if ($result20Pegel){
		echo 'erfolg verbindung und auswahl';
		echo '<br><br>';
		//ins logfile schreiben
		$msg = "erfolg verbindung und auswahl";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'fehler verbindung und auswahl';
		echo '<br><br>';
		//ins logfile schreiben
		$msg = "erfolg verbindung und auswahl";
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
			echo '<br><br>Datenbank:<br><br>';
			echo $row20Pegel["lat"];
			echo '<br>';
			echo $row20Pegel["lon"];
		echo '<br><br>Aehnlichkeit<br><br>';
		$avar = Transformation::GK_geo_6point($hw,$rw);
		echo $avar[0];
		echo '<br>';
		echo $avar[1];
			echo '<br><br>Aehnlichkeit_3eck<br><br>';
			$bvar = Transformation::GK_geo_6point_3eck($hw,$rw);
			echo $bvar[0];
			echo '<br>';
			echo $bvar[1];
			echo '<br><br>';

		} 
	  }
	}
	
    /*
	 * hiermit werden die GK koordinaten in lat und lon umgerechnet mithilfe eines externen Scripts
	 * transformiert die Koordinaten nur, wenn vorher keine gesetzt sind
	 * zum testen gedacht
     */	
public static function set_coord_extern() {

		global $db;
		
	  //geeignete anzahl der abfrage wählen hier ehr weniger 20-40 sollte okay sein
	  $result5Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon,daten_fehler FROM ".TABLE_PEGEL2." WHERE `lat` = '' AND `lon` = '' AND `Rechtswert_GK` != '0.00' AND `Hochwert_GK` != '0.00' ORDER BY `pegelnummer` DESC LIMIT 0, 20 ");
	  if ($result5Pegel){
		echo 'erfolg verbindung und auswahl';
		//ins logfile schreiben
		$msg = "erfolg verbindung und auswahl";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'fehler verbindung und auswahl';
		//ins logfile schreiben
		$msg = "fehler verbindung und auswahl";
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

		//den : zu , dann hat man LAT, LON, 52.6827554958, 9.70345365009
		$page = str_replace(":",",",$page);
		//jetzt wird am , getrennt und in ein arry geschrieben
		$avar = explode(",", $page);
		
		//wichtig ist nur feld 3 und 4 index startet bei 0
		$lat = $avar[2];
		$lon = $avar[3];
		
	$result = $db->qry(" UPDATE ".TABLE_PEGEL2." SET 
	pegelnummer='$pegelnummer',
	lat='$lat',
	lon='$lon'
	WHERE pegelnummer='$pegelnummer' ");

	if ($result){
	echo 'erfolg update koordinaten';
	echo '<br><br>';
	//ins logfile schreiben
	$msg = "erfolg update koordinaten";
	Log::write(LOG_OTHER, $msg);
	}
	else {
	echo 'fehler bitte in sql.log nachsehen';
	}
	 } 
	  }
		
	}
	
    /*
	 * hiermit werden die GK-Koordinaten in lat und lon umgerechnet mit hilfe von festen Paramtern
	 * transformiert die Koordinaten nur, wenn vorher keine gesetzt sind
	 * zum testen gedacht
     */	
public static function set_coord_aehnlichkeit() {

		global $db;
		
			//geeignete Anzahl der Abfrage wählen, damit die Berechnungen in unter 30 sekunden erfolgen können, 1500 - 2000 ist okay
	  $result5Pegel = $db->qry(" SELECT pegelnummer,Rechtswert_GK,Hochwert_GK,lat,lon,daten_fehler FROM ".TABLE_PEGEL2." WHERE `lat` = '' AND `lon` = '' AND `Rechtswert_GK` != '0.00' AND `Hochwert_GK` != '0.00' ORDER BY `pegelnummer` DESC LIMIT 0, 500 ");
	  if ($result5Pegel){
		echo 'erfolg verbindung und auswahl';
		//ins logfile schreiben
		$msg = "erfolg verbindung und auswahl";
		Log::write(LOG_OTHER, $msg);
		}
		else {
		echo 'fehler verbindung und auswahl';
		//ins logfile schreiben
		$msg = "fehler verbindung und auswahl";
		Log::write(LOG_OTHER, $msg);
		}
		
	  
	  for ($i = 0; $i < mysql_num_rows($result5Pegel); $i++  )
	  { 
	  	while ($row5Pegel = mysql_fetch_array($result5Pegel))
	      	{ 
			$pegelnummer = $row5Pegel["pegelnummer"];
			$rw = $row5Pegel["Rechtswert_GK"];
			$hw = $row5Pegel["Hochwert_GK"];
		
		//transformation mit aehnlichkeit
		$avar = Transformation::GK_geo_6point($hw,$rw);
		
		//wichtig ist php hat nur einen return wert dieser ist hier ein array also passend setzten
		$lat = $avar[0];
		$lon = $avar[1];
		
	$result = $db->qry(" UPDATE ".TABLE_PEGEL2." SET 
	pegelnummer='$pegelnummer',
	lat='$lat',
	lon='$lon'
	WHERE pegelnummer='$pegelnummer' ");

	if ($result){
	echo 'erfolg update koordinaten';
	echo '<br><br>';
	//ins logfile schreiben
	$msg = "erfolg update koordinaten";
	Log::write(LOG_OTHER, $msg);
	}
	else {
	echo 'fehler bitte in sql.log nachsehen';
	}
		
	} }
		
	}	

	/**
	 * Speichert die von SimpleXML übergebenen Objekte in der Datenbank
	 * hier für das statische XML-Dokument
	 * @param XML-Objekte
	 */
public static function save_update_xml($pegelnummer, $pegelname, $km, $messwert, $datum, $uhrzeit, $pnp, $tendenz) {

		global $db;
	
		/*
		//alle werte auf korrektheit pruefen, mal erst nur ob diese leer sind
		foreach (func_get_args() as $arg) {
			if(Util::my_empty($arg)==true) {
			$daten_fehler = 1;
			}
		}
		*/
		
		//besser
		
		//bei 0 gibt es keine fehlerhaften daten
		//bei 1 liegen unvollstaendige / fehlerhafte daten vor
		//bei 2 ist der pnp nicht vorhanden
		
		$daten_fehler = 0;
		
	    $array = func_get_args ();
		//sind messwert, pegelnummer, pegelname, name und namegebiet
		if(Util::my_empty($array[0])==true || Util::my_empty($array[1])==true || Util::my_empty($array[3])==true) {
		$daten_fehler = 1;
		}
		//pnp
		if(Util::my_empty($array[6])==true) {
		$daten_fehler = 2;
		}
		
		//umformungen , zu .
		$messwert = Util::getDotString($messwert);
		$km = Util::getDotString($km);
		$pnp = Util::getDotString($pnp);
		
		if ($pegelnummer != '' && $messwert != '') {
		
			$result = $db->qry(" INSERT INTO ".TABLE_PEGEL." (pegelnummer,pegelname,km,messwert,datum,uhrzeit,pnp,tendenz,daten_fehler) VALUES ('$pegelnummer','$pegelname','$km','$messwert','$datum','$uhrzeit','$pnp','$tendenz','$daten_fehler') ON DUPLICATE KEY UPDATE pegelname=VALUES(pegelname), km=VALUES(km), messwert=VALUES(messwert), datum=VALUES(datum), uhrzeit=VALUES(uhrzeit), pnp=VALUES(pnp), tendenz=VALUES(tendenz), daten_fehler=VALUES(daten_fehler); ");
			if ($result)
			{
			echo 'update: daten erfolg';
			//ins logfile schreiben
			$msg = "update: daten erfolg";
			Log::write(LOG_OTHER, $msg);
			}
			else
			{
			echo 'update: fehler bitte in sql.log nachsehen';
			}
		}
		else
		{
		echo 'update: fehler pegelnummer oder messwert nicht vorhanden';
		}
		return;
		}
		
	/**
	 * Speichert die von SimpleXML übergebenen Objekte in der Datenbank
	 * hier für das aus der SOAP Response gewonnene XML-Dokument
	 * @param XML-Objekte
	 */
public static function save_update_soap($pegelnummer, $pegelname, $km, $messwert, $datum, $uhrzeit, $pnp, $tendenz, $namegebiet, $name, $Rechtswert_GK, $Hochwert_GK, $streifenzone, $bezugssystem, $ellipsoid, $epsgCode) {

		global $db;
		
		/*
		//alle werte auf korrektheit pruefen, nur ob diese leer sind
		foreach (func_get_args() as $arg) {
			if(Util::my_empty($arg)==true) {
			$daten_fehler = 1;
			}
		}
		*/
		
		//besser
		
		//bei 0 gibt es keine fehlerhaften daten
		//bei 1 liegen unvollstaendige / fehlerhafte daten vor
		//bei 2 ist der pnp nicht vorhanden
		//bei 3 fehlen koordinaten informationen
		
		$daten_fehler = 0;
		
	    $array = func_get_args ();
		//sind messwert, pegelnummer, pegelname, name und namegebiet
		if(Util::my_empty($array[0])==true || Util::my_empty($array[1])==true || Util::my_empty($array[3])==true || Util::my_empty($array[8])==true || Util::my_empty($array[9])==true) {
		$daten_fehler = 1;
		}
		//pnp
		if(Util::my_empty($array[6])==true) {
		$daten_fehler = 2;
		}
		//rechtswert, hochwert, streifenzone und bezugssystem
		if(Util::my_empty($array[10])==true || Util::my_empty($array[11])==true || Util::my_empty($array[12])==true || Util::my_empty($array[13])==true || Util::my_empty($array[14])==true) {
		$daten_fehler = 3;
		}
		
		//umformungen mit dem strings als float gelten
		//floatval
		//ansonsten reicht auch das , durch . zu ersetzten sql macht dann den rest
		$messwert = Util::getDotString($messwert);
		$km = Util::getDotString($km);
		$pnp = Util::getDotString($pnp);


		//dot weg, komma zu . fuer sql db und spaetere umformung
		$Rechtswert_GK = Util::removeDotString($Rechtswert_GK);
		$Rechtswert_GK = Util::getDotString($Rechtswert_GK);
		$Hochwert_GK = Util::removeDotString($Hochwert_GK);
		$Hochwert_GK = Util::getDotString($Hochwert_GK);

		//fuer tendenz steigend fallend gleich nehmen statt der zahlen, damit daten mit XML vergleichbar
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
		echo 'update: daten erfolg';
			//ins logfile schreiben
			$msg = "update: daten erfolg";
			Log::write(LOG_OTHER, $msg);
		}
		else
		{
		echo 'update: fehler bitte in sql.log nachsehen';
		}
	}
	else
	{
	echo 'update: fehler pegelnummer oder messwert nicht vorhanden';
	}
	return;
	}	


	/**
	 * Speichert die von SimpleXML übergebenen Objekte in der Datenbank
	 * hier für das statische XML-Dokument
	 * Funktion angepasst, damit diese in einer shell benutzt werden kann und in dieser nicht unnötig viel Text steht
	 * @param XML-Objekte
	 */
public static function save_update_xml_shell($pegelnummer, $pegelname, $km, $messwert, $datum, $uhrzeit, $pnp, $tendenz) {

		global $db;
		
		/*
		//alle werte auf korrektheit pruefen, mal erst nur ob diese leer sind
		foreach (func_get_args() as $arg) {
			if(Util::my_empty($arg)==true) {
			$daten_fehler = 1;
			}
		}
		*/
		
		//besser
		
		//bei 0 gibt es keine fehlerhaften daten
		//bei 1 liegen unvollstaendige / fehlerhafte daten vor
		//bei 2 ist der pnp nicht vorhanden
		
		$daten_fehler = 0;
		
	    $array = func_get_args ();
		//sind messwert, pegelnummer, pegelname, name und namegebiet
		if(Util::my_empty($array[0])==true || Util::my_empty($array[1])==true || Util::my_empty($array[3])==true) {
		$daten_fehler = 1;
		}
		//pnp
		if(Util::my_empty($array[6])==true) {
		$daten_fehler = 2;
		}
		
		//umformungen , zu .
		$messwert = Util::getDotString($messwert);
		$km = Util::getDotString($km);
		$pnp = Util::getDotString($pnp);
		
		if ($pegelnummer != '' && $messwert != '') {
		
			$result = $db->qry(" INSERT INTO ".TABLE_PEGEL." (pegelnummer,pegelname,km,messwert,datum,uhrzeit,pnp,tendenz,daten_fehler) VALUES ('$pegelnummer','$pegelname','$km','$messwert','$datum','$uhrzeit','$pnp','$tendenz','$daten_fehler') ON DUPLICATE KEY UPDATE pegelname=VALUES(pegelname), km=VALUES(km), messwert=VALUES(messwert), datum=VALUES(datum), uhrzeit=VALUES(uhrzeit), pnp=VALUES(pnp), tendenz=VALUES(tendenz), daten_fehler=VALUES(daten_fehler); ");
			if ($result)
			{
			//keine ausgabe da sonst zuviel in der shell steht
			//echo 'update: daten erfolg';
			}
			else
			{
			echo 'update: fehler bitte in sql.log nachsehen';
			}
		}
		else
		{
		echo 'update: fehler pegelnummer oder messwert nicht vorhanden';
		}
		return;
		}
	
	}
?>