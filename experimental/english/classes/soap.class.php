<?php
/*
Created by Tim Reinartz as part of the Bachelor Thesis
last update 25.05.11 12:25 Uhr
The object of the file:
Represents a SOAP API for this task
*/

// based on 
// http://www.pegelonline.wsv.de/webservice/guideAkt
// Author: Christian Seewald, EES GmbH, mail: c.seewald@ees-gmbh.de
// a few changes were necessary to include compatibility with PHP 5.1

	class SOAPClientApi {
	
		var $soapClient;
		var $localTimezone;
		
		/*
		 * Constructor
		 * 
		 * $trace: If TRUE, the Soap request and response are displayed as XML
		 * $exceptions: If TRUE an exception of type SoapFault is thrown (TRUE recommended)
		 */
		function SOAPClientApi($trace = TRUE, $exceptions = TRUE) {
			$this->soapClient = new SoapClient(
				'http://www.pegelonline.wsv.de/webservices/version2_3/2007/10/31/PegelonlineWebservice?WSDL', 
				array ( 'trace'   => $trace, 'exceptions'=> $exceptions));
		}
		
		/*
		 * Individual queries of "Pegelonline-Webservice".
		 * 
		 * A language agnostic documentation is available at
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
		 * Modifies the response and thereby compensated the unfavorable 'auto-magic'
		 * Creation of proxy objects for certain responses by Soap Php Soap Extension
		 * (at 0 and 1 member (s) within an array in Soap Response)
		 * 
		 * the response can always be used in a foreach loop
		 * 
		 * should be called by all queries, that provide arrays
		 */
		function &modifyArrayResponse(&$response) {
			//Soap Extension provides zero with an empty result set
			if ($response == NULL) {
				return array();
			
			//Soap extension provides an array with an element,
			//if only one element is retruned by the web service
			} elseif (!is_array($response)) {
				return array($response);

			//else ok
			} else {
				return $response;
			}
		}
		
		/*
		 * Viewing the XML Soap request. Only works, if
		 * the trace option is enabled in the constructor.
		 */
		function echoLastSoapRequest() {
			echo "Last SOAP Request:\n";
	  		echo $this->soapClient->__getLastRequest() . "\n";
		}
		
		/*
		 * Viewing the XML Soap request. Only works, if
		 * the trace option is enabled in the constructor.
		 */
		function echoLastSoapResponse() {
  			echo "Last SOAP Response:\n";
  			echo $this->soapClient->__getLastResponse() . "\n";
		}
	}

?>