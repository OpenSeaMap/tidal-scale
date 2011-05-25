<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 11.05.11 12:15 Uhr
erzeugt die notwendigen Marker auf der Karte
*/

class Map {

    function __constuct() {
    }
    
    /*
     * Schreibt den notwendigen Header
	 * kann zusammen mit write_line in fast jeder Karte die OpenLayers unterstützt genutzt werden
     */
    public static function write_header()
	{
	#       lat	lon	icon	iconSize	iconOffset	title	description	popupSize
	 print("lat\tlon\ticon\ticonSize\ticonOffset\ttitle\tdescription\tpopupSize\n");
	}
	
    /*
     * Schreibt einen Eintrag in einer Zeile
	 * kann zusammen mit write_header in fast jeder Karte die OpenLayers unterstützt genutzt werden
	 * @param $mysqlresult
     */
	//mit $resMap aufrufen
	public static function write_line($row)
	{
	//         lat  lon
	print($row->lat."\t".$row->lon."\t");

	// icon
	print("./resources/places/tidal_scale_32.png\t");

	// iconSize
	//print("32,32\t");
	print("24,24\t");

	// iconOffset
	//print("-16,-16\t");
	print("-12,-12\t");

	//  title
	print("<nobr>".$row->pegelname."</nobr>\t");
	
	
	//"Problem mit UTF-8 umgehen"
	$name = Util::getCleanString($row->name);
	$namegebiet = Util::getCleanString($row->namegebiet);
	
	//umwandlungen fuer die Anzeige
	$name = Util::convertUpperString($name);
	$namegebiet = Util::convertUpperString($namegebiet);
	
	$tendenz = Util::convertArrow($row->tendenz);
	$daten_fehler = Util::show_daten_fehler($row->daten_fehler);

	//  description
	print("<nobr>".$name.", ".$namegebiet."<br><br>Messwert&nbsp;Tendenz PnP<br>".$row->messwert." ".$tendenz." ".$row->pnp."<br><br>".$row->datum."  -  ".$row->uhrzeit."<br>".$daten_fehler."<br><a href=\"http://www.pegelonline.wsv.de/gast/stammdaten?pegelnr=".$row->pegelnummer."\">Ganglinie</a></nobr><br/>");
	//print("<nobr>".$name." ".$namegebiet."<br><br>Messwert PnP Tendenz<br>".$row->messwert." ".$row->pnp." ".$row->tendenz."<br><br>Datum - Uhrzeit:<br>".$row->datum." - ".$row->uhrzeit."<br><br>Fehler vorhanden: ".$row->daten_fehler."<br><a href=\"http://www.pegelonline.wsv.de/gast/stammdaten?pegelnr=".$row->pegelnummer."\">Ganglinie</a></nobr><br/>");

	//popupSize
	 print("\t");
	 print("250,220");
	 print("\n");
	}
	
    /*
     * Schreibt einen Eintrag in einer Zeile
	 * kann so nur auf OpenSeaMap genutzt werden
	 * @param $mysqlresult
     */
	//mit $resMap aufrufen
	public static function write_line_osm($row)
	{
	
	//"Problem mit UTF-8 umgehen"
	$name = Util::getCleanString_osm($row->name);
	$namegebiet = Util::getCleanString_osm($row->namegebiet);
	
	//umwandlungen fuer die Anzeige muss umgeschrieben werden
	$name = Util::convertUpperString($name);
	$namegebiet = Util::convertUpperString($namegebiet);
	
	$tendenz = Util::convertArrow_osm($row->tendenz);
	$daten_fehler = Util::show_daten_fehler_osm($row->daten_fehler);

	//zeile
	echo "putTidalScaleMarker($row->pegelnummer, $row->lon, $row->lat, '".addslashes($row->pegelname)."', '$name', '$namegebiet', '$row->messwert', '$tendenz', '$row->pnp', '$row->datum', '$row->uhrzeit', '$daten_fehler');\n";
	}
	
}
?>