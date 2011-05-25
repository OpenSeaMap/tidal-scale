<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 25.05.11 12:25 Uhr
The object of the file:
All transformations in a class
allocated to different functions
For Bessel-ellipsoid and Krassowsky always different functions were used
*/
 
class Transformation {
	
	
    function __constuct() {
    	
    }
	
	/*
	 * These formulas based on [Gro76] and [HKL94]
     * transforms GK coordinates in lat and lon
	 * @param $hoch and $rechts (properly formatted, this is always the case, because the data is stored matching in the database)
	 * @return $lat and $lon
     */
    public static function GK_geo($hoch,$rechts) {
		//Conversion is not necessary
		$hw = $hoch;
		$rw = $rechts;
	
		//mathematical constants
		$PI = pi();
		$rho = 180 / $PI;
		
		// constants for Bessel-Ellipsoid
		$a = 6377397.155;
		$b = 6356078.963;
		
		//"2. numerische Exzentrizitaet"
		//$e_strich_2 = 0.00671921879;
		//better calculated by
		$e_strich_2 = (($a * $a) - ($b * $b)) / ($b * $b);
		
		//"Polkrümmungshalbmesser / Polkrümmungsradius"
		//$c = 6398786.849;
		//better calculated by
		$c = ($a * $a)/$b;
		
		//first number by the "rechtswert"(easting) for the determination of the "strip zone"
		$zone = substr($rw, 0, 1);
		//int cast
		$zone = intval($zone);
		
		//transformation
		$rm = $rw - $zone * 1000000 - 500000;
		$bI = $hw / 10000855.7646;
		$bII = $bI * $bI;
		//Constants were calculated and used
		$bf = 325632.08677 * $bI * ((((((0.00000562025 * $bII - 0.00004363980) * $bII + 0.00022976983) * $bII - 0.00113566119) * $bII + 0.00424914906) * $bII - 0.00831729565) * $bII + 1);
		$bf = $bf / 3600 / $rho;
		//conversion for lat and lon
		$cos_bf = cos(deg2rad($bf));		
		$g2 = $e_strich_2 * ($cos_bf * $cos_bf);
		$g1 = $c / (sqrt(1 + $g2));
		$tan_bf = tan(deg2rad($bf));
		$fa = $rm / $g1;
		
		//lat
		$breite = $bf - ($fa * $fa * $tan_bf * (1 + $g2) / 2) + ($fa * $fa * $fa * $fa * $tan_bf * (5 + 3 * $tan_bf * $tan_bf + 6 * $g2 - 6 * $g2 * $tan_bf * $tan_bf) / 24);
		$breite = $breite * $rho; 
		//lon
		$laenge = $fa - ($fa * $fa * $fa * (1 + 2 * $tan_bf * $tan_bf + $g2) / 6) + ($fa * $fa * $fa * $fa * $fa * (1 + 28 * $tan_bf * $tan_bf + 24 * $tan_bf * $tan_bf * $tan_bf * $tan_bf) / 120);
		$laenge = $laenge * $rho / $cos_bf + $zone * 3;

		//lat and lon
		$lat = $breite;
		$lon = $laenge;

	//PHP has only a return value, so this does not work
	//return $lat,$lon; !!!
	//an array is just one return value, this works
	return array($lat, $lon);
		
    }

