<!--
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 19.05.11 19:50 Uhr
Aufgabe der Datei:
Die erforderlichen Tabellen werden in der Datenbank angelegt
-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>MySQL Tabellen anlegen</title>
    </head>
    <body>
<?php
include('config.inc.php');

$result = $db->qry(" 
CREATE TABLE IF NOT EXISTS `pegelstaende` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL COMMENT 'Name',
  `pegelname` tinytext NOT NULL COMMENT 'Pegelname',
  `pegelnummer` int(32) NOT NULL COMMENT 'Pegelnummer',
  `km` float(10,2) NOT NULL COMMENT 'Lage bei km',
  `messwert` float(10,2) NOT NULL COMMENT 'Messwert',
  `datum` tinytext NOT NULL COMMENT 'Datum',
  `uhrzeit` tinytext NOT NULL COMMENT 'Zeit',
  `pnp` float(10,2) NOT NULL COMMENT 'Pegel-Nullpunkt',
  `tendenz` varchar(20) NOT NULL COMMENT 'Tendenz',
  `daten_fehler` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'daten_enthalten fehler ',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `pegelnummer` (`pegelnummer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");
	if ($result)
	{
	echo "table pegelsteande einfuegen: erfolg<br>";
	}
	else
	{
	echo 'table einfuegen: fehler bitte in sql.log nachsehen';
	}

$result2 = $db->qry(" 
CREATE TABLE IF NOT EXISTS `pegelstaende2` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `namegebiet` tinytext NOT NULL COMMENT 'Name Gebiet',
  `name` tinytext NOT NULL COMMENT 'Name',
  `pegelname` tinytext NOT NULL COMMENT 'Pegelname',
  `pegelnummer` int(32) NOT NULL COMMENT 'Pegelnummer',
  `km` float(10,2) NOT NULL COMMENT 'Lage bei km',
  `Rechtswert_GK` float(10,2) NOT NULL COMMENT 'Rechtswert',
  `Hochwert_GK` float(10,2) NOT NULL COMMENT 'Hochwert',
  `bezugssystem` tinytext NOT NULL COMMENT 'Bezugssystem',
  `messwert` float(10,2) NOT NULL COMMENT 'Messwert',
  `datum` tinytext NOT NULL COMMENT 'Datum',
  `uhrzeit` tinytext NOT NULL COMMENT 'Zeit',
  `pnp` float(10,2) NOT NULL COMMENT 'Pegel-Nullpunkt',
  `tendenz` varchar(20) NOT NULL COMMENT 'Tendenz',
  `ellipsoid` varchar(32) NOT NULL COMMENT 'Ellipsoid',
  `epsgCode` int(10) NOT NULL COMMENT 'epsgCode',
  `streifenzone` varchar(32) NOT NULL COMMENT 'Streifenzone',
  `lat` varchar(32) NOT NULL COMMENT 'lat',
  `lon` varchar(32) NOT NULL COMMENT 'lon',
  `daten_fehler` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'daten_enthalten fehler ',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `pegelnummer` (`pegelnummer`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");
	if ($result2)
	{
	echo "table pegelsteande2 einfuegen: erfolg<br>";
	}
	else
	{
	echo 'table einfuegen: fehler bitte in sql.log nachsehen';
	}

?>
<br><br>
<?=$db->getQueryCount()?> Datenbankabfragen in <?=substr($db->getQueryTimeSum(),0,6)?> Sekunden.
    </body>
</html>