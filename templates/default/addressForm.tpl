			<form name="adressdatenForm" method="post" action="">
				<table class="pawps_table">
					<tr>
						<td>Name, Vorname:</td>
						<td>
							<input type="text" name="lastname" size="20" maxlength="25" value="<?php echo $name; ?>" />, <input type="text" name="firstname" size="20" maxlength="25" value="<?php echo $firstname; ?>" />
						</td>
					</tr>
					<tr>
						<td>Strasse:</td>
						<td>
							<input type="text" name="street" size="40" maxlength="50" value="<?php echo $street; ?>" />
						</td>
					</tr>
					<tr>
						<td>Plz, Ort:</td>
						<td>
							<input type="text" name="plz" size="6" maxlength="6" value="<?php echo $plz; ?>" /> <input type="text" name="ort" size="30" maxlength="40" value="<?php echo $city; ?>" />
						</td>
					</tr>
					<tr>
						<td>Land:</td>
						<td> 
							<select name="land">
								<?php 
									foreach ($versandlaender as $land) {
										echo "<option value='" . $land->id . "'";
										if ($land->id == $landId) echo " selected";
										echo ">" . $land->title . "</option>";
									}
								?>
							</select>
						</td>
					</tr>
					<?php
						if ($rechnungsadresse) {
					?>
					<tr>
						<td>E-Mail:</td>
						<td>
							<input type="text" name="email" size="40" maxlength="50" value="<?php echo $email; ?>" />
						</td>
					</tr>
					<tr>
						<td>Lieferadresse:</td>
						<td>
							<select name="lieferadresseAbweichend">
								<option value="0">gleich Rechnungsadresse</option>
								<option value="1">abweichend</option>
							</select>
						</td>
					</tr>
					<?php
						}
					?>
				</table>
				
				<?php
					if ($rechnungsadresse) {
				?>
					<input type="submit" name="doAddRechnungsadresse" class="button-primary" value="weiter" />
				<?php
					} else {
				?>
					<input type="submit" name="doAddLieferadresse" class="button-primary" value="weiter" />
				<?php
					}
				?>					
			</form>