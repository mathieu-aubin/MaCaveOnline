<?php
	session_start();
	if(isset($_SESSION['login']) && isset($_SESSION['pass'])) {
		include 'connexion.php';
		if(isset($_POST['id_vin_modif'])) {
			$id_vin = $_POST['id_vin_modif'];
			$requete = "SELECT 
	                    FK_vin, 
	                    FK_utilisateur,
	                    note, 
	                    nb_bouteilles, 
	                    suivi_stock, 
	                    meilleur_vin, 
	                    prix_achat, 
	                    offert_par, 
	                    FK_lieu_achat, 
						FK_lieu_stockage, 
	                    YEAR(utilisateur_vin.conso_partir) as conso_partir, 
	                    YEAR(utilisateur_vin.conso_avant) as conso_avant, 
	                    commentaires, 
	                    vins.nom, 
	                    vins.annee, 
	                    appellation.appellation,
	                    type_vin.id_type   
	                FROM utilisateur_vin 
	                LEFT JOIN vins 
	                    ON utilisateur_vin.FK_vin = vins.id_vin
	                LEFT JOIN appellation 
	                    ON vins.FK_appellation = appellation.id_appellation
	                LEFT JOIN type_vin 
	                    ON vins.FK_type = type_vin.id_type
	                WHERE FK_vin =".$id_vin."
	                    AND FK_utilisateur = ".$_SESSION['user']; 
		    //echo $requete;
		    $select = $connexion->query($requete);
		    $select->setFetchMode(PDO::FETCH_ASSOC);

		    while ($ligne = $select->fetch()) {
		        $nom_vin_fiche = utf8_encode($ligne['nom']);
		        $annee_fiche = $ligne['annee'];
		        $note_fiche = $ligne['note'];
		        $nb_bouteilles_fiche = $ligne['nb_bouteilles'];
		        $suivi_stock_fiche = $ligne['suivi_stock'];
		        $meilleur_vin_fiche = $ligne['meilleur_vin'];
		        $prix_achat_fiche = $ligne['prix_achat'];
		        $offert_par_fiche = utf8_encode($ligne['offert_par']);
		        $lieu_achat_fiche = $ligne['FK_lieu_achat'];
		        $lieu_stockage_fiche = $ligne['FK_lieu_stockage'];
		        $conso_partir_fiche = $ligne['conso_partir'];
		        $conso_avant_fiche = $ligne['conso_avant'];
		        $commentaires_fiche = utf8_encode($ligne['commentaires']);
		    }
		    $select->closeCursor();
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bienvenue</title>
	<!-- Fichiers CSS -->
	<!-- Fichiers CSS -->
	<link href="css/bootstrap/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
	<link href="css/jquery/jquery-custom.min.css" rel="stylesheet" media="screen">
	<link href="css/style.css" rel="stylesheet">
	<!-- Fichiers Js -->
	<script src="js/jquery/jquery2.min.js"></script>
	<script src="js/jquery/jquery-custom.js"></script>
	<script src="js/jquery/bootbox.min.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
	<script src="js/ajax.js"></script>
	<script src="js/liste_appellation.js"></script>
	<script src="js/fiche_vin.js"></script>
	<script src="js/verif_require.js"></script>
	<script src="js/date.js"></script>
	<script src="js/option_autre.js"></script>
	<script src="js/datalist.js"></script>
</head>
<body>
	<?php
		include 'header.php';
	?>
		<section>
			<div class='row'>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h2 class="panel-title"><?php echo $nom_vin_fiche." - ".$annee_fiche; ?></h2>
						</div>
						<div class="panel-body">
							<form method='post' action='requetes.php' role='form' onsubmit="return verif_modif()">
				<?php
					// construction du select pour la note
					$select_note_fiche = "<select id='select_note_fiche' name='select_note_fiche' class='form-control'><option value=''>Choisir une note</option>";
					for ($i=0; $i < 21; $i++) {
						if(($note_fiche != 0) && ($i == $note_fiche)) $select_note_fiche .= "<option value=".$i." selected>".$i."</option>";
						else $select_note_fiche .= "<option value=".$i.">".$i."</option>";
					}
					$select_note_fiche .= "</select>";
					//on test si le suivi de stock est actif ou non
					if ($suivi_stock_fiche == 1) $checked_suivi = "checked";
					else $checked_suivi = "";
					//test si l'option meilleur vin est active
					if($meilleur_vin_fiche == 1) $checked_meilleurvin = "checked";
					else $checked_meilleurvin = "";
					//requete pour la construction du select du lieu d'achat
					$reqselect_achat = "SELECT id_lieu_achat, lieu_achat FROM lieu_achat";
					$res_achat = $connexion->query($reqselect_achat);
					$res_achat->setFetchMode(PDO::FETCH_ASSOC);
					$lieu_achat = [];
					while($row = $res_achat->fetch()) {
					 	$ligne1[] = $row['id_lieu_achat']; 
					 	$ligne2[] = $row['lieu_achat'];
					}
					$lieu_achat = array($ligne1, $ligne2);
					$select_achat = "<select id='select_lieu_achat' name='select_lieu_achat' onchange='option_autre(this.id, this.options[this.selectedIndex].value, \"modif\");' class='form-control col-lg-4'><option value=''>Choisir un lieu d'achat</option>";
					for ($i=0; $i < count($ligne1) ; $i++) { 
						if($lieu_achat[0][$i] == $lieu_achat_fiche) $select_achat .= "<option value=".$lieu_achat[0][$i]." selected>".$lieu_achat[1][$i]."</option>";
						else $select_achat .= "<option value=".$lieu_achat[0][$i].">".$lieu_achat[1][$i]."</option>";
					}
					$select_achat .= "<option value='autre'>Autre...</option></select>";
					$res_achat->closeCursor();
					//requete pour la construction du select du lieu de stockage
					$reqselect_stockage = "SELECT id_lieu_stockage, lieu_stockage FROM lieu_stockage";
					$res_stockage = $connexion->query($reqselect_stockage);
					$res_stockage->setFetchMode(PDO::FETCH_ASSOC);
					$lieu_stockage = [];
					while ($row2 = $res_stockage->fetch()) {
						$ligneA[] = $row2['id_lieu_stockage'];
						$ligneB[] = $row2['lieu_stockage'];
					}
					$lieu_stockage = array($ligneA, $ligneB);
					$select_stockage = "<select id='select_lieu_stockage' name='select_lieu_stockage' onchange='option_autre(this.id, this.options[this.selectedIndex].value, \"modif\");' class='form-control'><option value=''>Choisir un lieu de stockage</option>";
					for ($i=0; $i < count($ligneA) ; $i++) { 
						if($lieu_stockage[0][$i] == $lieu_stockage_fiche) $select_stockage .= "<option value=".$lieu_stockage[0][$i]." selected>".$lieu_stockage[1][$i]."</option>";
						else $select_stockage .= "<option value=".$lieu_stockage[0][$i].">".$lieu_stockage[1][$i]."</option>";
					}
					$select_stockage .= "<option value='autre'>Autre...</option></select>";
					$res_stockage->closeCursor();
					//boucle de construction des options des select pour la date
					//on récupère l'année en cours
					$date = getdate();
					$annee_debut = $date['year'];
					$annee_fin = $annee_debut + 30;
					$option_date_partir = "";
					$option_date_avant = "";
					for ($j = $annee_debut; $j <= $annee_fin; $j++) {
						if($j == $conso_partir_fiche) $option_date_partir .= "<option value=".$j." selected>".$j."</option>";
						else $option_date_partir .= "<option value=".$j.">".$j."</option>";
						if($j == $conso_avant_fiche) $option_date_avant .= "<option value=".$j." selected>".$j."</option>";
						else $option_date_avant .= "<option value=".$j.">".$j."</option>";
					}
					//gestion commentaires
					if($commentaires_fiche == '-') $commentaires_fiche = '';

					//gestion des erreur
					if(isset($_GET['err_bt'])) {
						if($_GET['err_bt'] == 1) {
							$erreur_bt = "Veuillez saisir un nombre entier.";
							$class_bt = "has-error";
							$class_span_bt = "help-block";
						}	
					}
					else {
						$erreur_bt = '';
						$class_bt = '';
						$class_span_bt = "";
					} 
					if(isset($_GET['err_off'])) {
						if($_GET['err_off'] == 1) {
							$erreur_off = "Veuillez saisir du texte.";
							$class_off = "has-error";
							$class_span_off = "help-block";
						}
					}
					else {
						$erreur_off = '';
						$class_off = '';
						$class_span_off = "";
					} 
					if(isset($_GET['err_pri'])) {
						if($_GET['err_pri'] == 1) {
							$erreur_pri = "Veuillez saisir une valeur numérique.";
							$class_pri = "has-error";
							$class_span_pri = "help-block";
						}
					}
					else {
						$erreur_pri = '';
						$class_pri = '';
						$class_span_pri = "";
					} 
					echo ("
						<div class='col-xs-12 col-sm-12 col-md-5 col-lg-5'>
							<div class='form-group ".$class_bt."' id='nb_bouteilles_modif'>
								<label class='control-label' for='nb_bouteilles'>Nombre de bouteilles</label>
							 	<input class='form-control' id='nb_bouteilles' name='nb_bouteilles' required value='".$nb_bouteilles_fiche."'>
							 	<span id='erreur_bt' class='".$class_span_bt."'>".$erreur_bt."</span>
							</div>
							<div class='form-group' id='note_fiche_modif'>
								<label class='control-label' for='select_note_fiche'>Note</label>
								".$select_note_fiche."
							</div>
							<div class='form-group' id='suivi_stock_modif'>
								<label class='control-label' for='suivi_stock'>Suivi du stock</label><br>
								<input type='checkbox' id='suivi_stock' name='suivi_stock' ".$checked_suivi.">
							</div>
							<div class='form-group' id='meilleur_vin_modif'>
								<label class='control-label' for='meilleur_vin'>Meilleur vin</label><br>
								<input type='checkbox' id='meilleur_vin' name='meilleur_vin' ".$checked_meilleurvin.">
							</div>
							<div class='form-group ".$class_pri."' id='prix_achat_modif'>
								<label class='control-label' for='prix_achat'>Prix d'achat</label>
								<input class='form-control' type='text' id='prix_achat' name='prix_achat' value=".$prix_achat_fiche.">
								<span id='erreur_prix' class='".$class_span_pri."'>".$erreur_pri."</span>
							</div>
							<div class='form-group ".$class_off."' id='offert_par_modif'>
								<label class='control-label' for='offert_par'>Offert par</label>
								<input class='form-control' type='text' id='offert_par' name='offert_par' value='".utf8_encode($offert_par_fiche)."'>
								<span id='erreur_offert' class='".$class_span_off."'>".$erreur_off."</span>
							</div>
							<div class='form-group' id='achat_modif'>
								<label class='control-label' for='select_lieu_achat' >Lieu d'achat</label>
								".utf8_encode($select_achat)."
							</div>
						</div>
						<div class='col-md-5 col-md-offset-2 col-lg-5 col-lg-offset-2'>
							<div class='form-group' id='stockage_modif'>
								<label class='control-label' for='select_lieu_stockage' >Lieu de stockage</label>
								".utf8_encode($select_stockage)."
							</div>
							<div class='form-group' id='conso_partir_modif'>
								<label class='control-label' for='select_conso_partir' >A consommer à partir de</label>
								<select id='select_conso_partir' name='select_conso_partir' class='form-control' onchange='option_date(3, this.value)'><option value=''>Choisir une date</option>
									".$option_date_partir."
								</select>
							</div>
							<div class='form-group' id='conso_max_modif'>
								<label class='control-label' for='select_conso_max' >A consommer avant</label>
								<select id='select_conso_max' name='select_conso_max' class='form-control'><option value=''>Choisir une date</option>
									".$option_date_avant."
								</select>	
							</div>
							<div class='form-group' id='commentaires_modif'>
								<label class='control-label' for='commentaires'>Commentaires</label>
								<textarea class='form-control' cols=50 rows=10 name='commentaires' id='commentaires'>".$commentaires_fiche."</textarea>
							</div>
							<input type='hidden' name='id_fk_vin' id='id_fk_vin' value=".$id_vin.">
							<input class='btn btn-sm btn-primary pull-right' type='submit' name='modifier' value='Modifier'>
						</div>
					</form>
					</div>"
					);
				}
				?>
				</div>
			</div>
		</section>
	</div> <!-- fin du container -->
</body>
</html>
<?php } 
	else header('location:accueil.php');
?>