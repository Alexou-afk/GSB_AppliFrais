<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsb_applifrais';   		
      	private static $user='root' ;    		
      	private static $mdp='root' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
		PdoGsb::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom from visiteur 
		where visiteur.login= :login and visiteur.mdp= :mdp";
		$idJeuRes = PdoGsb::$monPdo->prepare($req); 
		$idJeuRes->execute(array( ':login' => $login, ':mdp' => $mdp));			
		$ligne = $idJeuRes->fetch();
		return $ligne;
	}

	public function getInfosComptable($loginComptable, $mdpComptable){
		$reqComptable = "select comptable.cid as cid, comptable.cnom as cnom, comptable.cprenom as cprenom from comptable 
		where comptable.clogin= :clogin and comptable.cmdp= :cmdp";
		$idJeuResComptable = PdoGsb::$monPdo->prepare($reqComptable);
		$idJeuResComptable->execute(array( ':clogin' => $loginComptable, ':cmdp' => $mdpComptable));
		$ligneComptable = $idJeuResComptable->fetch();
		return $ligneComptable;

	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idVisiteur = :idVisiteur and lignefraishorsforfait.mois = :mois";
		$idJeuRes = PdoGsb::$monPdo->prepare($req); 
		$idJeuRes->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois));	
		$lesLignes = $idJeuRes->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['dateFrais'];
			$lesLignes[$i]['dateFrais'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from fichefrais where fichefrais.idVisiteur = :idVisiteur and fichefrais.mois = :mois";
		$idJeuRes = PdoGsb::$monPdo->prepare($req); 
		$idJeuRes->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois));	
		$ligne = $idJeuRes->fetch();
		return $ligne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idFraisForfait
		where lignefraisforfait.idVisiteur = :idVisiteur and lignefraisforfait.mois= :mois 
		order by lignefraisforfait.idFraisForfait";	
		$idJeuRes = PdoGsb::$monPdo->prepare($req); 
		$idJeuRes->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois));	
		$lesLignes = $idJeuRes->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$idJeuRes = PdoGsb::$monPdo->prepare($req);  
		$idJeuRes->execute();				
		$lesLignes = $idJeuRes->fetchAll();
		return $lesLignes;
	}

/**
 * Retourne tous les id de la table FraisHorsForfait
 *
 * @return un tableau associatif
 */
    public function getLesIdFraisHorsForfait()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT lignefraishorsforfait.id as idFHF '
            . 'FROM lignefraishorsforfait ORDER BY lignefraishorsforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

// Recherche du nom et prenom des visiteurs
	public function getLesVisiteurs(){
		$reqV = "select visiteur.id as id, visiteur.nom as nomV, visiteur.prenom as prenomV from visiteur order by visiteur.nom";
		$idJeuResVisiteur = PdoGsb::$monPdo->prepare($reqV);
		$idJeuResVisiteur->execute();
		$lesLignesV = $idJeuResVisiteur->fetchAll();
		return $lesLignesV;
	}
