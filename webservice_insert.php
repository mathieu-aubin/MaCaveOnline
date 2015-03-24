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
	$newRegion = isNouvelleEntree($region);
	if($newRegion)
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
		$newAoc = isNouvelleEntree($appellation);
		if($newAoc)
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
	$newAchat = isNouvelleEntree($lieuAchat);
	if($newAchat)
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
	$newStockage = isNouvelleEntree($lieuStockage);
	if($newStockage)
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

	/*$req = 'INSERT INTO vins(
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
exit;*/

	// Préparation requete insertion d'un nouveau vin (non lié à un utilisateur)
	$req = 'INSERT INTO vins(FK_region,	FK_type, FK_appellation, nom, annee, degre_alcool, conso_partir, conso_avant, FK_type_plat, Demo) 
		VALUES(:FK_region, :FK_type, :FK_appellation, :nom, :annee, :degre_alcool, :conso_partir, :conso_avant, :FK_type_plat, :Demo)';
	$stmt = $connexion->prepare($req);
	$stmt->bindValue(':FK_region', $region, PDO::PARAM_INT);
	$stmt->bindValue(':FK_type', $type, PDO::PARAM_INT);
	$stmt->bindValue(':FK_appellation', $appellation, PDO::PARAM_INT);
	$stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
	$stmt->bindValue(':annee', $annee);
	$stmt->bindValue(':degre_alcool', $degreAlcool, PDO::PARAM_STR);
	$stmt->bindValue(':conso_partir', $consoPartir);
	$stmt->bindValue(':conso_avant', $consoAvant);
	$stmt->bindValue(':FK_type_plat', $typePlat, PDO::PARAM_STR);
	$stmt->bindValue(':Demo', $demo);
	$resultatRequete = $stmt->execute();

	// on renvoie le résultat de la requête
	// On récupère l'id crée pour l'utiliser dans la prochaine requête
	if($resultatRequete)
	{
		$idVin = $connexion->lastInsertId();
		$stmt->closeCursor();
		echo "IdVin : ".$idVin;
	}
	else
	{
		$erreur = true;
	}

	

	// On vérifie si on a une nouvelle entrée pour une valeur
	function isNouvelleEntree($value)
	{
		if(gettype($value) == "string") return true;
		return false;
	}
?>