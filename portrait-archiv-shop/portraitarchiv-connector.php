<?php
function pawps_isConnectionWorking() {
	if (! pawps_syscheck ()) {
		return false;
	}
	
	$result = pawps_readRemoteUrl ( "ws/public/galerieService/lastGalerieUpdateTime" );
	
	return $result->{'success'} == true;
}
function pawps_getModuleToken() {
	return pawps_readRemoteUrl ( "ws/public/userService/getModulToken" )->{'token'};
}
function pawps_doAnmeldung($anmeldedaten) {
	$url = PAWPS_BASE_URL . "ws/public/userService/createFotograf?customerData=";
	$url .= json_encode ( $anmeldedaten );
	
	// $result = file_get_contents($url);
	echo $url;
	$result = pawps_justReadRemote ( $url );
	
	return $result;
}
function pawps_createReadRemoteUrl($scriptName, $paramString = null) {
	// Parameter auslesen
	$paHash = get_option ( PAWPS_OPTION_HASHKEY );
	$paHashRemote = get_option ( PAWPS_OPTION_HASHKEY_REMOTE );
	$paUserId = get_option ( PAWPS_OPTION_USERID );
	if (! isset ( $paHash ) || ! isset ( $paUserId ) || ! isset ( $paHashRemote )) {
		return PAWPS_DEFAULT_ERROR;
	}
	
	// Build URL
	$systemUrl = urlencode(site_url());
	
	$url = pawps_getUrl(true);
	$url .= $scriptName . "?";
	$url .= "loginData=";
 	$url .= json_encode(array('id' => trim($paUserId), 'hash' => trim($paHash), 'remoteHash' => trim($paHashRemote),  'systemUrl' => trim($systemUrl)));
	
	if (isset ( $paramString )) {
		$url .= "&" . $paramString;
	}
	
	return $url;
}
function pawps_readRemoteUrl($scriptName, $paramString = null ,$debug = false) {
	$url = pawps_createReadRemoteUrl ( $scriptName, $paramString );
	
	if ($debug) {
		echo $url;
	}
	
	$result = pawps_justReadRemote ( $url );
	
	return json_decode ( $result );
}
function pawps_justReadRemote($url) {
	pawps_doDebug ( "URL: " . $url );
	
	if (pawps_syscheck_urlFopen ()) {
		pawps_doDebug ( "Lese über fopen" );
		
		$arrContextOptions=array(
				"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
				),
		);
		
		$content = file_get_contents ( $url, false, stream_context_create($arrContextOptions) );
	} else if (pawps_syscheck_curl ()) {
		pawps_doDebug ( "Lese über curl" );
		$curl = curl_init ( $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		$content = curl_exec ( $curl );
		curl_close ( $curl );
	} else {
		pawps_doDebug ( "Removerbindung kann nicht hergestellt werden" );
		throw new Exception ( 'Remoteverbindung kann nicht hergestellt werden' );
	}
	
	pawps_doDebug ( "Result: " . $content );
	
	return $content;
}
function pawps_getRemoteLastUpdate() {
	$result = pawps_readRemoteUrl ( "ws/public/galerieService/lastGalerieUpdateTime" );
	
	if (! $result->{'success'}) {
		return "FEHLER";
	}
	
	return $result->lastUpdate;
}
function pawps_getRemoteShootingResult() {
	$result = pawps_readRemoteUrl ( "ws/public/galerieService/retrieveGalerien" );
	
	return $result;
}
function pawps_getRemotePricelistResult($pricelistId) {
	$result = pawps_readRemoteUrl ( "ws/public/galerieService/retrievePricelist", "pricelistId=" . $pricelistId );
	
	return $result;
}
function pawps_getRemoteImageList($shootingCode) {
	$codeElements = split ( "-", $shootingCode );
	$result = pawps_readRemoteUrl ( "ws/public/galerieService/retrieveImages", "veranstaltungId=" . $codeElements [1] );
	
	if ($result == "Veranstaltung nicht gefunden") {
		return PAWPS_DEFAULT_ERROR;
	}
	
	return $result;
}

