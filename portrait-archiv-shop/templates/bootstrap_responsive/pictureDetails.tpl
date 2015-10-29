<div class="row">
	<div class="col-xs-4 text-right">
		<?php if (!$image->isFirst()) { ?>
			<a href='<?php echo add_query_arg (array('pDetails' => $image->getPreviousId(), 'pawps_ordner' => $shooting->aktuellerOrdner->id)); ?>' title='vorheriges Bild anzeigen'><span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span></a>
		<?php } ?>
	</div>	
	<div class="col-xs-4 text-center">
		<a href='<?php echo add_query_arg (array('pDetails' => null, 'pawps_ordner' => $shooting->aktuellerOrdner->id)); ?>' title='zurueck zur Uebersicht'><span class='glyphicon glyphicon-eject' aria-hidden='true'></span></a>
	</div>	
	<div class="col-xs-4 text-left">
		<?php if (!$image->isLast()) { ?>
			<a href='<?php  echo add_query_arg (array('pDetails' => $image->getNextId(), 'pawps_ordner' => $shooting->aktuellerOrdner->id)); ?>' title='naechstes Bild anzeigen'><span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span></a>
		<?php } ?>
	</div>
</div>

<div class="row text-center">	
		<img src="<?php echo $image->getDetailUrl(); ?>" class="pawps_image" />

		<form name="warenkorbForm" method="post" action="" class="form-inline">
			<input type="hidden" name="imageId" value="<?php echo $image->id; ?>" />
			<input type="hidden" name="preislistId" value="<?php echo $shooting->pricelist_id; ?>" />
			 	<div class="col-xs-8 text-right">
			 	<select name="product" class="form-control" >
					<?php foreach ($shooting->getPricelist()->getProducts() as $product) { ?>
						<option value="<?php echo $product->id; ?>"><?php echo $product->title . " - " . $product->getPrice(1) . " EUR"; ?></option>
					<?php } ?>
				</select>
				</div>
				<div class="col-xs-1">x</div>
				<div class="col-xs-3 text-left">
				<input class="form-control" type="text" name="anzahl" size="2" maxlength="2" value="1" />
				</div>
			<input type="submit" name="addToBasket" class="btn btn-default" value="in Warenkorb" />
		</form>
</div>