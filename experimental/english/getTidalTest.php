<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 04.06.11 12:50 Uhr
The object of the file:
Query of information to display markers on the map.
*/
header("Content-type: text/plain; charset=UTF-8");
include('config.inc.php');
	
/*
	$b=$_REQUEST["b"];
	$l=$_REQUEST["l"];
	$t=$_REQUEST["t"];
	$r=$_REQUEST["r"];
*/
// better
if ( isset ($_REQUEST['l']) ) {
	$l=$_REQUEST["l"];
} 
else { 
  $left = null; 
}
if ( isset ($_REQUEST['t']) ) {
	$t=$_REQUEST["t"];
} 
else { 
  $top = null; 
}
if ( isset ($_REQUEST['r']) ) {
	$r=$_REQUEST["r"];
} 
else { 
  $right = null; 
}
if ( isset ($_REQUEST['b']) ) {
	$b=$_REQUEST["b"];
} 
else { 
  $bottom = null; 
}

	$resultMap = $db->qry(" SELECT pegelnummer,pegelname,km,messwert,datum,uhrzeit,pnp,tendenz,namegebiet,name,daten_fehler,lat,lon FROM ".TABLE_PEGEL2." WHERE lat !='' AND lon !='' AND $l<lon AND lon<$r AND $b<lat AND lat<$t ORDER BY `pegelnummer` LIMIT 100 ");
	  while($resMap = mysql_fetch_object($resultMap)) {
		  Map::write_line_osm($resMap);
	  }
?>