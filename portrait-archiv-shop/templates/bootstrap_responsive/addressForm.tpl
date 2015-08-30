<form name="adressdatenForm" method="post" action="" class="form-inline">
	<div class="form-group">
		<label>Name, Vorname:</label>
		<input class="form-control" type="text" name="lastname" size="20" maxlength="25" value="<?php echo $name; ?>" /> <input class="form-control" type="text" name="firstname" size="20" maxlength="25" value="<?php echo $firstname; ?>" />
	</div>
	<div class="clearfix"></div>
	
	<div class="form-group">
		<label>Strasse, Nr:</label>
		<input class="form-control" type="text" name="street" size="30" maxlength="50" value="<?php echo $street; ?>" /> <input class="form-control" type="text" name="number" size="8" maxlength="6" value="<?php echo $number; ?>" />
	</div>
	<div class="clearfix"></div>
	
	<div class="form-group">
		<label>Plz, Ort:</label>
		<input class="form-control" type="text" name="plz" size="6" maxlength="6" value="<?php echo $plz; ?>" /> <input class="form-control" type="text" name="ort" size="30" maxlength="40" value="<?php echo $city; ?>" />
	</div>
	<div class="clearfix"></div>
	
	<div class="form-group">
		<label>Land:</label>
		<select name="land" class="form-control">
								<?php 
									foreach ($versandlaender as $land) {
										echo "<option value='" . $land->id . "'";
										if ($land->id == $landId) echo " selected";
										echo ">" . $land->title . "</option>";
									}
								?>
							</select>
	</div>
	<div class="clearfix"></div>
	
	<?php
		if ($rechnungsadresse) {
	?>
		<div class="form-group">
			<label>E-Mail:</label>
			<input class="form-control" type="text" name="email" size="40" maxlength="50" value="<?php echo $email; ?>" />
		</div>
		<div class="clearfix"></div>
	
		<div class="form-group">
			<label>Lieferadresse:</label>
			<select name="lieferadresseAbweichend" class="form-control">
				<option value="0">gleich Rechnungsadresse</option>
				<option value="1">abweichend</option>
			</select>
		</div>
		<div class="clearfix"></div>
	<?php
		}
	?>
				
				<?php
					if ($rechnungsadresse) {
				?>
					<input type="submit" name="doAddRechnungsadresse" class="btn btn-default" value="weiter" />
				<?php
					} else {
				?>
					<input type="submit" name="doAddLieferadresse" class="btn btn-default" value="weiter" />
				<?php
					}
				?>					
			</form>