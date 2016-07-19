<script src='js/option_autre.js'></script>
<?php
session_start();
if (isset($_SESSION['login']) && isset($_SESSION['pass'])) {
    /** fichier de connexion à la BDD **/
    include 'connexion.php';

/* construction de la liste des AOC dynamiquement suite à la sélection d'une région **/
    if (isset($_POST['region'])) {
        $region = $_POST['region'];
        if ($region != 6 && $region != 12) {
            /**affichage de la liste des appellations en fonction de la région sélectionnée**/
            $requete = 'SELECT id_appellation, appellation FROM appellation WHERE FK_region = '.$region.' ORDER BY appellation ASC';
            /**execution de la requete**/
            if ($region != '') {
                $select = $connexion->query($requete);
                $select->setFetchMode(PDO::FETCH_ASSOC);
                /**on parcourt chaque ligne**/
                echo "<label class='control-label' for='select_appellation'>AOC</label><select id='select_appellation' name='select_appellation' class='form-control select'> <option value=''>Choisir une AOC</option>";
                while ($ligne = $select->fetch()) {
                    /**on stocke les valeurs dans un tableau**/
                    $id_appellation = $ligne['id_appellation'];
                    $appellation = $ligne['appellation'];
                    echo '<option value='.$id_appellation.'>'.utf8_encode($appellation).'</option>';
                }
                echo '</select>';
                $select->closeCursor(); /* on ferme le curseur des résultats**/
            }
        }
    }

/* construction de la fiche du vin sélectionné (fenêtre modale) **/
    if (isset($_POST['id_vin'])) {
        $id_vin = $_POST['id_vin'];
        $requete = 'SELECT 
                        FK_vin, 
                        FK_utilisateur,
                        note, 
                        nb_bouteilles, 
                        suivi_stock, 
                        meilleur_vin, 
                        prix_achat, 
                        offert_par, 
                        YEAR(utilisateur_vin.conso_partir) as conso_partir, 
                        YEAR(utilisateur_vin.conso_avant) as conso_avant, 
                        lieu_achat.lieu_achat, 
                        commentaires, 
                        lieu_stockage.lieu_stockage, 
                        vins.nom, 
                        vins.annee, 
                        appellation.appellation,
                        type_vin.id_type   
                    FROM utilisateur_vin 
                    LEFT JOIN vins 
                        ON utilisateur_vin.FK_vin = vins.id_vin
                    LEFT JOIN appellation 
                        ON vins.FK_appellation = appellation.id_appellation
                    LEFT JOIN lieu_achat 
                        ON utilisateur_vin.FK_lieu_achat = lieu_achat.id_lieu_achat
                    LEFT JOIN lieu_stockage 
                        ON utilisateur_vin.FK_lieu_stockage = lieu_stockage.id_lieu_stockage
                    LEFT JOIN type_vin 
                        ON vins.FK_type = type_vin.id_type
                    WHERE FK_vin ='.$id_vin.'
                        AND FK_utilisateur = '.$_SESSION['user'];
        //echo $requete;
        $select = $connexion->query($requete);
        $select->setFetchMode(PDO::FETCH_ASSOC);

        while ($ligne = $select->fetch()) {
            $nom_vin_fiche = utf8_encode($ligne['nom']);
            $aoc_fiche = utf8_encode($ligne['appellation']);
            $type_fiche = $ligne['id_type'];
            $annee_fiche = $ligne['annee'];
            $note_fiche = $ligne['note'];
            $nb_bouteilles_fiche = $ligne['nb_bouteilles'];
            $suivi_stock_fiche = $ligne['suivi_stock'];
            $meilleur_vin_fiche = $ligne['meilleur_vin'];
            $prix_achat_fiche = $ligne['prix_achat'];
            $offert_par_fiche = utf8_encode($ligne['offert_par']);
            $lieu_achat_fiche = utf8_encode($ligne['lieu_achat']);
            $lieu_stockage_fiche = utf8_encode($ligne['lieu_stockage']);
            $conso_partir_fiche = $ligne['conso_partir'];
            $conso_avant_fiche = $ligne['conso_avant'];
            $commentaires_fiche = utf8_encode($ligne['commentaires']);
        }
        if ($aoc_fiche !== '' && $aoc_fiche !== null) {
            $aoc_fiche .= ' - ';
        }
        if ($note_fiche == null) {
            $note_fiche = '<em>Non renseigné</em>';
        }
        if ($nb_bouteilles_fiche == null) {
            $nb_bouteilles_fiche = '<em>Non renseigné</em>';
        }
        if ($suivi_stock_fiche == 0) {
            $suivi_stock_fiche = 'Non';
        } else {
            $suivi_stock_fiche = 'Oui';
        }
        if ($meilleur_vin_fiche == 0) {
            $meilleur_vin_fiche = '<em>Non renseigné</em>';
        } else {
            $meilleur_vin_fiche = 'Oui';
        }
        if ($prix_achat_fiche == null) {
            $prix_achat_fiche = '<em>Non renseigné</em>';
            $prix_achat_edit = '';
        } else {
            $prix_achat_edit = $prix_achat_fiche;
        }
        if ($offert_par_fiche == null) {
            $offert_par_fiche = '<em>Non renseigné</em>';
            $offert_par_edit = '';
        } else {
            $offert_par_edit = $offert_par_fiche;
        }
        if ($lieu_achat_fiche == null) {
            $lieu_achat_fiche = '<em>Non renseigné</em>';
        }
        if ($lieu_stockage_fiche == null) {
            $lieu_stockage_fiche = '<em>Non renseigné</em>';
        }
        if ($conso_partir_fiche == null) {
            $conso_partir_fiche = '<em>Non renseigné</em>';
        }
        if ($conso_avant_fiche == null) {
            $conso_avant_fiche = '<em>Non renseigné</em>';
        }
        if ($commentaires_fiche == null) {
            $commentaires_fiche = '-';
        }

        /* si on a cliquer sur le vin, on gère l'echo pour l'affichage de la fiche **/
        if (isset($_POST['id_vin'])) {
            switch ($type_fiche) {
                case 1: $type_fiche = 'blanc';
                break;
                case 2: $type_fiche = 'rouge';
                break;
                case 3: $type_fiche = 'rose';
                break;
                case 4: $type_fiche = 'mousseux';
                break;
            }
            echo "<div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h3 class='modal-title'>".$nom_vin_fiche.' - '.$aoc_fiche.''.$annee_fiche."</h3>
                </div>
                <div class='contenu_fiche'>
                    <div class='modal-body'>
                        <div class='hidden-xs col-sm-6 col-md-6 col-lg-6 bouteille_".$type_fiche."'></div>
                        <dl class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
                            <dt>Nombre de bouteilles :</dt>
                            <dd>".$nb_bouteilles_fiche.'</dd>
                            <dt>Note :</dt>
                            <dd>'.$note_fiche.'</dd>
                            <dt>Suivi du stock :</dt>
                            <dd>'.$suivi_stock_fiche.'</dd>
                            <dt>Meilleur vin :</dt>
                            <dd>'.$meilleur_vin_fiche."</dd>
                            <dt>Prix d'achat :</dt>
                            <dd>".$prix_achat_fiche.'</dd>
                            <dt>Offert par :</dt>
                            <dd>'.$offert_par_fiche."</dd>
                            <dt>Lieu d'achat :</dt>
                            <dd>".$lieu_achat_fiche.'</dd>
                            <dt>Lieu de stockage :</dt>
                            <dd>'.$lieu_stockage_fiche.'</dd>
                            <dt>'.'A consommer à partir de :'.'</dt>
                            <dd>'.$conso_partir_fiche.'</dd>
                            <dt>A consommer avant :</dt>
                            <dd>'.$conso_avant_fiche.'</dd>
                            <dt>Commentaires :</dt>
                            <dd>'.$commentaires_fiche."</dd>
                        </dl>
                    </div>
                </div>
                <div class='modal-footer'>
                    <form method='post' action='modifier_fiche.php'>
                        <input type='hidden' name='id_vin_modif' id='id_vin_modif' value = '".$id_vin."'>
                        <input type='submit' class='btn btn-default' name='modif_fiche' value='Modifier la fiche'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Fermer</button>
                    </form>
                </div>
            ";
        }
        $select->closeCursor();
    }

/**insertion des modifications dans la BDD**/
    if (isset($_POST['modifier'])) {
        /**récupération des valeurs de chaque champ**/
        $id_vin = $_POST['id_fk_vin'];
        $nb_bouteilles = $_POST['nb_bouteilles'];
        //ctype_digit() vérifie si tous les caractères de la chaîne text sont des chiffres.
        if (!ctype_digit($nb_bouteilles)) {
            $erreur_bt = 'erreur';
        } else {
            $erreur_bt = '';
        }

        if ($_POST['select_note_fiche'] == '') {
            $note = null;
        } else {
            $note = $_POST['select_note_fiche'];
        }

        if (isset($_POST['suivi_stock'])) {
            $suivi_stock = 1;
        } else {
            $suivi_stock = 0;
        }

        if (isset($_POST['meilleur_vin'])) {
            $meilleur_vin = 1;
        } else {
            $meilleur_vin = 0;
        }

        if ($_POST['prix_achat'] == '') {
            $prix_achat_edit = null;
        } else {
            $prix_achat_edit = $_POST['prix_achat'];
        }
        if (!is_numeric($prix_achat_edit) && $prix_achat_edit != null) {
            $erreur_prix = 'erreur';
        } else {
            $erreur_prix = '';
        }

        if ($_POST['offert_par'] == '') {
            $offert_par_edit = null;
        } else {
            $offert_par_edit = $_POST['offert_par'];
        }

        if (!is_string($offert_par_edit) && $offert_par_edit != null) {
            $erreur_offert = 'erreur';
        } else {
            $erreur_offert = '';
        }

        if ($_POST['select_lieu_achat'] == '') {
            $lieu_achat = null;
        } else {
            $lieu_achat = $_POST['select_lieu_achat'];
        }

        if ($_POST['select_lieu_stockage'] == '') {
            $lieu_stockage = null;
        } else {
            $lieu_stockage = $_POST['select_lieu_stockage'];
        }

        if ($_POST['select_conso_partir'] == '') {
            $conso_partir = null;
        } else {
            $annee = $_POST['select_conso_partir'];
            $conso_partir = $annee.'-01-01';
        }

        if ($_POST['select_conso_max'] == '') {
            $conso_avant = null;
        } else {
            $annee = $_POST['select_conso_max'];
            $conso_avant = $annee.'-12-31';
        }

        if ($_POST['commentaires'] == '') {
            $commentaires = null;
        } else {
            if ($_POST['commentaires'] != '') {
                $commentaires = utf8_decode($_POST['commentaires']);
            }
            $commentaires = addslashes($commentaires);
        }

        /**requete d'ajout d'un nouveau lieu
           si option autre dans lieu achat
           on récupère la valeur entrée pour l'ajouter à la table lieu_achat**/
        if ($lieu_achat == 'autre') {
            //si on est en mode démo, on empêche l'ajout
            if ($_SESSION['user'] == 3) {
                //on donne une valeur par défaut
                $lieu_achat = 1;
            } else {
                $new_lieu_achat = addslashes(utf8_decode($_POST['achat_modif']));
                $req_achat = 'INSERT INTO lieu_achat(lieu_achat) VALUES(:lieu_achat)';
                $res_achat = $connexion->prepare($req_achat);
                $res_achat->bindValue(':lieu_achat', $new_lieu_achat, PDO::PARAM_STR);
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
            if ($_SESSION['user'] == 3) {
                //on donne une valeur par défaut
                $lieu_stockage = 1;
            } else {
                $new_lieu_stockage = addslashes(utf8_decode($_POST['stockage_modif']));
                $req_stockage = 'INSERT INTO lieu_stockage(lieu_stockage) VALUES(:lieu_stockage)';
                $res_stockage = $connexion->prepare($req_stockage);
                $res_stockage->bindValue(':lieu_stockage', $new_lieu_stockage, PDO::PARAM_STR);
                $res_stockage->execute();
                /**on récupère l'id du lieu crée pour l'ajouter sur la fiche**/
                $lieu_stockage = $connexion->lastinsertid();
                $res_stockage->closeCursor();
            }
        }
        if ($erreur_offert == '' && $erreur_bt == '' && $erreur_prix == '') {
            /**préparation de la requete d'update de la fiche**/
            $req = 'UPDATE utilisateur_vin SET FK_utilisateur = :user, 
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
                                                    AND FK_utilisateur = :user';
            $exec_update = $connexion->prepare($req);
            $exec_update->bindValue(':user', $_SESSION['user'], PDO::PARAM_INT);
            $exec_update->bindValue(':note', $note, PDO::PARAM_INT);
            $exec_update->bindValue(':nb_bouteilles', $nb_bouteilles, PDO::PARAM_INT);
            $exec_update->bindValue(':suivi_stock', $suivi_stock, PDO::PARAM_INT);
            $exec_update->bindValue(':meilleur_vin', $meilleur_vin, PDO::PARAM_INT);
            $exec_update->bindValue(':prix_achat', $prix_achat_edit, PDO::PARAM_INT);
            $exec_update->bindValue(':offert', $offert_par_edit, PDO::PARAM_STR);
            $exec_update->bindValue(':lieu_achat', $lieu_achat, PDO::PARAM_INT);
            $exec_update->bindValue(':lieu_stockage', $lieu_stockage, PDO::PARAM_INT);
            $exec_update->bindValue(':conso_partir', $conso_partir, PDO::PARAM_INT);
            $exec_update->bindValue(':conso_avant', $conso_avant, PDO::PARAM_INT);
            $exec_update->bindValue(':commentaires', $commentaires, PDO::PARAM_STR);
            $exec_update->bindValue(':id_vin', $id_vin, PDO::PARAM_INT);
            $exec_update->execute();
            $exec_update->closeCursor();

            /*$reqtest = "UPDATE utilisateur_vin SET FK_utilisateur = 2,note = ".$note.",nb_bouteilles = ".$nb_bouteilles.",suivi_stock = ".$suivi_stock.",meilleur_vin = ".$meilleur_vin.",
                                                prix_achat = ".$prix_achat_edit.",offert_par = ".$offert_par_edit.",FK_lieu_achat = ".$lieu_achat.",FK_lieu_stockage = ".$lieu_stockage.",conso_partir = ".$conso_partir.",conso_avant = ".$conso_avant.",commentaires = '".$commentaires."'
                                                WHERE FK_vin = ".$id_vin;
            echo("<br>".$reqtest);*/
            header('location:accueil.php?modif=ok');
        } else {
            if ($erreur_bt != '') {
                $err_bt = 'err_bt=1&';
            } else {
                $err_bt = 'err_bt=0&';
            }
            if ($erreur_offert != '') {
                $err_off = 'err_off=1&';
            } else {
                $err_off = 'err_off=0&';
            }
            if ($erreur_prix != '') {
                $err_pri = 'err_pri=1&';
            } else {
                $err_pri = 'err_pri=0&';
            }
            header('location:modifier_fiche.php?'.$err_bt.$err_off.$err_pri);
        }
    }

/**insertion d'un nouveau vin et de sa fiche dans la BDD**/
    if (isset($_POST['ajouter'])) {
        /**récupération des valeurs pour la table vins pour ajouter le vin dans la BDD (sans la colonne type_plat)
        champs obligatoires : Nom, Année, Région, AOC et Type de vin**/
        $erreur = '';
        $erreur_vin = '';
        /**nom du vin**/
        $nom_vin = addslashes(utf8_decode($_POST['nom_vin']));
        /**gestion erreur sur nom du vin (champ vide)**/
        if ($nom_vin == '') {
            $erreur .= 'nom';
        }
        /**année du vin**/
        $annee = $_POST['annee_vin'];
        /**gestion erreur pour l'année (non renseignée)**/
        if ($annee == '') {
            $erreur .= '_annee';
        }
        /**region du vin**/
        $region = $_POST['select_region'];
        /**gestion erreur sur region**/
        if ($region == '') {
            $erreur .= '_region';
        }
        /**type de vin**/
        $type = $_POST['select_type'];
        /**gestion erreur sur type de vin**/
        if ($type == '') {
            $erreur .= '_type';
        }
        /**vérification si le vin n'est pas déjà en base de données : Nom+Année+Type**/
        $verif_vin = "SELECT id_vin FROM vins WHERE nom='".$nom_vin."' AND annee=".$annee.' AND FK_type='.$type;
        $res_verif = $connexion->query($verif_vin);
        $vin_exist = $res_verif->fetch();
        if ($res_verif->rowCount() > 0) {
            $erreur_vin = $vin_exist[0];
        }
        $res_verif->closeCursor();

        /**si nouvelle région**/
        if ($region == 'autre') {
            $new_region = addslashes(utf8_decode($_POST['region_ajout']));
            /**on regarde si la region n'est pas déjà dans la table**/
            $check_region = "SELECT region FROM region WHERE region = '".$new_region."'";
            $res_check = $connexion->query($check_region);
            /**si on a dejà un nom, on renvoie une info**/
            if ($res_check->rowCount() > 0) {
                $nom_existant = utf8_decode('Région dejà existante. Veuillez la sélectionner dans la liste.');
            }
            /**sinon**/
            if (!(isset($nom_existant))) {
                //si on est en mode démo, on empêche l'ajout
                if ($_SESSION['user'] == 3) {
                    //on donne une valeur par défaut
                    $region = 1;
                } else {
                    /**insérer la nouvelle région dans la table region**/
                    $req_region = 'INSERT INTO region(region) VALUES(:region)';
                    $res_region = $connexion->prepare($req_region);
                    $res_region->bindValue(':region', $new_region);
                    $res_region->execute();
                    /**récupérer l'id de la nouvelle region pour la requete suivante**/
                    $region = $connexion->lastinsertid();
                    $res_region->closeCursor();
                }
            }
            $res_check->closeCursor();
        }
        /**si nouvelle région, on a aussi une AOC**/
        $new_aoc = '';
        if (isset($_POST['aoc_new'])) {
            $new_aoc = addslashes(utf8_decode($_POST['aoc_new']));
        }
        /**si AOC via liste déroulante**/
        elseif (isset($_POST['select_aoc'])) {
            /* si nouvelle AOC**/
            if ($_POST['select_aoc'] == 'autre') {
                $new_aoc = $_POST['aoc_ajout'];
            }
            $aoc = $_POST['select_aoc'];
        } else {
            $aoc = '';
        }

        if ($new_aoc != '') {
            //si on est en mode démo, on empêche l'ajout
            if ($_SESSION['user'] == 3) {
                //on donne une valeur par défaut
                $aoc = 1;
            } else {
                /**insérer la nouvelle AOC dans la table appellation**/
                $req_aoc = 'INSERT INTO appellation(appellation, FK_region) VALUES(:aoc, :region)';
                $res_aoc = $connexion->prepare($req_aoc);
                $res_aoc->bindValue(':aoc', $new_aoc);
                $res_aoc->bindValue(':region', $region);
                $res_aoc->execute();
                /**récupérer l'id de la nouvelle AOC pour la requete suivante**/
                $aoc = $connexion->lastinsertid();
                $res_aoc->closeCursor();
            }
        }
        if ($aoc == '') {
            $aoc = null;
        }

        if ($_POST['degre_alcool'] == '') {
            $degre_alcool = null;
        } else {
            $degre_alcool = $_POST['degre_alcool'];
        }
        //on regarde si on est en mode démo ou non
        $demo = 0;
        if ($_SESSION['user'] == 3) {
            $demo = 1;
        }
        /**si pas d'erreur**/
        if ($erreur == '' && $erreur_vin == '') {
            $req_vins = 'INSERT INTO vins(FK_region, FK_type, FK_appellation, nom, annee, degre_alcool, demo)
                            VALUES (:region, :type, :aoc, :nom, :annee, :degre_alcool, :demo)';
            /*$req_vins = "INSERT INTO vins(FK_region, FK_type, FK_appellation, nom, annee, degre_alcool, demo)
                            VALUES (".$region.", ".$type.", ".$aoc.", '".$nom_vin."', ".$annee.", ".$degre_alcool.", ".$demo.")";
            */
            $insert_vins = $connexion->prepare($req_vins);
            $insert_vins->bindValue(':region', $region);
            $insert_vins->bindValue(':type', $type);
            $insert_vins->bindValue(':aoc', $aoc);
            $insert_vins->bindValue(':nom', $nom_vin);
            $insert_vins->bindValue(':annee', $annee);
            $insert_vins->bindValue(':degre_alcool', $degre_alcool);
            $insert_vins->bindValue(':demo', $demo);
            $insert_vins->execute();
            /**on récupère l'id du vin crée**/
            $id_vin = $connexion->lastinsertid();
            $insert_vins->closeCursor();
            //echo $req_vins."<br>";

            /**récupération des valeurs pour la table utilisateur_vin**/
            //note
            if ($_POST['note'] == '') {
                $note = null;
            } else {
                $note = $_POST['note'];
            }
            //nb_bouteilles
            if ($_POST['nb_bouteilles'] == '') {
                $nb_bouteilles = null;
            } else {
                $nb_bouteilles = $_POST['nb_bouteilles'];
            }
            //vérification de la valeur du champ
            if (!ctype_digit($nb_bouteilles)) {
                $erreur .= '_bouteilles';
            } else {
                $erreur .= '_';
            }
            //suivi stock
            if (isset($_POST['suivi_stock'])) {
                $suivi_stock = 1;
            } else {
                $suivi_stock = 0;
            }
            //meilleur_vin
            if (isset($_POST['meilleur_vin'])) {
                $meilleur_vin = 1;
            } else {
                $meilleur_vin = 0;
            }
            //prix achat
            if ($_POST['prix_achat'] == '') {
                $prix_achat = null;
            } else {
                $prix_achat = $_POST['prix_achat'];
            }
            //vérification de la valeur du champ
            if (!is_numeric($prix_achat) && $prix_achat != null) {
                $erreur .= '_prix';
            } else {
                $erreur .= '_';
            }
            //offert par
            if ($_POST['offert_par'] == '') {
                $offert_par = null;
            } else {
                $offert_par = $_POST['offert_par'];
            }
            //vérification de la valeur du champ
            if (!is_string($offert_par) && $offert_par != null) {
                $erreur .= '_offert';
            } else {
                $erreur .= '_';
            }
            //lieu achat
            if ($_POST['select_achat'] == '') {
                $lieu_achat = null;
            } else {
                $lieu_achat = $_POST['select_achat'];
            }
            /**si option autre dans lieu achat
              on récupère la valeur entrée pour l'ajouter à la table lieu_achat**/
            if ($lieu_achat == 'autre') {
                //si on est en mode démo, on empêche l'ajout
                if ($_SESSION['user'] == 3) {
                    //on donne une valeur par défaut
                    $lieu_achat = 1;
                } else {
                    $new_lieu_achat = utf8_decode($_POST['achat_ajout']);
                    $req_achat = 'INSERT INTO lieu_achat(lieu_achat) VALUES(:lieu_achat)';
                    $res_achat = $connexion->prepare($req_achat);
                    $res_achat->bindValue(':lieu_achat', $new_lieu_achat);
                    $res_achat->execute();
                    /**on récupère l'id du lieu crée pour l'ajouter sur la fiche**/
                    $lieu_achat = $connexion->lastinsertid();
                    $res_achat->closeCursor();
                }
            }
            //lieu stockage
            if ($_POST['select_stockage'] == '') {
                $lieu_stockage = null;
            } else {
                $lieu_stockage = $_POST['select_stockage'];
            }
            /**si option autre dans lieu stockage
              on récupère la valeur entrée pour l'ajouter à la table lieu_stockage**/
            if ($lieu_stockage == 'autre') {
                //si on est en mode démo, on empêche l'ajout
                if ($_SESSION['user'] == 3) {
                    //on donne une valeur par défaut
                    $lieu_stockage = 1;
                } else {
                    $new_lieu_stockage = utf8_decode($_POST['stockage_ajout']);
                    $req_stockage = 'INSERT INTO lieu_stockage(lieu_stockage) VALUES(:lieu_stockage)';
                    $res_stockage = $connexion->prepare($req_stockage);
                    $res_stockage->bindValue(':lieu_stockage', $new_lieu_stockage);
                    $res_stockage->execute();
                    /**on récupère l'id du lieu crée pour l'ajouter sur la fiche**/
                    $lieu_stockage = $connexion->lastinsertid();
                    $res_stockage->closeCursor();
                }
            }
            //année de consommation à partir de
            if ($_POST['conso_partir'] == '') {
                $conso_partir = null;
            } else {
                $conso_partir = $_POST['conso_partir'].'-01-01';
            }
            //année consommation maximum
            if ($_POST['annee_conso_avant'] == '') {
                $conso_avant = null;
            } else {
                $conso_avant = $_POST['annee_conso_avant'].'-12-31';
            }
            //commentaires
            if ($_POST['commentaires'] == '') {
                $commentaires = null;
            } else {
                $commentaires = $_POST['commentaires'];
            }

            /*$req_user_vin = "INSERT INTO utilisateur_vin(FK_vin, FK_utilisateur, note, nb_bouteilles, suivi_stock, meilleur_vin, prix_achat, offert_par, FK_lieu_achat, FK_lieu_stockage, conso_partir, conso_avant, commentaires)
                             echo ($id_vin.", ".$_SESSION['user'].", ".$note.", ".$nb_bouteilles.", ".$suivi_stock.", ".$meilleur_vin.", ".$prix_achat.", ".$offert_par.", ".$lieu_achat.", ".$lieu_stockage.", ".$conso_partir.", ".$conso_avant.", ".$commentaires);
           // echo $req_user_vin;*/
            $req_user_vin = 'INSERT INTO utilisateur_vin(FK_vin, FK_utilisateur, note, nb_bouteilles, suivi_stock, meilleur_vin, prix_achat, offert_par, FK_lieu_achat, FK_lieu_stockage, conso_partir, conso_avant, commentaires)
                            VALUES (:id_vin, :user, :note, :nb_bouteilles, :suivi_stock, :meilleur_vin, :prix_achat, :offert_par, :lieu_achat, :lieu_stockage, :conso_partir, :conso_avant, :commentaires)';
            $insert_user_vin = $connexion->prepare($req_user_vin);
            $insert_user_vin->bindValue(':id_vin', $id_vin);
            $insert_user_vin->bindValue(':user', $_SESSION['user']);
            $insert_user_vin->bindValue(':note', $note);
            $insert_user_vin->bindValue(':nb_bouteilles', $nb_bouteilles);
            $insert_user_vin->bindValue(':suivi_stock', $suivi_stock);
            $insert_user_vin->bindValue(':meilleur_vin', $meilleur_vin);
            $insert_user_vin->bindValue(':prix_achat', $prix_achat);
            $insert_user_vin->bindValue(':offert_par', $offert_par);
            $insert_user_vin->bindValue(':lieu_achat', $lieu_achat);
            $insert_user_vin->bindValue(':lieu_stockage', $lieu_stockage);
            $insert_user_vin->bindValue(':conso_partir', $conso_partir);
            $insert_user_vin->bindValue(':conso_avant', $conso_avant);
            $insert_user_vin->bindValue(':commentaires', $commentaires);
            $insert_user_vin->execute();
            $insert_user_vin->closeCursor();

            header('location:ajouter.php?confirm=ok');
        } else {
            if ($erreur != '') {
                header('location:ajouter.php?err='.$erreur);
            }
            if ($erreur_vin != '') {
                header('location:ajouter.php?err2='.$erreur_vin);
            }
        }
    }
} else {
    header('location:accueil.php');
}
?>