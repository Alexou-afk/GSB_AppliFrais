  <!-- Division principale -->
  <div id="contenu">
      <h2>Validation des frais</h2>
      <form action="index.php?uc=validerFrais&action=valideFicheVisiteur"  method="post">
	     <div class="corpsForm">
		<!-- Combo visiteur -->
		<p>
		<label for="lstVisiteur">Choisir le visiteur</label>
		<select name='lstVisiteur' id='lstVisiteur'>
  			<?php
			  foreach($LesVisiteurs as $unVisiteur){
				  $id = $unVisiteur['id'];
				  $nom = $unVisiteur['nomV'];
				  $prenom = $unVisiteur['prenomV'];
				  ?>
				<option value="<?php echo $id ?>"> <?php echo $nom." ".$prenom; ?> </option>
			  <?php 
			  }
						

			?>
		</select>
		</p>
		
		<!-- Affichage du mois -->
		<p>
        <label for="txtMois">Mois</label>
		<input type="text" name="txtMois" size="12" value="<?php echo $leMois;?>" /><br><br>
		</p>
		
		<p class="titre" /><label class="titre">&nbsp;</label><input class="zone" type="submit" value="Valider"/>
		
      </div>
 
	 </form>