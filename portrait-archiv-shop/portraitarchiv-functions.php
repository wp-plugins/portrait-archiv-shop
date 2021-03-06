<?php
 
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-frontfunctions.php';

 function pawps_refreshShootings() {
 	global $wpdb;
 	
 	// hole alle Remote-Shootings
 	$remoteResult = pawps_getRemoteShootingResult();
 	
 	if (count($remoteResult) > 0 ) {
 		// prüfe ob Update notwendig
 		$lastLocaleUpdate = get_option(PAWPS_LAST_UPDATE_SHOOTINGS);
 		$lastRemoteUpdate = pawps_getRemoteLastUpdate();
 		
  		$doUpdate = (((strlen($lastLocaleUpdate) > 5) && ($lastLocaleUpdate < $lastRemoteUpdate)) || (strlen($lastLocaleUpdate) < 5));
  		
  		if ($doUpdate) {
		 	// Shootings gefunden -> aktualisiere lokale Datenbank
		 	foreach ($remoteResult as $jsonResult) {
		 		if ($jsonResult->lastUpdate > $lastLocaleUpdate) {
		 			// letztes Update nach lokalem Update -> änderung übernehmen
					if (($jsonResult->state == 1) && (strlen($jsonResult->title) > 0)) {
	 					// Shooting noch aktiv -> Änderungen übernehmen
	 					$existierendesShooting = pawps_loadShootingByData(null, $jsonResult->zugriffscode);
		 					
	 					// Datumsobjekt aufbauen	
	  					$datumString = date('Y-m-d H:M:s', $jsonResult->lastUpdate);
		 					
		 				// Shootingdate
						$shootingDateElements = explode(".", $jsonResult->date);
		 				$shootingDateDbFormat = $shootingDateElements[2] . "-" . $shootingDateElements[1] . "-" . $shootingDateElements[0];
		 					
		 				// Prüfe Update
		 				if (isset($existierendesShooting)) {
		 					// Shooting existiert -> Aktualisieren
		 					$wpdb->update(
		 							PAWPS_TABLENAME_SHOOTINGS,
		 							array(
		 									'title' => urldecode($jsonResult->title),
		 									'imageCount' => $jsonResult->imageCount,
		 									'shootingdate' => $shootingDateDbFormat,
		 									'pricelist_id' => $jsonResult->preisliste,
		 									'accesscode' => $jsonResult->zugriffscode,
		 									'lastupdate' => $datumString
										),
	 								array(
		 									'id' => $existierendesShooting->id
		 							));
		 					
		 					$shootingId = $existierendesShooting->id;
		 				} else {		 						
		 					// Shooting existiert noch nicht -> Eintrag in DB vornehmen
		 					$wpdb->insert(
		 							PAWPS_TABLENAME_SHOOTINGS, 
		 							array(
		 									'title' => urldecode($jsonResult->title),
		 									'imageCount' => $jsonResult->imageCount,
		 									'shootingdate' => $shootingDateDbFormat,
		 									'pricelist_id' => $jsonResult->preisliste,
											'accesscode' => $jsonResult->zugriffscode,
	 										'lastupdate' => $datumString
		 								));
		 						
		 					$shootingId = $wpdb->insert_id; 
		 				}
		 					
		 				// Images löschen
		 				$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_IMAGES . " WHERE veranstaltungsid=" . $shootingId);
		 				
		 				// Lade Shooting erneut
		 				$existierendesShooting = pawps_loadShootingByData($shootingId);
		 				pawps_refreshPricelist($existierendesShooting->pricelist_id, $lastRemoteUpdate);
		 			} else {
		 				// Shooting gelöscht -> entferne aus lokaler Datenbank
						$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_SHOOTINGS . " WHERE CODE='" . $jsonResult->zugriffscode . "'");
	 				}
	 			}
	 		}
		} else {
			// alle alten Shootings entfernen
	 		$wpdb->query('DELETE FROM  ' . PAWPS_TABLENAME_SHOOTINGS);
	 	}
  	
  		update_option(PAWPS_LAST_UPDATE_SHOOTINGS, time());
 	}
 }
 
 function pawps_loadPricelist ($id) {
 	global $wpdb;
 	
 	// Select aufbauen
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_PRICELISTS . " WHERE ";
 	$sql .= "id=" . $id;
 	
 	// Datensatz selektieren
 	$result = $wpdb->get_row($sql);
 	if (isset($result)) {
 		return new pawps_pricelist($result);
 	}
 	
 	return null;
 }
 
 function pawps_refreshPricelist($id, $lastRemoteUpdate = null) {
 	global $wpdb;
 
 	$pricelist = pawps_loadPricelist($id);
 	
 	if (!isset($lastRemoteUpdate)) {
 		$lastRemoteUpdate = pawps_getRemoteLastUpdate();
 	}
 	
 	// Prüfen ob Update notwendig
 	if (!isset($pricelist) || 
 		(((strlen($pricelist->lastupdate) > 5) && ($pricelist->lastupdate < $lastRemoteUpdate)) || (strlen($pricelist->lastupdate) < 5))) {
 		$remotePricelistJsonResult = pawps_getRemotePricelistResult($id);
 		
 		// Json interpretieren
 		$lastLocaleUpdate = get_option(PAWPS_LAST_UPDATE_SHOOTINGS);
 		if (!isset($pricelist) || 
 			(isset($remotePricelistJsonResult->lastUpdate) && ($remotePricelistJsonResult->lastUpdate > $lastLocaleUpdate))) {
 			// Datumsobjekt aufbauen
 			$pricelistDatumString = date('Y-m-d H:M:s', $remotePricelistJsonResult->lastUpdate);
 			
 			// Pricelist hast sich geändert oder existiert nicht -> aktualisieren
 			if (isset($pricelist)) {
 				// aktualisieren -> alte Preisliste löschen
 				pawps_deletePricelistById($pricelist->id);
 			} 
 			
 			// neu anlegen
 			// Shooting existiert noch nicht -> Eintrag in DB vornehmen
 			$wpdb->insert(
 					PAWPS_TABLENAME_PRICELISTS,
 					array(
 							'id' => $remotePricelistJsonResult->id,
 							'title' => urldecode($remotePricelistJsonResult->title),
 							'lastupdate' => $pricelistDatumString,
 							'laborId' => $remotePricelistJsonResult->laborId
 					));
 				
 			$pricelistId = $wpdb->insert_id;
 			
 			// Produkte der Preisliste anlegen 				
 			foreach ($remotePricelistJsonResult->artikel as $artikel) {
 				if (isset($artikel->title)) {
 					// Prüfe ob Produkt schon existiert - wenn ja aktualisieren, wenn nicht eintragen
 					// Select aufbauen
 					$sql = "SELECT * FROM " . PAWPS_TABLENAME_PRODUCTS . " WHERE ";
 					$sql .= "id=" . $artikel->id;
 					$result = $wpdb->get_row($sql);
 					if (isset($result)) {
 						// Existiert -> aktualisieren
 						$wpdb->update(
 								PAWPS_TABLENAME_PRODUCTS,
 								array(
 										'title' => urldecode($artikel->title)
 								),
 								array(
 										'id' => $artikel->id
 								));
 						$artikelId = $artikel->id;
 					} else {
		 				$wpdb->insert(
		 						PAWPS_TABLENAME_PRODUCTS,
		 						array(
		 								'id' => $artikel->id,
		 								'title' => urldecode($artikel->title)
		 						));
		 					
		 				$artikelId = $wpdb->insert_id;
 					}

		 			// Preise
	 				foreach ($artikel->preise as $preis) {
	 					$wpdb->insert(
	 							PAWPS_TABLENAME_PRICES,
	 							array(
	 									'pricelist_id' => $pricelistId,
	 									'product_id' => $artikelId,
	 									'gueltigAb' => $preis->gueltigAb,
	 									'price' => $preis->preis
	 							));
	 				}
	 				
	 				// Artikel zur Preisliste zuordnen
	 				$wpdb->insert(
 							PAWPS_TABLENAME_PRICELIST_PRODUCTS,
 							array(
 									'pricelist_id' => $pricelistId,
 									'product_id' => $artikelId
 							));
				}
			}
			
			// Versandlaender der Preisliste anlegen
			foreach ($remotePricelistJsonResult->laender as $land) {
				$wpdb->insert(
					PAWPS_TABLENAME_PRICELIST_COUNTRIES,
					array(
							'pricelist_id' => $pricelistId,
							'title' => $land->land
					));
			}
 		}
 		
 	}
 }

 // Löschen aller Preilisten
 function pawps_deletePricelistById($id) {
 	global $wpdb;
 	
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_PRICELIST_PRODUCTS . " WHERE pricelist_id=" . $id);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_PRICES . " WHERE pricelist_id=" . $id);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_PRICELISTS . " WHERE id=" . $id);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_PRICELIST_COUNTRIES . " WHERE pricelist_id=" . $id);
 }
 
 function pawps_loadShootingByData($id = null, $code = null) {
 	global $wpdb;
 	
 	// Select aufbauen
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_SHOOTINGS . " WHERE ";
 	if (isset($id)) {
 		$sql .= "id=" . $id;
 	} else if (isset($code)) {
 		$sql .= "accesscode='" . $code . "'";
 	} else {
 		return null;
 	}
 	
 	// Datensatz selektieren
 	$result = $wpdb->get_row($sql);
 	if (isset($result)) {
 		return new pawps_shooting($result);
 	} else {
 		return null;
 	}
 }
 
 function pawps_getShootingList() {
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_SHOOTINGS . " ORDER BY shootingdate DESC";
 	
 	return pawps_getShootingListByStatement($sql);
 }
 
 function pawps_getShootingListByStatement($sql) {
 	global $wpdb;
 	
 	$results = $wpdb->get_results($sql);
 	$resultList = array();
 	
 	foreach ($results as $tmpShooting) {
 		array_push($resultList, new pawps_shooting($tmpShooting));
 	}
 	
 	return $resultList;
 }
 
 function pawps_refreshImageList($shootingId, $technicalId) {
 	$remoteJsonResult = pawps_getRemoteImageList($shootingId);
 	
 	// Result erhalten -> alte Images entfernen
 	global $wpdb;
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_IMAGES . " WHERE veranstaltungsid=" . $technicalId);
 	
 	$codeElements = split ( "-", $shootingId );
 	$shootingId = $codeElements[1];
 	
 	// Images eintragen
 	foreach ($remoteJsonResult as $jsonImg) {
 		$wpdb->insert(
 				PAWPS_TABLENAME_IMAGES,
 				array(
 						'id' => $jsonImg->id,
 						'veranstaltungsid' => $technicalId,
 						'onlineVeranstaltungId' => $shootingId,
 						'ordnerId' => pawps_getOrdnerId($technicalId, urldecode($jsonImg->subDir)),
 						'subDir' => $jsonImg->subDir,
 						'detailUrl' => $jsonImg->detailUrl,
 						'thumbUrl' => $jsonImg->thumbUrl,
 						'baseUrl' => $jsonImg->baseUrl
 				));
 	}
 	
 	return true;
 }
 
 function pawps_getOrdnerId($veranstaltungId, $ordnerName) {
 	global $wpdb;
 	
 	unset($storedName);
 	
 	$sql = "SELECT id FROM " . PAWPS_TABLENAME_ORDNER . " WHERE veranstaltungsid=" . $veranstaltungId . " AND title='" . $ordnerName . "'";
 	$storedName = $wpdb->get_var($sql);
 	
 	if (isset($storedName) && ($storedName > 0)) {
 		return $storedName;
 	}
 	
 	// Ordner liegt noch nicht vor -> anlegen
 	$wpdb->insert(
 			PAWPS_TABLENAME_ORDNER,
 			array(
 					'veranstaltungsid' => $veranstaltungId,
 					'title' => $ordnerName
 			));
 	
 	$ordnerId = $wpdb->insert_id;
 	return $ordnerId;
 }
 
 function pawps_loadImageListFromDb($technicalId, $startIndex = null, $endIndex = null, $ordnerId = null) {
 	global $wpdb;
 	
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_IMAGES . " WHERE veranstaltungsid=" . $technicalId;
 	
 	if (isset($ordnerId)) {
 		$sql .= " AND ordnerId=" . $ordnerId;
 	}
 	
 	$sql .= " ORDER BY id";
 	
 	if (isset($startIndex) && isset($endIndex)) {
 		$sql .= " LIMIT " . $startIndex . "," . ($endIndex - $startIndex);
 	}
 	
 	$results = $wpdb->get_results($sql);
 	$resultList = array();
 	
 	foreach ($results as $tmpImage) {
 		array_push($resultList, new pawps_image($tmpImage));
 	}
 	
 	return $resultList;
 }

 function pawps_generateLocalHash() {
 	return pawps_getModuleToken();
 }
  
 function pawps_loadImageById($id) {
 	global $wpdb;
 	
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_IMAGES . " WHERE id=" . $id;
 	
 	$result = $wpdb->get_row($sql);
 	if (isset($result)) {
 		return new pawps_image($result);
 	} else {
 		return null;
 	}
 }
 
 function pawps_loadShootingConfig($id = null, $code = null, $guestPassword = null) {
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_CONFIG . " WHERE ";
 	if (isset($id)) {
 		$sql .= "id=" . $id;
 	} else if (isset($code)) {
 		$sql .= "shootingcode='" . $code . "'";
 	} else if (isset($guestPassword)) {
 		$sql .= "guestpassword='" . $guestPassword . "'";
 	} else {
 		return null;
 	}
 	
 	global $wpdb;
 	
 	$result = $wpdb->get_row($sql);
 	if (isset($result)) {
 		return new pawps_config($result);
 	} else {
 		return null;
 	}
 }
 
 function pawps_getLandbezeichnung($id) {
 	global $wpdb;
 	
 	$sql = "SELECT title FROM " . PAWPS_TABLENAME_PRICELIST_COUNTRIES . " WHERE id=" . $id;
 	 	
 	return $wpdb->get_var($sql);
 }
 
 function pawps_loadEnabledListElements() {
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_SHOOTINGS . " WHERE accesscode IN (";
 	$sql .= "SELECT shootingcode FROM " . PAWPS_TABLENAME_CONFIG . " WHERE state IN (2,3))";
 	$sql .= " AND imageCount>0";
 	
 	return pawps_getShootingListByStatement($sql);
 }
 
 function pawps_loadProductById($id, $pricelistId) {
 	global $wpdb;
 	
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_PRODUCTS . " WHERE id=" . $id;
 	$result = $wpdb->get_row($sql);
 	
 	return new pawps_product($result, $pricelistId);
 }
 
 function pawps_loadCustomerById($id) {
 	global $wpdb;
 	
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_CUSTOMERS . " WHERE id=" . $id;
 	$result = $wpdb->get_row($sql);
 
 	if (isset($result)) {
 		return new pawps_customer($result, $pricelistId);
 	} else {
 		return null;
 	}
 }
 
 function pawps_getOrderList() {
 	global $wpdb;
 
 	
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_WARENKORB . " WHERE ";
 	$sql .= "customerId IS NOT NULL AND ";
 	$sql .= "dateOrdered IS NOT NULL ";
 	$sql .= "ORDER BY dateOrdered DESC";
 	
 	$results = $wpdb->get_results($sql);
 	$resultList = array();
 
 	foreach ($results as $tmpWarenkorb) {
 		array_push($resultList, new pawps_warenkorb($tmpWarenkorb->id));
 	}
 
 	return $resultList;
 }
 
 function pawps_formatNumber($betrag) {
 	return number_format($betrag, 2, ",", "");
 }
 
 function pawps_isFieldValid($field) {
 	return isset($field) && (strlen($field) > 0);
 }
  
 function pawps_validateField($field, $fieldName) {
 	if (!pawps_isFieldValid($field)) {
 		return "Der eingegebene Wert im Feld '" . $fieldName . "' ist ungültig<br/>";
 	}
 	
 	return "";
 }
 
 function pawps_loadOrdnerById($id) {
 	global $wpdb;
 	
 	$sql = "SELECT * FROM " . PAWPS_TABLENAME_ORDNER . " WHERE id =" . $id;
 	$results = $wpdb->get_results($sql);
 	
 	foreach ($results as $ordner) {
 		return new pawps_ordner($ordner);
 	}
 	
 	return null;
 }
 
 function pawps_doDebug($debugMessage) {
 	if (get_option(PAWPS_DEBUG) == 1) {
 		echo $debugMessage . "<br />";
 	}
 }
 
 function pawps_is_email(&$address_to_validate, $strict = false) {
 	//Leading and following whitespaces are ignored
 	$address_to_validate = trim($address_to_validate);
 	//Email-address is set to lower case
 	$address_to_validate = strtolower($address_to_validate);
 
 	//List of signs which are illegal in name, subdomain and domain
 	$illegal_string = '\\\\(\\n)@';
 
 	//Parts of the regular expression = name@subdomain.domain.toplevel
 	$name      = '([^\\.'.$illegal_string.'][^'.$illegal_string.']?)+';
 	$subdomain = '([^\\._'.$illegal_string.']+\\.)?';
 	$domain    = '[^\\.\\-_'.$illegal_string.'][^\\._'.$illegal_string.']*[^\\.\\-_'.$illegal_string.']';
 	$toplevel  = '([a-z]{2,4}|museum|travel)';    //.museum and .travel are the only TLDs longer than four signs
 
 	$regular_expression = '/^'.$name.'[@]'.$subdomain.$domain.'\.'.$toplevel.'$/';
 
 	return preg_match($regular_expression, $address_to_validate) ? true : false;
 }
 
 function pawps_is_url($url) {
 	// SCHEME
 	$urlregex = "^(https?|ftp)\:\/\/";
 
 	// USER AND PASS (optional)
 	$urlregex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";
 
 	// HOSTNAME OR IP
 	$urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*";  // http://x = allowed (ex. http://localhost, http://routerlogin)
 	//$urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)+";  // http://x.x = minimum
 	//$urlregex .= "([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}";  // http://x.xx(x) = minimum
 	//use only one of the above
 
 	// PORT (optional)
 	$urlregex .= "(\:[0-9]{2,5})?";
 	// PATH  (optional)
 	$urlregex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
 	// GET Query (optional)
 	$urlregex .= "(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?";
 	// ANCHOR (optional)
 	$urlregex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
 
 	// check
 	if (eregi($urlregex, $url)) {return true; } else {return false; }
 }
 
 function pawps_copyDirectory($conn_id, $src_dir, $dst_dir) {
 	$dst_dir = "/" . $dst_dir;
 	$d = dir($src_dir);
 	while($file = $d->read()) { // do this for each file in the directory
 		if ($file != "." && $file != "..") { // to prevent an infinite loop
 			if (is_dir($src_dir."/".$file)) { // do the following if it is a directory
 				if (!@ftp_chdir($conn_id, $dst_dir."/".$file)) {
 					ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
 				}
 				pawps_copyDirectory($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
 			} else {
 				$upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
 				ftp_chmod($conn_id, 0777, $dst_dir."/".$file);
 			}
 		}
 	}
 	$d->close();
 }
 
 function pawps_mksubdirs($ftpcon, $ftpbasedir, $ftpath){
 	
 	$homeParts = explode('/', $ftpbasedir);
 	$subDirs = ftp_nlist($ftpcon, ".");
 	$found = false;
 	foreach ($subDirs as $dir) {
 		if (!isset($newBase)) {
	 		foreach ($homeParts as $part) {
	 			if ($dir == $part) {
	 				$found = true;
	 				$newBase = "";
	 			}
	 			
	 			if ($found) {
	 				$newBase .= $part . "/";
	 			}
	 		}
 		}
 	}
 	@ftp_chdir($ftpcon, $newBase); // /var/www/uploads
 	 	
 	$parts = explode('/',$ftpath); // 2013/06/11/username
 	foreach($parts as $part){
 		if(!@ftp_chdir($ftpcon, $part)){
 			ftp_mkdir($ftpcon, $part);
 			ftp_chdir($ftpcon, $part);
 		}
 	}
 	
 	return $newBase;
 }
 
 
 // Methode um taegliche Aktualisierung durchzufuehren
 function pawps_refresh_daily() {
 	pawps_refreshShootings();
 }
 
 // SystemCheck Function
 function pawps_syscheck() {
 	return pawps_syscheck_urlFopen() || pawps_syscheck_curl();
 }
 
 function pawps_syscheck_urlFopen() {
 	return ini_get('allow_url_fopen') == true;
 }
 
 function pawps_syscheck_curl() {
 	return function_exists('curl_init');
 }
 
 add_action('init', 'do_output_buffer');
 function do_output_buffer() {
 	ob_start();
 }
 
?>