<!--
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 27.04.11 15:12 Uhr
Aufgabe der Datei:
Entfernte XML Datei anzeigen
-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>XML-Dateien anzeigen und in mysql speichern entfernt</title>
    </head>
    <body>
<?php
include('config.inc.php');
	//entfernt erfordert fopen und entferntes url einbinden genauer
	//allow_url_fopen = On
	//allow_url_include = On
    
        $xml = simplexml_load_file($xmlurl);
        if($xml) { //pruefen ob gueltiges xml bzw. wohlgeformt
		echo 'XML Datei ist Fehlerfrei bzw. Wohlgeformt wird verarbeitet ...';
		//ins logfile schreiben
		$msg = "XML Datei ist Fehlerfrei bzw. Wohlgeformt ausgabe ...";
		Log::write(LOG_XML, $msg);
		foreach ($xml->table->gewaesser as $gewaesser) {
		echo '<h2>Gewaesser: ' . $gewaesser->name . '</h2>' . PHP_EOL;
            foreach($gewaesser->item as $item) {
            echo '<h4>Item: ' . $item->no . '</h4>' . PHP_EOL;
            ?>
        <table>
            <tr>
                <td>psmgr:</td>
                <td><?php Util::echo_wert($item->psmgr); ?></td>
            </tr>
			<tr>
                <td>pegelname:</td>
                <td><?php Util::echo_wert($item->pegelname); ?></td>
            </tr>
            <tr>
                <td>messwert:</td>
                <td><?php Util::echo_wert($item->messwert); ?></td>
            </tr>
			<tr>
                <td>km:</td>
                <td><?php Util::echo_wert($item->km); ?></td>
            </tr>
            <tr>
                <td>pnp:</td>
                <td><?php Util::echo_wert($item->pnp); ?></td>
            </tr>
			<tr>
                <td>tendenz:</td>
                <td><?php Util::echo_wert($item->tendenz); ?></td>
            </tr>
            <tr>
                <td>datum:</td>
                <td><?php Util::echo_wert($item->datum); ?></td>
            </tr>
			<tr>
                <td>uhrzeit:</td>
                <td><?php Util::echo_wert($item->uhrzeit); ?></td>
            </tr>
            <tr>
                <td>pegelnummer:</td>
                <td><?php Util::echo_wert($item->pegelnummer); ?></td>
            </tr>
        </table>
<?php	
//einzelne werte an klasse übergeben
Daten::save_update_xml($item->pegelnummer, $item->pegelname, $item->km, $item->messwert, $item->datum, $item->uhrzeit, $item->pnp, $item->tendenz);
            }
		}
        } else {
            echo '<p>Die Datei '. $xmlurl .' enhaelt fehler</p>';
			//ins logfile schreiben
			$msg = "Die Datei '. $xmlurl .' enhaelt fehler";
			Log::write(LOG_XML, $msg);
        }
?>
<br><br>
<?=$db->getQueryCount()?> Datenbankabfragen in <?=substr($db->getQueryTimeSum(),0,6)?> Sekunden.
    </body>
</html>