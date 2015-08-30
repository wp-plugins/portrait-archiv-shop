<div class="row">
	<div class="col-md-6">
		<h4>Rechnungsadresse</h4>
		<?php
			echo $warenkorb->getCustomer()->name . ", " . $warenkorb->getCustomer()->firstname . "<br/>"; 
			echo $warenkorb->getCustomer()->street . "<br/>";
			echo $warenkorb->getCustomer()->plz . " " . $warenkorb->getCustomer()->city . "<br/>";
			echo $warenkorb->getCustomer()->country . "<br/><br/>";
			echo "E-Mail: " .  $warenkorb->getCustomer()->email;
		?>
	</div>
	<div class="col-md-6">
		<h4>Versandadresse</h4>
		<?php if (isset($warenkorb->getCustomer()->ver_name)) {
				echo $warenkorb->getCustomer()->ver_name . ", " . $warenkorb->getCustomer()->ver_firstname . "<br/>"; 
				echo $warenkorb->getCustomer()->ver_street . "<br/>";
				echo $warenkorb->getCustomer()->ver_plz . " " . $warenkorb->getCustomer()->ver_city . "<br/>";
				echo $warenkorb->getCustomer()->ver_country . "<br/>";
			} else { ?>
				gleich Rechnungsadresse
			<?php } ?>
	</div>
</div>

<?php
	$displayOnly = true;
	require pawps_getTemplatePath ('warenkorbList');
?>

<form name="warenkorbSubmitForm" method="post" action="">
	<h4>Zahlungsweise</h4>
	<div class="radio">
	  <label>
	    <input type="radio" name="zahlungsweise" value="1" checked> Vorkasse
	  </label>
	</div>
	<div class="radio">
	  <label>
	    <input type="radio" name="zahlungsweise" value="3"> PayPal (zzgl. Zahlungsystemgebühr)
	  </label>
	</div>

	<h4>Allgemeine Geschäfts- und Lieferbedingungen</h4>
	<textarea name="agb" cols="60" rows="3" readonly="readonly"><?php echo $agbText; ?></textarea><br/>
	<input type="checkbox" name="acceptAgb" value="true" class="pageBackground">Ich akzeptiere Ihre allgemeinen Geschäfts- und Lieferbedingungen<br/><br/>

	<h4>Datenschutzbestimmungen</h4>
	<textarea name="datenschutz" cols="60" rows="3" readonly="readonly"><?php echo $datenschutzText; ?></textarea><br/>
	<input type="checkbox" name="acceptDatenschutz" value="true" class="pageBackground">Ich akzeptiere Ihre Datenschutzbestimmungen<br/><br/>
	
	<input type="submit" name="doBestellungAbsenden" class="btn btn-default" value="Bestellung aufgeben" />
</form>