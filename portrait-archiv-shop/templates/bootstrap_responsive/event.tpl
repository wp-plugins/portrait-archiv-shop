<div class="row">
	<div class="col-md-6 text-right">
	<?php
		if ($currentStartIndex > 0) {
			// Pfeil anzeigen
			echo "<a href='" . add_query_arg (array('pStart' => $currentStartIndex - $imagesPerPage, 'pawps_ordner' => $shooting->aktuellerOrdner->id)) . "' title='vorherige Seite'><span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span></a>";
		} else {
			echo "&nbsp;";
		}
	?>
	</div>
	
	<div class="col-md-6 text-left">
	<?php	
		if ($requestedEndIndex < (count($shooting->getImages(true)))) {
			// Pfeil anzeigen
			echo "<a href='" . add_query_arg (array('pStart' => $requestedEndIndex, 'pawps_ordner' => $shooting->aktuellerOrdner->id)) . "' title='nächste Seite'><span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span></a>";
		} else {
			echo "&nbsp;";
		}
	?>
	</div>
</div>

	<?php
		if (isset($shooting) && ($shooting->getOrdnerCount() > 1)) {
			?>
			<div class="row text-center">
				<form name="changeTeilnehmer" method="post" action="<?php echo add_query_arg (array('pawps_shooting'=> $shooting->id, 'pawps_ordner' => null)); ?>">
					<select name="pawps_ordner" onChange="document.changeTeilnehmer.submit()">
						<?php foreach ($shooting->getOrdner() as $ordner) { ?><option value="<?php echo $ordner->id; ?>" <?php if ($shooting->aktuellerOrdner->id == $ordner->id) echo 'selected'; ?>><?php echo $ordner->title; ?></option><?php } ?>
					</select>
				</form>
			</div>
			<?php
		}			
	?>
	
<div class="row">
	<?php			
	 	foreach ($images as $image) {
	?>
			<div class="col-md-3 col-sm-4 col-xs-6 text-center">
				<a href="<?php echo add_query_arg ( array('pawps_shooting' => $image->veranstaltungsid, 'pDetails' => $image->id, 'pStart' => null, 'pawps_ordner' => $shooting->aktuellerOrdner->id)); ?>" title="Bild anzeigen"><img src="<?php echo $image->getThumbUrl(); ?>" class="pawps_image" /></a>
			</div>
	<?php
		}
	?>
</div>