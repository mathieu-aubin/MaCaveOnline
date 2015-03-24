<?php
	include('connexion.php');
	include('formulaire_ajout.php'); 
	if(isset($_SESSION['login']) && isset($_SESSION['pass'])) {
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
			//confirmation de l'ajout du vin
			if(isset($_GET['confirm'])) {
				echo("<script>
                        $(function(e) {
                            bootbox.dialog({
                                message: 'Le vin a bien été ajouté à la base. Voulez-vous en ajouter un autre ?',
                                title: 'Vin ajouté',
                                buttons: {
                                    success: {
                                        label: 'Oui',
                                        className: 'btn-success'
                                    },
                                    danger: {
                                        label: 'Non',
                                        className: 'btn-danger',
                                        callback: function() {
                                            window.location = 'accueil.php';
                                        }
                                    }
                                }   
                            });
                        });
                    </script>
                ");
			}
			//erreur de vin déjà existant, on propose de voir la fiche du vin
			if(isset($_GET['err2'])) {
				$id_vin = $_GET['err2'];
				echo("<script>
						$(function(e) {
            				bootbox.dialog({
            					message: 'Ce vin existe déjà, voulez-vous voir sa fiche?',
 								title: 'Vin déjà existant',
  								buttons: {
    								success: {
      									label: 'Voir la fiche',
      									className: 'btn-success',
      									callback: function() {
      										fiche_vin('fiche_".$id_vin."');
      									}
      								},
    								danger: {
							      		label: 'Retour au formulaire d\'ajout',
							      		className: 'btn-danger',
							    	}
							    }	
       						});
						});
				    </script>
				");
			}
			//gestion des erreurs sur les champs obligatoires et sur inputs à vérifier
			if(isset($_GET['err'])) {
				//récupération des erreurs
				$erreur = explode('_', $_GET['err']);
				$boucle = count($erreur);
				for ($i=0; $i < $boucle; $i++) { 
					//err = 1 si champ nom est vide
					if($erreur[$i] == 'nom') {
						$erreur_nom = "Veuillez saisir un nom.";
						$class_nom = "has-error";
						$class_span_nom = "help-block";
					}
					//err = 2 si champ année est vide
					else if($erreur[$i] == 'annee') {
						$erreur_annee = "Veuillez renseigner une année.";
						$class_annee = "has-error";
						$class_span_annee = "help-block";
					}
					//err = 3 si champ region est vide
					else if($erreur[$i] == 'region') {
						$erreur_region = "Veuillez saisir une region.";
						$class_region = "has-error";
						$class_span_region = "help-block";
					}
					//err = 4 si champ type est vide
					else if($erreur[$i] == 'type') {
						$erreur_type = "Veuillez saisir un type de vin.";
						$class_type = "has-error";
						$class_span_type = "help-block";
					}
					//err = 5 si champ bouteilles à un problème
					else if($erreur[$i] == 'bouteilles') {
						$erreur_bouteilles = "Veuillez saisir un nombre valide.";
						$class_bouteilles = "has-error";
						$class_span_bouteilles = "help-block";
					}
					//err = 6 si champ prix d'achat à un problème
					else if($erreur[$i] == 'prix') {
						$erreur_prix = "Veuillez saisir un prix valide.";
						$class_prix = "has-error";
						$class_span_prix = "help-block";
					}
					//err = 7 si champ offert_par à un problème
					else if($erreur[$i] == 'offert') {
						$erreur_offert = "Veuillez saisir une valeur valide.";
						$class_offert = "has-error";
						$class_span_offert = "help-block";
					}
					//si on a pas d'erreur, on vide la case
					else unset($erreur[$i]);
				}
			}
		?>
		<section>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h2 class="panel-title">Ajouter un vin</h2>
						</div>
						<div class="panel-body">
							<form action="requetes.php" method='post' role='form' onsubmit="return verif()">
								<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5"><!-- Début zone partie gauche formulaire -->
									<!-- BDD vins => ajoute les infos pour la fiche du vin -->
									<!-- Nom du vin -->
									<div id="nom_vin" class="form-group <?php if(isset($class_nom)) echo $class_nom; ?>">
										<label class="control-label" for="nom_vin">Nom <span class="obligatoire">*</span></label>
										<input id="nom_vin_ajout" class='form-control' type="text" name="nom_vin" list="list_nom_vin" required placeholder="Nom du vin" onkeyup="datalist(this.value, 'ajout_nom')">
										<span class='<?php if(isset($class_span_nom)) echo $class_span_nom; ?>'>
											<?php if(isset($erreur_nom)) echo $erreur_nom; ?>
										</span>
			 						</div>
			 						<!-- Année -->
			 						<div id="annee_vin" class="form-group <?php if(isset($class_annee)) echo $class_annee; ?>" >
			 							<label for="annee_vin" class="control-label">Année <span class="obligatoire">*</span></label>
			 							<select id="annee_vin_ajout" name="annee_vin" class="form-control" required>
			 								<option value=''>Choisir une année</option>
											<script>option_date(1);</script>
										</select>
										<span class="<?php if(isset($class_span_annee)) echo $class_span_annee; ?>">
											<?php if(isset($erreur_annee)) echo $erreur_annee; ?>
										</span>
			 						</div>						
			 						<!-- Région -->
									<div id="region_ajout" class="form-group <?php if(isset($class_region)) echo $class_region; ?>">
										<label class="control-label" for="select_region" >Région <span class="obligatoire">*</span></label>
										<?php 
											echo($select_region);
										?>
										<span class="<?php if(isset($class_span_region)) echo $class_span_region; ?>">
											<?php if(isset($erreur_region)) echo $erreur_region; ?>
										</span>
									</div>
									<!-- AOC (liste dynamique) -->
									<div class="form-group" id="aoc_ajout"></div>
									<!-- Type de vin -->
									<div id="type_ajout" class="form-group <?php if(isset($class_type)) echo $class_type ?> " > 
										<?php
											echo($select_type);
										?>	
										<span class="<?php if(isset($class_span_type)) echo $class_span_type; ?>">
											<?php if(isset($erreur_type)) echo $erreur_type; ?>
										</span>
									</div>
									<!-- Degré d'alcool -->
			 						<div class="form-group" id="degre_alcool">
			 							<label for="degre_alcool" class="control-label">Degré d'alcool</label>
			 							<?php
			 								echo($select_alcool);
			 							?>
			 						</div>
			 						<!-- BDD utilisateur_vin => appréciations de l'utilisateur sur le vin -->
			 						<!-- Date de début de consommation du vin -->
			 						<div class="form-group" id="conso_partir">
			 							<label for="conso_partir" class="control-label">A consommer à partir de</label>
			 							<select id='select_conso_partir' name='conso_partir' class='form-control' onchange="option_date(2, this.value);">
			 								<option value=''>Choisir une date</option>
			 								<script>option_date(2, 'defaut');</script>
										</select>
			 						</div>
			 						<!-- Date limite de consommation du vin -->
			 						<div class="form-group" id="conso_avant">
										<label class='control-label' for='conso_avant' >A consommer avant</label>
										<select name='annee_conso_avant' class='form-control' id='select_annee_conso_avant'>
											<option value=''>Choisir une année</option>
											<script>option_date(2, 'defaut');</script>
										</select>
									</div>
			 						<!-- Note du vin -->
			 						<div class="form-group" id="note">
			 							<label for="note" class="control-label">Note</label>
			 							<?php
			 								echo($select_note);
			 							?>
			 						</div>
			 					</div> <!-- fin partie gauche formulaire -->
			 					<!-- début partie droite formulaire avec 2 colonnes vides entre -->
			 					<div class="col-md-5 col-md-offset-2 col-lg-5 col-lg-offset-2">
			 						<!-- Nombre de bouteilles -->
			 						<div id="nb_bouteilles_ajout" class="form-group <?php if(isset($class_bouteilles)) echo $class_bouteilles; ?>" >
			 							<label for="nb_bouteilles" class="control-label">Nombre de bouteilles</label>
			 							<input type="text" name="nb_bouteilles" id="nb_bouteilles" value="" class="form-control">
			 							<span id="erreur_bt" class="<?php if(isset($class_span_bouteilles)) echo $class_span_bouteilles; ?>">
			 								<?php if(isset($erreur_bouteilles)) echo $erreur_bouteilles; ?>
			 							</span>
			 						</div>
			 						<!-- Suivi du stock du vin -->
			 						<div class="form-group" id="suivi_stock">
			 							<label for="suivi_stock" class="control-label">Suivre le stock de ce vin</label><br>
			 							<input type="checkbox" name="suivi_stock" value="suivi_stock">
			 						</div>
			 						<!-- Ajouter à la liste des meilleurs vins -->
			 						<div class="form-group" id="meilleur_vin">
			 							<label for="meilleur_vin" class="control-label">Ajouter aux vins favoris</label><br>
			 							<input type="checkbox" name="meilleur_vin" value="meilleur_vin">
			 						</div>
			 						<!-- Prix d'achat du vin -->
			 						<div id="prix_achat_ajout" class="form-group <?php if(isset($class_achat)) echo $class_achat; ?>" >
			 							<label for="prix_achat" class="control-label">Prix d'achat</label>
			 							<input type="text" id="prix_achat" name="prix_achat" class="form-control" value="">
			 							<span id="erreur_prix" class="<?php if(isset($class_span_achat)) echo $class_span_achat; ?>">
											<?php if(isset($erreur_achat)) echo $erreur_achat; ?>
										</span>
			 						</div>
			 						<!-- Vin offert par -->
			 						<div id="offert_par_ajout" class="form-group <?php if(isset($class_offert)) echo $class_offert; ?>" >
			 							<label for="offert_par" class="control-label">Offert par</label>
			 							<input type="text" id="offert_par" name="offert_par" class="form-control" value="">
			 							<span id="erreur_offert" class="<?php if(isset($class_span_offert)) echo $class_span_offert ?>">
											<?php if(isset($erreur_offert)) echo $erreur_offert; ?>
										</span>
			 						</div>
			 						<!-- Lieu d'achat du vin -->
			 						<div class="form-group" id="achat_ajout">
			 							<label for="lieu_achat" class="control-label">Lieu d'achat</label>
										<?php
											echo($select_achat);
										?>
			 						</div>
			 						<!-- Lieu de stockage du vin -->
			 						<div class="form-group" id="stockage_ajout">
			 							<label for="lieu_stockage" class="control-label">Lieu de stockage</label>
										<?php
											echo($select_stockage);
										?>
			 						</div>
			 						<!-- Commentaires -->
			 						<div class="form-group" id="commentaires">
			 							<label for="commentaires" class="control-label">Commentaires</label>
										<textarea name="commentaires" class="form-control" cols="20" rows="5"></textarea>
			 						</div>
			 						<input class='btn btn-sm btn-primary pull-right' type="submit" value="Ajouter le vin" name="ajouter">
			 					</div><!-- fin partie droite formulaire -->
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- Fiche du vin à afficher si le vin est déjà existant -->
			<div class="modal fade" id="fiche_vin" tabindex="-1" role="dialog" aria-labelledby="FicheVin" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h2 class="modal-title">Fiche du vin</h2>
						</div>
						<div class="modal-body" id="fiche_body"></div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</section>
	</div>
</body>
</html>
<?php } 
	else header('location:accueil.php'); 
?>