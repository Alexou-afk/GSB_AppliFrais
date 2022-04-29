    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2>
		<?php  
			echo $_SESSION['cprenom']."  ".$_SESSION['cnom'];
		?>   
		</h2>
         <h3>Comptable</h3>    
      </div>  
        <ul id="menuList">
			<li class="smenu">
              <a href="index.php?uc=validerFrais&action=selectionnerVisiteur" title="Valider frais ">Validation des fiche de frais</a>
			</li>
			<li class="smenu">
              <a href="index.php?uc=suivrePaiement&action=selectionnerVisiteur" title="Suivre Paiement">Suivi de paiement</a>
			</li>
			<li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
			</li>
         </ul>
        
    </div>