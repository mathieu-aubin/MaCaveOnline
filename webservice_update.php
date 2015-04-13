<?php

    $erreur = false;
	// finchier de connexion à la BDD
	require 'connexion.php';
    // fichier de gestion des nouvelles entrées (région, AOC, lieux...)
    include_once 'nouvelleEntree.php';

	/*** REQUETES UPDATE ***/
    // Récupération des données de l'appli
    $donnees = $_POST['donneesVin'];
    
    // Parser le json reçu 
    if(isset($donnees) && $donnees != '')
    {
        $donnees = json_decode($donnees);
        $idVin = $donnees->idVin;       
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
        $idUtilisateur = $donnees->idUtilisateur;
    }

    //ctype_digit() vérifie si tous les caractères de la chaîne text sont des chiffres.
    if(!is_numeric($nbBouteilles)) $erreur_bt = "erreur";
    else $erreur_bt = '';
    
    if(!is_numeric($prixAchat) && $prixAchat != NULL) $erreur_prix = "erreur";
    else $erreur_prix = '';

    if(!is_string($offertPar) && $offertPar != NULL) $erreur_offert = "erreur";
    else $erreur_offert = '';
        
    if($commentaires != '' && $commentaires != NULL)
    {
        $commentaires = addslashes(utf8_decode($commentaires));
    }

    /* gestion des champs qui pourraient être ajoutés
        LieuAchat et LieuStockage */
    // Si on a une chaine de caractère, c'est qu'on ajoute un nouveau lieu
    if (is_string($lieuAchat)) 
    {
        /*//si on est en mode démo, on empêche l'ajout
        if($idUtilisateur == 3)
        {
            //on donne une valeur par défaut
            $lieuAchat = 1;
        }
        else 
        {*/
            $lieuAchat = insertNewLieuAchat($lieuAchat);
        
    }
    
    if (is_string($lieuStockage)) 
    {
        /*//si on est en mode démo, on empêche l'ajout
        if($idUtilisateur == 3) 
        {
            //on donne une valeur par défaut
            $lieuStockage = 1;
        }
        else 
        {*/
        $lieuStockage = insertNewLieuStockage($lieuStockage);
    }
    if($erreur_offert == '' && $erreur_bt == '' && $erreur_prix == '') 
    {   
        // préparation de la requete d'update de la fiche
        $reqUpdate = "UPDATE utilisateur_vin SET FK_utilisateur = :user, 
                                            note = :note, 
                                            nb_bouteilles = :nb_bouteilles, 
                                            suivi_stock = :suivi_stock,
                                            meilleur_vin = :meilleur_vin,
                                            prix_achat = :prix_achat,
                                            offert_par = :offert,
                                            FK_lieu_achat = :lieu_achat,
                                            FK_lieu_stockage = :lieu_stockage,
                                            conso_partir = :conso_partir,
                                            conso_avant = :conso_avant,
                                            commentaires = :commentaires
                                            WHERE FK_vin = :id_vin
                                                AND FK_utilisateur = :user";
        $exec_update = $connexion->prepare($reqUpdate);
        $exec_update->bindValue(":user", $idUtilisateur, PDO::PARAM_INT);
        $exec_update->bindValue(":note", $note, PDO::PARAM_INT);
        $exec_update->bindValue(":nb_bouteilles", $nbBouteilles, PDO::PARAM_INT);
        $exec_update->bindValue(":suivi_stock", $suiviStock, PDO::PARAM_INT);
        $exec_update->bindValue(":meilleur_vin", $favori, PDO::PARAM_INT);
        $exec_update->bindValue(":prix_achat", $prixAchat, PDO::PARAM_INT);
        $exec_update->bindValue(":offert", $offertPar, PDO::PARAM_STR);
        $exec_update->bindValue(":lieu_achat", $lieuAchat, PDO::PARAM_INT);
        $exec_update->bindValue(":lieu_stockage", $lieuStockage, PDO::PARAM_INT);
        $exec_update->bindValue(":conso_partir", $consoPartir);
        $exec_update->bindValue(":conso_avant", $consoAvant);
        $exec_update->bindValue(":commentaires", $commentaires, PDO::PARAM_STR);
        $exec_update->bindValue(":id_vin", $idVin, PDO::PARAM_INT);
        $exec_update->execute();
        $exec_update->closeCursor();

        // Erreur requête
        if($reqUpdate)
        {
            echo 'Vin modifié !<br>';
            $exec_update->closeCursor();
        }
        else
        {
            echo 'erreur requête <br>';
        }
    }
    else 
        {
            echo 'erreur offert : '.$erreur_offert.'<br>';
            echo 'erreur bt : '.$erreur_bt.'<br>';
            echo 'erreur prix : '.$erreur_prix;
        }
?>