function pawps_getRemoteWarenkorbVersandkosten($warenkorb) {
	$transferWarenkorb = new pawps_warenkorbTransfer($warenkorb);
	
	$chosenCountry = get_option(PAWPS_SYSTEMCOUNTRY);
	if ($chosenCountry == "CH") {
		$versandland = "Schweiz";
	} else {
		$versandland = "Deutschland";
	}
	$result = pawps_readRemoteUrl ( "ws/public/orderService/calculateShippingCosts", "warenkorb=" . json_encode ( $transferWarenkorb ) . "&shippingCountry=" . $versandland, false );
	
	if (! $result->{'success'}) {
		return -1;
	} else {
		return $result->{'versandkosten'};
	}
}
function pawps_getOnlineOrderRequest($warenkorb, $zahlungsweise = 1) {
	$transferWarenkorb = new pawps_warenkorbTransfer ($warenkorb);
	
	$customer = $warenkorb->getCustomer();
	$customerOrder = new pawps_customerSimple();
	$customerOrder->email = urlencode($customer->email);
	$customerOrder->name = urlencode($customer->name);
	$customerOrder->vorname = urlencode($customer->firstname);

	$shippingAddress = new pawps_address();
	$shippingAddress->coName = urlencode($customer->ver_name+ ", " + $customer->ver_firstname);
	$shippingAddress->strasse = urlencode($customer->ver_street);
	$shippingAddress->hausnummer = urlencode($customer->ver_number);
	$shippingAddress->plz = urlencode($customer->ver_plz);
	$shippingAddress->ort = urlencode($customer->ver_city);
	$shippingAddress->land = urlencode($customer->ver_country);
	
	$billAddress = new pawps_address();
	$billAddress->coName = urlencode($customer->name + ", " + $customer->firstname);
	$billAddress->strasse = urlencode($customer->street);
	$billAddress->hausnummer = urlencode($customer->number);
	$billAddress->plz = urlencode($customer->plz);
	$billAddress->ort = urlencode($customer->city);
	$billAddress->land = urlencode($customer->country);

	return pawps_createReadRemoteUrl ( "ws/public/orderService/createOrder", // 
			"warenkorb=" . json_encode ( $transferWarenkorb ) . //
			"&kunde=" . json_encode( $customerOrder) . //
			"&shipping=" . json_encode( $shippingAddress) . //
			"&bill=" . json_encode( $billAddress). //
			"&zahlungsweise=" . $zahlungsweise);
}

function pawps_createOrderByMail($warenkorb, $zahlungsweise) {
	// Fallback für den Fall dass die Übertragung an den Webservice nicht funktionierte
	$empfaenger = "technik@portrait-service.com";
	$topic = "WP-Modul - manuelle Bestelluebertragung";
	
	$message = "Eine Bestellung konnte nicht übermittelt werden - folgende Bestelldetails:\n\n";
	
	$customer = $warenkorb->getCustomer();
	if (! isset ( $customer )) {
		// HILFE - Keine Kundendaten
		$message .= "Fehlende Kundendaten\n\n";
	} else {
		$eigeneVersandadresse = isset ( $customer->ver_name ) && (strlen ( $customer->ver_name ) > 0);
		
		if ($eigeneVersandadresse) {
			$message = "Rechnungsadresse:\n";
		} else {
			$message = "Rechnungs- und Lieferadresse:\n";
		}
		
		$message .= $customer->name . ", " . $customer->firstname . "\n";
		$message .= $customer->street . ", " . $customer->number . "\n";
		$message .= $customer->plz . ", " . $customer->city . "\n";
		$message .= $customer->country . "\n";
		$message .= "E-Mail: " . $customer->email . "\n\n";
		
		if ($eigeneVersandadresse) {
			$message = "Lieferadresse:\n";
			$message .= $customer->ver_name . ", " . $customer->ver_firstname . "\n";
			$message .= $customer->ver_street . ", " . $customer->ver_number . "\n";
			$message .= $customer->ver_plz . ", " . $customer->ver_city . "\n";
			$message .= $customer->ver_country . "\n\n";
		}
	}
	
	$message .= "Bestelldetails:\n";
	$message .= "Grafik-ID;Anzahl;Produkt-ID;Produkt-Titel;Preis;Veranstaltung;Preisliste\n";
	foreach ( $warenkorb->positionen as $position ) {
		foreach ( $position->images as $image ) {
			$message .= $image->imageId . ";" . $image->count . ";" . $position->productId . ";";
			// Produkt einbauen
			$product = $position->getProduct ();
			if (isset ( $product )) {
				$message .= $product->title;
			} else {
				$message .= "Produktname nicht ermittelbar";
			}
			
			$message .= ";" . $image->price . ";";
			
			// Grafik einbauen
			
			$grafik = $image->getImage ();
			if (isset ( $grafik )) {
				$veranstaltung = pawps_loadShootingByData ( $grafik->veranstaltungsid );
				
				if (isset ( $veranstaltung )) {
					$message .= $veranstaltung->title . "(" . $veranstaltung->accesscode . ")";
				} else {
					$message .= "Veranstaltung nicht ermittelbar";
				}
			} else {
				$message .= "Veranstaltung nicht ermittelbar";
			}
			$message .= $position->pricelistId . "\n";
		}
	}
	
	$message .= "\n---- Ende der Bestellung - Ausgabe für Fehlersuche:\n";
	$message .= pawps_getOnlineOrderRequest ( $warenkorb, $zahlungsweise );
	
	// Mail versenden
	return wp_mail ( $empfaenger, $topic, $message );
}
function pawps_createOnlineOrder($warenkorb, $zahlungsweise) {
	$result = json_decode(pawps_justReadRemote (pawps_getOnlineOrderRequest ( $warenkorb, $zahlungsweise )));
	
	
	return $result;
}

function pawps_getUrl($base = true) {
	$chosenCountry = get_option(PAWPS_SYSTEMCOUNTRY);
	if ($chosenCountry == "DE") {
		if ($base) {
			return 'https://www.Portrait-Archiv.com/';
		} else {
			return "https://images.portrait-archiv.com/";
		}
	} else if ($chosenCountry == "CH") {
		if ($base) {
			return 'http://www.Portrait-Archiv.ch/';
		} else {
			return "http://images.portrait-archiv.ch/";
		}
		return 'http://www.Portrait-Archiv.ch/';
	}	
}

?>