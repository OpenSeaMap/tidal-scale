<?php
//test ausgaben entfernen
//echos entfernen


/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 28.04.11 17:35 Uhr
*/

/*
* alle wichtigen Funktionen die mit der Datenspeicherung in MySQL etwas zu tun haben,
* in einer Klasse zusammengefasst
*/
class Daten {
	
    function __construct() {
	    global $db;
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