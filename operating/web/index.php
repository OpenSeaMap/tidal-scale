<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 19.05.11 17:52 Uhr
Aufgabe der Datei:
Die Index Datei der Installation / Aktualisierung.
*/

//Ladeanzeige aus php.net doku entnommen
//http://de3.php.net/manual/de/function.flush.php

//wenn datei existiert wird install durchgeführt sonst aktualisierung
define('INSTALL', file_exists('./install/steps.inc.php'));
include('config.inc.php');

if (INSTALL) {
		
		//array mit allen schritten, weitere können leicht hinzugefügt werden
		require_once('install/steps.inc.php');
		if (isset($_REQUEST['step'])) {
			//sicherstellen das int, deswegen wenn notwendig umwandlung
			$step = intval($_REQUEST['step']);
			//wenn kein schritt gesetzt dann bei 1 anfangen
			if (!isset($steps[$step])) {
				$step = 1;
			}
		}
		else {
			//schritt gleich 1 so könnte man den installer immer wieder von vorne nach hinter durchgehen
			$step = 1;
		}
		//einen schritt weiter
		$nextstep = $step+1;
?>
<html>
<head>
	<title>Installation des Dienstes</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style type="text/css"><!--
		.percents {
		 background: #FFF;
		 border: 1px solid #CCC;
		 margin: 1px;
		 height: 20px;
		 position:absolute;
		 width:250px;
		 //z-index:10;
		 //left: 100px;
		 //top: 380px;
		 text-align: center;
		 float:left;
		}
		-->
		</style>	
</head>
<body>
<h1>Installation des Dienstes</h1>
	<div style="float:left;width:25%;">
		<h3>Installationsschritte:</h3>
		<ul>
		<?php
		//die einzelnen Schritte werden angezeigt
		foreach ($steps as $id => $lauf) {
			echo '<li';
			//wenn aktueller schritt dann bold
			if ($id == $step) {
				echo ' style="font-weight: bold;"';
			}
			echo '>';
			//wenn schritt schon fertig, dann link zum klicken
			if ($id < $step) {
				echo '<a href="index.php?step='.$id.'">'.$lauf.'</a>';
			}
			//sonst nur anzeigen
			else {
				echo $lauf;
			}
			echo '</li>';
		}
		?>
		</ul>
	</div>
	<div style="float:left;width:75%;">
		<form method="post" action="index.php?step=<?php echo $nextstep; ?>">
		<div>
			<h3>Aktueller Schritt: <?php echo $steps[$step]; ?></h3>
			<?php include('install/steps/'.$step.'.php'); ?>
		</div>
		</form>
	</div>

</body>
</html>

<?php
//Install Check else könnte man auch mit einem if not install machen
 } else
 { ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>Aktualisierung der Daten</title>
		<style type="text/css"><!--
		.percents {
		 background: #FFF;
		 border: 1px solid #CCC;
		 margin: 1px;
		 height: 20px;
		 position:absolute;
		 width:250px;
		 //z-index:10;
		 //left: 100px;
		 //top: 380px;
		 text-align: center;
		 float:left;
		}
		-->
		</style>
    </head>
    <body>
<h1>Aktualisierung der Daten</h1>
<div style="float:left;width:25%;">
<h3>Schritt:</h3>
<li><b>Die Pegel-Daten werden aktualisiert</b></li>
</div>
<div style="float:left;width:75%;">
<h3>Fortschritt:</h3>
<?php
if (ob_get_level() == 0) {
    ob_start();
}
		//fsock
		$page = Util::get_document($xmlurl);
        //$xml = simplexml_load_file($xmlurl); //geht nicht da fsocket benutzt
		//ist jetzt ein string
		$xml = simplexml_load_string($page);
		if($xml) { //pruefen ob gueltiges xml bzw. wohlgeformt
		echo 'XML Datei ist Fehlerfrei bzw. Wohlgeformt wird verarbeitet ... ';

flush();
ob_flush();
$temp = 0;
		foreach ($xml->table->gewaesser as $gewaesser) {
            foreach($gewaesser->item as $item) {
	//$anzahl = intval(sizeof($gewaesser)/10);
	$anzahl = count($item)/10;
	$temp = $temp + $anzahl;
	echo '<div class="percents">' . $temp . 'Daten&nbsp;verarbeitet</div>';
				Daten::save_update_xml_shell($pegelnummer = $item->pegelnummer, $pegelname = $item->pegelname, $km = $item->km, $messwert = $item->messwert, $datum = $item->datum, $uhrzeit = $item->uhrzeit, $pnp = $item->pnp, $tendenz = $item->tendenz);
            }
		}
	echo '<div class="percents">Done.</div>';
        } else {
            echo '<p>Die Datei '. $xmlname .' enhaelt fehler</p>';
        }
		echo "fertig";
ob_end_flush();
		?>
</div>
    </body>
</html>
  
 <?php
 //ende else
 }
  ?>