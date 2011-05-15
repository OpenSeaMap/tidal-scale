<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 06.05.11 16:22 Uhr
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
	 * Diese Formeln bzw. die Umrechung basiert auf [Gro76] und [HKL94]
     * transformiert die GK-Koordinaten in lat und lon
	 * @param $hoch und $rechts (richtig formatiert, dies ist immer der fall, da die Daten passend in der DB abgelegt werden)
	 * @return $lat und $lon
     */
    public static function GK_geo($hoch,$rechts) {
		//Umwandlung nicht notwendig 
		$hw = $hoch;
		$rw = $rechts;
	
		//mathematische Konstanten
		$PI = pi();
		$rho = 180 / $PI;
		
		// Konstanten für Essel-Ellipsoid
		$a = 6377397.155;
		$b = 6356078.963;
		
		//2. numerische Exzentrizitaet
		//$e_strich_2 = 0.00671921879;
		//oder besser wird berechnet durch 
		$e_strich_2 = (($a * $a) - ($b * $b)) / ($b * $b);
		
		//Polkrümmungshalbmesser / Polkrümmungsradius
		//$c = 6398786.849;
		//oder besser wird berechnet durch 
		$c = ($a * $a)/$b;
		
		//erste Stelle vom rechtswert fuer die Bestimmung der streifenzone nehmen
		$zone = substr($rw, 0, 1);
		//int cast
		$zone = intval($zone);
		
		//die Umwandlung
		$rm = $rw - $zone * 1000000 - 500000;
		$bI = $hw / 10000855.7646;
		$bII = $bI * $bI;
		//Konstanten berechnet und eingesetzt
		$bf = 325632.08677 * $bI * ((((((0.00000562025 * $bII - 0.00004363980) * $bII + 0.00022976983) * $bII - 0.00113566119) * $bII + 0.00424914906) * $bII - 0.00831729565) * $bII + 1);
		$bf = $bf / 3600 / $rho;
		//Umformungen die fuer breite und laenge benoetigt werden
		$cos_bf = cos(deg2rad($bf));		
		$g2 = $e_strich_2 * ($cos_bf * $cos_bf);
		$g1 = $c / (sqrt(1 + $g2));
		$tan_bf = tan(deg2rad($bf));
		$fa = $rm / $g1;
		
		//breite
		$breite = $bf - ($fa * $fa * $tan_bf * (1 + $g2) / 2) + ($fa * $fa * $fa * $fa * $tan_bf * (5 + 3 * $tan_bf * $tan_bf + 6 * $g2 - 6 * $g2 * $tan_bf * $tan_bf) / 24);
		$breite = $breite * $rho; 
		//laenge
		$laenge = $fa - ($fa * $fa * $fa * (1 + 2 * $tan_bf * $tan_bf + $g2) / 6) + ($fa * $fa * $fa * $fa * $fa * (1 + 28 * $tan_bf * $tan_bf + 24 * $tan_bf * $tan_bf * $tan_bf * $tan_bf) / 120);
		$laenge = $laenge * $rho / $cos_bf + $zone * 3;

		//lat und lon die Ergebnisse zuweisen
		$lat = $breite;
		$lon = $laenge;

	//PHP hat immer nur einen Rückgabewert, deswegen geht dies nicht
	//return $lat,$lon; !!!
	//ein array ist aber nur eine Rückgabe
	//deswegen geht folgendes
	return array($lat, $lon);
		
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
	
	   /*
		* GK koordinaten in Geo. Koordinaten [GJ11, S.114] hier für Bessel-Ellipsoid
		* @param $hoch und $rechts (richtig formatiert, dies ist immer der fall, da die Daten passend in der DB abgelegt werden) $laenge wird passend ausgelesen
		* @return $lat und $lon
		*/
	public static function GK_geo_bessel($hoch,$rechts,$laenge) {
		//Umwandlung nicht notwendig
		$hw = $hoch;
		$rw = $rechts;
	
		//mathematische Konstanten
		$PI = pi();
		$rho = 180 / $PI;
		
		//fuer Bessel-Ellipsoid
		$e0 = 111120.619607;
		$f2 = 0.143885364;
		$f4 = 0.000210771;
		$f6 = 0.000000427;
		
		$a = 6377397.155;
		$b = 6356078.963;
		
		//aus Streifenzonen laengen machen
		$l0 = $laenge*3;
		
		//wichtige Terme
		$y = $rw - ((($l0/3)+0.5) * pow(10, 6));
		//Polkrümmungshalbmesser
		$c = ($a * $a)/$b; 
		//2. numerische Exzentrizitaet
		$e_strich_2 = (($a * $a)-($b * $b))/($b * $b);
		$sigma = ($hw/$e0);
		
		//Umformungen die fuer breite und laenge benoetigt werden
		$sigma2 = $sigma * 2;
		$sigma4 = $sigma * 4;
		$sigma6 = $sigma * 6;
		
		$sin_sigma2 = sin(deg2rad($sigma2));
		$sin_sigma4 = sin(deg2rad($sigma4));
		$sin_sigma6 = sin(deg2rad($sigma6));
		
		$bx = $sigma + ($f2 * $sin_sigma2) + ($f4 * $sin_sigma4) + ($f6 * $sin_sigma6);
		
		$cos_bx = cos(deg2rad($bx));
		$cos_2 = $cos_bx * $cos_bx;
		
		$t = tan(deg2rad($bx));
		$t2 = $t * $t;
		
		$v = sqrt(1 + ($e_strich_2 * $cos_2));
		$v2 = $v * $v;

		$y_strich = ($y * $v)/$c;
		$y_strich_2 = $y_strich * $y_strich;
		
		$breite = $bx - (($y_strich_2 * $rho * $t * $v2) * (0.5 - ($y_strich_2 * ((4.97 + (3*$t2))/24))));
		
		$l_hilf1 = $y_strich * ($rho / $cos_bx);
		$l_hilf2 = pow((0.6+(1.1*$t2)), 2);
		$l_hilf3 = (1 - ($y_strich_2/6) * ($v2+(2*$t2) - ($y_strich_2 * $l_hilf2)));
		$l = $l_hilf1  * $l_hilf3;

		//lat und lon die Ergebnisse zuweisen
		$lat = $breite;
		$lon = abs($l+$l0);
		
		//Werte sollten fuer laenge zwischen 5 und 16 liegen fuer die breite zwischen 46 und 56
		//Ueberpruefung koennte eingebaut werden,
		//allerdings stammen die GK-Koordinaten so direkt von der Budesanstalt fuer Wasserbau,
		//somit ist eine Ueberpruefung nicht notwendig, da an den Werten nichts geaendert werden kann

	//PHP hat immer nur einen Rückgabewert, deswegen geht dies nicht
	//return $lat,$lon; !!!
	//ein array ist aber nur eine Rückgabe
	//deswegen geht folgendes
	return array($lat, $lon);
		
    }
	
	
	   /*
		* GK-Koordinaten in Geo. Koordinaten [GJ11, S.114] hier für Krassowsky-Ellipsoid
		* @param $hoch und $rechts (richtig formatiert, dies ist immer der fall, da die Daten passend in der DB abgelegt werden) $laenge wird passend ausgelesen
		* @return $lat und $lon
		*/
	public static function GK_geo_krass($hoch,$rechts,$laenge) {
		//Umwandlung nicht notwendig
		$hw = $hoch;
		$rw = $rechts;
	
		//mathematische Konstanten
		$PI = pi();
		$rho = 180 / $PI;
		
		//fuer Krassowsky-Ellipsoid
		$e0 = 111134.861087;
		$f2 = 0.144297408;
		$f4 = 0.000211980;
		$f6 = 0.000000431;
		
		$a = 6378245;
		$b = 6356863.019;
		
		//aus Streifenzonen laengen machen
		$l0 = $laenge*3;
		
		//wichtige Terme
		$y = $rw - ((($l0/3)+0.5) * pow(10, 6));
		//Polkrümmungshalbmesser
		$c = ($a * $a)/$b;
		//2. numerische Exzentrizitaet
		$e_strich_2 = (($a * $a)-($b * $b))/($b * $b);
		$sigma = ($hw/$e0);

		//Umformungen die fuer breite und laenge benoetigt werden
		$sigma2 = $sigma * 2;
		$sigma4 = $sigma * 4;
		$sigma6 = $sigma * 6;
		
		$sin_sigma2 = sin(deg2rad($sigma2));
		$sin_sigma4 = sin(deg2rad($sigma4));
		$sin_sigma6 = sin(deg2rad($sigma6));
		
		$bx = $sigma + ($f2 * $sin_sigma2) + ($f4 * $sin_sigma4) + ($f6 * $sin_sigma6);
		
		$cos_bx = cos(deg2rad($bx));
		$cos_2 = $cos_bx * $cos_bx;
		
		$t = tan(deg2rad($bx));
		$t2 = $t * $t;
		
		$v = sqrt(1 + ($e_strich_2 * $cos_2));
		$v2 = $v * $v;

		$y_strich = ($y * $v)/$c;
		$y_strich_2 = $y_strich * $y_strich;
		
		$breite = $bx - (($y_strich_2 * $rho * $t * $v2) * (0.5 - ($y_strich_2 * ((4.97 + (3*$t2))/24))));
		
		$l_hilf1 = $y_strich * ($rho / $cos_bx);
		$l_hilf2 = pow((0.6+(1.1*$t2)), 2);
		$l_hilf3 = (1 - ($y_strich_2/6) * ($v2+(2*$t2) - ($y_strich_2 * $l_hilf2)));
		$l = $l_hilf1  * $l_hilf3;

		//lat und lon die Ergebnisse zuweisen
		$lat = $breite;
		$lon = abs($l+$l0);
		
		//Werte sollten fuer laenge zwischen 5 und 16 liegen fuer die breite zwischen 46 und 56
		//Ueberpruefung koennte eingebaut werden,
		//allerdings stammen die GK-Koordinaten so direkt von der Budesanstalt fuer Wasserbau,
		//somit ist eine Ueberpruefung nicht notwendig, da an den Werten nichts geaendert werden kann


	//PHP hat immer nur einen Rückgabewert, deswegen geht dies nicht
	//return $lat,$lon; !!!
	//ein array ist aber nur eine Rückgabe
	//deswegen geht folgendes
	return array($lat, $lon);
		
    }

		/*
		* verschiebt die Koordinaten vom Bessel-Ellipsoid auf Bessel kartesisch [GJ11, S.111]
		* @param $lat und $lon auf dem Bessel-Ellipsoid
		* @return $x und $y auf bessel
		*/
	public static function geo_bessel_kart($lat,$lon,$pnp) {
		//Umwandlung nicht notwendig trägt aber zum verständnis bei
		$breite = $lat;
		$laenge = $lon;
		
		//Konstanten fuer Bessel-Ellipsoid
		$a = 6377397.155;
		$b = 6356075.963;
		
		//hoehe nicht gegeben daher 0 angenommen ...
		//$h = 0;
		//[Tor03, S. 291-293]
		//höhe entspricht bezugspegel amsterdammer pegel 37 meter
		$h = 37;
		//davon muss noch der pnp abgezogen bzw. addiert werden
		$h = $h - $pnp;
		
		//1. numerische Exzentrizitaet		
		$e2 = (($a * $a) - ($b * $b)) / ( $a * $a);
		
		$sin_b = sin(deg2rad($breite));
		$sin2_b = $sin_b * $sin_b;
		$cos_b = cos(deg2rad($breite));
		$cos_l = cos(deg2rad($laenge));
		$sin_l = sin(deg2rad($laenge));
		
		$n_hilf = sqrt(1 - ($e2 * $sin2_b));
		$n = $a / $n_hilf ;
		
		$x = ($n+$h) * $cos_b * $cos_l;
		$y = ($n+$h) * $cos_b * $sin_l;
		//$z = $n * $sin_b * (($b * $b) / ($a * $a)) + $h  * $sin_b
		$z = (((1 - $e2) * $n) + $h ) * $sin_b;

	//PHP hat immer nur einen Rückgabewert, deswegen geht dies nicht
	//return $x,$y,$z; !!!
	//ein array ist aber nur eine Rückgabe
	//deswegen geht folgendes
	return array($x, $y, $z);
		
    }

		/*
		* verschiebt die Koordinaten vom Krassowsky-Ellipsoid auf Krassowsky kartesisch [GJ11, S.111]
		* @param $lat und $lon auf dem Krassowsky-Ellipsoid
		* @return $x und $y auf besserl
		*/
	public static function geo_krass_kart($lat,$lon,$pnp) {
		//Umwandlung nicht notwendig trägt aber zum verständnis bei
		$breite = $lat;
		$laenge = $lon;
		
		//Konstanten Krassowsky-Ellipsoid
		$a = 6378245;
		$b = 6356863.019;
		
		//hoehe nicht gegeben daher 0 angenommen ...
		//$h = 0;
		//höhe entspricht bezugspegel kronstaedter pegel 16 meter niedriger als amsterdam 37-16 meter
		//[Tor03, S. 291-293]
		$h = 37-16;
		//davon muss noch der pnp abgezogen bzw. addiert werden
		$h = $h - $pnp;
		
		//1. numerische Exzentrizitaet
		$e2 = (($a * $a) - ($b * $b)) / ( $a * $a);
		
		$sin_b = sin(deg2rad($breite));
		$sin2_b = $sin_b * $sin_b;
		$cos_b = cos(deg2rad($breite));
		$cos_l = cos(deg2rad($laenge));
		$sin_l = sin(deg2rad($laenge));
		
		$n_hilf = sqrt(1 - ($e2 * $sin2_b));
		$n = $a / $n_hilf ;
		
		$x = ($n+$h) * $cos_b * $cos_l;
		$y = ($n+$h) * $cos_b * $sin_l;
		//$z = $n * $sin_b * (($b * $b) / ($a * $a)) + $h  * $sin_b
		$z = (((1 - $e2) * $n) + $h ) * $sin_b;

	//PHP hat immer nur einen Rückgabewert, deswegen geht dies nicht
	//return $x,$y,$z; !!!
	//ein array ist aber nur eine Rückgabe
	//deswegen geht folgendes
	return array($x, $y, $z);
		
    }

		/*
		* Rotation und Translation Bessel-Ellipsoid [GJ11, S.109-110]
		* Translations Werte nach [Tor03, Kap.7]
		* genauere Rotations Vektoren nach [BKG11b]
		* @param $x, $y, $z
		* @return $x, $y, $z
		*/
	public static function rotation_translation_bessel_wgs84($x1,$y1,$z1) {

	    // Rotierte Vektoren
		//schon ausgerechnet Werte aus [BKG11b] sind noch in Radianten umzurechnen
		//rotation X-axis +0.202"
		//rotation Y-axis +0.045"
		//rotation Z-axis -2.455"
		//jeweils mal PI durch 180 durch 3600
		
		$x2 = ($x1 * 1) + ($y1 * 0.0000119021759) + ($z1 * 0.000000218166156);
        $y2 = ($x1 * -0.0000119021759) + ($y1 * 1) + ($z1 * -0.000000979323636);
        $z2 = ($x1 * -0.000000218166156) + ($y1 * 0.0000009793236) + ($z1 * 1);
		
		//massstabsfaktor liegt zwischen 0.99999 < m < 1.00001
		//hier auf 0.9999933 gesetzt
		$m =  0.9999933;
		
        // Translationen anbringen
		$x = ($x2 * $m) + 598.095;
		$y = ($y2 * $m) + 73.707;
		$z = ($z2 * $m) + 418.197;

	//PHP hat immer nur einen Rückgabewert, deswegen geht dies nicht
	//return $x,$y,$z; !!!
	//ein array ist aber nur eine Rückgabe
	//deswegen geht folgendes
	return array($x, $y, $z);
		
    }

		/*
		* Rotation und Translation Krassowsky-Ellipsoid [GJ11, S.109-110]
		* Translations Werte nach [Tor03, Kap.7]
		* genauere Rotations Vektoren nach [BKG11c]
		* @param $x, $y, $z
		* @return $x, $y, $z
		*/
	public static function rotation_translation_krass_wgs84($x1,$y1,$z1) {

	    // Rotierte Vektoren
		//schon ausgerechnet Werte aus [BKG11c] sind noch in Radianten umzurechnen
		//rotation X-axis -0.063" -> -0.000000305433
		//rotation Y-axis -0.247" -> -0.000001197489
		//rotation Z-axis -0.041" -> -0.000000198774
		//jeweils mal PI durch 180 durch 3600
		
        $x2 = ($x1 * 1) + ($y1 * -0.000000305433) + ($z1 * -0.000001197489);
        $y2 = ($x1 * 0.000000305433) + ($y1 * 1) + ($z1 * -0.000000198774);
        $z2 = ($x1 * 0.000001197489) + ($y1 * 0.000000198774) + ($z1 * 1);
		
		//massstabsfaktor liegt zwischen 0.99999 < m < 1.00001
		//hier auf 0.9999933 gesetzt
		$m =  0.9999933;
		
        // Translationen anbringen
		$x = ($x2 * $m) + 24.9;
		$y = ($y2 * $m) - 126.4;
		$z = ($z2 * $m) - 93.2;

	//PHP hat immer nur einen Rückgabewert, deswegen geht dies nicht
	//return $x,$y,$z; !!!
	//ein array ist aber nur eine Rückgabe
	//deswegen geht folgendes
	return array($x, $y, $z);
		
    }
	
		/*
		* wandelt die kartesiche Koordinaten die mit rotation_translation umgewandelt wurden
		* in breite und laenge auf WGS84 um
		* [GJ11, S.112]
		* @param $x, $y und $z
		* @return $lat und $lon auf WGS84
		*/
	public static function kart_wgs84_geo($x,$y,$z) {
		
		//mathematische Konstanten
		$PI = pi();
		$rho = 180 / $PI;
		
		//Konstanten
		$a = 6378137;
		$b = 6356752.314;
		
		$e2 = (($a * $a) - ($b * $b)) / ( $a * $a);
		$e2_strich = (($a * $a) - ($b * $b)) / ( $b * $b);
		
		$p = sqrt(($x*$x)+($y*$y));
		
		$phi = atan(($z*$a)/($p*$b));
		$cos_phi = cos($phi);
		$sin_phi = sin($phi);
		
		$cos_phi_3 = $cos_phi * $cos_phi * $cos_phi;
		$sin_phi_3 = $sin_phi * $sin_phi * $sin_phi;

		$laenge = atan($y/$x);
		
		//aus anderer formel
		//$breite = atan((($z + ($e2 * ($a *$a) / $b * $sin_phi_3)) / ($p - ($e2 * $a * $cos_phi_3))));
		$breite = atan(($z + ($e2_strich * $b * $sin_phi_3))/($p - ($e2 * $a * $cos_phi_3)));
		
		//lat und lon die Ergebnisse zuweisen, wichtig mal rho nicht vergessen
		$lat = $breite * $rho;
		$lon = $laenge * $rho;
		
	//PHP hat immer nur einen Rückgabewert, deswegen geht dies nicht
	//return $lat,$lon; !!!
	//ein array ist aber nur eine Rückgabe
	//deswegen geht folgendes
	return array($lat, $lon);
		
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

}

?>