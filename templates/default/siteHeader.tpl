<table class="pawps_table">
	<tr>
		<td align="left">
			<?php
				if (isset($pageTitle)) {
					echo "<h3>" . $pageTitle . "</h3>";
				} else {
					echo "&nbsp;";
				}
			?>
		</td>
		<td align="right">
			<?php
				if (isset($warenkorb) && ($warenkorb->getAnzahl() > 0)) {
					?>
						<a href='<?php echo add_query_arg ('pawps_showWarenkorb', 1); ?>' title='Warenkorb anzeigen'>Warenkorb</a><br/>
					<?php
					echo $warenkorb->getAnzahl() . " Artikel<br/>";
					echo $warenkorb->getZwischensumme() . " EUR<br/>";
				} else {
					echo "&nbsp;";
				}
			?>
		</td>
	</tr>
</table>

<?php pawps_displayError($error, $message); ?>