/**
 * Met à jour la table LigneFraisForfait
 
 * Met à jour la table LigneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = :qte
			where lignefraisforfait.idVisiteur = :idVisiteur and lignefraisforfait.mois = :mois
			and lignefraisforfait.idFraisForfait = :idFrais";
			$resultat = PdoGsb::$monPdo->prepare($req); 
			$resultat->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois, ':idFrais' => $unIdFrais , ':qte' => $qte ));
		}
		
	}
/**
 * met à jour le nombre de justificatifs de la table FicheFrais
 * pour le mois et le visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbJustificatifs = :nbJustificatifs 
		where fichefrais.idVisiteur = :idVisiteur and fichefrais.mois = :mois";
		$resultat = PdoGsb::$monPdo->prepare($req); 
		$resultat->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois, ':nbJustificatifs' => $nbJustificatifs ));	
	}
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = :mois and fichefrais.idVisiteur = :idVisiteur";
		$idJeuRes = PdoGsb::$monPdo->prepare($req); 
		$idJeuRes->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois));	
		$ligne = $idJeuRes->fetch();
		if($ligne['nblignesfrais'] == 0){
			$ok = false;
		}
		return $ok;
	}

	/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function existeFicheFrais($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais where idVisiteur = $idVisiteur and mois = $mois and idEtat = CL ";
		$idJeuRes = PdoGsb::$monPdo->prepare($req); 
		$idJeuRes->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois));	
		$ligne = $idJeuRes->fetch();
		if($ligne['nblignesfrais'] == 1){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idVisiteur = :idVisiteur";
		$idJeuRes = PdoGsb::$monPdo->prepare($req); 
		$idJeuRes->execute(array( ':idVisiteur' => $idVisiteur));	
		$ligne = $idJeuRes->fetch();
		$dernierMois = $ligne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbJustificatifs,'
            . 'montantValide,dateModif,idEtat) '
            . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = PdoGsb::$monPdo->prepare(
                'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                . 'idFraisForfait,quantite) '
                . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(
                ':idFrais',
                $unIdFrais['idfrais'],
                PDO::PARAM_STR
            );
            $requetePrepare->execute();
        }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj/mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait (idVisiteur, mois, dateFrais, libelle, montant)
		values(:idVisiteur, :mois, :dateFr, :libelle , :montant)";
		$resultat = PdoGsb::$monPdo->prepare($req); 
		$resultat->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois, ':libelle' => $libelle, ':dateFr' => $dateFr, ':montant' => $montant ));
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id = :idFrais ";
		$resultat = PdoGsb::$monPdo->prepare($req); 
		$resultat->execute(array( ':idFrais' => $idFrais ));
	}
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idVisiteur = :idVisiteur 
		order by fichefrais.mois desc ";
		$idJeuRes = PdoGsb::$monPdo->prepare($req); 
		$idJeuRes->execute(array( ':idVisiteur' => $idVisiteur));	
		$ligne = $idJeuRes->fetch();
		$lesMois =array();
		while($ligne != null)	{
			$mois = $ligne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		     "numAnnee"  => "$numAnnee",
			 "numMois"  => "$numMois"
             );
			$ligne = $idJeuRes->fetch();		
		}
		return $lesMois;
	}
    /**
    * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
    * @param $idVisiteur 
    * @param $mois sous la forme aaaamm
    * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
    */	
	public function getLesInfosFicheFrais($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT ficheFrais.idEtat as idEtat, '
            . 'ficheFrais.dateModif as dateModif,'
            . 'ficheFrais.nbJustificatifs as nbJustificatifs, '
            . 'ficheFrais.montantValide as montantValide, '
            . 'etat.libelle as libEtat '
            . 'FROM fichefrais '
            . 'INNER JOIN Etat ON ficheFrais.idEtat = Etat.id '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }
    /**
    * Modifie l'état et la date de modification d'une fiche de frais
    * Modifie le champ idEtat et met la date de modif à aujourd'hui
    * @param $idVisiteur 
    * @param $mois sous la forme aaaamm
    */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update fichefrais set idEtat = :etat, dateModif = now() 
		where fichefrais.idVisiteur = :idVisiteur and fichefrais.mois = :mois";
		$resultat = PdoGsb::$monPdo->prepare($req); 
		$resultat->execute(array( ':idVisiteur' => $idVisiteur, ':mois' => $mois, ':etat' => $etat ));
	}

	public function majAccepterFicheFrais($mois){
		$req = "update fichefrais set idEtat = 'CL', dateModif = now() 
		where fichefrais.mois = :mois";
		$resultat = PdoGsb::$monPdo->prepare($req); 
		$resultat->execute(array(':mois' => $mois));
	}

    /**
     * Recupère la derniere fiche cloturée. 
     * @param int $moisPrecedent
     */
    public function ficheDuDernierMoisCL($moisPrecedent){
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.idEtat'
            .'FROM fichefrais'    
            .'WHERE fichefrais.idetat = "CR" '
            .'AND fichefrais.mois = :unMois '
            );
        $requetePrepare->bindParam(':unMois', $moisPrecedent, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    /**
     * Cloture les fiches de frais du mois precedent('CL').
     * @param int $moisPrecedent
     */
    public function clotureFiches($moisPrecedent){
        $requetePrepare = PdoGsb::$monPdo->prepare(
        'UPDATE fichefrais'
        .'SET fichefrais.idetat = "CL",fichefrais.dateModif = now()'
        .'FROM fichefrais'  
        .'WHERE fichefrais.idetat= "CR" '
        .'AND fichefrais.mois = :moisPrecedent '
        );
        $requetePrepare->bindParam(':moisPrecedent', $moisPrecedent, PDO::PARAM_STR);
        $requetePrepare->execute();        
    }
    
    /**
     * Met à jour la table ligneFraisHorsForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param char $idVisiteur  ID du visiteur
     * @param int $leMois       Mois sous la forme aaaamm
     * @param char $libelleHF   
     * @param date $dateHF
     * @param int $montantHF
     * @return null
     */
    
    public function majFraisHorsForfait(
        $idVisiteur,
        $leMois,
        $libelleHF,
        $dateHF,
        $montantHF
    ) {
        $dateHF = dateFrancaisVersAnglais($dateHF);
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.libelle = :unLibelle,lignefraishorsforfait.date = :uneDateHF,lignefraishorsforfait.montant = :unMontant '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND libelle=:unLibelle '
            );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelleHF, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateHF', $dateHF, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montantHF, PDO::PARAM_INT);
        $requetePrepare->execute();
    }
   
    /**
     * Retourne la somme du montant des frais forfaitisés
     * @param string $idVisiteur     ID du visiteur
     * @param int $leMois            Mois sous la forme aaaamm
     * @return int                   Somme du montant des frais forfaitisés
     */
     public function montantFF($idVisiteur,$leMois){
        $requetePrepare = PdoGsb::$monPdo->prepare(
        'SELECT SUM(lignefraisforfait.quantite*fraisforfait.montant)'  
        .'FROM lignefraisforfait join fraisforfait on(lignefraisforfait.idfraisforfait=fraisforfait.id)' 
        .'WHERE idvisiteur = :unIdVisiteur '
        .'AND mois = :unMois '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();  
        return $requetePrepare->fetchAll();
    }
    
    /**
     * Retourne la somme du montant des frais hors forfait
     * @param string $idVisiteur     ID du visiteur
     * @param int $leMois            Mois sous la forme aaaamm
     * @return int                   Somme du montant des frais hors forfait
     */
     public function montantHF($idVisiteur,$leMois){
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT SUM(lignefraishorsforfait.montant) '
            . 'FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraishorsforfait.mois = :unMois '
            . 'AND lignefraishorsforfait.libelle not in (SELECT libelle '
            . 'FROM lignefraishorsforfait '
            . 'WHERE libelle like "REFUSÉ:%")'    
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    /**
     * Retourne la somme du montant total des frais hors forfait+celui des frais forfaitisés
     * @param string $idVisiteur     ID du visiteur
     * @param int $leMois            Mois sous la forme aaaamm
     * @param int $total             Resultat de la somme du montant des frais forfaitisés+celui des frais hors forfait
     * @return int                   Montant total des frais forfaitisés et hors forfait
     */
    public function total($idVisiteur, $leMois, $total)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET montantvalide = :total '
            . 'WHERE idvisiteur = :unIdVisiteur  '
            . 'AND mois = :unMois'
        );
        $requetePrepare->bindParam(':total',$total, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Modifie le libellé en ajoutant en début le texte « REFUSE : » 
     * @param string $idVisiteur      ID du visiteur
     * @param int $leMois             Mois sous la forme aaaamm
     * @param string $libelleHF       Libellé du frais
     */
    public function refuseFHF($idVisiteur,$leMois,$libelleHF){
       // echo $idVisiteur." ".$leMois." ".$libelleHF;
        $requetePrepare = PdoGsb::$monPdo->prepare(
        "UPDATE lignefraishorsforfait "
        ."SET libelle = CONCAT('REFUSÉ: ', libelle) "
        ."WHERE lignefraishorsforfait.idVisiteur='$idVisiteur' "
        ."AND lignefraishorsforfait.mois = '$leMois' "
        ."AND libelle='$libelleHF';");
       /* $requetePrepare->bindParam(':unLibelle', $libelleHF, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);*/
        $requetePrepare->execute(); 
    }
    
    /**
     * Cree nouveau fhf dans la fiche du mois suivant dans le cas d'un report du frais hors forfait
     * @param string $idVisiteur    ID du visiteur
     * @param int $leMois           Mois sous la forme aaaamm
     * @param string $libelleHF     Libellé du frais
     * @param string $dateHF        Date du frais
     * @param int $montantHF        Montant du frais
     */
    public function creeFHFReporte($idVisiteur,$leMois,$libelleHF,$dateHF,$montantHF){
                    //var_dump($idVisiteur,$leMois,$libelleHF,$dateHF,$montantHF);
            $dateFr = dateFrancaisVersAnglais($dateHF);
            $requetePrepare = PdoGSB::$monPdo->prepare(
                "INSERT INTO lignefraishorsforfait "
                . "VALUES (null, :unIdVisiteur,:unMois,'$libelleHF', :uneDateFr,:unMontant)"
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
            //$requetePrepare->bindParam(':unLibelle', $libelleHF, PDO::PARAM_STR);
            $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMontant', $montantHF, PDO::PARAM_INT);
            $requetePrepare->execute();
    }
    /**
     * Enlève le texte "REFUSE: " du libelle du fhf qui a été reporté
     * @param string $idVisiteur   ID du visiteur
     * @param int $leMois          Mois sous la forme aaaamm
     * @param string $libelleHF    Libellé du frais
     * @param string $dateHF       Date du frais
     * @param int $montantHF       Montant du frais
     */
    public function enleverTexteRefuse($idVisiteur,$leMois,$libelleHF,$dateHF,$montantHF){
        //var_dump($idVisiteur,$leMois,$libelleHF,$date,$montantHF);
        $dateFr = dateFrancaisVersAnglais($dateHF);
        $requetePrepare = PdoGSB::$monPdo->prepare(
                "UPDATE lignefraishorsforfait "
                . "SET libelle=SUBSTR('$libelleHF',8) "
                . "WHERE idvisiteur='$idVisiteur' "
                . "AND mois ='$leMois' "
                . "AND dateFrais ='$dateFr'"
                . "AND montant='$montantHF'"
            );    
        $requetePrepare->execute();
        }
        /*$requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);  
        //$requetePrepare->bindParam(':unLibelle', $libelleHF, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montantHF, PDO::PARAM_INT);
        $requetePrepare->execute();*/
    /*    if($requetePrepare->execute()){
            echo "bien";
        }else{
            echo "pas bien";
        }*/
    
    /**
     * Supprime le frais hors forfait lorsqu'il est reporté
     * @param type $idFHF  ID du frais hors forfait
     * @param type $leMois   Mois sous la forme aaaamm  
     */

    public function supprimerFHFReporte($idFHF,$leMois)
    {
                $requetePrepare = PdoGSB::$monPdo->prepare(
                    'DELETE FROM lignefraishorsforfait '
                    . 'WHERE lignefraishorsforfait.id = :unIdFHF '
                    . 'AND lignefraishorsforfait.mois = :unMois '  
                );
                $requetePrepare->bindParam(':unIdFHF', $idFHF, PDO::PARAM_STR);
                $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
                $requetePrepare->execute();
    }
    
    /**
     * Retourne la liste de tous les visiteurs qui ont des fiches validées.
     *
     * @return array     la liste de tous les visiteurs sous forme de tableau associatif.
     */
    public function getLesVisiteursDontFicheVA()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT distinct id, nom, prenom '
            .'FROM visiteur join fichefrais on(id=idVisiteur)'
            .'WHERE fichefrais.idEtat="VA"'   
            .'ORDER BY nom'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais validée
     *
     * @param string $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDontFicheVA()
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT distinct fichefrais.mois AS mois FROM fichefrais '
            . 'WHERE fichefrais.idetat="VA"'    
            . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
public function getLesMoisDisponiblesDontFicheVA($idVisiteur){
    $req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idVisiteur = :idVisiteur and fichefrais.idetat='VA'
    order by fichefrais.mois desc ";
    $idJeuRes = PdoGsb::$monPdo->prepare($req); 
    $idJeuRes->execute(array( ':idVisiteur' => $idVisiteur));	
    $ligne = $idJeuRes->fetch();
    $lesMois =array();
    while($ligne != null)	{
        $mois = $ligne['mois'];
        $numAnnee =substr( $mois,0,4);
        $numMois =substr( $mois,4,2);
        $lesMois["$mois"]=array(
         "mois"=>"$mois",
         "numAnnee"  => "$numAnnee",
         "numMois"  => "$numMois"
         );
        $ligne = $idJeuRes->fetch();		
    }
    return $lesMois;
}

    /**
     * Modifie l'état de la fiche de frais en passant de "VA" à "RB"
     * @param string $idVisiteur      ID du visiteur
     * @param int $leMois             Mois sous la forme aaaamm
     */
    public function updateVAtoRB($idVisiteur, $leMois)
    {
        $requetePrepare = PDOGSB::$monPdo->prepare(
            'UPDATE fichefrais'
            .'SET idEtat = "RB"'
            ."WHERE fichefrais.idVisiteur = '$idVisiteur'"
            ."AND fichefrais.mois = '$leMois'"
        );
        $requetePrepare->execute();
    }
}
?>
