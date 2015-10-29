<div class="row">
	<div class="col-xs-4 text-left">
		<?php
			if (isset($pageTitle)) {
				echo "<h2>" . $pageTitle . "</h2>";
			} else {
				echo "&nbsp;";
			}
		?>
	</div>
	<div class="col-xs-4 text-center">
		<?php
			if (isset($_SESSION['PAWPS_LOGIN'])) {
				?>
					<a href='<?php echo remove_query_arg(array( 'pawps_showWarenkorb', 'pawps_shooting', 'pDetails', 'pawps_ordner'), add_query_arg ('pawps_galerieReset', 1)); ?>' title='Galerie Logout'>Galerie Reset</a>
				<?php
			} else {
				echo "&nbsp;";
			}
		?>
	</div>
	<div class="col-xs-4 text-right">
			<?php
				if (isset($warenkorb) && ($warenkorb->getAnzahl() > 0)) {
					?>
					<div class="row text-center">
						<a href='<?php echo remove_query_arg('pawps_galerieReset', add_query_arg ('pawps_showWarenkorb', 1)); ?>' title='Warenkorb anzeigen'><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Warenkorb</a>
					</div>
					<div class="row">
						<div class="col-xs-8">Artikel:</div>
						<div class="col-xs-4 text-left"><?php echo $warenkorb->getAnzahl(); ?></div>
					</div>
					<div class="row">
						<div class="col-xs-8">Summe:</div>
						<div class="col-xs-4 text-left"><?php echo $warenkorb->getZwischensumme(); ?> EUR</div>
					</div>
					<?php
				} else {
					echo "&nbsp;";
				}			
			?>
	</div>
</div>

<?php 
	if (isset($error) && (strlen($error) > 0)) {
		?>
		<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
		<?php
	} 
	
	if (isset($message) && (strlen($message) > 0)) {
		?>
		<div class="alert alert-success" role="alert"><?php echo $message; ?></div>
		<?php 
	}
?>