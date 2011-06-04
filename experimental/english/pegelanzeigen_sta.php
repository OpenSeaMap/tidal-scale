<!--
Created by Tim Reinartz as part of the Bachelor Thesis
last update 04.06.11 12:52 Uhr
The object of the file:
Query of information to display markers on the map saved in one file
-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>getPegelTest statisch Werte stehen in einer txt datei</title>
    </head>
    <body>
<?php
include('config.inc.php');

//system depending: create of file before writing into 
$datei="textfile.txt";

$txt = "lat	lon	title	description	icon	iconSize	iconOffset";

	$resultMap = $db->qry(" SELECT pegelnummer,pegelname,km,messwert,datum,uhrzeit,pnp,tendenz,namegebiet,name,daten_fehler,lat,lon FROM ".TABLE_PEGEL2." WHERE lat !='' AND lon !='' ORDER BY `pegelnummer` LIMIT 0, 500 ");
		while($resMap = mysql_fetch_object($resultMap)) {
		
	$name = Util::getCleanString($resMap->name);
	$namegebiet = Util::getCleanString($resMap->namegebiet);
	
	//conversion for the display
	$name = Util::convertUpperString($name);
	$namegebiet = Util::convertUpperString($namegebiet);
	
	$tendenz = Util::convertArrow($resMap->tendenz);
	$daten_fehler = Util::show_daten_fehler($resMap->daten_fehler);
		
		
$txt .= "
".$resMap->lat."	".$resMap->lon."	".$resMap->pegelname."	".$name." ".$namegebiet."<br><br>Messwert PnP Tendenz<br>".$resMap->messwert." ".$resMap->pnp." ".$tendenz."<br><br>".$resMap->datum." - ".$resMap->uhrzeit."".$daten_fehler."<br><a href=\"http://www.pegelonline.wsv.de/gast/stammdaten?pegelnr=".$resMap->pegelnummer."\">Ganglinie</a><br><br>(click to close)	Ol_icon_blue_example.png	20,20	0,-20";
	  }

//system depending: create of file before writing into
$file=fopen($datei,"w+");
fputs($file,$txt);
fclose($file);

echo "txt erstellen erfolgreich";
//write to logfile
$msg = "txt erstellen erfolgreich";
Log::write(LOG_OTHER, $msg);
?>
<br><br>
    </body>
</html>