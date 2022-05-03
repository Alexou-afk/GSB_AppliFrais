<?php
// gestion du comptable
include("vues/v_sommaireComptable.php");

$mois = getMois(date('d/m/Y'));
$idComptable = $_SESSION['cid'];
$action = trim(htmlentities($_REQUEST['action']));
switch($action)
{
	case'selectionnerVisiteur':{
		$lesMois = $pdo->getLesMoisDontFicheVA();
		// Rechercher les visiteurs dans la BD
		$lesVisiteurs = $pdo->getLesVisiteursDontFicheVA();
		// Inclusion de la vue v_listeVisiteur.php
		include("vues/v_listeVisiteur.php");break;
	}
	case'validerVisiteur' :{
		$lesVisiteurs = $pdo->getLesVisiteurs();
		$leMois = getMoisEnCours();
		$idVisiteur = trim($_REQUEST['lstVisiteur']);
		include("vues/v_etatFrais.php");break;
	}
	case'validerMois' :{
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
		include("vues/v_listeMois.php");
	}
	case'validerRemboursement' :{
		$lesVisiteurs = $pdo->getLesVisiteurs();
		$leMois = getMoisEnCours();
		$idVisiteur = trim($_REQUEST['lstVisiteur']);
		include("vues/v_listeFrais.php");break;
	}
}

?>