<table class="pawps_table">
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
			echo "<a href='" . remove_query_arg('pawps_galerieReset', add_query_arg (array('pStart' => $currentStartIndex - $imagesPerPage, 'pawps_ordner' => $shooting->aktuellerOrdner->id))) . "' title='vorherige Seite'><img src='" . plugins_url( 'resources/previous.png' , __FILE__ ) . "' border='0' height='30px' width='30px' /></a>";
		} else {
			echo "&nbsp;";
		}
		echo "</td>";
		
		if ($needCenter) { 
			echo "<td>&nbsp;</td>";
		}
				
		echo "<td align='right' colspan='" . $colspan . "'>";
		if ($requestedEndIndex < (count($shooting->getImages(true)))) {
			// Pfeil anzeigen
			echo "<a href='" . remove_query_arg('pawps_galerieReset', add_query_arg (array('pStart' => $requestedEndIndex, 'pawps_ordner' => $shooting->aktuellerOrdner->id))) . "' title='nächste Seite'><img src='" . plugins_url( 'resources/next.png' , __FILE__ ) . "' border='0' height='30px' width='30px' /></a>";
		} else {
			echo "&nbsp;";
		}
		echo "</td></tr>";	
		
		if (isset($shooting) && ($shooting->getOrdnerCount() > 1)) {
			?>
				<tr>
					<td colspan="<?php echo $displayCols; ?>" align="center">
						<form name="changeTeilnehmer" method="post" action="<?php echo remove_query_arg('pawps_galerieReset', add_query_arg (array('pawps_shooting'=> $shooting->id, 'pawps_ordner' => null))); ?>">
							<select name="pawps_ordner" onChange="document.changeTeilnehmer.submit()">
								<?php foreach ($shooting->getOrdner() as $ordner) { ?><option value="<?php echo $ordner->id; ?>" <?php if ($shooting->aktuellerOrdner->id == $ordner->id) echo 'selected'; ?>><?php echo $ordner->title; ?></option><?php } ?>
							</select>
						</form>
					</td>
				</tr>
			<?php
		}			
			
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
				<a href="<?php echo remove_query_arg('pawps_galerieReset', add_query_arg ( array('pawps_shooting' => $image->veranstaltungsid, 'pDetails' => $image->id, 'pStart' => null, 'pawps_ordner' => $shooting->aktuellerOrdner->id))); ?>" title="Bild anzeigen"><img src="<?php echo $image->getThumbUrl(); ?>" class="pawps_image" /></a>
			</td>
			<?php
				$currentPos ++;
		}
		if ($isOpened) {
			echo "</tr>";
		}
	?>
</table>	