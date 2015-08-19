<?php 

 function pawps_publicEventList() {
 	// allgemeine URL-Requests behandeln
 	$mode = pawps_handlePublicRequests();
 	
 	if ($mode == 1) {
	 	$shootings = pawps_loadEnabledListElements();
	 	
	 	if (isset($shootings) && (count($shootings) > 0)) {
	 		// Shootings verfügbar
	 		$currentPos = 0;
	 		$displayCols = get_option(PAWPS_DISPLAY_COLS);

	 		// Template anzeigen
	 		pawps_showHeader(null, true, $error, $message);	 		
	 		require pawps_getTemplatePath('eventList');
	 	} else {
	 		// keine Shootings verfügbar
	 		$noEntriesMessage = "Derzeit sind keine öffentlichen Shootings verfügbar";
	 		
	 		// Template anzeigen
	 		require pawps_getTemplatePath ('noEntries');
	 	}
 	}
 } 
 
 function pawps_showPublicEvent($shootingId) {
 	$_GET['pawps_shooting'] = $shootingId;
 	
 	$shooting = pawps_loadShootingByData($shootingId);
 	
 	if (isset($shooting)) {
 		pawps_handlePublicRequests();
 	} else {
 		// Fehler
 		$noEntriesMessage = "Das gewünschte Shooting ist nicht verfügbar";
	 		
	 	// Template anzeigen
	 	require pawps_getTemplatePath ('noEntries');
 	}
 }
 
 function pawps_shootingByGalleriecode() {
 	if (!isset($_SESSION['PAWPS_LOGIN'])) {
 		$passwordCheckDone = false;
 		if (isset($_POST['PA_GALLERIECODE']) && (strlen($_POST['PA_GALLERIECODE']) > 0)) {
 			// Suche Veranstaltung anhand Galleriecode
 			$config = pawps_loadShootingConfig(null, $_POST['PA_GALLERIECODE']);
 			if (isset($config) && ($config->state > 0)) {
 				$shooting = pawps_loadShootingByData(null, $config->shootingcode);
 				if (isset($shooting)) {
 					// Shooting anzeigen
 					$passwordCheckDone = true;
 					$_SESSION['PAWPS_LOGIN'] = $config->id;
 				}
 			}
 
 			if (!$passwordCheckDone) {
 				$error = "Zu dem von Ihnen eingegebenen Code konnte keine Galerie gefunden reden";
 			}
 		}
 			
 		// noch kein Passwort in Session -> frage Passwort ab
 		if (!$passwordCheckDone) {
 			require pawps_getTemplatePath ('galleriecodeForm');
 		}
 	}
 
 	if (isset($_SESSION['PAWPS_LOGIN'])) {
 		// Passwort in Session gesetzt -> Lade Veranstaltung
 		$config = pawps_loadShootingConfig($_SESSION['PAWPS_LOGIN']);
 		if (isset($config)) {
 				
 			// Shooting setzen
 			$shooting = pawps_loadShootingByData(null, $config->shootingcode);
 			if (isset($shooting)) {
 				$_GET['pawps_shooting'] = $shooting->id;
 			}
 		}
 			
 		if (isset($shooting)) {
 			pawps_handlePublicRequests();
 			//pawps_showEvent(null, $shooting);
 		} else {
 			unset ($_SESSION['PAWPS_LOGIN']);
 			pawps_shootingByGalleriecode();
 		}
 	}
 }
 
 function pawps_shootingByCode() {
 	if (!isset($_SESSION['PAWPS_LOGIN'])) {
 		$passwordCheckDone = false;
 		if (isset($_POST['PA_GUESTPASSWORD']) && (strlen($_POST['PA_GUESTPASSWORD']) > 0)) {
 			// Suche Veranstaltung anhand Gästepasswort
 			$config = pawps_loadShootingConfig(null, null, $_POST['PA_GUESTPASSWORD']);
 			if (isset($config) && ($config->state > 0)) {
 				$shooting = pawps_loadShootingByData(null, $config->shootingcode);
 				if (isset($shooting)) {
 					// Shooting anzeigen
 					$passwordCheckDone = true;
 					$_SESSION['PAWPS_LOGIN'] = $config->id;
 				}
 			}
 			
 			if (!$passwordCheckDone) {
 				$error = "Das eingegebene Passwort ist ungültig";
 			}
 		}
 		
 		// noch kein Passwort in Session -> frage Passwort ab
 		if (!$passwordCheckDone) {
 			require pawps_getTemplatePath ('passwordForm');
 		}
 	}
 	
 	if (isset($_SESSION['PAWPS_LOGIN'])) {
 		// Passwort in Session gesetzt -> Lade Veranstaltung
 		$config = pawps_loadShootingConfig($_SESSION['PAWPS_LOGIN']);
 		if (isset($config)) {
 		
	 		// Shooting setzen
	 		$shooting = pawps_loadShootingByData(null, $config->shootingcode);
	 		if (isset($shooting)) {
	 			$_GET['pawps_shooting'] = $shooting->id;
	 		}
 		}
 		
 		if (isset($shooting)) {
 			pawps_handlePublicRequests();
 			//pawps_showEvent(null, $shooting);
 		} else {
 			unset ($_SESSION['PAWPS_LOGIN']);
 			pawps_shootingByCode();
 		}
 	}
 }
 
 function pawps_showImage($imageId) {
 	$image = pawps_loadImageById($_GET['pDetails']);
 	
 	if (!isset($image)) {
 		// Image nicht gefunden -> und tschü½ss
 		return;
 	}
 	
 	// Image geladen -> anzeigen
 	$shooting = pawps_loadShootingByData($image->veranstaltungsid);
 	
 	if (!isset($shooting)) {
 		// Shooting nicht gefunden -> bye
 		return;
 	}
 	
 	$config = $shooting->getShootingConfig();
 	
 	if (!isset($config) || ($config->state == 0)) {
 		// Config ungültig -> raus hier
 		return;
 	}
 	
 	if (isset($_POST['addToBasket']) && is_numeric($_POST['product']) && 
 		is_numeric($_POST['anzahl']) && is_numeric($_POST['imageId'])) {
 		// Füge Artikel zu Warenkorb hinzu
 		$warenkorb = new pawps_warenkorb($_SESSION['PAWPS_WARENKORB']);
 		
 		// Prüfe ob neue Position von gleicher Preisliste wie alte
 		if (count($warenkorb->positionen) > 0) {
 			$erstePosition = reset($warenkorb->positionen);
 			$altePreisliste = $erstePosition->getPricelist();
 			$neuePreisliste = pawps_loadPricelist($_POST['preislistId']);
 			if ($altePreisliste->laborId != $neuePreisliste->laborId) {
 				$error = "Leider ist es nicht möglich Bestellungen dieses Shootings mit denen im aktuellen Warenkorb zu mischen.";
 			}
 		}
 		
 		if (!isset($error) || (strlen($error) == 0)) {
 			$warenkorb->addPosition($_POST['imageId'], $_POST['product'], $_POST['anzahl'], $_POST['preislistId']);
 			$message = "Der Artikel wurde in den Warenkorb gelegt";
 		}
 	}
 	
 	// Prüfe ob Ordner-ID übergeben wurde -> falls ja setzen
 	if (isset($_POST['pawps_ordner'])) {
 		$shooting->aktuellerOrdner = pawps_loadOrdnerById($_POST['pawps_ordner']);
 	} else if (isset($_GET['pawps_ordner'])) {
 		$shooting->aktuellerOrdner = pawps_loadOrdnerById($_GET['pawps_ordner']);
 	}
 	
 	// Shooting - Config und Image da - Anzeigen
 	pawps_showHeader($shooting->title, true, $error, $message);
	require pawps_getTemplatePath('pictureDetails');
 }
 
 function pawps_showEvent($eventCode = null, $shooting = null) {
	if (isset($eventCode)) {
		$shooting = pawps_loadShootingByData(null, $eventCode);
	}
	
	if (!isset($shooting)) {
		// Shooting nicht gefunden / übergeben
		return;
	}
	
	$config = $shooting->getShootingConfig();
	
	if (!isset($config)) {
		// Config nicht gefunden
		return;
	}
	
	if ($config->state == 0) {
		// Shooting inaktiv -> so ned
		return;
	}

	$passwordCheckDone = false;
	
	if (isset($_SESSION['PAWPS_LOGIN']) && ($_SESSION['PAWPS_LOGIN'] == $config->id)) {
		$passwordCheckDone = true;
	} else {
		unset($_SESSION['PAWPS_LOGIN']);
		
		if (isset($_POST['PA_GUESTPASSWORD'])) {
			if ($_POST['PA_GUESTPASSWORD'] == $config->guestpassword) {
				$_SESSION['PAWPS_LOGIN'] = $config->id;
				$passwordCheckDone = true;
			} else {
				$error = "Das eingegebene Passwort ist ungültig";
			}
		}
	}
	
	// bei Config-Typ 2 -> Keine Passwortprüfung
	if (($config->state == 2) || ($config->state == 4)) {
		$passwordCheckDone = true;
	} else if (!$passwordCheckDone && 
		(($config->state == 1) || ($config->state == 3))) {
		// mit Passwort -> check anzeigen
		pawps_showPasswordRequestForm($shooting);
	}
	
	if ($passwordCheckDone) {
		// Shooting geladen -> anzeigen
		$displayRows = get_option(PAWPS_DISPLAY_ROWS);
		$displayCols = get_option(PAWPS_DISPLAY_COLS);
		
		$currentStartIndex = 0;
		if (isset($_GET['pStart']) && is_numeric($_GET['pStart'])) {
			$currentStartIndex = $_GET['pStart'];
		}
		$imagesPerPage = $displayCols * $displayRows;
		$requestedEndIndex = $currentStartIndex + $imagesPerPage;
		
		// Prüfe ob Ordner-ID übergeben wurde -> falls ja setzen
		if (isset($_POST['pawps_ordner'])) {
			$shooting->aktuellerOrdner = pawps_loadOrdnerById($_POST['pawps_ordner']);
		} else if (isset($_GET['pawps_ordner'])) {
			$shooting->aktuellerOrdner = pawps_loadOrdnerById($_GET['pawps_ordner']);
		}
		
		$images = $shooting->getImagePage($currentStartIndex, $requestedEndIndex);
		
		// Template anzeigen
		pawps_showHeader($shooting->title, TRUE, $error, $message);
		
		require pawps_getTemplatePath ('event');
	} else {
		$noEntriesMessage = "Das gewünschte Element kann leider nicht angezeigt werden";
		require pawps_getTemplatePath ('noEntries');
	}
 }
 
 function pawps_showPasswordRequestForm($shooting) {
	if (!isset($shooting)) {
		// Keine Daten übergeben -> und raus
		return;
	}
	
	// Template anzeigen
	require pawps_getTemplatePath ('passwordForm');
 }
 
 function pawps_showWarenkorb() {
	$warenkorb = new pawps_warenkorb($_SESSION['PAWPS_WARENKORB']);
	$displayType = 1;
	
	if ($warenkorb->getAnzahl() > 0) {
		// Warenkorbbehandlung macht nur Sinn, wenn Artikel vorhanden sind ...
		if (isset($_POST['doUpdateWarenkorb']) && isset($warenkorb)) {
			$neueAnzahl = $_POST['warenkorbAnzahl'];
			foreach ($warenkorb->positionen as $position) {
				if (isset($neueAnzahl[$position->id])) {
					$posAnzahl = $neueAnzahl[$position->id];
					foreach ($position->images as $img) {
						if (isset($posAnzahl[$img->imageId]) && is_numeric($posAnzahl[$img->imageId])) {
							// Neue Anzahl gesetzt -> aktualisiere in Objekct
							$img->setCount($posAnzahl[$img->imageId], true, $position->id);
						}
					}
				}
				
				// Preise neu berechnen
				$position->calculatePrices(true);
			}
		} else if (isset($_POST['doOrderWarenkorb'])) {
			// Bestellung aufgeben -> Adressdaten hinterlegen
			$displayType = 2;
			$versandlaender = $warenkorb->getMoeglicheVersandlaender();
			
		} else if (isset($_POST['doAddRechnungsadresse'])) {
			// Form validieren
			$error = pawps_validateField($_POST['lastname'], "Name");
			$error .= pawps_validateField($_POST['firstname'], "Vorname");
			$error .= pawps_validateField($_POST['street'], "Strasse");
			$error .= pawps_validateField($_POST['number'], "Hausnummer");
			$error .= pawps_validateField($_POST['plz'], "Plz");
			$error .= pawps_validateField($_POST['ort'], "Ort");
			$error .= pawps_validateField($_POST['email'], "E-Mail");
			
			if (strlen($error) == 0) {
				// passt -> Daten persistieren
				global $wpdb;
				$wpdb->insert(
						PAWPS_TABLENAME_CUSTOMERS,
						array(
								'email' => $_POST['email'],
								'name' => $_POST['lastname'],
								'firstname' => $_POST['firstname'],
								'street' => $_POST['street'],
								'number' => $_POST['number'],
								'plz' => $_POST['plz'],
								'city' => $_POST['ort'],
								'country' => pawps_getLandbezeichnung($_POST['land'])
						));
				
				$customerId = $wpdb->insert_id;
				
				// Bestellung im Warenkorb abschliessen
				$wpdb->update(
						PAWPS_TABLENAME_WARENKORB,
						array(
								'dateUpdate' => current_time('mysql'),
								'customerId' => $customerId,
								'orderAnzahlPositionen' => $warenkorb->getAnzahl(),
								'orderGesamtpreis' => $warenkorb->getGesamtpreis(false)
						),
						array(
								'id' => $warenkorb->id
						));
				$warenkorb->customerId = $customerId;
			}
			
			if ((strlen($error) == 0) && ($_POST['lieferadresseAbweichend'] == 1)) {
				$versandlaender = $warenkorb->getMoeglicheVersandlaender();
				$displayType = 3;
			} else if (strlen($error) == 0) {
				$displayType = 4;
			} else {
				$versandlaender = $warenkorb->getMoeglicheVersandlaender();
				$displayType = 2;
				
				$name = $_POST['lastname'];
				$firstname = $_POST['firstname'];
				$street = $_POST['street'];
				$number = $_POST['number'];
				$plz = $_POST['plz'];
				$city = $_POST['ort'];
				$landId = $_POST['land'];
				$email = $_POST['email'];
			}
		} else if (isset($_POST['doAddLieferadresse'])) {
			$error = pawps_validateField($_POST['lastname'], "Name");
			$error .= pawps_validateField($_POST['firstname'], "Vorname");
			$error .= pawps_validateField($_POST['street'], "Strasse");
			$error .= pawps_validateField($_POST['number'], "Hausnummer");
			$error .= pawps_validateField($_POST['plz'], "Plz");
			$error .= pawps_validateField($_POST['ort'], "Ort");
			
			if (strlen($error) == 0) {
				// Kunden aktualisieren
				global $wpdb;
				$wpdb->update(
						PAWPS_TABLENAME_CUSTOMERS,
						array(
								'ver_name' => $_POST['lastname'],
								'ver_firstname' => $_POST['firstname'],
								'ver_street' => $_POST['street'],
								'ver_number' => $_POST['number'],
								'ver_plz' => $_POST['plz'],
								'ver_city' => $_POST['ort'],
								'ver_country' => pawps_getLandbezeichnung($_POST['land'])
						),
						array(
								'id' => $warenkorb->customerId
						));
			}
			
			if (strlen($error) == 0) {
				$displayType = 4;
			} else {
				$name = $_POST['lastname'];
				$firstname = $_POST['firstname'];
				$street = $_POST['street'];
				$number = $_POST['number'];
				$plz = $_POST['plz'];
				$city = $_POST['ort'];
				$landId = $_POST['land'];
				
				$versandlaender = $warenkorb->getMoeglicheVersandlaender();
				$displayType = 3;
			}
		} else if (isset($_POST['doBestellungAbsenden'])) {
			// prüfe ob AGB und Datenschutz akzeptiert
			$error = "";
			if (!(isset($_POST['acceptAgb']) && ($_POST['acceptAgb'] == TRUE))) {
				$error .= "Bitte akzeptieren Sie die 'Allgemeine Geschäfts- und Lieferbedingungen'<br/>";
			}
			if (!(isset($_POST['acceptDatenschutz']) && ($_POST['acceptDatenschutz'] == TRUE))) {
				$error .= "Bitte akzeptieren Sie die 'Datenschutzbestimmungen'<br/>";
			}

			if (strlen($error) == 0) {
				// Bestellung durchfuehren
				$zahlungsweise = $_POST['zahlungsweise'];
				if (!isset($zahlungsweise) || !is_numeric($zahlungsweise)) {
					$zahlungsweise = 1;
				}
				
				$result = pawps_createOnlineOrder($warenkorb, $_POST['zahlungsweise']);
				
				if ($result->{'success'}) {
					$message = "Ihre Bestellung wurde erfolgreich aufgegeben, weitere Details sind bereits per Mail auf dem Weg zu Ihnen.";
					
					$bestellnummer = $result->{'bestellnummer'};
					
					// Eintrag mit Bestellnummer
					global $wpdb;
					$wpdb->update(
							PAWPS_TABLENAME_WARENKORB,
							array(
									'dateOrdered' => current_time('mysql'),
									'orderId' => $bestellnummer
							),
							array(
									'id' => $warenkorb->id
							));
					
					// redirect zu PayPal sofern notwendig
					if ($result->{'externeZahlungRedirect'}) {
						wp_redirect($result->{'externeZahlungRedirectUrl'});
					}
				} else {
					if (pawps_createOrderByMail($warenkorb, $_POST['zahlungsweise'])) {
						$message = "Ihre Bestellung wurde übertragen, Sie erhalten nach Prüfung der selbigen eine Bestellbestätigung per Mail.";
						
						// Eintrag ohne Bestellnummer
						global $wpdb;
						$wpdb->update(
								PAWPS_TABLENAME_WARENKORB,
								array(
										'dateOrdered' => current_time('mysql')
								),
								array(
										'id' => $warenkorb->id
								));
						
					} else {
						$error = "Beim Aufgeben der Bestellung ist ein Fehler aufgetreten.";
					}
				}
			}
			
			// entsprechende Page anzeigen
			if (strlen($error) == 0) {
				$displayType = 5;
			} else {
				$displayType = 4;
			}
		} else if (isset($_GET['removePosition']) && isset($_GET['removePositionImage'])) {
			$position = $warenkorb->positionen[$_GET['removePosition']];			
			if (isset($position)) {
				$image = $position->images[$_GET['removePositionImage']];
				if (isset($image)) {
					$image->setCount(0, true, $position->id);
				}
				
				// Preise neu berechnen
				$position->calculatePrices(true);
			}
		}
	}
	
	// Preise neu berechnen
	$warenkorb->recalculateWarenkorb();
	
	// entscheiden was anzuzeigen ist
	switch ($displayType) {
		case 5:
			pawps_showHeader("Bestellung durchgeführt", FALSE, $error, $message);
			
			// Warenkorb aus Session entfernen -> neuen leeren Warenkorb anlegen
			unset($_SESSION['PAWPS_WARENKORB']);
			break;
		case 4:
			pawps_showHeader("Bestellung prüfen", FALSE, $error, $message);
			$agbText = file_get_contents(plugin_dir_path( __FILE__ ) .'/resources/agb.tpl');
			$datenschutzText = file_get_contents(plugin_dir_path( __FILE__ ) .'/resources/datenschutz.tpl');
			require pawps_getTemplatePath ('warenkorbZusammenfassung');
			break;
		case 3: 
			pawps_showHeader("Versandadresse festlegen", FALSE, $error, $message);
			require pawps_getTemplatePath ('addressForm');
			break;
		case 2: 
			pawps_showHeader("Rechnungsadresse festlegen", FALSE, $error, $message);
			$rechnungsadresse = true;
			require pawps_getTemplatePath ('addressForm');
			break;
		default:
			pawps_showHeader("Warenkorb anzeigen", FALSE, $error, $message);
			require pawps_getTemplatePath ('warenkorbList');
			break;
	}
 }
 
 function pawps_handlePublicRequests() {
 	if (!pawps_isConnectionWorking()) {
 		// Verbindung nicht existent -> Fehlerseite anzeigen
 		$noEntriesMessage = "Die gewünschte Funktion steht derzeit nicht zur Verfügung";
 		
 		// Template anzeigen
 		require pawps_getTemplatePath ('noEntries');
 		
 		return 0;
 	} else {
		// Prüfe ob Warenkorb in Session
		if (!isset($_SESSION['PAWPS_WARENKORB'])) {
			$warenkorb = new pawps_warenkorb(); 
			$_SESSION['PAWPS_WARENKORB'] = $warenkorb->id;
		}
	
		// Request behandeln
		if (isset($_GET['pawps_showWarenkorb']) && ($_GET['pawps_showWarenkorb'] == 1)) {
			pawps_showWarenkorb();
			return 4;
		} else if (isset($_GET['pDetails']) && is_numeric($_GET['pDetails'])) {
			pawps_showImage($_GET['pDetails']);
			return 3;
		} else if (isset($_GET['pawps_shooting']) && is_numeric($_GET['pawps_shooting'])) {
			// Shooting laden
			$shooting = pawps_loadShootingByData($_GET['pawps_shooting']);
			pawps_showEvent($shooting->accesscode);
			return 2;
		} else {
			return 1;
		}
 	}
 }
 
 function pawps_showHeader($pageTitle = null, $withWarenkorbLink = TRUE, $error = null, $message = null) {
	if ($withWarenkorbLink && isset($_SESSION['PAWPS_WARENKORB'])) {
		$warenkorb = new pawps_warenkorb($_SESSION['PAWPS_WARENKORB']);
	}
	
	require pawps_getTemplatePath ('siteHeader');
 }

?>