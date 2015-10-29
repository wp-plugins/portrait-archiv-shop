<div class="row">
	<?php 
	 	foreach ($shootings as $shooting) {
		?>
			<div class="col-md-4 col-xs-6 text-center">
				<a href='<?php echo remove_query_arg('pawps_galerieReset', add_query_arg ('pawps_shooting', $shooting->id)); ?>' title='Shooting <?php echo $shooting->title; ?> anzeigen'>
				<img src='<?php echo $shooting->getPreviewImage()->getThumbUrl(); ?>' class="pawps_image" /><br/>
				<?php echo $shooting->title; ?>
				</a>
			</div>
		<?php
		}
	?>
</div>