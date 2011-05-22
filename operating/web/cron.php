<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 19.05.11 18:58 Uhr
Aufgabe der Datei:
Datei zur Aktualisierung, kann per Cron aufgerufen werden.
*/
include('config.inc.php');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>Cron</title>
    </head>
    <body>
<h1>Cron</h1>
<?php
if (ob_get_level() == 0) {
    ob_start();
}
		//fsock
		$page = Util::get_document($xmlurl);
        //$xml = simplexml_load_file($xmlurl); //geht nicht da fsocket benutzt
		//ist jetzt ein string
		$xml = simplexml_load_string($page);
		if($xml) { //pruefen ob gueltiges xml bzw. wohlgeformt
		echo 'XML Datei ist Fehlerfrei bzw. Wohlgeformt wird verarbeitet ... ';
    flush();
    ob_flush();
		foreach ($xml->table->gewaesser as $gewaesser) {
            foreach($gewaesser->item as $item) {
				Daten::save_update_xml_shell($pegelnummer = $item->pegelnummer, $pegelname = $item->pegelname, $km = $item->km, $messwert = $item->messwert, $datum = $item->datum, $uhrzeit = $item->uhrzeit, $pnp = $item->pnp, $tendenz = $item->tendenz);
            }
		}
        } else {
            echo '<p>Die Datei '. $xmlname .' enhaelt fehler</p>';
        }
		echo "fertig";
ob_end_flush();
		?>
	<br><br>
    </body>
</html>
