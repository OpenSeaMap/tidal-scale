<!--
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 17.04.11 17:35 Uhr
Aufgabe der Datei:
Daten die Lokal verfügbar sind anzeigen.
-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>XML-Dateien lesen/anzeigen lokal</title>
    </head>
    <body>
<?php
include('config.inc.php');
    //lokal z.b. mit wget geholt
 	if(file_exists($xmlname)) {
        $xml = simplexml_load_file($xmlname);
        if($xml) { //pruefen ob gueltiges xml bzw. wohlgeformt
		//var_dump($xml);
		echo 'XML Datei ist Fehlerfrei bzw. Wohlgeformt ausgabe ...';
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
            }
		}
        } else {
            echo '<p>Die Datei '. $xmlname .' enhaelt fehler</p>';
			//ins logfile schreiben
			$msg = "Die Datei '. $xmlname .' enhaelt fehler";
			Log::write(LOG_XML, $msg);
        }
    } else {
		echo '<p>Die Datei '. $xmlname .' ist nicht vorhanden</p>';
		//ins logfile schreiben
		$msg = "Die Datei '. $xmlname .' ist nicht vorhanden";
		Log::write(LOG_XML, $msg);
	}
?>
    </body>
</html>