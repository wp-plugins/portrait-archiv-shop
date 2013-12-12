<?php 

	if ( !defined('WP_UNINSTALL_PLUGIN') ) {
		exit();
	}

	require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-configure.php';

	// Options entfernen
 	delete_option(PAWPS_OPTION_HASHKEY);
 	delete_option(PAWPS_OPTION_HASHKEY_REMOTE);
 	delete_option(PAWPS_OPTION_USERID);
 	delete_option(PAWPS_LAST_UPDATE_SHOOTINGS);
 	delete_option(PAWPS_DEBUG);
 	
 	// Alte Datenbank entfernen
 	require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-setup.php';
 	
 	pawps_cleanupDatabase();
 	
 	// Schedule deaktivieren
 	wp_clear_scheduled_hook('pawps_refresh_daily_hook'); 	
 	
?>