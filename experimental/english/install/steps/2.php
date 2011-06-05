<div>
<p><b>create database tables</b></p>
<p>
Now the required tables are created in the database:<br><br>
<?php
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
	echo "insert table pegelsteande: done<br>";
	}
	else
	{
	echo 'insert table : error see sql.log';
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
	echo "insert table pegelsteande2: done<br>";
	}
	else
	{
	echo 'insert table : error see sql.log';
	}

?>
<br><br>
<?=$db->getQueryCount()?> Database queries in <?=substr($db->getQueryTimeSum(),0,6)?> seconds.

</p>
<p>Click &quot;Next&quot; to continue</p>
</div>
<div><input type="submit" value="Next" /></div>