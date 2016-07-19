<?php
    // On sélectionne tous les enregistrements à afficher
    $sql = '
			SELECT id_vin, vins.nom as nom_vin, annee
			FROM utilisateur_vin 
			LEFT JOIN vins ON utilisateur_vin.FK_vin = vins.id_vin
			LEFT JOIN utilisateur ON utilisateur_vin.FK_utilisateur = utilisateur.id_utilisateur
			LEFT JOIN lieu_achat ON utilisateur_vin.FK_lieu_achat = lieu_achat.id_lieu_achat
			LEFT JOIN lieu_stockage ON utilisateur_vin.FK_lieu_stockage = lieu_stockage.id_lieu_stockage
			LEFT JOIN region ON vins.FK_region = region.id_region
			LEFT JOIN type_vin ON vins.FK_type = type_vin.id_type
			LEFT JOIN appellation ON vins.FK_appellation = appellation.id_appellation
			LEFT JOIN plat ON vins.FK_type_plat = plat.id_plat
			WHERE vins.FK_region '.$condition_region.' 
			AND vins.FK_appellation '.$condition_appellation.' 
			AND vins.FK_type '.$condition_type.'
			AND (utilisateur_vin.nb_bouteilles > 0 OR utilisateur_vin.nb_bouteilles IS NOT NULL)
			AND (utilisateur_vin.FK_lieu_stockage '.$condition_stockage.' OR utilisateur_vin.FK_lieu_stockage IS NULL) 
			ORDER BY annee ASC
			LIMIT '.$current_items.', '.$posts_per_page;
    $res = mysql_query($sql);
    //echo "query : ".$sql."<br>";
    //exit;

    // On compte le nombre d'article
    $total = mysql_query('
			SELECT * FROM utilisateur_vin 
			LEFT JOIN vins ON utilisateur_vin.FK_vin = vins.id_vin
			LEFT JOIN utilisateur ON utilisateur_vin.FK_utilisateur = utilisateur.id_utilisateur
			LEFT JOIN lieu_achat ON utilisateur_vin.FK_lieu_achat = lieu_achat.id_lieu_achat
			LEFT JOIN lieu_stockage ON utilisateur_vin.FK_lieu_stockage = lieu_stockage.id_lieu_stockage
			LEFT JOIN region ON vins.FK_region = region.id_region
			LEFT JOIN type_vin ON vins.FK_type = type_vin.id_type
			LEFT JOIN appellation ON vins.FK_appellation = appellation.id_appellation
			LEFT JOIN plat ON vins.FK_type_plat = plat.id_plat
			WHERE vins.FK_region '.$condition_region.' 
			AND vins.FK_appellation '.$condition_appellation.' 
			AND (utilisateur_vin.nb_bouteilles > 0 OR utilisateur_vin.nb_bouteilles IS NOT NULL)
			AND vins.FK_type '.$condition_type.'
			AND (utilisateur_vin.FK_lieu_stockage '.$condition_stockage.' OR utilisateur_vin.FK_lieu_stockage IS NULL)
			ORDER BY annee ASC');
    $total_posts = mysql_num_rows($total);
