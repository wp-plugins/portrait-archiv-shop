<table class="pawps_table">
	<tr valign="top">
		<td>
			Rechnungsadresse
		</td>
		<td>
			Versandadresse
		</td>
	</tr>
	<tr>
		<td>
			<?php
				echo $warenkorb->getCustomer()->name . ", " . $warenkorb->getCustomer()->firstname . "<br/>"; 
				echo $warenkorb->getCustomer()->street . "<br/>";
				echo $warenkorb->getCustomer()->plz . " " . $warenkorb->getCustomer()->city . "<br/>";
				echo $warenkorb->getCustomer()->country . "<br/><br/>";
				echo "E-Mail: " .  $warenkorb->getCustomer()->email;
			?>
		</td>
		<td>
			<?php if (isset($warenkorb->getCustomer()->ver_name)) {
				echo $warenkorb->getCustomer()->ver_name . ", " . $warenkorb->getCustomer()->ver_firstname . "<br/>"; 
				echo $warenkorb->getCustomer()->ver_street . "<br/>";
				echo $warenkorb->getCustomer()->ver_plz . " " . $warenkorb->getCustomer()->ver_city . "<br/>";
				echo $warenkorb->getCustomer()->ver_country . "<br/>";
			} else { ?>
				gleich Rechnungsadresse
			<?php } ?>
		</td>
	</tr>
</table>

<?php
	$displayOnly = true;
	require pawps_getTemplatePath ('warenkorbList');
?>

<form name="warenkorbSubmitForm" method="post" action="">
	<div class="pageSubTopic">Zahlungsweise</div>
	<input type="radio" name="zahlungsweise" value="1" checked /> Vorkasse<br/>
	<input type="radio" name="zahlungsweise" value="3" /> PayPal (zzgl. Zahlungsystemgebühr)<br/>
	
	<div class="pageSubTopic">Allgemeine Geschäfts- und Lieferbedingungen</div>
	<textarea name="agb" cols="60" rows="3" readonly="readonly"><?php echo $agbText; ?></textarea><br/>
	<input type="checkbox" name="acceptAgb" value="true" class="pageBackground">Ich akzeptiere Ihre allgemeinen Geschäfts- und Lieferbedingungen<br/><br/>

	<div class="pageSubTopic">Datenschutzbestimmungen</div>
	<textarea name="datenschutz" cols="60" rows="3" readonly="readonly"><?php echo $datenschutzText; ?></textarea><br/>
	<input type="checkbox" name="acceptDatenschutz" value="true" class="pageBackground">Ich akzeptiere Ihre Datenschutzbestimmungen<br/><br/>
	
	<input type="submit" name="doBestellungAbsenden" class="button-primary" value="Bestellung aufgeben" />
</form>