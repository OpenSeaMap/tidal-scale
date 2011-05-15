<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 14.05.11 18:54 Uhr
*/

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
	<title>Installation</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<div>
		<h3>Installationsschritte</h3>
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
	<div>
		<form method="post" action="index.php?step=<?php echo $nextstep; ?>">
		<div>
			<h3><?php echo $steps[$step]; ?></h3>
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
 
 <!--
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 14.05.11 18:54 Uhr
-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
    <head>
        <title>Index </title>
    </head>
    <body>
<h1>Willkommen</h1>
<div>Durch den Aufruf werden die Pegel-Daten aktualisiert</div><br>
<?php
		//fsock
		$page = Util::get_document($xmlurl);
        //$xml = simplexml_load_file($xmlurl); //geht nicht da fsocket benutzt
		//ist jetzt ein string
		$xml = simplexml_load_string($page);
		if($xml) { //pruefen ob gueltiges xml bzw. wohlgeformt
		echo 'XML Datei ist Fehlerfrei bzw. Wohlgeformt wird verarbeitet ... ';
		foreach ($xml->table->gewaesser as $gewaesser) {
            foreach($gewaesser->item as $item) {
				Daten::save_update_xml_shell($pegelnummer = $item->pegelnummer, $pegelname = $item->pegelname, $km = $item->km, $messwert = $item->messwert, $datum = $item->datum, $uhrzeit = $item->uhrzeit, $pnp = $item->pnp, $tendenz = $item->tendenz);
            }
		}
        } else {
            echo '<p>Die Datei '. $xmlname .' enhaelt fehler</p>';
        }
		echo "fertig";
		?>
	<br><br>
    </body>
</html>
  
 <?php
 }
  ?>