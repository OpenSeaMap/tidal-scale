<div>
<p><b>process SOAP XML</b></p>
<p>
Data from the XML file inserted into MySQL:<br><br>
<?php
if (ob_get_level() == 0) {
    ob_start();
}
	//self-generated
    if(file_exists($soapname)) {
        $xml = simplexml_load_file($soapname);
        if($xml) { //test whether "well-formed" or valid XML
		echo 'XML File is error-free and well-formed processing ...';
		//write to logfile
		$msg = "XML File is error-free and well-formed processing ...";
		Log::write(LOG_SOAP, $msg);
flush();
ob_flush();
$temp = 0;
		foreach ($xml->table->gewaesser as $gewaesser) {
            foreach($gewaesser->item as $item) {

	$temp = $temp + 1;
echo '<div class="percents">' . $temp . ' Data&nbsp;processing</div>';
//single assets handed over to class
Daten::save_update_soap($pegelnummer = $item->pegelnummer, $pegelname = $item->pegelname, $km = $item->km, $messwert = $item->messwert, $datum = $item->datum, $uhrzeit = $item->uhrzeit, $pnp = $item->pnp, $tendenz = $item->tendenz, $namegebiet = $item->namegebiet, $name = $item->name, $Rechtswert_GK = $item->rechtswert, $Hochwert_GK = $item->hochwert, $streifenzone = $item->streifenzone, $bezugssystem = $item->bezugssystem, $ellipsoid = $item->ellipsoid, $epsgCode = $item->epsgCode);
            }
		}
		echo '<div class="percents">Done.</div>';
		} else {
            echo '<p>Error in '. $soapname .' </p>';
			//write to logfile
			$msg = "Error in '. $soapname .' ";
			Log::write(LOG_SOAP, $msg);
        }
ob_end_flush();
    } else {
		echo '<p>File '. $soapname .' missing</p>';
		//write to logfile
		$msg = "File '. $soapname .' missing";
		Log::write(LOG_SOAP, $msg);
	}
?>
<br><br>
<?=$db->getQueryCount()?> Database queries in <?=substr($db->getQueryTimeSum(),0,6)?> seconds. 
</p>
<p>Click &quot;Next&quot; to continue</p>
</div>
<div><input type="submit" value="Next" /></div>