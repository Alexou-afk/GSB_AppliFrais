  <!-- Division principale -->
  <div id="contenu">
      <h2>Suivi des paiements</h2>
      <form action="index.php?uc=suivrePaiement&action=validerVisiteur" method="post">
	     <div class="corpsForm">
		<!-- Combo visiteur -->
		<p>
		<label for="lstVisiteurs" accesskey="n">Choisir le visiteur : </label>
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $id = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        if ($id == $visiteurASelectionner) {
                            ?>
                            <option selected value="<?php echo $id ?>">
                                <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $id ?>">
                                <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
		</p>
		
		<!-- Affichage du mois -->
		<p>
        <label for="lstMois">Mois : </label>
		<select id="lstMois" name="lstMois">
			<?php
			foreach($lesMois as $unMois){
				$mois = $unMois['mois'];
				$numAnnee = $unMois['numAnnee'];
				$numMois = $unMois['numMois'];
				if ($mois == $moisASelectionner) {
					?>
					<option selected value="<?php echo $mois ?>">
						<?php echo $numMois . '/' . $numAnnee ?> </option>
					<?php
				} else {
					?>
					<option value="<?php echo $mois ?>">
						<?php echo $numMois . '/' . $numAnnee ?> </option>
					<?php
				}
			}
			?>
		</select>
		</p>
		
		<p class="titre" /><label class="titre">&nbsp;</label><input class="zone" type="submit" value="Valider"/>
		
      </div>
 
	 </form>