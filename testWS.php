<?php

	$datas = array(
		'nom' => 'vin_test1',
		'annee' => '1937',
		'region' => 'regionTest',
		'appellation' => 1,
		'type' => 1,
		'degreAlcool' => 33,
		'lieuStockage' => 1,
		'lieuAchat' => 2,
		'consoPartir' => '2042-01-01',
		'consoAvant' => '2042-01-01',
		'typePlat' => 1,
		'note' => 20,
		'nbBouteilles' => 11,
		'suiviStock' => 1,
		'favori' => 0,
		'prixAchat' => 55,
		'offertPar' => 'Serge',
		'commentaires' => 'blébléblé',
		'utilisateur' => 3
	);

	$donneesInsert = json_encode($datas);

	$datas2 = array(
		'idVin' => 325,
		'lieuStockage' => 'lieuTest',
		'lieuAchat' => 'LieuTest',
		'consoPartir' => '2023-01-01',
		'consoAvant' => '2023-01-01',
		'typePlat' => NULL,
		'note' => 13,
		'nbBouteilles' => 5,
		'suiviStock' => 1,
		'favori' => 1,
		'prixAchat' => NULL,
		'offertPar' => 'Sergio',
		'commentaires' => 'blabla',
		'idUtilisateur' => 3
	);
	$donneesUpdate = json_encode($datas2);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Test Webservice</title>
</head>
<body>
	<form action="webservice_insert.php" method="post">
		<input type="hidden" name="donneesVin" value=<?php echo $donneesInsert; ?> >
		<input type="submit" value="Test Insert">

	</form>
	<form action="webservice_update.php" method="post">
		<input type="hidden" name="donneesVin" value=<?php echo $donneesUpdate; ?> >
		<input type="submit" value="Test Update">

	</form>
</body>
</html>