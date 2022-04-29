<?php
session_start();
require_once("include/fct.inc.php");
require_once ("modele/class.pdogsb.inc.php");
include("vues/v_entete.php") ;
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
$estConnecteComptable = estConnecteComptable();
if(!isset($_REQUEST['uc']) || !$estConnecte){
	if(!isset($_REQUEST['uc']) || !$estConnecteComptable){
     $_REQUEST['uc'] = 'connexion';
}	
} 
$uc = trim(htmlentities($_REQUEST['uc']));
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
	case 'gererFrais' :{
		include("controleurs/c_gererFrais.php");break;
	}
	case 'etatFrais' :{
		include("controleurs/c_etatFrais.php");break; 		
	}
	case 'validerFrais':{
		include("controleurs/c_validerFrais.php");break;
		
	}
	case 'suivrePaiement':{
		include("controleur/c_suivrePaiementFrais.php");break;
	}
}
include("vues/v_pied.php") ;
?>

