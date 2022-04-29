<?php
// gestion du comptable
include("vues/v_sommaireComptable.php");
$idComptable = $_SESSION['cid'];
$action = trim(htmlentities($_REQUEST['action']));
switch($action)
{
	case'selectionnerVisiteur':{
		$leMois = getMoisEnCours();
		// Rechercher les visiteurs dans la BD
		$LesVisiteurs = $pdo->getLesVisiteurs();
		// Inclusion de la vue v_listeVisiteur.php
		include("vues/v_listeVisiteur.php");break;
	}
	case'valideFicheVisiteur' :{
		$lesVisiteurs = $pdo->getLesVisiteurs();
		$leMois = getMoisEnCours();
		$idVisiteur = trim($_REQUEST['lstVisiteur']);
		include("vues/v_listeFrais.php");break;
	}

}
?>