<?php
	/* finchier de connexion à la BDD */
	require 'connexion.php';

	/*** REQUETES UPDATE ***/
	/**récupération des valeurs de chaque champ**/
        $id_vin = $_POST['id_fk_vin'];
        $nb_bouteilles = $_POST['nb_bouteilles'];
        //ctype_digit() vérifie si tous les caractères de la chaîne text sont des chiffres.
        if(!ctype_digit($nb_bouteilles)) $erreur_bt = "erreur";
        else $erreur_bt = '';
        
        if ($_POST['select_note_fiche'] == '') $note = NULL;
        else $note = $_POST['select_note_fiche'];
        
        if (isset($_POST['suivi_stock'])) $suivi_stock = 1;
        else $suivi_stock = 0;
        
        if (isset($_POST['meilleur_vin'])) $meilleur_vin = 1;
        else $meilleur_vin = 0;
        
        if ($_POST['prix_achat'] == '') $prix_achat_edit = NULL;
        else $prix_achat_edit = $_POST['prix_achat'];
        if(!is_numeric($prix_achat_edit) && $prix_achat_edit != NULL) $erreur_prix = "erreur";
        else $erreur_prix = '';
        
        if ($_POST['offert_par'] == '') $offert_par_edit = NULL;
        else $offert_par_edit = $_POST['offert_par'];

        if(!is_string($offert_par_edit) && $offert_par_edit != NULL) $erreur_offert = "erreur";
        else $erreur_offert = '';
        
        if ($_POST['select_lieu_achat'] == '') $lieu_achat = NULL;
        else $lieu_achat = $_POST['select_lieu_achat'];
        
        if ($_POST['select_lieu_stockage'] == '') $lieu_stockage = NULL;
        else $lieu_stockage = $_POST['select_lieu_stockage'];
        
        if ($_POST['select_conso_partir'] == '') $conso_partir = NULL;
        else {
            $annee = $_POST['select_conso_partir'];
            $conso_partir = $annee."-01-01";
        } 
        
        if ($_POST['select_conso_max'] == '') $conso_avant = NULL;
        else {
          $annee = $_POST['select_conso_max'];
          $conso_avant = $annee."-12-31";  
        } 
        
        if ($_POST['commentaires'] == '') $commentaires = NULL;
        else {
            if($_POST['commentaires'] != '') $commentaires = utf8_decode($_POST['commentaires']);
            $commentaires = addslashes($commentaires);
        } 

        /**requete d'ajout d'un nouveau lieu
           si option autre dans lieu achat
           on récupère la valeur entrée pour l'ajouter à la table lieu_achat**/
        if ($lieu_achat == 'autre') {
            //si on est en mode démo, on empêche l'ajout
            if($_SESSION['user'] == 3) {
                //on donne une valeur par défaut
                $lieu_achat = 1;
            }
            else {
                $new_lieu_achat = $_POST['achat_modif'];
                $req_achat = "INSERT INTO lieu_achat(lieu_achat) VALUES(:lieu_achat)";
                $res_achat = $connexion->prepare($req_achat);
                $res_achat->bindValue(":lieu_achat", $new_lieu_achat, PDO::PARAM_STR);
                $res_achat->execute();
                /**on récupère l'id du lieu crée pour l'ajouter sur la fiche**/
                $lieu_achat = $connexion->lastinsertid();
                $res_achat->closeCursor();
            }
        }
        /**si option autre dans lieu stockage
           on récupère la valeur entrée pour l'ajouter à la table lieu_stockage**/
        if ($lieu_stockage == 'autre') {
            //si on est en mode démo, on empêche l'ajout
            if($_SESSION['user'] == 3) {
                //on donne une valeur par défaut
                $lieu_stockage = 1;
            }
            else {
                $new_lieu_stockage = $_POST['stockage_modif'];
                $req_stockage = "INSERT INTO lieu_stockage(lieu_stockage) VALUES(:lieu_stockage)";
                $res_stockage = $connexion->prepare($req_stockage);
                $res_stockage->bindValue(":lieu_stockage", $new_lieu_stockage, PDO::PARAM_STR);
                $res_stockage->execute();
                /**on récupère l'id du lieu crée pour l'ajouter sur la fiche**/
                $lieu_stockage = $connexion->lastinsertid();
                $res_stockage->closeCursor();
            }
        }
        if($erreur_offert == '' && $erreur_bt == '' && $erreur_prix == '') {   
            /**préparation de la requete d'update de la fiche**/
            $req = "UPDATE utilisateur_vin SET FK_utilisateur = :user, 
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
            $exec_update = $connexion->prepare($req);
            $exec_update->bindValue(":user", $_SESSION['user'], PDO::PARAM_INT);
            $exec_update->bindValue(":note", $note, PDO::PARAM_INT);
            $exec_update->bindValue(":nb_bouteilles", $nb_bouteilles, PDO::PARAM_INT);
            $exec_update->bindValue(":suivi_stock", $suivi_stock, PDO::PARAM_INT);
            $exec_update->bindValue(":meilleur_vin", $meilleur_vin, PDO::PARAM_INT);
            $exec_update->bindValue(":prix_achat", $prix_achat_edit, PDO::PARAM_INT);
            $exec_update->bindValue(":offert", $offert_par_edit, PDO::PARAM_STR);
            $exec_update->bindValue(":lieu_achat", $lieu_achat, PDO::PARAM_INT);
            $exec_update->bindValue(":lieu_stockage", $lieu_stockage, PDO::PARAM_INT);
            $exec_update->bindValue(":conso_partir", $conso_partir, PDO::PARAM_INT);
            $exec_update->bindValue(":conso_avant", $conso_avant, PDO::PARAM_INT);
            $exec_update->bindValue(":commentaires", $commentaires, PDO::PARAM_STR);
            $exec_update->bindValue(":id_vin", $id_vin, PDO::PARAM_INT);
            $exec_update->execute();
            $exec_update->closeCursor();


?>