		/*
		* transforms GK coordinates in lat and lon with constants
		* designed to test
		* @param $hoch and $rechts (properly formatted, this is always the case, because the data is stored matching in the database)
		* @return $lat and $lon
		*/
		public static function GK_geo_6point_3eck($hoch,$rechts) {
		
		/*
		Punkte bilden ein Dreieck
		Breite and Laenge berechnet mit [BKG11]
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
		
		//constants
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
		* transforms GK coordinates in lat and lon with constants
		* designed to test
		* @param $hoch and $rechts (properly formatted, this is always the case, because the data is stored matching in the database)
		* @return $lat and $lon
		*/
	public static function GK_geo_6point($hoch,$rechts) {
	
		/*
		Punkte willkührlich gewählt
		Breite and Laenge berechnet mit [BKG11]
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
	
		//constants
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
	   	* These formulas based on [GJ11, S.114]
		* transforms GK coordinates in geo. coorinates
		* Bessel-Ellipsoid
		* @param $hoch and $rechts (properly formatted, this is always the case, because the data is stored matching in the database)
		* @return $lat and $lon
		*/
	public static function GK_geo_bessel($hoch,$rechts,$laenge) {
		//Conversion is not necessary
		$hw = $hoch;
		$rw = $rechts;
	
		//mathematical constants
		$PI = pi();
		$rho = 180 / $PI;
		
		//Bessel-Ellipsoid
		$e0 = 111120.619607;
		$f2 = 0.143885364;
		$f4 = 0.000210771;
		$f6 = 0.000000427;
		
		$a = 6377397.155;
		$b = 6356078.963;
		
		//"strip zone"
		$l0 = $laenge*3;
		
		//important terms
		$y = $rw - ((($l0/3)+0.5) * pow(10, 6));
		//"Polkrümmungshalbmesser"
		$c = ($a * $a)/$b; 
		//"2. numerische Exzentrizitaet"
		$e_strich_2 = (($a * $a)-($b * $b))/($b * $b);
		$sigma = ($hw/$e0);
		
		//Forming needed for the latitude and longitude
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

		//lat and lon
		$lat = $breite;
		$lon = abs($l+$l0);
		
		//Werte sollten fuer laenge zwischen 5 and 16 liegen fuer die breite zwischen 46 and 56
		//Ueberpruefung koennte eingebaut werden,
		//allerdings stammen die GK-Koordinaten so direkt von der Budesanstalt fuer Wasserbau,
		//somit ist eine Ueberpruefung nicht notwendig, da an den Werten nichts geaendert werden kann

	//PHP has only a return value, so this does not work
	//return $lat,$lon; !!!
	//an array is just one return value, this works
	return array($lat, $lon);
		
    }
	
	
	   /*
	    * These formulas based on [GJ11, S.114]
		* transforms GK coordinates in geo. coorinates
		* Krassowsky-Ellipsoid
		* @param $hoch and $rechts (properly formatted, this is always the case, because the data is stored matching in the database)
		* @return $lat and $lon
		*/
	public static function GK_geo_krass($hoch,$rechts,$laenge) {
		//Conversion is not necessary
		$hw = $hoch;
		$rw = $rechts;
	
		//mathematical constants
		$PI = pi();
		$rho = 180 / $PI;
		
		//Krassowsky-Ellipsoid
		$e0 = 111134.861087;
		$f2 = 0.144297408;
		$f4 = 0.000211980;
		$f6 = 0.000000431;
		
		$a = 6378245;
		$b = 6356863.019;
		
		//"strip zone"
		$l0 = $laenge*3;
		
		//important terms
		$y = $rw - ((($l0/3)+0.5) * pow(10, 6));
		//"Polkrümmungshalbmesser"
		$c = ($a * $a)/$b;
		//"2. numerische Exzentrizitaet"
		$e_strich_2 = (($a * $a)-($b * $b))/($b * $b);
		$sigma = ($hw/$e0);

		//Forming needed for the latitude and longitude
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

		//lat and lon
		$lat = $breite;
		$lon = abs($l+$l0);
		
		//Werte sollten fuer laenge zwischen 5 and 16 liegen fuer die breite zwischen 46 and 56
		//Ueberpruefung koennte eingebaut werden,
		//allerdings stammen die GK-Koordinaten so direkt von der Budesanstalt fuer Wasserbau,
		//somit ist eine Ueberpruefung nicht notwendig, da an den Werten nichts geaendert werden kann


	//PHP has only a return value, so this does not work
	//return $lat,$lon; !!!
	//an array is just one return value, this works
	return array($lat, $lon);
		
    }

		/*
		* shifts the coordinates from Bessel-Ellipsoid to Bessel Cartesian [GJ11, S.111]
		* @param $lat and $lon Bessel-Ellipsoid
		* @return $x and $y
		*/
	public static function geo_bessel_kart($lat,$lon,$pnp) {
		//Conversion is not necessary
		$breite = $lat;
		$laenge = $lon;
		
		//Bessel-Ellipsoid
		$a = 6377397.155;
		$b = 6356075.963;
		
		//height not given, therefore, assumed to be 0 ...
		//$h = 0;
		//[Tor03, S. 291-293]
		//height reference level corresponds to level 37 meters Amsterdam
		$h = 37;
		//the PNP must be deducted or added from h
		$h = $h - $pnp;
		
		//"1. numerische Exzentrizitaet	"	
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

	//PHP has only a return value, so this does not work
	//return $x,$y,$z; !!!
	//an array is just one return value, this works
	return array($x, $y, $z);
		
    }

		/*
		* shifts the coordinates from Krassowsky-Ellipsoid to Krassowsky Cartesian [GJ11, S.111]
		* @param $lat and $lon Krassowsky-Ellipsoid
		* @return $x and $y
		*/
	public static function geo_krass_kart($lat,$lon,$pnp) {
		//Conversion is not necessary
		$breite = $lat;
		$laenge = $lon;
		
		//Krassowsky-Ellipsoid
		$a = 6378245;
		$b = 6356863.019;
		
		//height not given, therefore, assumed to be 0 ...
		//$h = 0;
		//height reference level corresponds to "kronstadt" level 16 meters lower than amsterdam 37-16 meters 
		//[Tor03, S. 291-293]
		$h = 37-16;
		//the PNP must be deducted or added from h
		$h = $h - $pnp;
		
		//"1. numerische Exzentrizitaet"
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

	//PHP has only a return value, so this does not work
	//return $x,$y,$z; !!!
	//an array is just one return value, this works
	return array($x, $y, $z);
		
    }

		/*
		* Rotation and translation Bessel-Ellipsoid [GJ11, S.109-110]
		* Translations values by [Tor03, Kap.7]
		* better Translations values from [BKG11b]
		* @param $x, $y, $z
		* @return $x, $y, $z
		*/
	public static function rotation_translation_bessel_wgs84($x1,$y1,$z1) {

	    // Rotated vectors
		//already calculated values from [BKG11b] are still to be converted into radians
		//rotation X-axis +0.202" -> 0.0000119021759
		//rotation Y-axis +0.045" -> 0.000000218166156
		//rotation Z-axis -2.455" -> -0.0000009793236
		//PI each time by 180 by 3600
		
		$x2 = ($x1 * 1) + ($y1 * 0.0000119021759) + ($z1 * 0.000000218166156);
        $y2 = ($x1 * -0.0000119021759) + ($y1 * 1) + ($z1 * -0.000000979323636);
        $z2 = ($x1 * -0.000000218166156) + ($y1 * 0.0000009793236) + ($z1 * 1);
		
		//scale factor 0.99999 < m < 1.00001
		//here 0.9999933
		$m =  0.9999933;
		
        // translation
		$x = ($x2 * $m) + 598.095;
		$y = ($y2 * $m) + 73.707;
		$z = ($z2 * $m) + 418.197;

	//PHP has only a return value, so this does not work
	//return $x,$y,$z; !!!
	//an array is just one return value, this works
	return array($x, $y, $z);
		
    }

		/*
		* Rotation and translation Krassowsky-Ellipsoid [GJ11, S.109-110]
		* Translations values by [Tor03, Kap.7]
		* better Translations values from [BKG11c]
		* @param $x, $y, $z
		* @return $x, $y, $z
		*/
	public static function rotation_translation_krass_wgs84($x1,$y1,$z1) {

	    // Rotated vectors
		//already calculated values from [BKG11c] are still to be converted into radians
		//rotation X-axis -0.063" -> -0.000000305433
		//rotation Y-axis -0.247" -> -0.000001197489
		//rotation Z-axis -0.041" -> -0.000000198774
		//PI each time by 180 by 3600
		
        $x2 = ($x1 * 1) + ($y1 * -0.000000305433) + ($z1 * -0.000001197489);
        $y2 = ($x1 * 0.000000305433) + ($y1 * 1) + ($z1 * -0.000000198774);
        $z2 = ($x1 * 0.000001197489) + ($y1 * 0.000000198774) + ($z1 * 1);
		
		//scale factor 0.99999 < m < 1.00001
		//here 0.9999933
		$m =  0.9999933;
		
        // translation
		$x = ($x2 * $m) + 24.9;
		$y = ($y2 * $m) - 126.4;
		$z = ($z2 * $m) - 93.2;

	//PHP has only a return value, so this does not work
	//return $x,$y,$z; !!!
	//an array is just one return value, this works
	return array($x, $y, $z);
		
    }
	
		/*
		* transforms the Cartesian coordinates with translation to WGS84
		* [GJ11, S.112]
		* @param $x, $y and $z
		* @return $lat and $lon WGS84
		*/
	public static function kart_wgs84_geo($x,$y,$z) {
		
		//mathematical constants
		$PI = pi();
		$rho = 180 / $PI;
		
		//constants
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
		
		//from other formulaic
		//$breite = atan((($z + ($e2 * ($a *$a) / $b * $sin_phi_3)) / ($p - ($e2 * $a * $cos_phi_3))));
		$breite = atan(($z + ($e2_strich * $b * $sin_phi_3))/($p - ($e2 * $a * $cos_phi_3)));
		
		//lat and lon assign to the results, important not forgotten rho
		$lat = $breite * $rho;
		$lon = $laenge * $rho;
		
	//PHP has only a return value, so this does not work
	//return $lat,$lon; !!!
	//an array is just one return value, this works
	return array($lat, $lon);
		
    }
	
	/*
     * rounded if required for osm 
	 * @param $zahl and $stelle
	 * @retrun $zahl (rounded)
     */
	public static function r_round($zahl, $stelle) {
	$digit = pow(10, $stelle);
	$zahl = ($zahl * $digit);
	$zahl = round($zahl);
	//or
	//$zahl = ceil($zahl);
	$zahl = ($zahl / $digit);
	
	return $zahl;
	}

}

?>