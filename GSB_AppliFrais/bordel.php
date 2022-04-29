// Récupération des infos du formulaire
		$idVisiteur = $_REQUEST['lstVisiteur'];
		$mois = $_REQUEST['txtMois'];
		// Vérifier l'existence de fiches frais
		if (existeFicheFrais($idVisiteur, $mois)) {
			$lesFraisForfait = $pdo->getlesFraisForfait();
			$lesFraisHorsForfait = $pdo->getlesFraisHorsForfait();
		}
		else{
			ajouterreur("Il n'y a aucunes fiches frais enregistrées");
		}
		include("vues/v_listeFrais.php");break;