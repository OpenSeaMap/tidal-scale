<!--
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 29.04.11 19:54 Uhr
Aufgabe der Datei:
Die erstellten Tabellen werden in der Datenbank geleert.
-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>MySQL Tabellen leeren</title>
    </head>
    <body>
<?php
include('config.inc.php');

$result = $db->qry(" 
TRUNCATE `pegelstaende` ;
");
	if ($result)
	{
	echo "table pegelsteande leeren: erfolg<br>";
	}
	else
	{
	echo 'table leeren: fehler bitte in sql.log nachsehen';
	}

$result2 = $db->qry(" 
TRUNCATE `pegelstaende2` ;
");
	if ($result2)
	{
	echo "table pegelsteande2 leeren: erfolg<br>";
	}
	else
	{
	echo 'table leeren: fehler bitte in sql.log nachsehen';
	}
	
$result3 = $db->qry(" 
TRUNCATE `pegelstaende3` ;
");
	if ($result3)
	{
	echo "table pegelsteande3 leeren: erfolg<br>";
	}
	else
	{
	echo 'table leeren: fehler bitte in sql.log nachsehen';
	}
?>
<br><br>
<?=$db->getQueryCount()?> Datenbankabfragen in <?=substr($db->getQueryTimeSum(),0,6)?> Sekunden.
    </body>
</html>






