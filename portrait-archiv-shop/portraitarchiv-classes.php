<?php

 class pawps_shooting {
 	var $id, $title, $imageCount, $shootingdate, $pricelist_id, $accesscode, $lastupdate;
 	
 	// TMP Values
 	var $tmpPricelist, $tmpImagelist, $tmpConfig, $tmpPreviewImage;
 	var $tmpImgPageStart, $tmpImgPageEnd, $tmpImgPage, $tmpOrdner;
 	var $aktuellerOrdner;
 	
 	function __construct($values) {
 		$this->id = $values->id;
 		$this->title = $values->title;
 		$this->imageCount = $values->imageCount;
 		$this->shootingdate = $values->shootingdate;
 		$this->pricelist_id = $values->pricelist_id;
 		$this->accesscode = $values->accesscode;
 		$this->lastupdate = $values->lastupdate;
 		
 		$alleOrdner = $this->getOrdner();
 		$this->aktuellerOrdner = $alleOrdner[0];
 	}
 	
 	function getPricelist() {
 		if (!isset($this->tmpPricelist)) {
 			$this->tmpPricelist = pawps_loadPricelist($this->pricelist_id);
 			
 			if (!isset($this->tmpPricelist)) {
 				// Aus irgend welchen Gründen ist die Preisliste nicht vorhanden -> versuche erneut aufzulösen
 				if ($this->pricelist_id > 0) {
 					pawps_refreshPricelist($this->pricelist_id);
 					$this->tmpPricelist = pawps_loadPricelist($this->pricelist_id);
 				}
 			}
 			
 		}
 		return $this->tmpPricelist;
 	}
 	
 	function getImages($onlyOrdner = false) {
 		if (!isset($this->tmpImagelist)) {
 			// Liste nicht gesetzt - versuche aus DB zu laden
 			if ($onlyOrdner) {
 				$imgList = pawps_loadImageListFromDb($this->id, null, null, $this->aktuellerOrdner->id);
 			} else {
 				$imgList = pawps_loadImageListFromDb($this->id);
 			}
 			
 			if (!isset($imgList) || (count($imgList) == 0)) {
 				// Versuche Images von Remote zu holen
 				if (pawps_refreshImageList($this->accesscode, $this->id)) {
 					if ($onlyOrdner) {
 						$imgList = pawps_loadImageListFromDb($this->id, null, null, $this->aktuellerOrdner->id);
 					} else {
 						$imgList = pawps_loadImageListFromDb($this->id);
 					}
 				}
 			}
 			
 			if (isset($imgList) && (count($imgList) > 0)) {
 				$this->tmpImagelist = $imgList;
 			}
 		}
 		
 		return $this->tmpImagelist;
 	}
 	
 	function getImagePage($startIndex, $endIndex) {
 		// Inititalisieren sofern nicht bereits geschehen
 		if (!isset($this->tmpImgPageStart)) {
 			$this->tmpImgPageStart = 0;
 			$this->tmpImgPageEnd = 0;
 		}
 		
 		// Prüfen ob neuladen
 		if (($this->tmpImgPageStart <> $startIndex) ||
 			($this->tmpImgPageEnd <> $endIndex) ||
 			(!isset($this->tmpImgPage))) {
 			$this->tmpImgPageStart = $startIndex;
 			$this->tmpImgPageEnd = $endIndex;
 			
 			$this->tmpImgPage = pawps_loadImageListFromDb($this->id, $startIndex, $endIndex, $this->aktuellerOrdner->id);
 			
 			if (!isset($this->tmpImgPage) || (count($this->tmpImgPage) == 0)) {
 				// Versuche Images von Remote zu holen
 				if (pawps_refreshImageList($this->accesscode, $this->id)) {
 					$this->tmpImgPage = pawps_loadImageListFromDb($this->id, $startIndex, $endIndex);
 				}
 			} 			
 		}
 		
 		return $this->tmpImgPage;
 	}
 	
 	function getIntegrationState() {
 		if (!isset($this->tmpConfig)) {
 			$this->tmpConfig = pawps_loadShootingConfig(null, $this->accesscode);
 		}
 		
 		if (isset($this->tmpConfig)) {
 			return $this->tmpConfig->state;
 		} else {
	 		return 0;
 		}
 	}
 	
 	function getPreviewImage() {
 		if (!isset($this->tmpPreviewImage)) {
 			// load PreviewImage
 			$tmpImageList = pawps_loadImageListFromDb($this->id, 0, 1);
 			
 			if (!isset($tmpImageList) || (count($tmpImageList) == 0)) {
 				// Versuche Images von Remote zu holen
 				if (pawps_refreshImageList($this->accesscode, $this->id)) {
 					$tmpImageList = pawps_loadImageListFromDb($this->id, 0, 1);
 				}
 			}
 			
 			if (isset($tmpImageList) && (count($tmpImageList) > 0)) {
 				$this->tmpPreviewImage = $tmpImageList[0];
 			}
 		}
 		
 		return $this->tmpPreviewImage;
 	}
 	
 	function getShootingConfig() {
 		return pawps_loadShootingConfig(null, $this->accesscode);
 	}
 	
 	function getOrdnerCount() {
 		return count($this->getOrdner());
 	}
 	
 	function getOrdner() {
 		if (!isset($this->tmpOrdner)) {
 			$this->tmpOrdner = array();
 			
 			global $wpdb;
 			
 			$sql = "SELECT * FROM " . PAWPS_TABLENAME_ORDNER . " WHERE veranstaltungsid =" . $this->id;
 			$sql .= " ORDER BY title";
 			
 			$results = $wpdb->get_results($sql);

 			foreach ($results as $ordner) {
 				array_push($this->tmpOrdner, new pawps_ordner($ordner));
 			}
 		}
 		
 		return $this->tmpOrdner;
 	}
 }
 
 class pawps_ordner {
 	var $id, $shootingId, $title;
 	
 	function __construct($values) {
 		$this->id = $values->id;
 		$this->shootingId = $values->veranstaltungsid;
 		$this->title = $values->title;
 	}
 }
 
 class pawps_pricelist {
 	var $id, $title, $lastupdate = 0, $laborId;
 	
 	// TMP Values
 	var $tmpProducts, $tmpCountries;
 	
 	function __construct($values) {
 		$this->id = $values->id;
 		$this->title = $values->title;
 		$this->lastupdate = $values->lastupdate;
 		$this->laborId = $values->laborId;
 	}
 	
 	function getProducts() {
 		if (!isset($this->tmpProducts)) {
 			global $wpdb;
 			
 			$sql = "SELECT * FROM " . PAWPS_TABLENAME_PRODUCTS . " WHERE id in (";
			$sql .= "SELECT product_id FROM " . PAWPS_TABLENAME_PRICELIST_PRODUCTS . " WHERE pricelist_id=" . $this->id . ")";
 			$sql .= " ORDER BY title";
 			
 			$results = $wpdb->get_results($sql);
 			$this->tmpProducts = array();
 			
 			foreach ($results as $tmpProduct) {
 				array_push($this->tmpProducts, new pawps_product($tmpProduct, $this->id));
 			}
 		}
 		
 		return $this->tmpProducts;
 	}
 	
 	function getCountries() {
 		if (!isset($this->tmpCountries)) {
 			global $wpdb;
 		
 			$sql = "SELECT * FROM " . PAWPS_TABLENAME_PRICELIST_COUNTRIES . " WHERE pricelist_id=" . $this->id;
 			$sql .= " ORDER BY title";
 		
 			$results = $wpdb->get_results($sql);
 			$this->tmpCountries = array();
 		
 			foreach ($results as $tmpCountrie) {
 				array_push($this->tmpCountries, new pawps_country($tmpCountrie));
 			}
 		}
 			
 		return $this->tmpCountries;
 	}
 	
 	function getProduct($id) {
 		foreach ($this->getProducts() as $product) {
 			if ($product->id == $id) {
 				return $product;
 			}
 		}
 		
 		return null;
 	}
 }
 
 class pawps_country {
 	var $id, $title;
 	
 	function __construct($values) {
 		$this->id = $values->id;
 		$this->title = $values->title;
 	} 	
 }
 
 class pawps_product {
 	var $id, $title, $pricelistId;
 	
 	// TMP Values
 	var $tmpPrices;
 	
 	function __construct($values, $pricelistId) {
 		$this->id = $values->id;
 		$this->title = $values->title;
 		$this->pricelistId = $pricelistId;
 	}
 	
 	function getPrices() {
 		if (!isset($this->tmpPrices)) {
 			global $wpdb;
 		
 			$sql = "SELECT * FROM " . PAWPS_TABLENAME_PRICES . " WHERE ";
 			$sql .= " pricelist_id=" . $this->pricelistId . " AND ";
 			$sql .= " product_id=" . $this->id;
 			$sql .= " ORDER BY gueltigAb";
 			
 			$results = $wpdb->get_results($sql);
 			$this->tmpPrices = array();
 		
 			foreach ($results as $tmpProduct) {
 				array_push($this->tmpPrices, new pawps_price($tmpProduct));
 			}
 		}
 			
 		return $this->tmpPrices;
 	}
 	
 	function getPrice($gueltigAb, $formatiert = true) {
 		$preise = $this->getPrices();
 		
 		foreach ($preise as $preis) {
 			if ($preis->gueltigAb <= $gueltigAb) {
 				return $preis->getPrice($formatiert);
 			}
 		}
 	}
 }
 
 class pawps_price {
 	var $id, $pricelist_id, $product_id, $gueltigAb, $price;
 	
 	function __construct($values) {
 		$this->id = $values->id;
 		$this->pricelistId = $values->pricelistId;
 		$this->product_id = $values->product_id;
 		$this->gueltigAb = $values->gueltigAb;
 		$this->price = $values->price;
 	}
 	
 	function getAb() {
 		return $this->gueltigAb;
 	}
 	
 	function getPrice($formatiert = true) {
 		if ($formatiert) {
 			return pawps_formatNumber($this->price);
 		}
 		return $this->price;
 	}
 }
 
 class pawps_image {
 	var $id, $veranstaltungsid, $detailUrl, $thumbUrl, $baseUrl, $ordnerId;
 	
 	function __construct($values) {
 		$this->id = $values->id;
 		$this->veranstaltungsid = $values->veranstaltungsid;
 		$this->detailUrl = $values->detailUrl;
 		$this->thumbUrl = $values->thumbUrl;
 		$this->baseUrl = $values->baseUrl;
 		$this->ordnerId = $values->ordnerId;
 	}
 	
 	function isFirst() {
 		global $wpdb;
 		$sql = "SELECT MIN(id) FROM " . PAWPS_TABLENAME_IMAGES . " WHERE ";
 		$sql .= "veranstaltungsid=" . $this->veranstaltungsid;
 		$sql .= " AND ordnerId=" . $this->ordnerId;
 		$minId = $wpdb->get_var($sql);
 		return $this->id == $minId;
 	}
 	
 	function isLast() {
 		global $wpdb;
 		$sql = "SELECT MAX(id) FROM " . PAWPS_TABLENAME_IMAGES . " WHERE ";
 		$sql .= "veranstaltungsid=" . $this->veranstaltungsid;
 		$sql .= " AND ordnerId=" . $this->ordnerId;
 		$maxId = $wpdb->get_var($sql);
 		return $this->id == $maxId;
 	}
 	
 	function getPreviousId() {
 		global $wpdb;
 		$sql = "SELECT id FROM " . PAWPS_TABLENAME_IMAGES . " WHERE ";
 		$sql .= "veranstaltungsid=" . $this->veranstaltungsid;
 		$sql .= " AND ordnerId=" . $this->ordnerId;
 		$sql .= " AND id <" . $this->id;
 		$sql .= " ORDER BY id DESC";
 		$sql .= " LIMIT 0,1";
 		return $wpdb->get_var($sql);
 	}
 	
 	function getNextId() {
 		global $wpdb;
 		$sql = "SELECT id FROM " . PAWPS_TABLENAME_IMAGES . " WHERE ";
 		$sql .= "veranstaltungsid=" . $this->veranstaltungsid;
 		$sql .= " AND ordnerId=" . $this->ordnerId;
 		$sql .= " AND id >" . $this->id;
 		$sql .= " ORDER BY id ASC";
 		$sql .= " LIMIT 0,1";
 		return $wpdb->get_var($sql);
 	}
 	
 	function getThumbUrl() {
 		return urldecode($this->baseUrl . "/" . $this->thumbUrl);
 	}
 	
 	function getDetailUrl() {
 		return urldecode($this->baseUrl . "/" . $this->detailUrl);
 	}
 }
 
 class pawps_config {
 	var $id, $shootingcode, $state, $guestpassword;
 	
 	function __construct($values) {
 		$this->id = $values->id;
 		$this->shootingcode = $values->shootingcode;
 		// 0 = inaktiv
 		// 1 = nur per Passwort
 		// 2 = Liste
 		// 3 = Liste und Passwort 		
 		$this->state = $values->state;
 		$this->guestpassword = $values->guestpassword;
 	}
 	
 	function getPassword() {
 		if (!isset($this->guestpassword)) {
 			return "";
 		} else {
 			return $this->guestpassword;
 		}
 	}
 }
 
 class pawps_warenkorb {
 	var $id, $positionen, $betrag, $customerId, $orderId, $orderAnzahlPositionen, $orderGesamtpreis, $dateOrdered;
 	
 	var $tmpVersand, $tmpAnzahl, $tmpCustomer;
 	
 	function __construct($id = null) {
 		global $wpdb;
 		
 		$this->positionen = array();
 		$this->betrag = 0;
 			
 		if (isset($id)) {
 			// reload from database
 			$this->id = $id;
 			
			// Lade alten Warenkorb 			
 			$sql = "SELECT * FROM " . PAWPS_TABLENAME_WARENKORB . " WHERE ";
 			$sql .= "id=" . $id;
 			
 			// Datensatz selektieren
 			$result = $wpdb->get_row($sql);
 			if (!isset($result)) {
 				// Warenkorb nicht verfügbar
 				$wpdb->insert(
 						PAWPS_TABLENAME_WARENKORB,
 						array(
 								'id' => $id,
 								'dateCreate' => current_time('mysql'),
 								'dateUpdate' => current_time('mysql')
 						));
 			} else {
				// Warenkorbwerte eintragen
	 			$this->id = $result->id;
	 			$this->customerId = $result->customerId;
	 			$this->orderId = $result->orderId;
	 			$this->orderAnzahlPositionen = $result->orderAnzahlPositionen;
	 			$this->orderGesamtpreis = $result->orderGesamtpreis;
 				$this->dateOrdered = $result->dateOrdered;
 				
	 			// lese Positionen
	 			$sql = "SELECT * FROM " . PAWPS_TABLENAME_WARENKORB_PRODUCTS . " WHERE ";
	 			$sql .= " warenkorbId=" . $this->id;
	 			$results = $wpdb->get_results($sql);
	 			foreach ($results as $posResult) {
	 				$tmpPos = new pawps_warenkorbPosition($posResult->productId, $posResult->pricelistId);
	 				$tmpPos->id = $posResult->id;
	 				
	 				$sql = "SELECT * FROM " . PAWPS_TABLENAME_WARENKORB_IMAGES . " WHERE ";
	 				$sql .= " productId=" . $tmpPos->id;
	 				$imgResults = $wpdb->get_results($sql);
	 				foreach ($imgResults as $posImage) {
	 					$tmpPos->addImageOrder($posImage->imageId, $posImage->anzahl);
	 				}
	 				
	 				$this->positionen[$posResult->productId] = $tmpPos;
	 			}
	 			
	 			// Preise der einzelnen Positionen neu berechnen
	 			foreach ($this->positionen as $position) {
	 				$position->calculated = false;
	 				$position->calculatePrices();
	 				$this->betrag = $this->betrag + $position->getBetrag(false);
	 			}
 			}
 		} else {
 			// create new instance
 			$wpdb->insert(
 					PAWPS_TABLENAME_WARENKORB,
 					array(
 							'dateCreate' => current_time('mysql'),
 							'dateUpdate' => current_time('mysql')
 					));
 				
 			$this->id = $wpdb->insert_id;
 			$_SESSION['PAWPS_WARENKORB'] = $this->id;
 		}
 	}
 	
 	function addPosition($imageId, $productId, $anzahl, $pricelistId) {
 		global $wpdb;
 		
 		if (!isset($this->positionen[$productId])) {
 			$this->positionen[$productId] = new pawps_warenkorbPosition($productId);
 			
 			// Eintrag vornehmen
 			$wpdb->insert(
 					PAWPS_TABLENAME_WARENKORB_PRODUCTS,
 					array(
 							'warenkorbId' => $this->id,
 							'productId' => $productId,
 							'pricelistId' => $pricelistId
 					));
 				
 			$this->positionen[$productId]->id = $wpdb->insert_id;
 		}
 		
 		$this->positionen[$productId]->addImageOrder($imageId, $anzahl);
 		$this->betrag = $this->betrag + $this->positionen[$productId];
 		
 		// Image eintragen
 		$wpdb->insert(
 				PAWPS_TABLENAME_WARENKORB_IMAGES,
 				array(
 						'productId' => $this->positionen[$productId]->id,
 						'imageId' => $imageId,
 						'anzahl' => $anzahl,
 				));
 	}
 	
 	function getAnzahl() {
 		if (!isset($this->tmpAnzahl)) {
 			$this->tmpAnzahl = 0;
 			foreach ($this->positionen as $pos) {
 				foreach ($pos->images as $img) {
 					$this->tmpAnzahl = $this->tmpAnzahl + $img->count;
 				}
 			}
 		}
 		
 		return $this->tmpAnzahl;
 	}
 	
 	function getZwischensumme($formatiert = true) {
 		if ($formatiert) {
 			return pawps_formatNumber($this->betrag);
 		}
 		return $this->betrag;
 	}
 	
 	function getGesamtpreis($formatiert = true) {
 		$endpreis = $this->getZwischensumme(false) + $this->getVersandkosten(false);
 		
 		if ($formatiert) {
 			return pawps_formatNumber($endpreis);
 		}
 		return $endpreis;
 	}
 	
 	function getVersandkosten($formatiert = true) {
 		if ($this->getAnzahl() < 1) {
 			$this->tmpVersand = 0;
 		}
 		
 		if (!isset($this->tmpVersand)) {
 			$this->tmpVersand = pawps_getRemoteWarenkorbVersandkosten($this);
 		} 
 		
 		if ($formatiert) {
 			return pawps_formatNumber($this->tmpVersand);
 		}
 		return $this->tmpVersand;
 	}
 	
 	function getMoeglicheVersandlaender() {
 		$erstePosition = reset($this->positionen);
 		$pricelist = $erstePosition->getPricelist();
 		return $pricelist->getCountries();
 	}
 	
 	function recalculateWarenkorb() {
 		$this->betrag = 0;
 		unset($this->tmpVersand);
 		foreach ($this->positionen as $position) {
 			$position->calculated = false;
 			$position->calculatePrices();
 			$this->betrag = $this->betrag + $position->getBetrag(false);
 		}
 	}
 	
 	function getCustomer() {
 		if (!isset($this->tmpCustomer) && isset($this->customerId)) {
 			$this->tmpCustomer = pawps_loadCustomerById($this->customerId);
 		}
 		
 		return $this->tmpCustomer;
 	}
 }
 
 class pawps_warenkorbPosition {
 	var $id, $productId, $anzahl, $pricelistId;
 	
 	var $images, $pricelist, $calculated = false;
 	
 	function __construct($productId = null, $pricelistId = null) {
 		$this->productId = $productId;
	 	$this->anzahl = 0;
	 	$this->images = array();
	 	$this->pricelistId = $pricelistId;
 	}
 	
 	function addImageOrder($imageId, $anzahl) {
 		if (!isset($this->images[$imageId])) {
 			$this->images[$imageId] = new pawps_warenkorbProductPosition($imageId);
 		}
 		
 		$this->images[$imageId]->setCount($anzahl);
 		$this->anzahl = $this->anzahl + $anzahl;
 	}
 	
 	function calculatePrices($zwingendNeuberechnen = false) {
 		if ($zwingendNeuberechnen) {
 			$this->calculated = false;
 		}
 		if (!$this->calculated) {
 			// Anzahl neu berechnen
 			$this->anzahl = 0;
 			foreach ($this->images as $img) {
 				$this->anzahl = $this->anzahl + $img->count;
 			}
 			
 			// Preise neu Berechnen
 			foreach ($this->images as $img) {
 				$img->price = $this->getProduct()->getPrice($this->anzahl, false) * $img->count;
 			}
 		}
 	}
 	
 	function getBetrag($formatiert = true) {
 		$betrag = $this->getEinzelpreis(false) * $this->anzahl;
 		
 		if ($formatiert) {
 			return pawps_formatNumber($betrag);
 		}
 		return $betrag;
 	}
 	
 	function getEinzelpreis($formatiert = true) {
 		$betrag = $this->getProduct()->getPrice($this->anzahl, false);
 			
 		if ($formatiert) {
 			return pawps_formatNumber($betrag);
 		}
 		return $betrag;
 	}
 	
 	function getPricelist() {
 		if (!isset($this->pricelist)) {
 			$this->pricelist = pawps_loadPricelist($this->pricelistId);
 		}
 		
 		return $this->pricelist;
 	}
 	
 	function getProduct() {
 		return $this->getPricelist()->getProduct($this->productId);
 	}
 }
 
 class pawps_warenkorbProductPosition {
 	var $imageId, $count, $price;
 	
 	function __construct($imageId) {
 		$this->imageId = $imageId;
 		$this->count = 0;
 		$this->price = 0;
 	}
 	
 	function setCount($anzahl, $withDbUpdate = false, $productId = null) {
 		$this->count = $anzahl;
 		
 		if ($withDbUpdate) {
 			global $wpdb;
 			
 			$wpdb->update(
 					PAWPS_TABLENAME_WARENKORB_IMAGES,
 					array(
 							'anzahl' => $anzahl
 					),
 					array(
 							'productId' => $productId,
 							'imageId' => $this->imageId
 					));
 		}
 	}
 	
 	function getPrice($formatiert = true) {
 		if ($formatiert) {
 			return pawps_formatNumber($this->price);
 		}
 		
 		return $this->price;
 	}
 	
 	function getImage() {
 		return pawps_loadImageById($this->imageId);
 	}
 }
 
 class pawps_customer {
 	var $id, $email, $name, $firstname, $street, $number, $plz, $city, $country, $ver_name, 
 		$ver_firstname, $ver_street, $ver_number, $ver_plz, $ver_city, $ver_country;
 	
 	function __construct($data) {
 		$this->id = $data->id;
 		$this->email = $data->email;
 		$this->name = $data->name;
 		$this->firstname = $data->firstname;
 		$this->street = $data->street;
 		$this->number = $data->number;
 		$this->plz = $data->plz;
 		$this->city = $data->city;
 		$this->country = $data->country;
 		$this->ver_name = $data->ver_name;
 		$this->ver_firstname = $data->ver_firstname;
 		$this->ver_street = $data->ver_street;
 		$this->ver_number = $data->ver_number;
 		$this->ver_plz = $data->ver_plz;
 		$this->ver_city = $data->ver_city;
 		$this->ver_country = $data->ver_country; 		
 	}
 }
 
 class pawps_transferWarenkorbPosition {
 	var $imageId, $productId, $anzahl;
 }
 
 class pawps_warenkorbTransfer {
 	var $positionen, $customer;
 	
 	function __construct($warenkorb, $withCustomer = true) {
 		$this->positionen = array();
 		
 		foreach ($warenkorb->positionen as $position) {
 			foreach ($position->images as $img) {
 				if ($img->count > 0) {
	 				$tmpPos = new pawps_transferWarenkorbPosition();
	 				$tmpPos->imageId = $img->imageId;
	 				$tmpPos->productId = $position->productId;
	 				$tmpPos->anzahl = $img->count;
	 				array_push($this->positionen, $tmpPos);
 				}
 			}
 		}
 		
 		if ($withCustomer) {
	 		$customer = $warenkorb->getCustomer();
	 		if (isset($customer)) {
	 			$customer->email = urlencode($customer->email);
	 			$customer->name = urlencode($customer->name);
	 			$customer->firstname = urlencode($customer->firstname);
	 			$customer->street = urlencode($customer->street);
	 			$customer->plz = urlencode($customer->plz);
	 			$customer->city = urlencode($customer->city);
	 			$customer->country = urlencode($customer->country);
	 			$customer->number = urlencode($customer->number);
	 			$customer->ver_name = urlencode($customer->ver_name);
	 			$customer->ver_firstname = urlencode($customer->ver_firstname);
	 			$customer->ver_street = urlencode($customer->ver_street);
	 			$customer->ver_plz = urlencode($customer->ver_plz);
	 			$customer->ver_city = urlencode($customer->ver_city);
	 			$customer->ver_country = urlencode($customer->ver_country);
	 			$customer->ver_number = urlencode($customer->ver_number);
	 			
	 			$this->customer = $customer;
	 		}
 		}
 	}
 }
 
 class pawps_newFotograf {
 	var $firma, $name, $vorname, $strasse, $nr, $plz, $ort, $telefon, $email, $homepage, $wpUrl;
 }

?>