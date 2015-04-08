<?php
	
	// $connexion pour accès PDO
	require 'connexion.php';
	include_once 'nouvelleEntree.php';

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
	if(gettype($region) == "string")
	{
		$region = insertNewRegion($region);

		// Gestion nouvelle appellation si présente avec la nouvelle région
		if(gettype($appellation) == "string")
		{
			$appellation = $insertNewAoc($appellation, $region);
		}
	}

	// Gestion nouveau lieu d'achat
	if(gettype($lieuAchat) == "string")
	{
		$lieuAchat = insertNewLieuAchat($lieuAchat);
	}

	// Gestion nouveau lieu de stockage
	if(gettype($lieuStockage) == "string")
	{
		$lieuStockage = insertNewLieuStockage($lieuStockage);
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
	if($region != false && $appellation != false && $lieuAchat != false && $lieuStockage != false)
	{
		// Préparation requete insertion d'un nouveau vin (non lié à un utilisateur)
		$insertVin = 'INSERT INTO vins(FK_region, FK_type, FK_appellation, nom, annee, degre_alcool, conso_partir, conso_avant, FK_type_plat, Demo) 
			VALUES(:FK_region, :FK_type, :FK_appellation, :nom, :annee, :degre_alcool, :conso_partir, :conso_avant, :FK_type_plat, :Demo)';
		$stmt = $connexion->prepare($insertVin);
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
			echo "IdVin : ".$idVin.'<br>';
			$insertUserVin = 'INSERT INTO utilisateur_vin(FK_vin, FK_utilisateur, note, nb_bouteilles, suivi_stock, meilleur_vin, prix_achat, offert_par, FK_lieu_achat, FK_lieu_stockage, conso_partir, conso_avant, commentaires)
				VALUES(:FK_vin, :FK_utilisateur, :note, :nb_bouteilles, :suivi_stock, :meilleur_vin, :prix_achat, :offert_par, :FK_lieu_achat, :FK_lieu_stockage, :conso_partir, :conso_avant, :commentaires)';
			$stmt = $connexion->prepare($insertUserVin);
			$stmt->bindValue(':FK_vin', $idVin, PDO::PARAM_INT);
			$stmt->bindValue(':FK_utilisateur', $utilisateur, PDO::PARAM_INT);
			$stmt->bindValue(':note', $note, PDO::PARAM_STR);
			$stmt->bindValue(':nb_bouteilles', $nbBouteilles, PDO::PARAM_STR);
			$stmt->bindValue(':suivi_stock', $suiviStock);
			$stmt->bindValue(':meilleur_vin', $favori, PDO::PARAM_STR);
			$stmt->bindValue(':prix_achat', $prixAchat);
			$stmt->bindValue(':offert_par', $offertPar);
			$stmt->bindValue(':FK_lieu_achat', $lieuAchat, PDO::PARAM_STR);
			$stmt->bindValue(':FK_lieu_stockage', $lieuStockage, PDO::PARAM_STR);
			$stmt->bindValue(':conso_partir', $consoPartir, PDO::PARAM_STR);
			$stmt->bindValue(':conso_avant', $consoAvant, PDO::PARAM_STR);
			$stmt->bindValue(':commentaires', $commentaires);
			$resRequete = $stmt->execute();

			if($resRequete)
			{
				echo 'Vin inséré !<br>';
				$stmt->closeCursor();
			}
			else
			{
				$erreur = true;
			}
		}
	}
	else
	{
		$erreur = true;
	}

	if($erreur)
	{
		echo 'ERREUR';
	}

?>