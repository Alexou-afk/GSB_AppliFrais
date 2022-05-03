<?php
// gestion du comptable
include("vues/v_sommaireComptable.php");

$mois = getMois(date('d/m/Y'));
$idComptable = $_SESSION['cid'];
$action = trim(htmlentities($_REQUEST['action']));
switch($action)
{
	case'selectionnerVisiteur':{
        $lesVisiteurs = $pdo->getLesVisiteursDontFicheVA();
			include("vues/v_listeVisiteurRembourse.php");
			
	break;
	}
	case'validerVisiteur' :{
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $lesMois = $pdo->getLesMoisDontFicheVA();
		include("vues/v_listeMoisRembourse.php");
	break;
	}
	case'validerMois' :{
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
		$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
		
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
		
		
		$sommeHF = $pdo->montantHF($idVisiteur,$leMois);
		$totalHF=$sommeHF[0][0];
		$sommeFF=$pdo->montantFF($idVisiteur,$leMois);
		$totalFF=$sommeFF[0][0];
		$montantTotal=$totalHF+$totalFF;

		$numMois = substr($leMois,4);
		$numAnnee = substr($leMois,0, -2);

		$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];

		include("vues/v_listeFraisRembourse.php");
	break;
	}
	case'validerRemboursement' :{
		$lesVisiteurs = $pdo->getLesVisiteurs();
		$leMois = getMoisEnCours();
		$idVisiteur = trim($_REQUEST['lstVisiteur']);
		include("vues/v_listeFraisRembourse.php");break;
	}
}

?>