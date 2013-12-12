<?php

/*
Plugin Name: Portrait-Archiv.com Photostore
Plugin URI: http://www.Portrait-Service.com/
Description: Der Portrait-Archiv.com Photostore stellt dem Benutzer die Moeglichkeit zur einfachen Integration eines Online Foto Nachbestellsystems zur Verfuegung
Version: 1.1.4
Author: Thomas Schiffler
Author URI: http://www.Portrait-Service.com/
*/

 // Session wird benötigt
 session_start();

 // weitere Referenzen einbinden
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-configure.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-classes.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-connector.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-functions.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-adminarea.php';
 require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-template-functions.php';

 // Initialisierung und Anlegen der Optionen
 function pawps_plugin_activate() {
 	if ( ! current_user_can( 'activate_plugins' ) )
 		return;
 	
 	add_option(PAWPS_OPTION_HASHKEY, pawps_generateLocalHash());
 	add_option(PAWPS_OPTION_HASHKEY_REMOTE);
 	add_option(PAWPS_OPTION_USERID);
 	add_option(PAWPS_LAST_UPDATE_SHOOTINGS);
 	add_option(PAWPS_DB_VERSION, 0);
 	add_option(PAWPS_DEBUG, 0);

 	// Anzeige-Konfiguration
 	add_option(PAWPS_TEMPLATE_NAME, 'default');
 	add_option(PAWPS_DISPLAY_ROWS, 3);
 	add_option(PAWPS_DISPLAY_COLS, 4);
 	
 	// Create Database
 	require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-setup.php';
 	pawps_setupDatabase();
 	
 	// Schedule Update
 	wp_schedule_event(time(), 'daily', 'pawps_refresh_daily_hook');
 }
 
 // Deaktivierung und Aufräumen
 function pawps_plugin_deactivate() {
 	if ( ! current_user_can( 'activate_plugins' ) )
 		return;
 	
 	// Schedule deaktivieren
 	wp_clear_scheduled_hook('pawps_refresh_daily_hook');
 }
 
  function pawps_insertContent() {
 	$pawps_content = get_the_content();
 	if (strlen($pawps_content) > 0) {		
 		
 		// Eventliste
 		if (strpos($pawps_content, "[pawps_publicList]") > -1) {
 			ob_start();
 			pawps_publicEventList();
 			$displayContent = ob_get_contents();
 			ob_end_clean();
 			
 			$pawps_content = str_replace("[pawps_publicList]", $displayContent, $pawps_content);
 		}
 		
 		// Einzelevent
 		if ((strpos($pawps_content, "[pawps_galerie]") > -1) && (strpos($pawps_content, "[/pawps_galerie]") > -1)) {
 			$shootingId = substr($pawps_content, strpos($pawps_content, "[pawps_galerie]") + strlen("[pawps_galerie]"));
 			$shootingId = substr($shootingId, 0, strpos($shootingId, "[/pawps_galerie]"));
 			if (is_numeric($shootingId)) {
 				ob_start();
 				pawps_showPublicEvent($shootingId);
 				$displayContent = ob_get_contents();
 				ob_end_clean();
 				
 				$pawps_content = str_replace("[pawps_galerie]" . $shootingId . "[/pawps_galerie]", 
 						$displayContent, $pawps_content);
 			}
 		}
 		
 		// einfache Passworteingabe
 		if (strpos($pawps_content, "[pawps_password]") > -1) {
 			ob_start();
 			pawps_shootingByCode();
 			$displayContent = ob_get_contents();
 			ob_end_clean();
 		
 			$pawps_content = str_replace("[pawps_password]", $displayContent, $pawps_content);
 		} 		
 	}

 	return $pawps_content;
 	
 }
 
 function pawps_register_stylesheet() {
 	wp_register_style('portrait-archiv-shop', plugins_url('portrait-archiv-shop/templates/' . get_option(PAWPS_TEMPLATE_NAME) . '/style.css'));
 	wp_enqueue_style('portrait-archiv-shop');
 }
 

 // Die aktivieren/deaktivieren Funktionen registrieren
 add_action('activate_' . plugin_basename(__FILE__),   'pawps_plugin_activate');
 add_action('deactivate_' . plugin_basename(__FILE__), 'pawps_plugin_deactivate');
 
 // eigentliche Funktionen registrieren einbauen
 add_action('wp_enqueue_scripts', 'pawps_register_stylesheet');
 add_action('admin_enqueue_scripts', 'pawps_register_stylesheet');
 add_filter('the_content', 'pawps_insertContent', 9);
 
 // Regelmäßigen Refresh aktivieren
 add_action('pawps_refresh_daily_hook', 'pawps_refresh_daily');

 // Menü hinzufügen
 add_action('admin_menu', 'pawps_admin_menu' );
 
?>