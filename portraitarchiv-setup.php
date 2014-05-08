<?php

 function pawps_setupDatabase() {
 	$currentDatabaseVersion = 3;
 	
 	if (get_option(PAWPS_DB_VERSION) < $currentDatabaseVersion) {
	 	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	 	
	 	// Shootings
	 	$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_SHOOTINGS . ' (
	 			id MEDIUMINT(5) NOT NULL AUTO_INCREMENT,
	 			title VARCHAR(200) NOT NULL,
	 			imageCount MEDIUMINT(4) NOT NULL DEFAULT 0,
	 			shootingdate DATE NOT NULL, 
	 			pricelist_id MEDIUMINT(5) NOT NULL, 
	 			accesscode VARCHAR(20) NOT NULL,
	 			lastupdate DATETIME NOT NULL,
	 			PRIMARY KEY(ID));';
		dbDelta( $tableSql );
	
		// Pricelists
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_PRICELISTS . ' (
	 			id MEDIUMINT(5) NOT NULL AUTO_INCREMENT,
				laborId MEDIUMINT(5) NOT NULL DEFAULT 0,
	 			title VARCHAR(200) NOT NULL, 			
	 			lastupdate DATETIME NOT NULL,
	 			PRIMARY KEY(ID));';
		dbDelta( $tableSql );
		
		// Products
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_PRODUCTS . ' (
	 			id MEDIUMINT(5) NOT NULL AUTO_INCREMENT,
	 			title VARCHAR(200) NOT NULL,
	 			PRIMARY KEY(ID));';
		dbDelta( $tableSql );
		
		// Prices
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_PRICES . ' (
	 			id MEDIUMINT(5) NOT NULL AUTO_INCREMENT,
				pricelist_id MEDIUMINT(5) NOT NULL,
				product_id MEDIUMINT(5) NOT NULL,
				gueltigAb MEDIUMINT(3) NOT NULL,
				price DOUBLE(6,2) NOT NULL,
	 			PRIMARY KEY(ID));';
		dbDelta( $tableSql );
		
		// Pricelists->Products
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_PRICELIST_PRODUCTS . ' (
				pricelist_id MEDIUMINT(5) NOT NULL,
				product_id MEDIUMINT(5) NOT NULL);';	
		dbDelta( $tableSql );
		
		// Moegliche Versandlaender
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_PRICELIST_COUNTRIES . ' (
				id MEDIUMINT(5) NOT NULL AUTO_INCREMENT,
				pricelist_id MEDIUMINT(5) NOT NULL,
				title VARCHAR(50) NOT NULL,
	 			PRIMARY KEY(ID));';
		dbDelta( $tableSql );
		
		// Unterordner
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_ORDNER . ' (
				id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
				veranstaltungsid MEDIUMINT(5) NOT NULL,
				title VARCHAR(100) NOT NULL,
				PRIMARY KEY(ID));';
		dbDelta( $tableSql );
		
		// Images
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_IMAGES . ' (
				id MEDIUMINT(8) NOT NULL,
				veranstaltungsid MEDIUMINT(5) NOT NULL,
				ordnerId MEDIUMINT(8) NOT NULL,
				baseUrl VARCHAR(100) NOT NULL,
				detailUrl VARCHAR(20) NOT NULL,
				thumbUrl VARCHAR(20) NOT NULL,
				PRIMARY KEY(ID));';	
		dbDelta( $tableSql );
		
		// lokale Shooting-Config
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_CONFIG . ' (
				id MEDIUMINT(5) NOT NULL AUTO_INCREMENT,
				shootingcode VARCHAR(20) NOT NULL,
				state MEDIUMINT(1) NOT NULL DEFAULT 0,
				guestpassword VARCHAR(25),
				PRIMARY KEY(id));';
		dbDelta( $tableSql );
		
		// Warenkorb-Tabelle
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_WARENKORB . ' (
				id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
				dateCreate DATETIME NOT NULL,
				dateUpdate DATETIME NOT NULL,
				dateOrdered DATETIME,
				customerId MEDIUMINT(8),
				orderId MEDIUMINT(8),
				orderAnzahlPositionen MEDIUMINT(4),
				orderGesamtpreis DOUBLE(6,2),
				PRIMARY KEY(id));';
		dbDelta( $tableSql );
		
		// Warenkorb Artikel
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_WARENKORB_PRODUCTS . ' (
				id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
				warenkorbId MEDIUMINT(8) NOT NULL,
				productId MEDIUMINT(5) NOT NULL,
				pricelistId MEDIUMINT(5) NOT NULL,
				PRIMARY KEY(id));';
		dbDelta( $tableSql );
		
		// Warenkorb Images
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_WARENKORB_IMAGES . ' (
				productId MEDIUMINT(8) NOT NULL,
				imageId MEDIUMINT(8) NOT NULL,
				anzahl MEDIUMINT(5) NOT NULL);';
		dbDelta( $tableSql );
		
		// Warenkorb Images
		$tableSql = 'CREATE TABLE ' . PAWPS_TABLENAME_CUSTOMERS . ' (
				id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
				email varchar(50) NOT NULL,
				name varchar(30) NOT NULL,
				firstname varchar(30) NOT NULL,
				street varchar(50) NOT NULL,
				number varchar(6),
				plz varchar(6) NOT NULL,
				city varchar(40) NOT NULL,
				country varchar(50) NOT NULL,
				ver_name varchar(30),
				ver_firstname varchar(30),
				ver_street varchar(50),
				ver_number varchar(6),
				ver_plz varchar(6),
				ver_city varchar(40),
				ver_country varchar(50),				
				PRIMARY KEY(id));';
		dbDelta( $tableSql );
		
		// aktuelle Version in DB eintragen
		update_option(PAWPS_DB_VERSION, $currentDatabaseVersion);
 	}
 }
 
 function pawps_cleanupDatabase() {
 	global $wpdb;
 	
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_SHOOTINGS);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_PRICELISTS);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_PRODUCTS);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_PRICES);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_PRICELIST_COUNTRIES);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_PRICELIST_PRODUCTS);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_IMAGES);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_CONFIG);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_WARENKORB);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_WARENKORB_PRODUCTS);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_WARENKORB_IMAGES);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_CUSTOMERS);
 	$wpdb->query('DROP TABLE ' . PAWPS_TABLENAME_ORDNER);
 	
 	// Version entfernen
 	delete_option(PAWPS_DB_VERSION);
 }
 
 function pawps_clearDatabaseValues() {
 	update_option(PAWPS_LAST_UPDATE_SHOOTINGS, 0);
 	
 	global $wpdb;
 	
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_SHOOTINGS);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_ORDNER);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_PRICELISTS);
  	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_PRODUCTS);
  	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_PRICES);
  	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_PRICELIST_PRODUCTS);
  	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_IMAGES);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_WARENKORB);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_WARENKORB_PRODUCTS);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_WARENKORB_IMAGES);
 	$wpdb->query('DELETE FROM ' . PAWPS_TABLENAME_CUSTOMERS);
 }
 
?>