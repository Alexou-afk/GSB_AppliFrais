<!-- Division principale -->
<div id="contenu">
      <h2> Suivi de paiement</h2>

	<form action="index.php?uc=suivrePaiement&action=modifierFraisForfait" method="post" role="form">

	<input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
  <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">

		<div class="corpsForm">


    <b>Visiteur : </b> <?php echo $idVisiteur ?><br>
    <b>Mois : </b> <?php echo $numMois."-".$numAnnee ?><br>


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
								<th width='80' input type='text' size='3' name="lesFrais[<?php echo $idFrais ?>]" ><?php echo $quantite?></th>
		 				<?php
          					}
						?>
	
						</tr>
					</table>				 
			</div>


	</form>

	<form action="index.php?uc=validerFrais&action=validerFrais" method="post">

	<input name="lstMois" type="hidden" id="lstMois" class="form-control" value="<?php echo $moisASelectionner ?>">
    <input name="lstVisiteurs" type="hidden" id="lstVisiteurs" class="form-control" value="<?php echo $visiteurASelectionner ?>">



			
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
						
						<th width='100'input type='text' size='12' name='txtDate'   ><?php echo $dateHF ?></th>
						<th width='220'input type='text' size='30' name='txtLibelle'><?php echo $libelleHF ?></th> 
						<th width='90' input type='text' size='10' name='txtMontant'><?php echo $montantHF ?>'</th>
					</tr>
        			<?php 
          				}
					?>	
					
				</table>		
			</div>
			<br></br>
			<p class="titre" />
			<div style="clear:left;"><h3>Hors classification</h3>
			<b>Nombres de justificatifs : </b> <?php echo $nbJustificatifs ?><br>
			<b>Montant total de la fiche : </b><?php echo $montantTotal ?>	
			<p class="titre" /><label class="titre">&nbsp;</label><input class="zone" type="submit" value="Rebourser"/>
			</div>	
	

		</div>
	</form>
 
	 
  </div>