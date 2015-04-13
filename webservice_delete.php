<?php
	
	// $connexion pour accès PDO
	require 'connexion.php';

	// Récupération du vin à supprimer
	$idVin = $_POST['vinSuppr'];

	if($idVin != '' && ctype_digit($idVin))
	{
		// On supprime le vin uniquement dans la table utilisateur_vin
		$sql = "DELETE FROM utilisateur_vin WHERE FK_vin =  :idVin";
		$stmt = $connexion->prepare($sql);
		$stmt->bindParam(':idVin', $idVin);   
		$resSql = $stmt->execute();

		if($resSql) echo 'Vin supprimé ! <br>';
		else echo 'erreur';
	}
?>