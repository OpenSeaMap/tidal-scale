<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 04.06.11 12:57 Uhr
The object of the file:
The index file of installation / upgrade.
*/

//Charging indicator taken from php.net doku
//http://de3.php.net/manual/de/function.flush.php

//Idea for the installation steps from
//http://php.net/manual/en/function.next.php

//if file exists install else update
define('INSTALL', file_exists('./install/steps.inc.php'));
include('config.inc.php');

if (INSTALL) {
		
		//array with all the steps, others can be added easily
		require_once('install/steps.inc.php');
		if (isset($_REQUEST['step'])) {
			//ensure type int, if necessary transformation
			$step = intval($_REQUEST['step']);
			//if no step is set, begin at 1
			if (!isset($steps[$step])) {
				$step = 1;
			}
		}
		else {
			//step equal to 1, so you could go through the installer again from begin to end
			$step = 1;
		}
		//next step
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
		//the individual steps are shown
		foreach ($steps as $id => $lauf) {
			echo '<li';
			//if current then bold
			if ($id == $step) {
				echo ' style="font-weight: bold;"';
			}
			echo '>';
			//if step done, show link
			if ($id < $step) {
				echo '<a href="index.php?step='.$id.'">'.$lauf.'</a>';
			}
			//else show
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
//Install Check else
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
		//now is string
		$xml = simplexml_load_string($page);
		if($xml) { //test whether "well-formed" or valid XML
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
 //end else
 }
  ?>