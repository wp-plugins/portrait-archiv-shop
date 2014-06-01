<?php if (!isset($displayOnly) || !$displayOnly) { ?>
	<form name="warenkorbForm" method="post" action="<?php echo add_query_arg (array('removePosition'=> null, 'removePositionImage' => null)); ?>">
<?php } ?>
			<table class="pawps_table">
				<tr>
					<td>&nbsp;</td>
					<td>Artikel</td>
					<td>Anzahl</td>
					<td>Einzelpreis</td>
					<td>Gesamtpreis</td>
					<?php if (!isset($displayOnly) || !$displayOnly) { ?>
						<td>&nbsp;</td>
					<?php } ?>
				</tr>
	<?php 
	
	foreach ($warenkorb->positionen as $position) {
		foreach ($position->images as $image) {
			if ($image->count > 0) {
				?>
					<tr>
						<td>
							<a href="<?php echo add_query_arg (array('pDetails'=> $image->imageId, 'pawps_showWarenkorb' => null)); ?>" title="Bild anzeigen"><img src='<?php echo $image->getImage()->getThumbUrl(); ?>' border='0' /></a>
						</td>
						<td><?php echo $position->getProduct()->title; ?></td>
						<td>
							<?php if (!isset($displayOnly) || !$displayOnly) { ?>
								<input type="text" name="warenkorbAnzahl[<?php echo $position->id; ?>][<?php echo $image->imageId; ?>]" value="<?php echo $image->count; ?>" size="2" maxlength="2" />
							<?php } else { 
								echo $image->count; 
							 } ?>
						</td>
						<td><?php echo $position->getEinzelpreis(); ?> </td>
						<td><?php echo $image->getPrice(); ?> </td>
						<?php if (!isset($displayOnly) || !$displayOnly) { ?>
							<td>
								<a href="<?php echo add_query_arg (array('removePosition'=> $position->productId, 'removePositionImage' => $image->imageId)); ?>" title="Artikel aus Warenkorb entfernen"><img src='<?php echo plugins_url( 'resources/basket_delete.png' , __FILE__ ); ?>' border='0' height='15px' width='15px' /></a>
							</td>
						<?php } ?>
					</tr>
				<?php 
			}
		}
	}
	
	?>
				<tr>
					<td colspan="4" align="right"><b>Zwischensumme:</b></td>				
					<td><?php echo $warenkorb->getZwischensumme(); ?></td>
					<?php if (!isset($displayOnly) || !$displayOnly) { ?>
						<td>&nbsp;</td>
					<?php } ?>						
				</tr>
				<tr>
					<td colspan="4" align="right"><b>zzgl. Versand:</b></td>				
					<td><?php echo $warenkorb->getVersandkosten(); ?></td>
					<?php if (!isset($displayOnly) || !$displayOnly) { ?>
						<td>&nbsp;</td>
					<?php } ?>						
				</tr>
				<tr>
					<td colspan="4" align="right"><b>Gesamtpreis:</b></td>				
					<td><?php echo $warenkorb->getGesamtpreis(); ?></td>
					<?php if (!isset($displayOnly) || !$displayOnly) { ?>
						<td>&nbsp;</td>
					<?php } ?>						
				</tr>
			</table>
			
			<?php if (!isset($displayOnly) || !$displayOnly) { ?>
				<input type="submit" name="doUpdateWarenkorb" class="button-primary" value="Warenkorb aktualisieren" />
				<input type="submit" name="doOrderWarenkorb" class="button-primary" value="Bestellung aufgeben" />
</form>
<?php } ?>