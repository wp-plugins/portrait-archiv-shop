 	<table width="450px" align="center">
		<tr>
			<td align="left" width="25%">
				<?php if (!$image->isFirst()) { ?>
					<a href='<?php echo add_query_arg (array('pDetails' => $image->getPreviousId(), 'pawps_ordner' => $shooting->aktuellerOrdner->id)); ?>' title='vorheriges Bild anzeigen'><img src='<?php echo plugins_url( 'resources/previous.png' , __FILE__ ); ?>' border='0' height='30px' width='30px' /></a>
				<?php } ?>
			</td>
			<td align="center">
					<a href='<?php echo add_query_arg (array('pDetails' => null, 'pawps_ordner' => $shooting->aktuellerOrdner->id)); ?>' title='zurueck zur Uebersicht'><img src='<?php echo plugins_url( 'resources/up.png' , __FILE__ ); ?>' border='0' height='30px' width='30px' /></a>
			</td>
			<td align="right" width="25%">
				<?php if (!$image->isLast()) { ?>
					<a href='<?php  echo add_query_arg (array('pDetails' => $image->getNextId(), 'pawps_ordner' => $shooting->aktuellerOrdner->id)); ?>' title='naechstes Bild anzeigen'><img src='<?php  echo plugins_url( 'resources/next.png' , __FILE__ ); ?>' border='0' height='30px' width='30px' /></a>
				<?php } ?>
			</td>
		</tr>
	</table>
	<center>
		<img src="<?php echo $image->getDetailUrl(); ?>" class="pawps_image" />

		<form name="warenkorbForm" method="post" action="">
			<input type="hidden" name="imageId" value="<?php echo $image->id; ?>" />
			<input type="hidden" name="preislistId" value="<?php echo $shooting->pricelist_id; ?>" />
			<table border="0">
				<tr>
					<td>
						<select name="product">
							<?php foreach ($shooting->getPricelist()->getProducts() as $product) { ?>
								<option value="<?php echo $product->id; ?>"><?php echo $product->title . " - " . $product->getPrice(1) . " EUR"; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						x
					</td>
					<td>
						<input type="text" name="anzahl" size="2" maxlength="2" value="1" />
					</td>
				</tr>
			</table>
			<input type="submit" name="addToBasket" class="button-primary" value="in Warenkorb" />
		</form>
	</center>