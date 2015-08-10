<?php

 // Database wird benötigt um Prefix aufzulösen
 global $wpdb;

 // Definiere benötigte Felder
 define ('PAWPS_OPTION_HASHKEY', 'PAWPS_HASH');
 define ('PAWPS_OPTION_HASHKEY_REMOTE', 'PAWPS_HASH_REMOTE');
 define ('PAWPS_OPTION_USERID', 'PAWPS_USERID');
 define ('PAWPS_LAST_UPDATE_SHOOTINGS', 'PAWPS_LASTUPDATE_SHOOTINGS');
 define ('PAWPS_DB_VERSION', 'PAWPS_DB_VERSION');
 define ('PAWPS_DISPLAY_ROWS', 'PAWPS_DISPLAY_ROWS');
 define ('PAWPS_DISPLAY_COLS', 'PAWPS_DISPLAY_COLS');
 define ('PAWPS_TEMPLATE_NAME', 'PAWPS_TEMPLATE_NAME'); 
 define ('PAWPS_DEBUG', 'PAWPS_DEBUG');
 
 define ('PAWPS_TABLENAME_SHOOTINGS', $wpdb->prefix . "PAWPS_SHOOTINGS");
 define ('PAWPS_TABLENAME_ORDNER', $wpdb->prefix . "PAWPS_ORDNER");
 define ('PAWPS_TABLENAME_PRICELISTS', $wpdb->prefix . "PAWPS_PRICELISTS");
 define ('PAWPS_TABLENAME_PRODUCTS', $wpdb->prefix . "PAWPS_PRODUCTS");
 define ('PAWPS_TABLENAME_PRICES', $wpdb->prefix . "PAWPS_PRICES");
 define ('PAWPS_TABLENAME_PRICELIST_COUNTRIES', $wpdb->prefix . "PAWPS_PRICELIST_COUNTRIES");
 define ('PAWPS_TABLENAME_PRICELIST_PRODUCTS', $wpdb->prefix . "PAWPS_PRICELIST_PRODUCTS");
 define ('PAWPS_TABLENAME_IMAGES', $wpdb->prefix . "PAWPS_IMAGES");
 define ('PAWPS_TABLENAME_CONFIG', $wpdb->prefix . "PAWPS_CONFIG");
 define ('PAWPS_TABLENAME_WARENKORB', $wpdb->prefix . "PAWPS_WARENKORB");
 define ('PAWPS_TABLENAME_WARENKORB_PRODUCTS', $wpdb->prefix . "PAWPS_WARENKORB_PRODUCTS");
 define ('PAWPS_TABLENAME_WARENKORB_IMAGES', $wpdb->prefix . "PAWPS_WARENKORB_IMAGES");
 define ('PAWPS_TABLENAME_CUSTOMERS', $wpdb->prefix . "PAWPS_CUSTOMERS");
 
 // Standardwerte
 define ('PAWPS_USERTPL_START', 'eigeneVorlage-');
 
 // Umgebungsinfo
 define ('PAWPS_BASE_URL', 'http://localhost:8080/Portrait-Archiv/');
 define ('PAWPS_IMAGE_BASE_URL', 'http://images.portrait-archiv.com');
 
?>