  <!-- Division principale -->
  <div id="contenu">
      <h2>Validation des frais</h2>
      <form action="index.php?uc=validerFrais&action=validerFicheVisiteur" method="post">
	     <div class="corpsForm">
		<!-- Combo visiteur -->
		<p>
		<label for="lstVisiteurs" accesskey="n">Choisir le visiteur : </label>
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $id = $unVisiteur['id'];
                        $nom = $unVisiteur['nomV'];
                        $prenom = $unVisiteur['prenomV'];
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
        <label for="lstMois">Mois</label>
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
	 

	<form action="index.php?uc=validerFrais&action=modifierFraisForfait" method="post" role="form">

	<input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
    <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">

		<div class="corpsForm">
			<!-- Frais forfait -->
			<div style="clear:left;"><h3>Frais au forfait </h3>
					<table style="color:white;" border="1">
						<tr><th></th><th>Etape</th><th>Km </th><th>Nuitée </th><th>Repas midi</th></tr>
						<tr align="center"><th>Quantité</th>
					 
						<?php
          					foreach (  $lesFraisForfait as $unFraisForfait  ) 
		  					{
								$idFrais = $unFraisForfait['idfrais'];
								$quantite = $unFraisForfait['quantite'];
						?>
								<td width='80'><input type='text' size='3' name="lesFrais[<?php echo $idFrais ?>]" value='<?php echo $quantite?>'/></td>
		 				<?php
          					}
						?>
	
						</tr>
					</table>				 
					<p class="titre" /><label class="titre">&nbsp;</label><input class="zone" type="submit" value="Valider"/>
			</div>
		</div>

	</form>

	<form action="index.php?uc=validerFrais&action=validerFrais" method="post">

	<input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
    <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">


		<div class="corpsForm">
			
			<!-- Frais hors-forfait -->	

            <p class="titre" />
			<div style="clear:left;"><h3>Hors Forfait </h3>
			<table style="color:white;" border="1">
					<tr>
					<th>Date</th>
					<th>Libellé </th>
					<th>Montant</th>
					</tr>
					<?php      
          				foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
		  				{
							$idFrais = $unFraisHorsForfait['id'];
							$dateHF = $unFraisHorsForfait['dateFrais'];
							$libelleHF = $unFraisHorsForfait['libelle'];
							$montantHF = $unFraisHorsForfait['montant'];
					?>
					<tr align='center'>
						
						<td width='100'><input type='text' size='12' name='txtDate' value ='<?php echo $dateHF ?>'/></td>
						<td width='220'><input type='text' size='30' name='txtLibelle' value ='<?php echo $libelleHF ?>'/></td> 
						<td width='90'><input type='text' size='10' name='txtMontant' value ='<?php echo $montantHF ?>'/></td>

						<td width='90'><a href= "index.php?uc=validerFrais&action=SupprimerFraisHorsForfait&idFrais=<?php echo $idFrais?>&idVisiteur=<?php echo $visiteurASelectionner?>&mois=<?php echo $moisASelectionner?>"
                       		onclick="return confirm('Voulez-vous vraiment supprimer cette ligne de frais hors forfait ?');"
                       		title="Supprimer la ligne de frais hors forfait">Supprimer</a></td>>

						<td width='90'><a href= "index.php?uc=validerFrais&action=reporterFraisHorsForfait&idFrais=<?php echo $idFrais?>&idVisiteur=<?php echo $visiteurASelectionner?>&mois=<?php echo $moisASelectionner?>&libelleHF=<?php echo $libelleHF?>&dateHF=<?php echo $dateHF?>&montantHF=<?php echo $montantHF?>"
                       		onclick="return confirm('Voulez-vous vraiment reporter cette ligne de frais hors forfait ?');"
                       		title="Reporter la ligne de frais hors forfait">Reporter</a></td>
					</tr>
        			<?php 
          				}
					?>	
					
				</table>		
			</div>
			<br></br>
			<p class="titre" />
			<div style="clear:left;"><h3>Hors classification</h3>
			<b>Nombres de justificatifs : </b><input type='text' class='zone' size='8' id='txtNbJustificatifs' name='txtNbJustificatifs' value='<?php echo $nbJustificatifs ?>'><br>
			<b>Montant total de la fiche : </b><input type='text' class='zone' size='8' name='txtMontant' value='<?php echo $montantTotal ?>'>	
			<p class="titre" /><label class="titre">&nbsp;</label><input class="zone" type="submit" value="Valider"/>
			       <input id="annuler" type="reset" value="Annuler" size="20" />
			</div>	
	

		</div>
	</form>


 
	 
  </div>