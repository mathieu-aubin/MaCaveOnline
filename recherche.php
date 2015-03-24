<?php
	session_start ();
	if(isset($_SESSION['login']) && isset($_SESSION['pass'])) {
	require 'connexion.php';
	include 'pagination/head.pagination.php';
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bienvenue</title>
	<!-- Fichiers CSS -->
	<link href="css/bootstrap/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
	<link href="css/jquery/jquery-custom.min.css" rel="stylesheet" media="screen">
	<link href="css/style.css" rel="stylesheet" media="screen">
	<!-- Fichiers Js -->	
	<script src="js/jquery/jquery2.min.js"></script>
	<script src="js/jquery/jquery-custom.js"></script>
	<script src="js/jquery/bootbox.min.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
	<script src="js/datalist.js"></script>
	<script src="js/ajax.js"></script>
	<script src="js/fiche_vin.js"></script>
	<script>
		function center_pagination() {
    		if(document.getElementById('listing_vins') !== null && document.getElementById('navPage') !== null){
	    		var pagination = document.getElementById('listing_vins').offsetWidth;
				document.getElementById('navPage').style.width = pagination+"px";
			}
		};
		$( document ).ready(function() {
    		center_pagination();
		});</script>
</head>
<body>
		<?php
			include 'header.php';
		?>
		<section>
			<?php
				//récupération de la valeur de la recherche
				if(isset($_GET['search']) || isset($_GET['paged'])) {
					if(isset($_GET['search'])) $nom = $_GET['search'];
					if(isset($_GET['paged'])) $nom = $_GET['search'];
					$req = "
							SELECT id_vin, vins.nom, annee, appellation.appellation, type_vin.type, utilisateur_vin.FK_utilisateur, utilisateur_vin.nb_bouteilles, vins.FK_appellation, vins.FK_type
							FROM utilisateur_vin
							LEFT JOIN vins 
								ON utilisateur_vin.FK_vin = id_vin
							LEFT JOIN appellation 
								ON appellation.id_appellation = vins.FK_appellation
							LEFT JOIN type_vin 
								ON type_vin.id_type = vins.FK_type
							WHERE vins.nom
								LIKE '%".$nom."%'
								AND FK_utilisateur = ".$_SESSION['user']." 
								AND (utilisateur_vin.nb_bouteilles > 0 AND utilisateur_vin.nb_bouteilles IS NOT NULL) 
							LIMIT ".$current_items.", ".$posts_per_page
						;
						$req2 = "
							SELECT id_vin, vins.nom, annee, appellation.appellation, type_vin.type, utilisateur_vin.FK_utilisateur, utilisateur_vin.nb_bouteilles, vins.FK_appellation, vins.FK_type
							FROM utilisateur_vin
							LEFT JOIN vins 
								ON utilisateur_vin.FK_vin = id_vin
							LEFT JOIN appellation 
								ON appellation.id_appellation = vins.FK_appellation
							LEFT JOIN type_vin 
								ON type_vin.id_type = vins.FK_type
							WHERE vins.nom
								LIKE '%".$nom."%'
								AND FK_utilisateur = ".$_SESSION['user']." 
								AND (utilisateur_vin.nb_bouteilles > 0 AND utilisateur_vin.nb_bouteilles IS NOT NULL)
						";
					//echo $req;
					$res = $connexion->query($req);
					$res->setFetchMode(PDO::FETCH_ASSOC);
					if($res->rowCount() != 0) {
						echo "<div id='listing_vins' class='container'>
								<h1 id='res_search'>Résultats de la recherche</h1>
								<ul class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
						$i=1;
							while($ligne = $res->fetch()) {
								$icone = '';
								$nbr_bouteilles = $ligne['nb_bouteilles']." bouteilles";
								//si on a qu'une bouteilles, on enlève le 's' à la fin
								if($ligne['nb_bouteilles'] == 1) {
									$nbr_bouteilles = $ligne['nb_bouteilles']." bouteille";
								} 

								switch(utf8_encode($ligne['type'])) {
									case 'Blanc' : $icone = "img/icone_vin_blanc.png";
									break;
									case 'Rouge' : $icone = "img/icone_vin_rouge.png";
									break;
									case 'Rosé' : $icone = "img/icone_vin_rose.png";
									break;
									case 'Mousseux' : $icone = "img/icone_vin_mousseux.png";
									break;
									} 
								if($i == 1) echo "<div class='row'>";	
								else if ($i == 4 || $i == 7) echo "</div><div class='row'>";
								//pour chaque résultat dans la 2eme colonne ou la 3eme, on ajoute une colonne vide
								if($i == 1 || $i == 4 || $i == 7) $class = 'col-xs-12 col-md-3 col-lg-3';
								else $class = 'col-xs-12 col-md-3 col-md-offset-1 col-lg-3 col-lg-offset-1';
									echo ("
										<li id='vin_".$ligne['id_vin']."' class='mini-fiche ".$class."' onclick=fiche_vin(this.id)>
											<div>
												<img src='".$icone."' alt='Icone_vin' class='img-resposive' />
												<div><span class='nom_mini_fiche'>".utf8_encode($ligne['nom'])."</span><br>".utf8_encode($ligne['appellation'])."<br>".$ligne['annee']."<br>".$nbr_bouteilles."
												</div>
											</div>
										</li>
									");
								if($i == 9) echo "</div>";
								$i++;
							}
						echo "</ul></div>";
					}
					$res->closeCursor();
					$total = $connexion->query($req2);
					$total_posts = $total->rowCount();
					include("pagination/foot.pagination.php");
					if($total_posts == 0) {
						echo("<script>
							$(function(e) {
	            				bootbox.dialog({
	            					message: 'Il n\'y a aucun résultat pour cette recherche.',
	 								title: 'Aucun résultat',
	  								buttons: {
	    								success: {
	      									label: 'Ok',
	      									className: 'btn-success',
	      									callback: function() {
	      										window.location = 'accueil.php';
	      									}
	      								}
								    }	
	       						});
							});
					    </script>");
					}
					$total->closeCursor();
				}
				else header('location:accueil.php');
			?>
			<div>
				<?php include 'modal.php' ?>
			</div>
		</section>
		<footer></footer>
	</div>
</body>
</html>
<?php } 
	else header('location:accueil.php');
?>