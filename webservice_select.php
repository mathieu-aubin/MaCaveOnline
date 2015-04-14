<?php
	
	// $connexion pour accès PDO
	require 'connexion.php';

	/*** Initialisation variables ***/
	$idUtilisateur = $_POST['idUtilisateur'];

	// Récupération des données de l'appli
	if(isset($idUtilisateur) && $idUtilisateur != '')
	{
		$selectVins = $connexion->prepare('SELECT * FROM vins 
						LEFT JOIN utilisateur_vin 
						ON vins.id_vin = utilisateur_vin.FK_vin
						WHERE FK_utilisateur = '.$idUtilisateur.';');
		$selectVins->execute();

		$liste = $selectVins->fetchAll(PDO::FETCH_ASSOC);
		
		echo json_encode($liste, JSON_PRETTY_PRINT);
	}
	else echo 'erreur';

?>