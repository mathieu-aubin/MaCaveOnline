<?php
	
	// $connexion pour accès PDO
	require 'connexion.php';

	/*** Initialisation variables ***/
	$erreur = false;

	// Récupération des données de l'appli
	$donnees = $_POST['donneesVin'];
	
	// Parser le json reçu 
	if(isset($donnees) && $donnees != '')
	{
		$donnees = json_decode($donnees);		
		$nom = $donnees->nom;
		$annee = $donnees->annee;
		$region = $donnees->region;
		$appellation = $donnees->appellation;
		$type = $donnees->type;
		$degreAlcool = $donnees->degreAlcool;
		$lieuStockage = $donnees->lieuStockage;
		$lieuAchat = $donnees->lieuAchat;
		$consoPartir = $donnees->consoPartir;
		$consoAvant = $donnees->consoAvant;
		$typePlat = $donnees->typePlat;
		$note = $donnees->note;
		$nbBouteilles = $donnees->nbBouteilles;
		$suiviStock = $donnees->suiviStock;
		$favori = $donnees->favori;
		$prixAchat = $donnees->prixAchat;
		$offertPar = $donnees->offertPar;
		$commentaires = $donnees->commentaires;
		$utilisateur = $donnees->utilisateur;
		$demo = 1;
	}

	// Gestion nouvelle région
	if(isNouvelleEntree($region))
	{
		// Insertion du nom de la nouvelle région
		$insertNouvelleRegion = $connexion->prepare('INSERT INTO region(region) VALUES(:region)');
		$insertNouvelleRegion->bindValue('region', $region, PDO::PARAM_STR);
		$res = $insertNouvelleRegion->execute();
		// Vérification du résultat de la requête
		// On récupère l'id crée pour l'utiliser dans les prochaines requêtes
		if($res) 
		{
			$region = $connexion->lastInsertId();
			$insertNouvelleRegion->closeCursor();
		}
		else $erreur = true;

		// Gestion nouvelle appellation si présente avec la nouvelle région
		if(isNouvelleEntree($appellation)
		{
			// Insertion de la nouvelle appellation
			$insertNouvelleAppellation = $connexion->prepare('INSERT INTO appellation(appellation, FK_region) VALUES(:aoc, :region)');
			$insertNouvelleAppellation->bindValue('aoc', $appellation, PDO::PARAM_STR);
			$insertNouvelleAppellation->bindValue('region', $region, PDO::PARAM_INT);
			$res = $insertNouvelleAppellation->execute();
			// Vérification du résultat de la requête
			// On récupère l'id crée pour l'utiliser dans les prochaines requêtes
			if($res) 
			{
				$appellation = $connexion->lastInsertId();
				$insertNouvelleAppellation->closeCursor();
			}
			else $erreur = true;
		}
	}

	// Gestion nouveau lieu d'achat
	if(isNouvelleEntree($lieuAchat))
	{
		// Insertion du nouveau lieu d'achat
		$insertNouveauLieuAchat = $connexion->prepare('INSERT INTO lieu_achat(lieu_achat) VALUES(:lieu_achat)');
		$insertNouveauLieuAchat->bindValue('lieu_achat', $lieuAchat, PDO::PARAM_STR);
		$res = $insertNouveauLieuAchat->execute();
		// Vérification du résultat de la requête
		// On récupère l'id crée pour l'utiliser dans les prochaines requêtes
		if($res) 
		{
			$lieuAchat = $connexion->lastInsertId();
			$insertNouveauLieuAchat->closeCursor();
		}
		else $erreur = true;
	}

	// Gestion nouveau lieu de stockage
	if(isNouvelleEntree($lieuStockage))
	{
		$insertNouveauLieuStockage = $connexion->prepare('INSERT INTO lieu_stockage(lieu_stockage) VALUES(:lieu_stockage)');
		$insertNouveauLieuStockage->bindValue('lieu_stockage', $lieuStockage, PDO::PARAM_STR);
		$res = $insertNouveauLieuStockage->execute();
		// Vérification du résultat de la requête
		// On récupère l'id crée pour l'utiliser dans les prochaines requêtes
		if($res) 
		{
			$lieuStockage = $connexion->lastInsertId();
			$insertNouveauLieuStockage->closeCursor();
		}
		else $erreur = true;
	}

	$req = 'INSERT INTO vins(
		FK_region,
		FK_type,
		FK_appellation,
		nom,
		annee,
		degre_alcool,
		conso_partir,
		conso_avant,
		FK_type_plat
		) 
		VALUES(
		'.$region.',
		'.$type.',
		'.$appellation.',
		"'.$nom.'",
		"'.$annee.'",
		'.$degreAlcool.',
		'.$consoPartir.',
		'.$consoAvant.',
		'.$typePlat.'
		)';
echo $req;
exit;

	// Préparation requete insertion d'un nouveau vin (non lié à un utilisateur)
	$stmt = $connexion->prepare('INSERT INTO vins(
		FK_region,
		FK_type,
		FK_appellation,
		nom,
		annee,
		degre_alcool,
		conso_partir,
		conso_avant,
		FK_type_plat
		) 
		VALUES(
		:FK_region,
		:FK_type,
		:FK_appellation,
		:nom,
		:annee,
		:degre_alcool,
		:conso_partir,
		:conso_avant,
		:FK_type_plat,
		:Demo
		)');
	$stmt->bindValue('FK_region', $region, PDO::PARAM_INT);
	$stmt->bindValue('FK_type', $type, PDO::PARAM_INT);
	$stmt->bindValue('FK_appellation', $appellation, PDO::PARAM_INT);
	$stmt->bindValue('nom', $nom, PDO::PARAM_STR);
	$stmt->bindValue('annee', $annee, PDO::PARAM_STR);
	$stmt->bindValue('degre_alcool', $degreAlcool, PDO::PARAM_STR);
	$stmt->bindValue('conso_partir', $consoPartir, PDO::PARAM_STR);
	$stmt->bindValue('conso_avant', $consoAvant, PDO::PARAM_STR);
	$stmt->bindValue('FK_type_plat', $typePlat, PDO::PARAM_STR);
	$stmt->bindValue('Demo', $demo, PDO::PARAM_STR);
	$resultatRequete = $stmt->execute();

	// on renvoie le résultat de la requête
	// On récupère l'id crée pour l'utiliser dans la prochaine requête
	if($resultatRequete) 
	{
		$idVin = $stmt->lastInsertId();
		$stmt->closeCursor();
	}
	else $erreur = true;
	//return $resultatRequete;
	

	// On vérifie si on a une nouvelle entrée pour une valeur
	function isNouvelleEntree($value)
	{
		if(gettype($value) == "string") return true;
		return false;
	}
?>