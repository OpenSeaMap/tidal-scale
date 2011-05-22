#!/usr/bin/php
<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 13.05.11 12:20 Uhr
Aufgabe der Datei:
Daten per Socketverbindung aktualisieren, kann für die automatische Aktualisierung genutzt werden.
*/

//vollständiger pfad zur config notwendig
//muss angepasst werden
include('/var/www/httpdocs/test/config.inc.php');
//muss angepasst werden


		//fsock
		$page = Util::get_document($xmlurl);
        //$xml = simplexml_load_file($xmlurl); //geht nicht da fsocket benutzt
		//ist jetzt ein string
		$xml = simplexml_load_string($page);
		if($xml) { //pruefen ob gueltiges xml bzw. wohlgeformt
		echo 'XML Datei ist Fehlerfrei bzw. Wohlgeformt wird verarbeitet ...';
		foreach ($xml->table->gewaesser as $gewaesser) {
            foreach($gewaesser->item as $item) {
				Daten::save_update_xml_shell($pegelnummer = $item->pegelnummer, $pegelname = $item->pegelname, $km = $item->km, $messwert = $item->messwert, $datum = $item->datum, $uhrzeit = $item->uhrzeit, $pnp = $item->pnp, $tendenz = $item->tendenz);
            }
		}
        } else {
            echo '<p>Die Datei '. $xmlname .' enhaelt fehler</p>';
        }

echo "done";
?>