<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 25.05.11 12:25 Uhr
The object of the file:
generates the necessary markers on the map
*/

class Map {

    function __constuct() {
    }
    
    /*
     * Writes the necessary headers
	 * may be used with write_line supported in almost every map using OpenLayers
     */
    public static function write_header()
	{
	#       lat	lon	icon	iconSize	iconOffset	title	description	popupSize
	 print("lat\tlon\ticon\ticonSize\ticonOffset\ttitle\tdescription\tpopupSize\n");
	}
	
    /*
     * Writes an entry in a row
	 * may be used with write_line supported in almost every map using OpenLayers
	 * @param $mysqlresult
     */
	//call with $ resMap
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
	
	
	//"deal with UTF-8"
	$name = Util::getCleanString($row->name);
	$namegebiet = Util::getCleanString($row->namegebiet);
	
	//conversion for the display
	$name = Util::convertUpperString($name);
	$namegebiet = Util::convertUpperString($namegebiet);
	
	$tendenz = Util::convertArrow($row->tendenz);
	$daten_fehler = Util::show_daten_fehler($row->daten_fehler);

	//  description
	print("<nobr>".$name.", ".$namegebiet."<br><br>Messwert&nbsp;Tendenz PnP<br>".$row->messwert." ".$tendenz." ".$row->pnp."<br><br>".$row->datum."  -  ".$row->uhrzeit."<br>".$daten_fehler."<br><a href=\"http://www.pegelonline.wsv.de/gast/stammdaten?pegelnr=".$row->pegelnummer."\">Ganglinie</a></nobr><br/>");

	//popupSize
	 print("\t");
	 print("250,220");
	 print("\n");
	}
	
    /*
     * Writes an entry in a row
	 * can only be used on OpenSeaMap
	 * @param $mysqlresult
     */
	//call with $ resMap
	public static function write_line_osm($row)
	{
	
	//"deal with UTF-8"
	$name = Util::getCleanString_osm($row->name);
	$namegebiet = Util::getCleanString_osm($row->namegebiet);
	
	//conversion for the display had to be rewritten
	$name = Util::convertUpperString($name);
	$namegebiet = Util::convertUpperString($namegebiet);
	
	$tendenz = Util::convertArrow_osm($row->tendenz);
	$daten_fehler = Util::show_daten_fehler_osm($row->daten_fehler);

	//row
	echo "putTidalScaleMarker($row->pegelnummer, $row->lon, $row->lat, '".addslashes($row->pegelname)."', '$name', '$namegebiet', '$row->messwert', '$tendenz', '$row->pnp', '$row->datum', '$row->uhrzeit', '$daten_fehler');\n";
	}
	
}
?>