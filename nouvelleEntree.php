<?php

    function insertNewRegion($value)
    {
        global $connexion;
        // Insertion du nom de la nouvelle région
        $insertNouvelleRegion = $connexion->prepare('INSERT INTO region(region) VALUES(:region)');
        $insertNouvelleRegion->bindValue('region', utf8_encode($value), PDO::PARAM_STR);
        $res = $insertNouvelleRegion->execute();
        // Vérification du résultat de la requête
        // On récupère l'id crée pour l'utiliser dans les prochaines requêtes
        if ($res) {
            $region = $connexion->lastInsertId();
            $insertNouvelleRegion->closeCursor();

            return $region;
        } else {
            return false;
        }
    }

    function insertNewAoc($value, $region)
    {
        global $connexion;
        // Insertion de la nouvelle appellation
        $insertNouvelleAppellation = $connexion->prepare('INSERT INTO appellation(appellation, FK_region) VALUES(:aoc, :region)');
        $insertNouvelleAppellation->bindValue('aoc', utf8_encode($value), PDO::PARAM_STR);
        $insertNouvelleAppellation->bindValue('region', $region, PDO::PARAM_INT);
        $res = $insertNouvelleAppellation->execute();
        // Vérification du résultat de la requête
        // On récupère l'id crée pour l'utiliser dans les prochaines requêtes
        if ($res) {
            $appellation = $connexion->lastInsertId();
            $insertNouvelleAppellation->closeCursor();

            return $appellation;
        } else {
            return false;
        }
    }

    function insertNewLieuAchat($value)
    {
        global $connexion;
        // Insertion du nouveau lieu d'achat
        $insertNouveauLieuAchat = $connexion->prepare('INSERT INTO lieu_achat(lieu_achat) VALUES(:lieu_achat)');
        $insertNouveauLieuAchat->bindValue('lieu_achat', utf8_encode($value), PDO::PARAM_STR);
        $res = $insertNouveauLieuAchat->execute();
        // Vérification du résultat de la requête
        // On récupère l'id crée pour l'utiliser dans les prochaines requêtes
        if ($res) {
            $lieuAchat = $connexion->lastInsertId();
            $insertNouveauLieuAchat->closeCursor();

            return $lieuAchat;
        } else {
            return false;
        }
    }

    function insertNewLieuStockage($value)
    {
        global $connexion;
        $insertNouveauLieuStockage = $connexion->prepare('INSERT INTO lieu_stockage(lieu_stockage) VALUES(:lieu_stockage)');
        $insertNouveauLieuStockage->bindValue('lieu_stockage', utf8_encode($value), PDO::PARAM_STR);
        $res = $insertNouveauLieuStockage->execute();
        // Vérification du résultat de la requête
        // On récupère l'id crée pour l'utiliser dans les prochaines requêtes
        if ($res) {
            $lieuStockage = $connexion->lastInsertId();
            $insertNouveauLieuStockage->closeCursor();

            return $lieuStockage;
        } else {
            return false;
        }
    }
