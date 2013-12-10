<?php 
 
 function pawps_admin_menu() {
	add_menu_page(
		'Portrait-Archiv.com Adminbereich',
		'Portrait-Archiv.com',
		'manage_options',
		'pawps_admin_menu_mainpage',
		'pawps_admin_menu_mainpage',
		plugins_url( 'portrait-archiv-shop/resources/logo.png' ) );
	
	add_submenu_page(
		'pawps_admin_menu_mainpage',
		'Portrait-Archiv.com Anleitung',
		'Anleitung',
		'manage_options',
		'pawps_admin_menu_anleitung',
		'pawps_admin_menu_anleitung');
	
	
	add_submenu_page(
		'pawps_admin_menu_mainpage',
		'Portrait-Archiv.com Grundeinstellungen',
		'Grundeinstellungen',
		'manage_options',
		'pawps_admin_menu_grundeinstellungen',
		'pawps_admin_menu_grundeinstellungen');
	
	add_submenu_page(
		'pawps_admin_menu_mainpage',
		'Übersicht der Galerien',
		'Galerien',
		'manage_options',
		'pawps_admin_menu_shootings',
		'pawps_admin_menu_shootings');
		
	add_submenu_page(
		'pawps_admin_menu_mainpage',
		'Übersicht der Bestellungen',
		'Bestellungen',
		'manage_options',
		'pawps_admin_menu_orders',
		'pawps_admin_menu_orders');
	
// 	add_submenu_page(
// 		'pawps_admin_menu_mainpage',
// 		'Debug / Protokoll',
// 		'Debug',
// 		'manage_options',
// 		'pawps_admin_menu_debug',
// 		'pawps_admin_menu_debug');
 }

 function pawps_showAdminHeader($title = null) {
 	?>
 	 		<div class="wrap">
 		 		<h2><img src="<?php echo plugins_url( 'portrait-archiv-shop/resources/logo.png' ); ?>" /> Portrait-Archiv.com<?php if (isset($title)) echo " > " . $title; ?></h2>
 		 	</div>
 	<?php 	
 }
 
 function pawps_admin_menu_mainpage() {
 	pawps_showAdminHeader();
 	 
 	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'Ihre aktuelle Berechtigung verhindert den Zugriff auf diese Seite' ) );
	}
	
	?>
	
	<div class="wrap">
		<h3>Was ist Portrait-Archiv.com</h3>
		Das Portrait-Archiv ist ein Angebot der Firma <a href="http://www.Portrait-Service.com" target="_blank">Portrait-Service</a>.
		Mit Hilfe von Portrait-Archiv.com haben Sie die Möglichkeit, Ihre Bilder einfach und unkompliziert online zum Verkauf 
		anzubieten. Hierbei genügt es Ihre Bilder in Ihrem Online Archiv zu veröffentlichen und die gewünschten Verkaufspreise 
		der einzelnen Artikel anzugeben. Egal ob Photoabzug, Poster, digitaler Download ... fast alles ist möglich. Die gesamte
		Abwicklung, von der Bestellannahme über den Zahlungseingang bis zur Produktion und Auslieferung übernimmt hierbei 
		Portrait-Archiv.com für Sie. Pünktlich zum Monatsanfang erhalten Sie Ihre detaillierte Provisionsabrechnung und die 
		Überweisung der angefallenen Provisionen auf Ihr angegebenes Konto. <br/><br/>
		
		Weitere Details zu Portrait-Archiv.com finden Sie unter den nachfolgenden Links:
		<ul>
			<li><a href="http://www.portrait-service.com/portrait-archiv/funktionsweise/?REF=pawps" target="_blank">die Funktionsweise von Portrait-Archiv.com</a></li>
			<li><a href="http://www.portrait-service.com/portrait-archiv/hochste-qualitat-in-der-produktion-ihrer-kundenbestellungen/?REF=pawps" target="_blank">Laborpartner</a></li>
			<li><a href="http://www.portrait-service.com/portrait-archiv/berechnen-sie-ihre-individuelle-umsatzsteigerung/?REF=pawps" target="_blank">Steigern Sie Ihren Umsatz durch Ihr Online-Portal</a></li>
			<li><a href="http://www.portrait-service.com/portrait-archiv/unsere-antworten-auf-ihre-fragen/?REF=pawps" target="_blank">FAQ - Sie Fragen, wir Antworten</a></li>
			<li><a href="http://www.portrait-service.com/portrait-archiv/eroffnen-sie-ihr-online-portrait-studio/?REF=pawps" target="_blank">Eröffnen Sie Ihr eigenes Portrait-Archiv</a></li>
		</ul> 
				
		<br/>
		<h3>Funktionsweise</h3>
		<img src="http://www.portrait-service.com/wp-content/uploads/So-funktioniert%C2%B4s_dunkel-1024x669.jpg" />		
 	</div>
	
	<?php 
 }
 
 function pawps_admin_menu_anleitung() {
 	pawps_showAdminHeader("Anleitung");
 
 	if ( !current_user_can( 'manage_options' ) )  {
 		wp_die( __( 'Ihre aktuelle Berechtigung verhindert den Zugriff auf diese Seite' ) );
 	}
 	
 	?>
 		
 		<div class="wrap">
 			<h3>Systemeinrichtung</h3>
 			Damit das Shopmodul auf Ihre Galerien bei Portrait-Archiv.com zugreifen ist es notwendig dass Sie die 
 			Systemauthentfizierung durchführen. Führen Sie hierzu die folgenden Schritte durch:
 			<ol>
 				<li>Navigieren Sie innerhalb Ihres Wordpress-Modules zum Menüpunkt 'Portrait-Archiv.com > Grundeinstellungen'
 				und notieren Sie sich den Wert aus dem Feld 'Modul-Token'<br/>
 				<img src="<?php echo plugins_url( 'portrait-archiv-shop/resources/docu/pawps_einrichten_1.png' ); ?>" alt="Modul-Token im WP-Adminbereich" class="pawps_image" /></li> 				
 				<li>Loggen Sie sich mit Ihren Userdaten auf Portrait-Archiv.com ein. Klicken Sie auf dem Menüpunkt 
 				'Wordpress-Modul' im Bereich 'Integration' innerhalb Ihres Benutzermenüs.</li>
 				<li>Tragen Sie dort im Feld 'Token aus Modul' den im Punkt 1 notierten Token ein<br/>
 				<img src="<?php echo plugins_url( 'portrait-archiv-shop/resources/docu/pawps_einrichten_2.png' ); ?>" alt="Modul-Token im WP-Adminbereich" width="500px" class="pawps_image" /></li>
 				<li>Klicken Sie nun auf den Button 'Portrait-Archiv.com Hash-Key generieren'<br/>
 				<img src="<?php echo plugins_url( 'portrait-archiv-shop/resources/docu/pawps_einrichten_3.png' ); ?>" alt="Token auf Portrait-Archiv.com generieren" width="500px" class="pawps_image" /><br/>
 				</li>
 				<li>Notieren Sie sich nun die User-ID sowie den generierten 'Portrait-Archiv.com Token' und übertragen 
 				sie diese in die entsprechenden Felder in den 'Grundeinstellungen' Ihres Modules.<br/>
 				Speichern Sie die Änderungen durch einen Klick auf den Button 'Verbindungsdaten speichern'<br/>
 				<img src="<?php echo plugins_url( 'portrait-archiv-shop/resources/docu/pawps_einrichten_4.png' ); ?>" alt="Modulkonfiguration abschliessen" width="500px" class="pawps_image" /><br/>
 				Die Verbindung zwischen Portrait-Archiv.com und Ihrer Wordpress-Modulinstallation ist nun erfolgreich
 				hergestellt.</li> 
 			</ol>
 		
 	 		<h3>mögliche Template-Tags</h3>
 	 		Mit Hilfe des Portrait-Archiv.com Shopmodules haben Sie die Möglichkeit sowohl eine Liste von Galerien  
 	 		als auch einzelne Galerien in Ihre bestehenden Wordpress-Seiten einzubinden. Hierzu genügt es einen bestimmten Tag 
 	 		in Ihre Seite einzufügen. Das Modul ersetzt dieses Tag selbstständig gegen die entsprechenden Inhalte. <br/>
 	 		<br/>
 	 		Derzeit stehen folgende Template-Tags zur Verfügung: 
 	 		
 	 		<table class="wp-list-table widefat fixed pages" cellspacing="0">
 				<thead>
 					<tr>
 						<th scope='col' width="200px">Beispiel</th>
 						<th scope='col'>Beschreibung</th>
 						</tr>
 				</thead>
 				<tbody id="the-list">
 					<tr valign="top">
 						<td>[pawps_publicList]</td>
 						<td>
 							Fügt eine Liste aller Ihrer Gallieren ein, welche von Ihnen für die Anzeige in der 
 							öffentlichen Liste markiert wurden. Die Galerien werden mit dem Titel sowie einem zufälligen
 							Vorschaubild aus der Galerie dargestellt.  Nach dem Klick auf den Titel oder das Vorschaubild
 							gelangt Ihr Besucher, je nach Konfiguration, entweder zur Eingabe des von Ihnen hinterlegten 
 							Gästekennwortes oder direkt zur Galerie.
 						</td>
 					</tr>
 					<tr valign="top">
 						<td>[pawps_galerie]1[/pawps_galerie]</td>
 						<td>
 							Fügt eine einzelne Galerie zur Anzeige in Ihre Seite ein. Die ID der gewählte Galerie entspricht 
 							der in der Galerieübersicht angezeigten numerischen ID. Ersetzen Sie einfach die 1 aus dem Beispiel
 							gegen die gewünschte ID um die Galerie in Ihre Seite einzufügen. Der Besucher wird direkt beim Besuch 
 							der Seite auf die gewünschte Galerie bzw. zur Abfrage des hitnerlegten Gästekennwortes geleitet. 
 						</td>
 					</tr>
 					<tr valign="top">
 						<td>[pawps_password]</td>
 						<td>
 							Fügt den Dialog zur Eingabe eines Gästepasswortes ein. Bei Aufruf der Seite mit dem eingefügten 
 							Tag ist lediglich der Passwortdialog sichtbar. Nach Eingabe des individuell festgelegten Gästekennwortes 
 							gelangt der Besucher zur jeweiligen Galerie. 
 						</td>
 					</tr>
 				</tbody>
 			</table>
 			
 			<h3>weitere Hilfeartikel</h3>
 	 		<ul>
 	 			<li><a href="http://www.portrait-service.com/portrait-archiv/unsere-antworten-auf-ihre-fragen/wie-stelle-ich-die-verbindung-zwischen-wordpress-und-meinen-galerien-her/" target="_blank">Wie stelle ich die Verbindung zwischen WordPress und meinen Galerien her?</a></li>
 	 			<li><a href="http://www.portrait-service.com/portrait-archiv/unsere-antworten-auf-ihre-fragen/wie-kann-ich-meine-shootings-in-meinen-wordpress-blog-integrieren/" target="_blank">Wie kann ich meine Shootings in meinen WordPress-Blog integrieren?</a></li>
 	 			<li><a href="http://www.portrait-service.com/portrait-archiv/unsere-antworten-auf-ihre-fragen/wie-kann-ich-das-design-meines-wordpress-shops-aendern/" target="_blank">Wie kann ich das Design meines WordPress Shops ändern?</a></li>
 	 			<li><a href="http://www.portrait-service.com/portrait-archiv/unsere-antworten-auf-ihre-fragen/wie-kann-ich-den-anzeigemodus-einer-galerie-aendern/" target="_blank">Wie kann ich den Anzeigemodus einer Galerie ändern?</a></li>
 	 		</ul>
 	 	</div>
 		
 		<?php  	
 }

 function pawps_admin_menu_grundeinstellungen() {
	pawps_showAdminHeader("Grundeinstellungen");
 	 	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'Ihre aktuelle Berechtigung verhindert den Zugriff auf diese Seite' ) );
	}

	// alte Werte aus Options laden
	$paHash = get_option(PAWPS_OPTION_HASHKEY);
	$paHashRemote = get_option(PAWPS_OPTION_HASHKEY_REMOTE);
	$paUserId = get_option(PAWPS_OPTION_USERID);

	if (isset($_POST['grundeinstellungSubmit'])) {
		// Einstellungen übernehmen
		$paHashRemote = $_POST['PA_HASHKEY_REMOTE'];
		$paUserId = $_POST['PA_USERID'];
		
		update_option(PAWPS_OPTION_HASHKEY_REMOTE, $paHashRemote );
		update_option(PAWPS_OPTION_USERID, $paUserId);
		
		// Verbindung prüfen
		if (!isset($error) || (strlen($error) == 0)) {
			if (!pawps_isConnectionWorking()) {
				$error = "Die Verbindung konnte nicht hergestellt werden, bitte überprüfen Sie die hinterlegten Token";
			} else {
				// Datenbank leeren
				require_once plugin_dir_path ( __FILE__ ) .'/portraitarchiv-setup.php';
				pawps_clearDatabaseValues();
				pawps_refreshShootings();
				$message = "Die Verbindung wurde erfolgreich hergestellt und alle lokalen Veranstaltungsdaten entfernt";
			}
		}
	}	
	
	?>
 		<div class="wrap">
 			<h3>Portrait-Archiv.com Grundeinstellungen</h3>
 			<?php pawps_displayError($error, $message); ?>
 			<form name="verbindungsDatenForm" method="post" action="">
				<table class="wp-list-table widefat fixed pages" cellspacing="0">
					<tr>
						<td width="150px">User-ID:</td>
						<td><input type="text" name="PA_USERID" value="<?php echo $paUserId; ?>" size="10" maxlength="10" ></td>
					</tr>
					<tr>
						<td>Modul-Token:</td>
						<td>
							<input type="text" name="PA_HASHKEY" value="<?php echo $paHash; ?>" size="20" maxlength="20" readonly>
							<div style="font-size:10px"><b>Hinweis:</b> Dieser Token muss von Ihnen auf Portrait-Archiv.com in Ihrer Wordpress-Konfiguration hinterlegt werden</div>
						</td>
					</tr>
					<tr>
						<td>Portrait-Archiv.com Token:</td>
						<td>
							<input type="text" name="PA_HASHKEY_REMOTE" value="<?php echo $paHashRemote; ?>" size="20" maxlength="20" >
							<div style="font-size:10px"><b>Hinweis:</b> Dieser Token wird Ihnen nach Eingabe des Modul-Tokes auf Portrait-Archiv.com bereitgestellt und muss hier hinterlegt werden</div>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="grundeinstellungSubmit" class="button-primary" value="Verbindungsdaten speichern" />
				</p>
			
			</form>
 		</div> 	
 		
 	<?php 
 		if (pawps_isConnectionWorking()) {
			unset($error);
			unset($message);
			
			// alte Werte aus Options laden
			$displayRows = get_option(PAWPS_DISPLAY_ROWS);
			$displayCols = get_option(PAWPS_DISPLAY_COLS);
						
			if (isset($_POST['anzeigeeinstellungSubmit'])) {
				$displayRows = $_POST['PA_DISPLAY_ZEILEN'];
				$displayCols = $_POST['PA_DISPLAY_SPALTEN'];
				
				if	(!is_numeric($displayRows) || !is_numeric($displayCols) ||
					$displayRows < 1 || $displayCols < 1) {
					$error = "Eine Darstellung mit Werten kleiner 1 in den Feldern 'Zeilen' oder 'Spalten' wird nicht unterstützt.";
				} else {
					update_option(PAWPS_DISPLAY_ROWS, $displayRows);
					update_option(PAWPS_DISPLAY_COLS, $displayCols);
				}
				
				update_option(PAWPS_TEMPLATE_NAME, $_POST['pawps_template']);
				
				$message = "Ihre Änderungen wurden übernommen";
			}
			
			// Liste möglicher Templates
			$tmpBaseDir = plugin_dir_path( __FILE__ ) . "/templates";
			$handle =  opendir($tmpBaseDir);
			$verzeichnisliste = array();
			while ($datei = readdir($handle)) {
				if ($datei != "." && $datei != "..") {
					if (is_dir($tmpBaseDir . '/' . $datei)) {
						array_push($verzeichnisliste, $datei);
					}
				}
			}
			$currentTemplate = get_option(PAWPS_TEMPLATE_NAME);
	?>
	 		<div class="wrap">
	 			<h3>Anzeige Konfiguration</h3>
	 			<?php pawps_displayError($error, $message); ?>
	 			<form name="verbindungsDatenForm" method="post" action="">
					<table class="wp-list-table widefat fixed pages" cellspacing="0">
						<tr>
							<td width="150px">Galeriedarstellung:</td>
							<td>
								Zeilen: <input type="text" name="PA_DISPLAY_ZEILEN" value="<?php echo $displayRows; ?>" size="3" maxlength="3" >
								Spalten: <input type="text" name="PA_DISPLAY_SPALTEN" value="<?php echo $displayCols; ?>" size="3" maxlength="3" >
								<br/>
								<div style="font-size:10px"><b>Hinweis:</b> Legt fest wie viele Spalten und Zeilen an Bildern max. pro Galerieseite angezeigt werden sollen</div>
							</td>
						</tr>
						<tr>
							<td>Template:</td>
							<td>
								<select name="pawps_template">
									<?php
										foreach ($verzeichnisliste as $verzeichnis) {
											echo "<option";
											if ($verzeichnis == $currentTemplate) {
												echo " selected";
											}
											echo ">";
											echo $verzeichnis;
											echo "</option>";
										} 
									?>
								</select>
							</td>
						</tr>
					</table>
					<p class="submit">
						<input type="submit" name="anzeigeeinstellungSubmit" class="button-primary" value="Anzeigekonfiguration speichern" />
					</p>
				
				</form>
	 		</div> 	
 	<?php
 		} 		
 }
 
 function pawps_admin_menu_debug() {
 	pawps_showAdminHeader("Debug / Protokoll");

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'Ihre aktuelle Berechtigung verhindert den Zugriff auf diese Seite' ) );
	}

	$paDebugMode = get_option(PAWPS_DEBUG);
 			
	if (isset($_POST['pawps_developmentDataSubmit'])) {
		$paDebugMode = $_POST['PA_DEBUGMODE'];
		update_option(PAWPS_DEBUG, $paDebugMode);
			
		$message = "Ihre Änderungen wurden übernommen";
	}
		
 	?>
 		<div class="wrap">
			<h3>Portrait-Archiv.com Entwicklungseinstellung</h3>
			<?php pawps_displayError($error, $message); ?>
			<form name="configDataForm" method="post" action="">
				<table class="wp-list-table widefat fixed pages" cellspacing="0">
					<tr>
						<td width="150px">Debug-Mode:</td>
						<td>
							<select name="PA_DEBUGMODE">
								<option value="0"<?php if ($paDebugMode == 0) echo " selected"; ?>>Inaktiv - keine Debugausgaben</option>
								<option value="1"<?php if ($paDebugMode == 1) echo " selected"; ?>>Aktiv - Debugausgaben - NUR NACH ANWEISUNG nutzen</option>
							</select><br/>
							<div style="font-size:10px"><b>Hinweis:</b> Im Debug-Modus werden diverse Kommunikationsinformationen direkt im HTML ausgegeben.<br/>
							Bitte verwenden Sie diese Information nur nach vorheriger Aufforderung und in keinem Fall im Praxiseinsatz !</div>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="pawps_developmentDataSubmit" class="button-primary" value="Entwicklungseinstellungen speichern" />
				</p>
			
			</form>
		</div>
 	<?php 
 }
 
 function pawps_admin_menu_orders() {
 	pawps_showAdminHeader("Bestellungen");

 	if ( !current_user_can( 'manage_options' ) )  {
 		wp_die( __( 'Ihre aktuelle Berechtigung verhindert den Zugriff auf diese Seite' ) );
 	}
 	
 	if (!pawps_isConnectionWorking()) {
 		wp_die( __( 'Bitte konfigurieren Sie zunächst die Verbindung im Menü >Grundeinstellung<' ) );
 	}
 	
 	// Errors anzeigen
 	pawps_displayError($error, $message);
 	
 	$orders = pawps_getOrderList();
 	
 	if (count($orders) == 0) {
		?>
	 		- Bisher konnte kein Bestelleingang verbucht werden -
	 	<?php 
	} else {
		?>
	 	<div class="wrap">
	 		Bislang konnten folgende Bestellungen über das Modul verbucht werden:<br/>
	 		
			<table class="wp-list-table widefat fixed pages" cellspacing="0">
				<thead>
					<tr>
						<th scope='col'>Bestellnummer</th>
						<th scope='col'>Datum</th>
						<th scope='col'>Kunde</th>
						<th scope='col'>Positionen</th>
						<th scope='col'>Betrag</th>
					</tr>
				</thead>
				<?php 
					foreach ($orders as $order) {
						?>
							<tr>
								<td><?php 
										if (isset($order->orderId)) {
											echo $order->orderId;
										} else {
											echo "nicht ermittelbar";
										}
									?>
								</td>
								<td><?php echo $order->dateOrdered; ?></td>
								<td>
									<?php 
										echo $order->getCustomer()->name . ", " . $order->getCustomer()->firstname . "<br/>"; 
										echo $order->getCustomer()->street . "<br/>";
										echo $order->getCustomer()->plz . ", " . $order->getCustomer()->city . "<br/>";
										echo $order->getCustomer()->country;
									?>
								</td>
								<td><?php echo $order->orderAnzahlPositionen; ?></td>
								<td><?php echo $order->orderGesamtpreis; ?></td>
							</tr>
						<?php 
					}
				?>
			</table>
			<u>Hinweis:</u><br/>
			Bestellungen mit der Bestellnummer 'nicht ermittelbar' konnten im Bestellprozess nicht automatisiert 
			übertragen werden. Stattdessen wurden diese per Mail an Portrait-Archiv.com übersendet und manuell 
			im System erfasst. Die Bestellungen konnen online innerhalb Ihres Portrait-Archiv.com Accounts eingesehen werden.
		</div>
	 	<?php
	}
 }
 
 function pawps_admin_menu_shootings() {
	pawps_showAdminHeader("Galerien"); 
 		
 	if ( !current_user_can( 'manage_options' ) )  {
 		wp_die( __( 'Ihre aktuelle Berechtigung verhindert den Zugriff auf diese Seite' ) );
 	}

 	if (!pawps_isConnectionWorking()) {
		wp_die( __( 'Bitte konfigurieren Sie zunächst die Verbindung im Menü >Grundeinstellung<' ) );
	}

	// Errors anzeigen
	pawps_displayError($error, $message); 

	// Entscheiden was anzuzeigen ist
	if (isset($_GET['showShootingImages']) && is_numeric($_GET['showShootingImages'])) {
		pawps_admin_content_shootingImages($_GET['showShootingImages']);
	} else if (isset($_GET['showShootingPricelist']) && is_numeric($_GET['showShootingPricelist'])) {
		pawps_admin_content_shootingPricelist($_GET['showShootingPricelist']);
	} else if (isset($_GET['editShootingConfig']) && is_numeric($_GET['editShootingConfig'])) {
		pawps_admin_content_shootingEditConfig($_GET['editShootingConfig']);		
	} else {
		pawps_admin_content_shootingList();
	}	
 }
 
 function pawps_admin_content_shootingEditConfig($shootingId) {
 	$shooting = pawps_loadShootingByData($shootingId);
 
 	if (!isset($shooting)) {
 		$error = "Das gewünschte Shooting konnte nicht geladen werden";
 		pawps_admin_content_shootingList();
 		exit();
 	}
 	
 	// Anzeigen
 	$config = pawps_loadShootingConfig(null, $shooting->accesscode);
 	
 	if (!isset($config)) {
		// Keine Config vorhanden -> lege leere an
		global $wpdb;
		
		$wpdb->insert(
			PAWPS_TABLENAME_CONFIG,
			array(
					'shootingcode' => $shooting->accesscode
			));

		$config = pawps_loadShootingConfig(null, $shooting->accesscode);
	}
	
	if (!isset($config)) {
		$error = "Beim Laden der gewünschten Informationen ist ein Fehler aufgetreten.";
		pawps_admin_content_shootingList();
		exit();
	}
	
	if (isset($_POST['shootingConfigSpeichern'])) {
		// geÃƒÂ¤nderte Konfigurationswerte übernehmen und persistieren
		global $wpdb;
		
		$pwd = "";
		if (isset($_POST['PA_SHOOTINGTYPE']) && 
			(($_POST['PA_SHOOTINGTYPE'] == "1") || ($_POST['PA_SHOOTINGTYPE'] == "3"))) {
			// Gästepasswort validieren
			if (strlen($_POST['PA_GUESTPASSWORD']) == 0) {
				$error = "Bei dem von Ihnen gewählten Shooting-Typ ist die Angabe eines Gästepasswortes zwingend erforderlich";
			} else {
				// Prüfe ob Passwort eindeutig
				$sql = "SELECT COUNT(*) FROM " . PAWPS_TABLENAME_CONFIG . " WHERE ";
				$sql .= "id<>" . $config->id;
				$sql .= " AND guestpassword='" . $_POST['PA_GUESTPASSWORD'] . "'";
				$count = $wpdb->get_var( $sql );
				if ($count > 0) {
					$error = "Das von Ihnen gewählte Gästepasswort ist bereits vergeben.";
				} else {
					$pwd = $_POST['PA_GUESTPASSWORD'];
				}
			}
		}
		
		if (!isset($error) && (strlen($error) < 1)) {
			// änderungen persistieren
			// Existiert -> aktualisieren
			$wpdb->update(
					PAWPS_TABLENAME_CONFIG,
					array(
							'state' => $_POST['PA_SHOOTINGTYPE'],
							'guestpassword' => $pwd
					),
					array(
							'id' => $config->id
					));

			$message = "Ihre Änderungen wurden gespeichert";
		}
		
		$config = pawps_loadShootingConfig($config->id);
	}
	
	// Config anzeigen ...
	?>
	<div class="wrap">
		<h3>Folgende Konfiguration wurde derzeit für das Shooting '<?php echo $shooting->title; ?>' hinterlegt</h3>
		
		<h4>Basisinformationen</h4>
		<table class="wp-list-table widefat fixed pages" cellspacing="0">
			<tr>
				<td width="150px">Titel:</td>
				<td>
					<?php echo $shooting->title; ?>
				</td>
			</tr>
			<tr>
				<td>Datum:</td>
				<td><?php echo $shooting->shootingdate; ?></td>
			</tr>
			<tr>
				<td>Anzahl Bilder:</td>
				<td><?php echo $shooting->pricelist_id; ?></td>
			</tr>
			<tr>
				<td>Preisliste:</td>
				<td><?php echo $shooting->getPricelist()->title; ?></td>
			</tr>
		</table>
		
		<h4>Konfiguration</h4>
		<form name="shootingConfigForm" method="post" action="">
			<?php pawps_displayError($error, $message); ?>
			<table class="wp-list-table widefat fixed pages" cellspacing="0">
				<tr>
					<td width="150px">Shooting-Typ:</td>
					<td>
						<select name="PA_SHOOTINGTYPE">
							<option value="0" <?php if ($config->state == 0) echo 'selected';?>>inaktiv - Shooting ist auf dieser Seite nicht verfügbar</option>
							<option value="1" <?php if ($config->state == 1) echo 'selected';?>>Verfügbar durch Eingabe des Gästepasswortes</option>
							<option value="4" <?php if ($config->state == 4) echo 'selected';?>>Verfügbar ohne Gästepasswortes</option>
							<option value="2" <?php if ($config->state == 2) echo 'selected';?>>Darstellung in öffentlicher Liste ohne Passwort</option>
							<option value="3" <?php if ($config->state == 3) echo 'selected';?>>Darstellung in öffentlicher Liste mit Gästepasswort</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Gästepasswort:</td>
					<td><input type="text" name="PA_GUESTPASSWORD" value="<?php echo $config->getPassword(); ?>" size="25" maxlength="25" ></td>
				</tr>
			</table>
			
			<p class="submit">
				<input type="submit" name="shootingConfigSpeichern" class="button-primary" value="Konfiguration speichern" />
			</p>
		</form>
	</div>
	<?php 
 }
 
 function pawps_admin_content_shootingImages($shootingId) {
 	$shooting = pawps_loadShootingByData($shootingId);
 	
 	if (!isset($shooting)) {
		$error = "Das gewünschte Shooting konnte nicht geladen werden";
		pawps_admin_content_shootingList();
		exit();
	}
	
	// Shooting geladen -> anzeigen
	$displayRows = get_option(PAWPS_DISPLAY_ROWS);
	$displayCols = get_option(PAWPS_DISPLAY_COLS);
	
	$currentStartIndex = 0;
	if (isset($_GET['pStart']) && is_numeric($_GET['pStart'])) {
		$currentStartIndex = $_GET['pStart'];
	}
	$imagesPerPage = $displayCols * $displayRows;
	$requestedEndIndex = $currentStartIndex + $imagesPerPage;
	
	$images = $shooting->getImagePage($currentStartIndex, $requestedEndIndex);
	
	?>
	<div class="wrap">
		<h3>Das Shooting '<?php echo $shooting->title; ?>' verfügt derzeit über <?php echo $shooting->imageCount; ?> Bilder</h3>
		<table width="95%" border="0" align="center">
			<?php
				echo "<tr>";
				// Next und Prev Pfeile setzen
				if ($displayCols % 2 == 0) {
					$needCenter = false;
					$colspan = $displayCols % 2;
				} else {
					$needCenter = true;
					$colspan = ($displayCols - 1) % 2;
				}
				
				echo "<td align='left' colspan='" . $colspan . "'>";
				if ($currentStartIndex > 0) {
					// Pfeil anzeigen
					echo "<a href='" . add_query_arg ('pStart', $currentStartIndex - $imagesPerPage) . "' title='vorherige Seite'><img src='" . plugins_url( 'resources/previous.png' , __FILE__ ) . "' border='0' height='30px' width='30px' /></a>";
				} else {
					echo "&nbsp;";
				}
				echo "</td>";
				
				if ($needCenter) {
					echo "<td>&nbsp;</td>";
				}
				
				echo "<td align='right' colspan='" . $colspan . "'>";
				if ($requestedEndIndex < $shooting->imageCount) {
					// Pfeil anzeigen
					echo "<a href='" . add_query_arg ('pStart', $requestedEndIndex) . "' title='nächste Seite'><img src='" . plugins_url( 'resources/next.png' , __FILE__ ) . "' border='0' height='30px' width='30px' /></a>";
				} else {
					echo "&nbsp;";
				}
				echo "</td>";				
			
				$currentPos = 0;
			 	foreach ($images as $image) {
					if ($currentPos % $displayCols == 0) {
						if ($isOpened) {
							echo "</tr>";
						}
						echo "<tr>";
						$isOpened = true;
					}
					?>
						<td align="center">
							<img src="<?php echo $image->getThumbUrl(); ?>" />
						</td>
				<?php
					$currentPos ++;
				}
				if ($isOpened) {
					echo "</tr>";
				}
			?>
		</table>
	</div>
	<?php
 } 

 function pawps_admin_content_shootingPricelist($shootingId) {
 	$shooting = pawps_loadShootingByData($shootingId);
 	
 	if (!isset($shooting)) {
		$error = "Das gewünschte Shooting konnte nicht geladen werden";
		pawps_admin_content_shootingList();
		exit();
	}
	
	// Shooting geladen -> anzeigen
	?>
	<div class="wrap">
		<h3>'<?php echo $shooting->title; ?>' wird mit folgenden Produkten angeboten</h3>
	<?php

		$pricelist = $shooting->getPricelist();
		
		if (isset($pricelist)) {
			echo "Preisliste: " . $pricelist->title . "<br/>";

			?>
				<table class="wp-list-table widefat fixed pages" cellspacing="0">
					<thead>
						<tr>
							<th scope='col'>Titel</th>
							<th scope='col'>Verkaufspreis</th>
						</tr>
					</thead>
			<?php 
			foreach ($pricelist->getProducts() as $product) {
				if (count($product->getPrices()) == 1) {
					echo "<tr><td>" . $product->title . "</td><td>" . $product->getPrice(1) . " EUR</td></tr>";
				} else {
					echo "<tr><td colspan='2'>" . $product->title . "</td></tr>";
					foreach ($product->getPrices() as $price) {
						echo "<tr><td>&nbsp;</td><td>ab " . $price->getAb() . " = " . $price->getPrice() . " EUR</td></tr>";
					}
				}
			}
			
			echo "</table>";

		} else {
			?>
				- die Preisliste konnte leider nicht aufgelöst werden -
			<?php 
		}
		
	?>
	</div>
	<?php 
 }

 function pawps_admin_content_shootingList() {
	// führe direkten Refresh durch	
	if (isset($_POST['manuellerDatenrefreshDurchfuehren'])) {
		update_option(PAWPS_LAST_UPDATE_SHOOTINGS, 0);
		pawps_refreshShootings();
	}
	
	// loesche Datenbankinhalte
	if (isset($_POST['manuelleDatenbereinigungDurchfuehren'])) {
		require_once plugin_dir_path( __FILE__ ) .'/portraitarchiv-setup.php';
		pawps_clearDatabaseValues();
	}
	
	?>
		<div class="wrap">
	  		<form name="refreshForm" method="post" action="">
	 			<?php 
	 				$lastLocaleUpdate = get_option(PAWPS_LAST_UPDATE_SHOOTINGS);	 	
	 				if (strlen($lastLocaleUpdate) > 5) {
						$lastLocaleUpdateString = date('d.m.Y H:i:s', $lastLocaleUpdate);
					} else {
						unset ($lastLocaleUpdateString);
					}
					
					if (isset($lastLocaleUpdateString)) {
						// Aktualisierung wurde bereits durchgefÃ¯Â¿Â½hrt
						?> Die letzte Datenaktualisierung wurde am <?php echo $lastLocaleUpdateString; ?> durchgeführt. <?php 
					} else {
						// noch keine Aktualisierung durchgefÃƒÂ¼hrt
						?> Ihre Daten wurden bisher noch nicht aktualisiert / geladen. Bitte füren Sie eine manuelle Datenaktualisierung durch. <?php
					}				
	 			?>
					Zur manuellen Datenaktualisierung genügt ein Klick auf den folgenden Button:					
					<p class="submit">
						<input type="submit" name="manuellerDatenrefreshDurchfuehren" class="button-primary" value="<?php esc_attr_e('Refresh') ?>" />
					</p>
			</form>
			
			<h3>Aktuelle Galerien</h3>
			<?php 
				$aktuelleShootings = pawps_getShootingList();
				if (isset($aktuelleShootings) && (count($aktuelleShootings) > 0)) {
					// Shootings vorhanden
					?>
						<table class="wp-list-table widefat fixed pages" cellspacing="0">
							<thead>
								<tr>
									<th scope='col' width='50px'>&nbsp;</th>
									<th scope='col' width='50px'>ID</th>
									<th scope='col'>Titel</th>
									<th scope='col' width='150px'>Datum</th>
									<th scope='col' width='150px'>Anzahl Bilder</th>
									<th scope='col' width='50px'>Status</th>
								</tr>
							</thead>
							<tbody id="the-list">
								<?php 
									foreach ($aktuelleShootings as $shooting) {
										echo "<tr valign=\"top\">";
											echo "<td align='center'>";
											echo "<a href='" . add_query_arg ('showShootingImages', $shooting->id) . "' title='Bilder der Galerie anzeigen'><img src='" . plugins_url( 'resources/eye.png' , __FILE__ ) . "' border='0' height='15px' width='15px' /></a>";
											echo "<a href='" . add_query_arg ('showShootingPricelist', $shooting->id) . "' title='Preisliste der Galerie anzeigen'><img src='" . plugins_url( 'resources/euro.png' , __FILE__ ) . "' border='0' height='15px' width='15px' style='padding-left: 5px'/></a>";
											echo "</td>";
											echo "<td>";
											echo $shooting->id;
											echo "</td>";
											echo "<td>";
											echo $shooting->title;
											echo "</td>";
											echo "<td>" . date('d.m.Y', strtotime($shooting->shootingdate)) . "</td>";
											echo "<td>" . $shooting->imageCount . "</td>";
											echo "<td align='center'>";
												echo "<a href='" . add_query_arg ('editShootingConfig', $shooting->id) . "'>";
												if ($shooting->getIntegrationState() == 0) {
													echo "<img src='" . plugins_url( 'resources/x_alt.png' , __FILE__ ) . "' border='0' height='15px' width='15px' title='nicht angeboten' />";
												} else if (($shooting->getIntegrationState() == 2) || ($shooting->getIntegrationState() == 4)) {
													echo "<img src='" . plugins_url( 'resources/unlock_stroke.png' , __FILE__ ) . "' border='0' height='15px' width='15px' title='oeffentlich, ohne Passwort' />";
												} else {
													echo "<img src='" . plugins_url( 'resources/unlock_fill.png' , __FILE__ ) . "' border='0' height='15px' width='15px' title='mit Gaestepasswort' />";
												}
												echo "</a>";
											echo "</td>";
											echo "</tr>";
									}
								?>
							</tbody>
						</table>
						
						<h4>Legende</h4>
						<ul>
							<li><img src='<?php echo plugins_url( 'resources/x_alt.png' , __FILE__ ); ?>' border='0' height='15px' width='15px' title='nicht angeboten' /> - Veranstaltung nicht öffentlich verfügbar</li>
							<li><img src='<?php echo plugins_url( 'resources/unlock_stroke.png' , __FILE__ ); ?>' border='0' height='15px' width='15px' title='öffentlich ohne Kennwort' /> - Veranstaltung öffentlich verfügbar - ohne Gästekennwort</li>
							<li><img src='<?php echo plugins_url( 'resources/unlock_fill.png' , __FILE__ ); ?>' border='0' height='15px' width='15px' title='nur mit Kennwort' /> - Veranstaltung verfügbar - nur mit Gästekennwort</li>
						</ul>
						
					<?php 
				} else {
					// keine Shootings vorhanden
					?>- Bisher sind keine Galerien für Sie hinterlegt - <?php 
				}
			
			?>
			
			<?php 
				if (isset($lastLocaleUpdateString)) {
			?>
					<h3>manuelle Datenbereinigung</h3>
					<form name="cleanupForm" method="post" action="">
						Zur manuellen Bereinitung der Datenbank klicken Sie bitte auf den folgenden Button:
						<p class="submit">
							<input type="submit" name="manuelleDatenbereinigungDurchfuehren" class="button-primary" value="<?php esc_attr_e('Clean') ?>" />
						</p>
					</form>					
			<?php 
				}			
			?>
		</div>
		<?php 
  }
?>