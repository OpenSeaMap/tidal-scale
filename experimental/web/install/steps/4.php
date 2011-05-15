<div>
<p><b>SOAP XML verarbeiten</b></p>
<p>
Nun werden die Daten aus der XML-Datei in MySQL eingefuegt:<br><br>
<b>Bitte ans Ende der Seite Scrollen</b><br><br>
<?php
	//selbst erzeugt
    if(file_exists($soapname)) {
        $xml = simplexml_load_file($soapname);
        if($xml) { //pruefen ob gueltiges xml bzw. wohlgeformt
		echo 'XML Datei ist Fehlerfrei bzw. Wohlgeformt wird verarbeitet ... ';
		//ins logfile schreiben
		$msg = "XML Datei ist Fehlerfrei bzw. Wohlgeformt ausgabe ...";
		Log::write(LOG_SOAP, $msg);
		foreach ($xml->table->gewaesser as $gewaesser) {
		echo '<br><br>';
            foreach($gewaesser->item as $item) {
            ?>
        <table>
			<tr>
                <td>namegebiet:</td>
                <td><?php  Util::echo_wert($item->namegebiet); ?></td>
            </tr>
			<tr>
                <td>name:</td>
                <td><?php  Util::echo_wert($item->name); ?></td>
            </tr>
			<tr>
                <td>pegelname:</td>
                <td><?php  Util::echo_wert($item->pegelname); ?></td>
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
                <td><?php  Util::echo_wert($item->datum); ?></td>
            </tr>
			<tr>
                <td>uhrzeit:</td>
                <td><?php  Util::echo_wert($item->uhrzeit); ?></td>
            </tr>
            <tr>
                <td>pegelnummer:</td>
                <td><?php  Util::echo_wert($item->pegelnummer); ?></td>
            </tr>
			<tr>
                <td>rechtswert:</td>
                <td><?php Util::echo_wert($item->rechtswert); ?></td>
            </tr>
			<tr>
                <td>hochwert:</td>
                <td><?php Util::echo_wert($item->hochwert); ?></td>
            </tr>
			<tr>
                <td>streifenzone:</td>
                <td><?php Util::echo_wert($item->streifenzone); ?></td>
            </tr>
			<tr>
                <td>bezugssystem:</td>
                <td><?php Util::echo_wert($item->bezugssystem); ?></td>
            </tr>
			<tr>
                <td>ellipsoid:</td>
                <td><?php Util::echo_wert($item->ellipsoid); ?></td>
            </tr>
			<tr>
                <td>epsgCode:</td>
                <td><?php Util::echo_wert($item->epsgCode); ?></td>
            </tr>
        </table>
<?php
//einzelne werte an klasse übergeben
Daten::save_update_soap($pegelnummer = $item->pegelnummer, $pegelname = $item->pegelname, $km = $item->km, $messwert = $item->messwert, $datum = $item->datum, $uhrzeit = $item->uhrzeit, $pnp = $item->pnp, $tendenz = $item->tendenz, $namegebiet = $item->namegebiet, $name = $item->name, $Rechtswert_GK = $item->rechtswert, $Hochwert_GK = $item->hochwert, $streifenzone = $item->streifenzone, $bezugssystem = $item->bezugssystem, $ellipsoid = $item->ellipsoid, $epsgCode = $item->epsgCode);
       
            }
		}
		} else {
            echo '<p>Die Datei '. $soapname .' enhaelt fehler</p>';
			//ins logfile schreiben
			$msg = "Die Datei '. $soapname .' enhaelt fehler";
			Log::write(LOG_SOAP, $msg);
        }
    } else {
		echo '<p>Die Datei '. $soapname .' ist nicht vorhanden</p>';
		//ins logfile schreiben
		$msg = "Die Datei '. $soapname .' ist nicht vorhanden";
		Log::write(LOG_SOAP, $msg);
	}
?>
<br><br>
<?=$db->getQueryCount()?> Datenbankabfragen in <?=substr($db->getQueryTimeSum(),0,6)?> Sekunden. 
</p>
<p>Klicken Sie auf &quot;Weiter&quot; um fortzufahren</p>
</div>
<div><input type="submit" value="Weiter" /></div>