<?php
/*
erstellt von Tim Reinartz im Rahmen der Bachelor-Thesis
letzte Änderung 20.04.11 14:05 Uhr
*/

// basiert auf der API die unter 
// http://www.pegelonline.wsv.de/webservice/guideAkt gefunden werden kann
// Author: Christian Seewald, EES GmbH, mail: c.seewald@ees-gmbh.de
// es waren ein paar änderungen notwendig unter anderem um kompatiblität mit php 5.1 aufwärts herzustellen


/*
stellt die für dieses Problem passende SOAP API zur Verfügung
*/
	class SOAPClientApi {
	
		var $soapClient;
		var $localTimezone;
		
		/*
		 * Konstruktur
		 * 
		 * $trace: Wenn TRUE kann der Soap Request und Response als XML angezeigt werden
		 * $exceptions: Wenn TRUE wird bei einem Soap-Fehler eine Exception vom Typ SoapFault geworfen wird (TRUE empfehlenswert)
		 */
		function SOAPClientApi($trace = TRUE, $exceptions = TRUE) {
			$this->soapClient = new SoapClient(
				'http://www.pegelonline.wsv.de/webservices/version2_3/2007/10/31/PegelonlineWebservice?WSDL', 
				array ( 'trace'   => $trace, 'exceptions'=> $exceptions));
		}
		
		/*
		 * Einzelne Abfragen von Pegelonline-Webservice.
		 * 
		 * Eine sprachenagnostische Dokumentation findet sich unter 
		 * http://www.pegelonline.wsv.de/webservice/dokuAkt.jsp
		 */
		
		function getParameterList() {
			return $this->modifyArrayResponse($this->soapClient->getParameterList()->getParameterListReturn);
		}
		
		function getFlussgebieteList($parameterName) {
			$params = array (
				'parameterName'=>$parameterName
			);
			return $this->modifyArrayResponse($this->soapClient->getFlussgebieteList($params)->getFlussgebieteListReturn);
		}
		
		function getGewaesserList($parameterName, $flussgebietName) {
			$params = array (
				'parameterName'=>$parameterName,
				'flussgebietName'=>$flussgebietName
			);
			
			return $this->modifyArrayResponse($this->soapClient->getGewaesserList($params)->getGewaesserListReturn);
		}
		
		function getGewaesser($gewaesserName) {
			$params = array (
				'gewaesserName'=>$gewaesserName
			);
			$this->soapClient->getGewaesser($params)->getGewaesserReturn;
		}
		
		function getMessstellenList($parameterName, $gewaesserNamen) {
			$params = array (
				'parameterName'=>$parameterName,
				'gewaesserNamen'=>$gewaesserNamen
			);
			return $this->modifyArrayResponse($this->soapClient->getMessstellenList($params)->getMessstellenListReturn);
		}
		
		function getMessstelle($messstelleNummer, $messstelleName) {
			$params = array (
				'messstelleNummer'=>$messstelleNummer,
				'messstelleName'=>$messstelleName
			);
			return $this->soapClient->getMessstelle($params)->getMessstelleReturn;
		}
		
		function getMessstellenParameter($messstelleNummer, $messstelleName) {
			$params = array (
				'messstelleNummer'=>$messstelleNummer,
				'messstelleName'=>$messstelleName
			);
			return $this->modifyArrayResponse($this->soapClient->getMessstellenParameter($params)->getMessstellenParameterReturn);
		}
		
		function getDatenverfuegbarkeit($parameterName, $messstellenNummern, $messstellenNamen) {
			$params = array (
				'parameterName'=>$parameterName,	
				'messstellenNummern'=>$messstellenNummern,
		      	'messstellenNamen'=>$messstellenNamen,
			);
			return $this->soapClient->getDatenverfuegbarkeit($params)->getDatenverfuegbarkeitReturn;
		}
		
		function getPegelinformationen($parameterName, $messstellenNummern, $messstellenNamen) {
			$params = array (
				'parameterName'=>$parameterName,	
				'messstellenNummern'=>$messstellenNummern,
		      	'messstellenNamen'=>$messstellenNamen,
			);
			return $this->modifyArrayResponse($this->soapClient->getPegelinformationen($params)->getPegelinformationenReturn);
		}
		
		/*
		 * Modifiziert den Response und kompensiert damit die ungünstige 'auto-magic'
		 * Erstellung der Proxy-Objekte bei bestimmten Soap Responses durch Php Soap Extension 
		 * (bei 0 und 1 Element(en) innerhalb eines Arrays im Soap Response)
		 * 
		 * Somit kann der response immer in einer foreach-Schleife verwendet werden  
		 * 
		 * Sollte bei allen Abfragen, die arrays liefern aufgerufen werden
		 */
		function &modifyArrayResponse(&$response) {
			//Soap Extension liefert Null bei leerer Ergebnismenge
			if ($response == NULL) {
				return array();
			
			//Soap Extension liefert kein array mit einem Element, wenn nur ein Element durch Webservice
			//zurück gegeben wird
			} elseif (!is_array($response)) {
				return array($response);

			//Ansonsten ok
			} else {
				return $response;
			}
		}
		
		/*
		 * Anzeigen des Soap XML Requests. Funktioniert nur, wenn
		 * die trace-Option im Konstruktor aktiviert wurde.
		 */
		function echoLastSoapRequest() {
			echo "Last SOAP Request:\n";
	  		echo $this->soapClient->__getLastRequest() . "\n";
		}
		
		/*
		 * Anzeigen des Soap XML Response. Funktioniert nur, wenn
		 * die trace-Option im Konstruktor aktiviert wurde.
		 */
		function echoLastSoapResponse() {
  			echo "Last SOAP Response:\n";
  			echo $this->soapClient->__getLastResponse() . "\n";
		}
	}

?>