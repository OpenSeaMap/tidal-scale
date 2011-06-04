<div>
<p><b>Daten von SOAP Schnittstelle lokal speichern</b></p>
<p>
Nun werden die Daten von der SOAP Schnittstelle empfangen:<br><br>
<?php
//api file
require(PATH_CLASSES.'soap.class.php');

try {
$clientApi = new SOAPClientApi();

//all water level raw data collected 
$arrPegel = $clientApi->getPegelinformationen("WASSERSTAND ROHDATEN", NULL, NULL);

//system depending: create of file before writing into 
$datei="pegel_soap.xml";

$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
	if(!empty($arrPegel)){

$xml .= "
<data>
<title>Aktuelle Wasserstaende selbst aus soap schnittstelle geholt und als xml nutzbar gemacht</title>
<table>";
			
foreach ($arrPegel as $pegelinformation) {
$xml .= "
<gewaesser>
<item>
<namegebiet>" . $pegelinformation->pegelonlineFlussgebiet->name . "</namegebiet>
<name>" . $pegelinformation->pegelonlineGewaesser->name . "</name>
<pegelname>" . $pegelinformation->pegelonlineMessstelle->name . "</pegelname>
<pegelnummer>" . htmlentities($pegelinformation->pegelonlineMessstelle->nummer, ENT_QUOTES, "UTF-8") . "</pegelnummer>
<km>" . (!empty($pegelinformation->pegelonlineMessstelle->kilometerstand) ?  number_format($pegelinformation->pegelonlineMessstelle->kilometerstand, 2, ',', '') : "") . "</km>
<rechtswert>" . (!empty($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->rechtswert) ? number_format($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->rechtswert, 2, ',', '.') : "") . "</rechtswert>
<hochwert>" . (!empty($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->hochwert) ? number_format($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->hochwert, 2, ',', '.') : "") . "</hochwert>
<streifenzone>" . (!empty($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->pegelonlineKoordinatensystem->streifenZone) ? ($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->pegelonlineKoordinatensystem->streifenZone) : "") . "</streifenzone>
<bezugssystem>" . (!empty($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->pegelonlineKoordinatensystem->bezugssystem) ? ($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->pegelonlineKoordinatensystem->bezugssystem) : "") . "</bezugssystem>
<ellipsoid>" . (!empty($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->pegelonlineKoordinatensystem->ellipsoid) ? ($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->pegelonlineKoordinatensystem->ellipsoid) : "") . "</ellipsoid>
<epsgCode>" . (!empty($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->pegelonlineKoordinatensystem->epsgCode) ? ($pegelinformation->pegelonlineMessstelle->pegelonlineKoordinate->pegelonlineKoordinatensystem->epsgCode) : "") . "</epsgCode>
<messwert>" . $pegelinformation->pegelonlineAktuelleMessung->messwert . "</messwert>
<datum>" . util::convertTime($pegelinformation->pegelonlineAktuelleMessung->zeitpunkt)->format('d.m.Y') . "</datum>
<uhrzeit>" . util::convertTime($pegelinformation->pegelonlineAktuelleMessung->zeitpunkt)->format('H:i') . "</uhrzeit>
<tendenz>" . $pegelinformation->pegelonlineAktuelleMessung->tendenz . "</tendenz>
<pnp>" . (!empty($pegelinformation->pegelonlinePegelnullpunkt->hoehe) ? number_format($pegelinformation->pegelonlinePegelnullpunkt->hoehe, 2, ',', '') : "") . "</pnp>
</item>
</gewaesser>";
		}
$xml .= "
</table>
";

	}

$xml .= "</data>";

} catch (SoapFault $fault) {
		var_dump($fault);
		//write to logfile
		Log::write(LOG_SOAP, $fault);
	}

//system depending: create of file before writing into
$file=fopen($datei,"w+");
fputs($file,$xml);
fclose($file);

echo "soap xml erstellen erfolgreich";
//write to logfile
$msg = "soap xml erstellen erfolgreich";
Log::write(LOG_SOAP, $msg);
?>

</p>
<p>Klicken Sie auf &quot;Weiter&quot; um fortzufahren</p>
</div>
<div><input type="submit" value="Weiter" /></div>