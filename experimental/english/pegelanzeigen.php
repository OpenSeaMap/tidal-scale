<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 04.06.11 12:52 Uhr
The object of the file:
Query of information to display markers on the map.
*/
header("Content-type: text/plain; charset=UTF-8");
include('config.inc.php');
//important, this must be the first line
Map::write_header();

/*
$left = $_GET["l"];
$top = $_GET["t"];
$right = $_GET["r"];
$bottom = $_GET["b"];
$zoom = $_GET["z"];
$k_list = $_GET["f"];
*/

// so ist besser
if ( isset ($_GET['l']) ) {
$left = $_GET["l"];
} 
else { 
  $left = null; 
}
if ( isset ($_GET['t']) ) {
$top = $_GET["t"];
} 
else { 
  $top = null; 
}
if ( isset ($_GET['r']) ) {
$right = $_GET["r"];
} 
else { 
  $right = null; 
}
if ( isset ($_GET['b']) ) {
$bottom = $_GET["b"];
} 
else { 
  $bottom = null; 
}
if ( isset ($_GET['z']) ) {
$zoom = $_GET["z"];
} 
else { 
  $zoom = null; 
}
if ( isset ($_GET['f']) ) {
$k_list = $_GET["f"];
} 
else { 
  $k_list = null; 
}

//helper
/*
if ($left>$right)
{
    $temp =$left;
    $left=$right;
    $right=$temp;
}

if ($bottom>$top)
{
    $temp =$top;
    $top=$bottom;
    $bottom=$temp;
}
*/

//always get 50 records from databbase
$resultMap = $db->qry(" SELECT pegelnummer,pegelname,km,messwert,datum,uhrzeit,pnp,tendenz,namegebiet,name,daten_fehler,lat,lon FROM ".TABLE_PEGEL2." WHERE $left<lon AND lon<$right AND $bottom<lat AND lat<$top ORDER BY `pegelnummer` LIMIT 0, 50 ");
		while($resMap = mysql_fetch_object($resultMap)) {
		  Map::write_line($resMap);
	  }
?>
