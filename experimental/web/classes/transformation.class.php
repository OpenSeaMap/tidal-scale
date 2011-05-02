<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 02.05.11 11:52 Uhr
*/

/*
 * Alle Transformationen in einer Klasse
 * aufgeteilt auf verschiedene Funktionen
 * Fuer Bessel- und Krassowsky-Ellipsoid immer verschiedene Funktionen benutzt
 */
 
class Transformation {
	
	
    function __constuct() {
    	
    }
	
	/*
     * Zahlen auf feste Stellen runden, wenn fuer osm benoetigt
	 * @param $zahl und $stelle
	 * @retrun $zahl (gerundet)
     */
	public static function r_round($zahl, $stelle) {
	$digit = pow(10, $stelle);
	$zahl = ($zahl * $digit);
	$zahl = round($zahl);
	//oder
	//$zahl = ceil($zahl);
	$zahl = ($zahl / $digit);
	
	return $zahl;
	}

		/*
		* GK-Koordinaten mithilfe von Konstanten transformieren
		* zum Testen geschrieben
		* @param $hoch und $rechts (richtig formatiert, dies ist immer der fall, da die Daten passend in der DB abgelegt werden)
		* @return $lat und $lon
		*/
		public static function GK_geo_6point_3eck($hoch,$rechts) {
		
		/*
		Punkte bilden ein Dreieck
		Breite und Laenge berechnet mit [BKG11]
		Rechts		Hoch
		Laenge 		Breite
		KONSTANZ
		3513757.75 	5280398.00
		9.18214		47.66178
		BORKUM SÜDSTRAND
		2543850.75	5938584.00
		6.66142		53.57685
		UECKERMÜNDE
		5438544.50 	5958673.00
		14.06624	53.75560

		L = a2 * R + b2 * H + c2
		B = a1 * H + b1 * R + c1

		//matlab programm
		clear all;
		clc;
		digits(12)
		A= [5280398.00,3513757.75,1,0,0,0; 5938584.00,2543850.75,1,0,0,0; 5958673.00,5438544.50,1,0,0,0; 0,0,0,3513757.75,5280398.00,1; 0,0,0,2543850.75,5938584.00,1; 0,0,0,5438544.50,5958673.00,1];
		b= [47.66178,53.57685,53.75560,9.18214,6.66142,14.06624]';
		x= vpa(A\b)

		Ergibt:
		0.00000898602650489
		-0.000000000611562607167
		0.214132498492
		0.00000255848047014
		-0.0000000596060728315
		0.507003207607
		*/
		
		//Konstanten
		$a1 = 0.00000898602650489;
		$b1 = -0.000000000611562607167;
		$c1 = 0.214132498492;
		$a2 = 0.00000255848047014;
		$b2 = -0.0000000596060728315;
		$c2 = 0.507003207607;

		$hw = $hoch;
		$rw = $rechts;
		
		$lon = $a2 * $rw + $b2 * $hw + $c2;
		$lat = $a1 * $hw + $b1 * $rw + $c1;
	
	return array($lat, $lon);
	}

		/*
		* GK-Koordinaten mithilfe von Konstanten transformieren
		* zum Testen geschrieben
		* @param $hoch und $rechts (richtig formatiert, dies ist immer der fall, da die Daten passend in der DB abgelegt werden)
		* @return $lat und $lon
		*/
	public static function GK_geo_6point($hoch,$rechts) {
	
		/*
		Punkte willkührlich gewählt
		Breite und Laenge berechnet mit [BKG11]
		Rechts		Hoch
		Laenge 		Breite
		KÖLN
		3356914.43	5646606.45
		6.96330		50.93695
		SCHLESWIG
		3536932.00	6042547.00
		9.56916		54.51138
		GLÜCKSTADT
		3527058.00	5961553.00
		9.40944		53.78437

		L = a2 * R + b2 * H + c2
		B = a1 * H + b1 * R + c1

		//matlab programm
		clear all;
		clc;
		digits(8)
		A= [5646606.45,3356914.43,1,0,0,0; 6042547.00,3536932.00,1,0,0,0; 5961553.00,3527058.00,1,0,0,0; 0,0,0,3356914.43,5646606.45,1; 0,0,0,3536932.00,6042547.00,1; 0,0,0,3527058.00,5961553.00,1];
		b= [50.93695,54.51138,53.78437,6.96330,9.56916,9.40944]';
		x= vpa(A\b)

		Ergibt:
		0.0000089571933
		0.00000015506242
		-0.16132672
		0.000013852667
		0.00000028321562
		-41.138125
		*/
	
		//Konstanten
		$a1 = 0.0000089571933;
		$b1 = 0.00000015506242;
		$c1 = -0.16132672;
		$a2 = 0.000013852667;
		$b2 = 0.00000028321562;
		$c2 = -41.138125;

		$hw = $hoch;
		$rw = $rechts;
		
		$lon = $a2 * $rw + $b2 * $hw + $c2;
		$lat = $a1 * $hw + $b1 * $rw + $c1;
	
	return array($lat, $lon);
	}
	
	  

}

?>