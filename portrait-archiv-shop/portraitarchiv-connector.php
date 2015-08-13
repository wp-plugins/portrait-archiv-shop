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
	$url = PAWPS_BASE_URL . $scriptName . "?";
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
		$content = file_get_contents ( $url );
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
	$transferWarenkorb = new pawps_warenkorbTransfer($warenkorb, false);
	
	// TODO Versandland ist noch fest auf Deutschland hinterlegt
	$result = pawps_readRemoteUrl ( "ws/public/orderService/calculateShippingCosts", "warenkorb=" . json_encode ( $transferWarenkorb ) . "&shippingCountry=Deutschland", false );
	
	if (! $result->{'success'}) {
		return -1;
	} else {
		return $result->{'versandkosten'};
	}
}
function pawps_getOnlineOrderRequest($warenkorb) {
	$transferWarenkorb = new pawps_warenkorbTransfer ( $warenkorb, true );
	
	return pawps_createReadRemoteUrl ( "createRemoteOrder", "warenkorb=" . json_encode ( $transferWarenkorb ) );
}
function pawps_createOrderByMail($warenkorb) {
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
	$message .= pawps_getOnlineOrderRequest ( $warenkorb );
	
	// Mail versenden
	return wp_mail ( $empfaenger, $topic, $message );
}
function pawps_createOnlineOrder($warenkorb) {
	return pawps_justReadRemote ( pawps_getOnlineOrderRequest ( $warenkorb ) );
}

?>