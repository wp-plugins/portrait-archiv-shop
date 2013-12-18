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
			echo "<a href='" . add_query_arg ('pStart', $currentStartIndex - $imagesPerPage) . "' title='vorherige Seite'><img src='" . plugins_url( 'resources/previous.png' , __FILE__ ) . "' border='0' height='30px' width='30px' /></a>";
		} else {
			echo "&nbsp;";
		}
		echo "</td>";
		
		if ($needCenter) { 
			echo "<td>&nbsp;</td>";
		}
				
		echo "<td align='right' colspan='" . $colspan . "'>";
		if ($requestedEndIndex < $shooting->imageCount) {
			// Pfeil anzeigen
			echo "<a href='" . add_query_arg ('pStart', $requestedEndIndex) . "' title='nächste Seite'><img src='" . plugins_url( 'resources/next.png' , __FILE__ ) . "' border='0' height='30px' width='30px' /></a>";
		} else {
			echo "&nbsp;";
		}
		echo "</td>";				
			
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
				<a href="<?php echo add_query_arg ( array('pawps_shooting' => $image->veranstaltungsid, 'pDetails' => $image->id, 'pStart' => null)); ?>" title="Bild anzeigen"><img src="<?php echo $image->getThumbUrl(); ?>" class="pawps_image" /></a>
			</td>
			<?php
				$currentPos ++;
		}
		if ($isOpened) {
			echo "</tr>";
		}
	?>
</table>	