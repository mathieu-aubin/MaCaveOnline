<?php

    $datas = [
        'nom'          => 'Les bons vins',
        'annee'        => '1937',
        'region'       => 'regionTest',
        'appellation'  => 1,
        'type'         => 1,
        'degreAlcool'  => 33,
        'lieuStockage' => 1,
        'lieuAchat'    => 2,
        'consoPartir'  => '2042-01-01',
        'consoAvant'   => '2042-01-01',
        'typePlat'     => 1,
        'note'         => 20,
        'nbBouteilles' => 11,
        'suiviStock'   => 1,
        'favori'       => 0,
        'prixAchat'    => 55,
        'offertPar'    => 'Serge',
        'commentaires' => 'blé blé blé',
        'utilisateur'  => 3,
    ];

    //echo json_encode($datas);
    $donneesInsert = json_encode($datas);

    $datas2 = [
        'idVin'         => 325,
        'lieuStockage'  => 'lieuTest',
        'lieuAchat'     => 'LieuTest',
        'consoPartir'   => '2023-01-01',
        'consoAvant'    => '2023-01-01',
        'typePlat'      => null,
        'note'          => 13,
        'nbBouteilles'  => 5,
        'suiviStock'    => 1,
        'favori'        => 1,
        'prixAchat'     => null,
        'offertPar'     => 'Sergio',
        'commentaires'  => 'blabla',
        'idUtilisateur' => 3,
    ];
    $donneesUpdate = json_encode($datas2);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Test Webservice</title>
</head>
<body>
	<div id="container" style='margin: 100px auto; width: 1200px;'>
		<form action="webservice_insert.php" method="post" style="margin-bottom: 20px;">
			<input type="hidden" name="donneesVin" value=<?php echo $donneesInsert; ?> >
			<input type="submit" value="Test Insert">
		</form>
		<form action="webservice_update.php" method="post" style="margin-bottom: 20px;">
			<input type="hidden" name="donneesVin" value=<?php echo $donneesUpdate; ?> >
			<input type="submit" value="Test Update">
		</form>
		<form action="webservice_delete.php" method="post" style="margin-bottom: 20px;">
			<label for="vinSuppr">Vin à supprimer : </label>
			<input type="text" name="vinSuppr">
			<input type="submit" value="Test Delete">
		</form>
		<form action="webservice_select.php" method="post" style="margin-bottom: 20px;">
			<input type="hidden" name="idUtilisateur" value='3' >
			<input type="submit" value="Select Vins">
		</form>
	</div>
</body>
</html>