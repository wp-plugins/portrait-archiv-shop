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
					<table border="0">
						<tr valign="top">
							<td colspan="2"><a href='<?php echo add_query_arg ('pawps_showWarenkorb', 1); ?>' title='Warenkorb anzeigen'>Warenkorb</a><br/></td>
						</tr>
						<tr>
							<td>Artikel:</td>
							<td><?php echo $warenkorb->getAnzahl(); ?></td>
						</tr>
						<tr>
							<td>Summe:</td>
							<td><?php echo $warenkorb->getZwischensumme(); ?> EUR</td>
						</tr>
					</table>
					<?php
				} else {
					echo "&nbsp;";
				}			
			?>
		</td>
	</tr>
</table>

<?php pawps_displayError($error, $message); ?>