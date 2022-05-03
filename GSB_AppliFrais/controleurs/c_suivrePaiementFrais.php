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
        $lesMois = $pdo->getLesMoisDontFicheVA();
	    include("vues/v_listeVisiteurRembourse.php");
	}
	case'validerVisiteur' :{
		$lesVisiteurs = $pdo->getLesVisiteurs();
		$leMois = getMoisEnCours();
		$idVisiteur = trim($_REQUEST['lstVisiteurs']);
		include("vues/v_listeMoisRembourser.php");break;
	}
	case'validerMois' :{
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
		include("vues/v_listeFraisRembourser.php");
	}
	case'validerRemboursement' :{
		$lesVisiteurs = $pdo->getLesVisiteurs();
		$leMois = getMoisEnCours();
		$idVisiteur = trim($_REQUEST['lstVisiteur']);
		include("vues/v_listeFraisRembourser.php");break;
	}
}

?>