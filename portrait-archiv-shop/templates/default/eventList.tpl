<table class="pawps_table">
<?php 
 	foreach ($shootings as $shooting) {
		if ($currentPos % $displayCols == 0) {
			if ($isOpened) {
				echo "</tr>";
			}
			echo "<tr>";
			$isOpened = true;
		}
	?>
		<td align="center">
			<a href='<?php echo remove_query_arg('pawps_galerieReset', add_query_arg ('pawps_shooting', $shooting->id)); ?>' title='Shooting <?php echo $shooting->title; ?> anzeigen'>
			<img src='<?php echo $shooting->getPreviewImage()->getThumbUrl(); ?>' class="pawps_image" /><br/>
			<?php echo $shooting->title; ?>
			</a>
		</td>
	<?php
		$currentPos ++;
	}
	if ($isOpened) {
		echo "</tr>";
	}
?>
</table>