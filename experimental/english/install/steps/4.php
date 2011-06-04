<div>
<p><b>SOAP XML verarbeiten</b></p>
<p>
Nun werden die Daten aus der XML-Datei in MySQL eingefuegt:<br><br>
<?php
if (ob_get_level() == 0) {
    ob_start();
}
	//self-generated
    if(file_exists($soapname)) {
        $xml = simplexml_load_file($soapname);
        if($xml) { //test whether "well-formed" or valid XML
		echo 'XML Datei ist Fehlerfrei bzw. Wohlgeformt wird verarbeitet ... ';
		//write to logfile
		$msg = "XML Datei ist Fehlerfrei bzw. Wohlgeformt ausgabe ...";
		Log::write(LOG_SOAP, $msg);
flush();
ob_flush();
$temp = 0;
		foreach ($xml->table->gewaesser as $gewaesser) {
            foreach($gewaesser->item as $item) {

	$temp = $temp + 1;
echo '<div class="percents">' . $temp . ' Daten&nbsp;verarbeitet</div>';
//single assets handed over to class
Daten::save_update_soap($pegelnummer = $item->pegelnummer, $pegelname = $item->pegelname, $km = $item->km, $messwert = $item->messwert, $datum = $item->datum, $uhrzeit = $item->uhrzeit, $pnp = $item->pnp, $tendenz = $item->tendenz, $namegebiet = $item->namegebiet, $name = $item->name, $Rechtswert_GK = $item->rechtswert, $Hochwert_GK = $item->hochwert, $streifenzone = $item->streifenzone, $bezugssystem = $item->bezugssystem, $ellipsoid = $item->ellipsoid, $epsgCode = $item->epsgCode);
            }
		}
		echo '<div class="percents">Done.</div>';
		} else {
            echo '<p>Die Datei '. $soapname .' enhaelt fehler</p>';
			//write to logfile
			$msg = "Die Datei '. $soapname .' enhaelt fehler";
			Log::write(LOG_SOAP, $msg);
        }
ob_end_flush();
    } else {
		echo '<p>Die Datei '. $soapname .' ist nicht vorhanden</p>';
		//write to logfile
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