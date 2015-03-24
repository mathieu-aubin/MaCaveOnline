<?php
	session_start ();
	require 'connexion.php';
	include("pagination/head.pagination.php");
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
	<link rel="stylesheet" href="css/jquery/jquery-custom.min.css">
	<link href="css/style.css" rel="stylesheet">
	<!-- Fichiers Js -->
	<script src="js/jquery/jquery2.min.js"></script>
	<script src="js/jquery/jquery-custom.js"></script>
	<script src="js/jquery/bootbox.min.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
	<script src="js/datalist.js"></script>
	<script src="js/option_autre.js"></script>
	<script src="js/ajax.js"></script>
	<script src="js/liste_appellation.js"></script>
	<script src="js/fiche_vin.js"></script>
	<script>
		function aoc_visible() {
			document.getElementById('div_appellation').style.display = "block";
		};
		function center_pagination() {
    		if(document.getElementById('pagination') !== null && document.getElementById('navPage') !== null){
	    		var pagination = document.getElementById('pagination').offsetWidth;
				document.getElementById('navPage').style.width = pagination+"px";   			
    		}
		};
		$( document ).ready(function() {
    		center_pagination();
		});
	</script>
</head>
<body onresize="center_pagination();">
		<?php
			include 'header.php';
			//validation modification fiche
			if(isset($_GET['modif']) && $_GET['modif'] == 'ok') 
			{
				echo("<script>
						$(function(e) {
            				bootbox.dialog({
            					message: 'La fiche du vin a bien été modifiée.',
 								title: 'Fiche modifiée',
  								buttons: {
    								success: {
      									label: 'Ok',
      									className: 'btn-primary',
      								},
      							}	
       						});
						});
				    </script>
				");
			}
		?>
		<section>
			<div id="panels">
				<div class="col-xs-12 col-sm-5 col-lg-4">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h2 class="panel-title">Liste des vins</h2>
						</div>
						<div class="panel-body">
							<form id="listing" action="accueil.php" method="post" role="form">
								<div id="region" class="form-group">
									<label class="control-label" for="select-region" >Région</label>
									<div>
										<select id="select_region" name="select_region" onchange="liste_appellation('index', this.value);" class="form-control">
											<option value="">Choisir une région</option>
											<?php
												/*** construction des listes déroulantes pour la recherche ***/
												/**on récupère la liste des régions et leur id**/
											    $select_region = '';
											    $req_region = "SELECT id_region, region FROM region";
											    $res_region = $connexion->query($req_region);
											    $res_region->setFetchMode(PDO::FETCH_ASSOC);
											    while($ligne = $res_region->fetch ()) 
											    {
											    	if(isset($_POST['affiche_liste'])) 
											    	{
											    		if($ligne['id_region'] == $_POST['select_region']) 
											    		{
											    			$select_region .= "<option value=".$ligne['id_region']." selected>".utf8_encode($ligne['region'])."</option>";
											    		}
											    		else $select_region .= "<option value=".$ligne['id_region'].">".utf8_encode($ligne['region'])."</option>";
											    	}
											    	else if(isset($_GET['paged'])) 
											    	{
														if($ligne['id_region'] == $_GET['region']) 
														{
											    			$select_region .= "<option value=".$ligne['id_region']." selected>".utf8_encode($ligne['region'])."</option>";
											    		}
											    		else $select_region .= "<option value=".$ligne['id_region'].">".utf8_encode($ligne['region'])."</option>";
													}
													else $select_region .= "<option value=".$ligne['id_region'].">".utf8_encode($ligne['region'])."</option>";
											    }
											    $select_region .= "</select>";
											    $res_region->closeCursor();						
												echo $select_region;
											?>
										</select>
									</div>
								</div>
								<div class="form-group" id="div_appellation">
									<div id="appellation">
										<?php
											if(isset($_POST['affiche_liste'])) 
											{
												if($_POST['select_region'] != "" && $_POST['select_region'] != 6 && $_POST['select_region'] != 12) 
												{	
													$requete = "SELECT id_appellation, appellation FROM appellation WHERE FK_region = ".$_POST['select_region'];
										            /**execution de la requete**/
										            if ($_POST['select_region'] != "") 
										            {
										                $select = $connexion->query($requete);
										                $select->setFetchMode(PDO::FETCH_ASSOC);
										                /**on parcourt chaque ligne**/
										                echo ("<label class='control-label' for='select_appellation'>AOC</label><select id='select_appellation' name='select_appellation' class='form-control select'> <option value=''>Choisir une AOC</option>");
										                while ($ligne = $select->fetch()) 
										                {
										                    /**on stocke les valeurs dans un tableau**/
										                    $id_appellation = $ligne['id_appellation'];
										                    $appellation = $ligne['appellation'];
										                    if ($id_appellation == $_POST['select_appellation']) 
										                    {
										                    	echo("<option value=".$id_appellation." selected >".utf8_encode($appellation)."</otpion>");
										                    }
										                    else echo ("<option value=".$id_appellation.">".utf8_encode($appellation)."</option>");
										                }
										                echo("</select>");
													}
												}
											}
											else if(isset($_GET['paged'])) 
											{
												if($_GET['region'] != "" && $_GET['region'] != 6 && $_GET['region'] != 12) 
												{	
													$requete = "SELECT id_appellation, appellation FROM appellation WHERE FK_region = ".$_GET['region'];
										            /**execution de la requete**/
										            if ($_GET['region'] != "") 
										            {
										                $select = $connexion->query($requete);
										                $select->setFetchMode(PDO::FETCH_ASSOC);
										                /**on parcourt chaque ligne**/
										                echo ("<label class='control-label' for='select_appellation'>AOC</label><select id='select_appellation' name='select_appellation' class='form-control select'> <option value=''>Choisir une AOC</option>");
										                while ($ligne = $select->fetch()) 
										                {
										                    /**on stocke les valeurs dans un tableau**/
										                    $id_appellation = $ligne['id_appellation'];
										                    $appellation = $ligne['appellation'];
										                    if ($id_appellation == $_GET['appellation']) 
										                    {
										                    	echo("<option value=".$id_appellation." selected >".utf8_encode($appellation)."</otpion>");
										                    }
										                    else echo ("<option value=".$id_appellation.">".utf8_encode($appellation)."</option>");
										                }
										                echo("</select>");
													}
												}
											}
										?>
									</div>
								</div>
								<div id="type_vin" class="form-group">
									<label class="control-label" for="select_type">Type de vin</label>
									<div>
										<select id="select_type" name="select_type" class="form-control">
											<option value="">Type de vin</option>
											<?php
												/**liste des types de vin**/
											    $select_type = '';
											    $req_type = "SELECT id_type, type FROM type_vin";
											    $res_type = $connexion->query($req_type);
											    $res_type->setFetchMode(PDO::FETCH_ASSOC);
											    while($ligne = $res_type->fetch())
											    {
											    	if(isset($_POST['affiche_liste']))
											    	{
											    		if($ligne['id_type'] == $_POST['select_type'])
											    		{
											    			$select_type .= "<option value=".$ligne['id_type']." selected>".utf8_encode($ligne['type'])."</option>";
											    		}
											    		else $select_type .= "<option value=".$ligne['id_type'].">".utf8_encode($ligne['type'])."</option>";
											    	}
											    	else if(isset($_GET['paged']))
											    	{
														if($ligne['id_type'] == $_GET['type'])
														{
											    			$select_type .= "<option value=".$ligne['id_type']." selected>".utf8_encode($ligne['type'])."</option>";
											    		}
											    		else $select_type .= "<option value=".$ligne['id_type'].">".utf8_encode($ligne['type'])."</option>";
													}
											    	else $select_type .= "<option value=".$ligne['id_type'].">".utf8_encode($ligne['type'])."</option>";
											    }
											    $select_type .= "</select>";
											    $res_type->closeCursor();
											    echo $select_type;
											?>
										</select>
									</div>
								</div>
								<div id="lieu_stockage" class="form-group">
									<label class="control-label" for="select_stockage">Lieu de stockage</label>
									<div>
										<select id='select_stockage' name="select_stockage" class="form-control">
											<option value="">Lieu de stockage</option>
											<?php
												/**affichage de la liste des lieux de stockage**/
											    $select_lieu_stockage = '';
											    $req_stockage = "SELECT id_lieu_stockage, lieu_stockage FROM lieu_stockage";
											    $res_stockage = $connexion->query($req_stockage);
											    $res_stockage->setFetchMode(PDO::FETCH_ASSOC);
											    while($ligne = $res_stockage->fetch())
											    {
											    	if(isset($_POST['affiche_liste']))
											    	{
											    		if($ligne['id_lieu_stockage'] == $_POST['select_stockage'])
											    		{
											    			$select_lieu_stockage .= "<option value=".$ligne['id_lieu_stockage']." selected>".utf8_encode($ligne['lieu_stockage'])."</option>";
											    		}
											    		else $select_lieu_stockage .= "<option value=".$ligne['id_lieu_stockage'].">".utf8_encode($ligne['lieu_stockage'])."</option>";
											    	}
											    	else if(isset($_GET['paged']))
											    	{
														if($ligne['id_lieu_stockage'] == $_GET['stockage'])
														{
											    			$select_lieu_stockage .= "<option value=".$ligne['id_lieu_stockage']." selected>".utf8_encode($ligne['lieu_stockage'])."</option>";
											    		}
											    		else $select_lieu_stockage .= "<option value=".$ligne['id_lieu_stockage'].">".utf8_encode($ligne['lieu_stockage'])."</option>";
													}
											    	else $select_lieu_stockage .= "<option value=".$ligne['id_lieu_stockage'].">".utf8_encode($ligne['lieu_stockage'])."</option>";
											    }
											    $select_lieu_stockage .= "</select>";
											    $res_stockage->closeCursor();

												echo $select_lieu_stockage;
											?>
										</select>
									</div>
								</div>
								<input type="submit" name="affiche_liste" value="Afficher la liste" class="btn btn-sm btn-primary pull-right"> 
							</form>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-offset-2 col-sm-5 col-lg-offset-3 col-lg-5">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h2 class="panel-title">Suivi de la cave</h2>
						</div>											
						<div class="panel-body" id="suivi_cave">
							<div id="notifications" class="tabbable">
								<ul id="ul_notif" class="nav nav-pills">
									<li id="boire_dans_annee" class="active"><a href="#boire_avant" data-toggle="tab">Vins à boire</a></li>
									<li id="stock_bas"><a href="#stock" data-toggle="tab">Stock bas</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="boire_avant">
										<ul>
										<?php
											// on récupère l'année en cours
											$annee_encours = getdate();
											$annee_encours = $annee_encours['year'];
											//on récupère les id des vins où l'année de consommation max est inférieur à l'année en cours
											$req_boire_avant = "SELECT FK_vin FROM utilisateur_vin WHERE FK_utilisateur = ".$_SESSION['user']." AND YEAR(conso_avant) <= ".$annee_encours." ORDER BY YEAR(conso_avant) ASC";
											$res_boire_avant = $connexion->query($req_boire_avant);
											$res_boire_avant->setFetchMode(PDO::FETCH_ASSOC);
											while($ligne_avant = $res_boire_avant->fetch())
											{
												//on récupère la liste des vins (nom + année) correspondant
												$req_liste_vin = "SELECT id_vin, nom, annee FROM vins WHERE id_vin = ".$ligne_avant['FK_vin'];
												$res_liste_vin = $connexion->query($req_liste_vin);
												$res_liste_vin->setFetchMode(PDO::FETCH_ASSOC);
												while($ligne = $res_liste_vin->fetch())
												{
													//on les affiche sous forme de liste à puce avec la fonction qui crée la fiche du vin en pop up au clic
													echo ("<li class='cursor' id='aboire_".$ligne['id_vin']."' onclick=fiche_vin(this.id)>".utf8_encode($ligne['nom'])." - ".$ligne['annee']."</li>");
												}
												$res_liste_vin->closeCursor();
											}													
											$res_boire_avant->closeCursor();
										?>
										</ul>
									</div>
									<div class="tab-pane" id="stock">
										<ul class="row">
										<?php
											//on récupère les id des vins où le stock restant est inférieur à 3 bouteilles
											$req_stock = "SELECT FK_vin FROM utilisateur_vin WHERE FK_utilisateur = ".$_SESSION['user']." AND suivi_stock = 1 AND nb_bouteilles BETWEEN 1 AND 2  ";
											$res_stock = $connexion->query($req_stock);
											$res_stock->setFetchMode(PDO::FETCH_ASSOC);
											while($ligne_stock = $res_stock->fetch())
											{
												//on les affiche sous forme de liste à puce avec la fonction qui crée la fiche du vin en pop up au clic
												$req_liste_stock = "SELECT id_vin, nom, annee FROM vins WHERE id_vin = ".$ligne_stock['FK_vin'];
												$res_liste_stock = $connexion->query($req_liste_stock);
												$res_liste_stock->setFetchMode(PDO::FETCH_ASSOC);
												while($ligne = $res_liste_stock->fetch())
												{
													//on les affiche sous forme de liste à puce avec la fonction qui crée la fiche du vin en pop up au clic
													echo ("<li class='col-xs-12 col-sm-12 col-md-12 col-lg-12 cursor' id='stock_".$ligne['id_vin']."' onclick=fiche_vin(this.id)>".utf8_encode($ligne['nom'])." - ".$ligne['annee']."</li>");
												}
												$res_liste_stock->closeCursor();
											}
											$res_stock->closeCursor();
										?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
				<?php 
					/** construction du listing des résultats **/
				    if(isset($_POST['affiche_liste']) || isset($_GET['paged'])) 
				    {
				        $total_posts = "index";
				        $region = "";
				        $where_region = "";
				        $appellation = "";
				        $where_appellation = "";
				        $type = "";
				        $where_type = "";
				        $stockage = "";
				        $where_stockage = "";
				        $condition_where = "";
				        $visible = "";
				        $user = $_SESSION['user'];

				        if(isset($_POST['affiche_liste'])) 
				        {
				            //récupération des variables transmises par le formulaire
				            if(isset($_POST['select_region'])) $region = $_POST['select_region'];
				            if(isset($_POST['select_appellation'])) $appellation = $_POST['select_appellation'];
				            if(isset($_POST['select_type'])) $type = $_POST['select_type'];
				            if(isset($_POST['select_stockage'])) $stockage = $_POST['select_stockage'];
				        }
				        else if(isset($_GET['paged']))
				        {
				        	$region = $_GET['region'];
				            $appellation = $_GET['appellation'];
				            $type = $_GET['type'];
				            $stockage = $_GET['stockage'];
				        }
				        
				        //gestion des valeurs vides
				        //si une valeur est vide, on exécute la requete avec un LIKE et non un =
				        if ($region !== "") $where_region = "vins.FK_region = ".$region;
				        //pas d'appellation si Champagne ou Vins étrangers
				        if ($region == 6 || $region == 12) $where_appellation = "vins.FK_appellation IS NULL";
				        else if ($appellation !== "") $where_appellation = "vins.FK_appellation = ".$appellation;
				        if ($type !== "") $where_type = "vins.FK_type = ".$type;
				        if ($stockage !== "") $where_stockage = "utilisateur_vin.FK_lieu_stockage = ".$stockage;

				        // si pas d'AOC
				        if ($region !== "" && $appellation == "" && $type !== "" && $stockage !== "") $condition_where = " AND ".$where_region." AND ".$where_type." AND ".$where_stockage;
				        // si pas de type
				        else if ($region !== "" && $appellation !== "" && $type == "" && $stockage !== "") $condition_where = " AND ".$where_region." AND ".$where_appellation." AND ".$where_stockage;
				        // si pas de stockage
				        else if ($region !== "" && $appellation !== "" && $type !== "" && $stockage == "") $condition_where = " AND ".$where_region." AND ".$where_appellation." AND ".$where_type;
				        // si pas de région (et donc pas d'AOC)
				        else if ($region == "" && $type !== "" && $stockage !== "") $condition_where = " AND ".$where_type." AND ".$where_stockage;
				        // si pas de type et pas d'AOC
				        else if ($region !== "" && $appellation == "" && $type == "" && $stockage !== "") $condition_where = " AND ".$where_region." AND ".$where_stockage;
				        // si pas de stockage et pas d'AOC
				        else if ($region !== "" && $appellation == "" && $type !== "" && $stockage == "") $condition_where = " AND ".$where_region." AND ".$where_type;
				        // si pas de type et pas de stockage
				        else if ($region !== "" && $appellation !== "" && $type == "" && $stockage == "") $condition_where = " AND ".$where_region." AND ".$where_appellation;
				        // si pas de région (donc pas d'AOC) et pas de type
				        else if ($region == "" && $type == "" && $stockage !== "") $condition_where = " AND ".$where_stockage;
				        // si pas de région (donc pas d'AOC) et pas de stockage
				        else if ($region == "" && $type !== "" && $stockage == "") $condition_where = " AND ".$where_type;
				        // si pas d'AOC et pas de type et pas de stockage
				        else if ($region !== "" && $appellation == "" && $type == "" && $stockage == "") $condition_where = " AND ".$where_region;
				        // si aucun champ
				        else if ($region == "" && $appellation == "" && $type == "" && $stockage == "") $condition_where = "";
				        // si tous les champs
				        else if ($region !== "" && $appellation !== "" && $type !== "" && $stockage !== "") $condition_where = " AND ".$where_region." AND ".$where_appellation." AND ".$where_type." AND ".$where_stockage;

				        //on récupère la liste des données en fonction des valeurs de chaque liste
				        $req_total = "
				            SELECT id_vin, vins.nom as nom_vin, annee, appellation, type, utilisateur_vin.nb_bouteilles, region.region, lieu_stockage.lieu_stockage 
				            FROM vins 
				            LEFT JOIN utilisateur_vin 
				            	ON utilisateur_vin.FK_vin = vins.id_vin 
				            LEFT JOIN lieu_achat 
				                ON utilisateur_vin.FK_lieu_achat = lieu_achat.id_lieu_achat 
				            LEFT JOIN lieu_stockage 
				                ON utilisateur_vin.FK_lieu_stockage = lieu_stockage.id_lieu_stockage 
				            LEFT JOIN region 
				                ON vins.FK_region = region.id_region 
				            LEFT JOIN type_vin 
				                ON vins.FK_type = type_vin.id_type 
				            LEFT JOIN appellation   
				                ON vins.FK_appellation = appellation.id_appellation 
				            WHERE utilisateur_vin.FK_utilisateur = ".$user."
				            AND (utilisateur_vin.nb_bouteilles > 0 AND utilisateur_vin.nb_bouteilles IS NOT NULL)
				            ".$condition_where."
				            ORDER BY annee ASC";
				           
				        $total = $connexion->query($req_total);
				        $total_posts = $total->rowCount();
				           
				        $req = $req_total." LIMIT ".$current_items.", ".$posts_per_page;
				        $res = $connexion->query($req);				            
				            
				        // echo $req;
				        // execution de la requete
				        $sql = $connexion->query($req);
				        $id_vin = array();
				        $nom_vin = array();
				        $annee = array();
				        $annee = array();
				        $nom_appellation = array();
				        $type_de_vin = array();
				        $nbr_bouteilles = array();
				        while($resultat = $sql->fetch(PDO::FETCH_ASSOC)) 
				        {
				            $id_vin[] = $resultat['id_vin'];
				            $nom_vin[] = $resultat['nom_vin'];
				            $nom_region[] = $resultat['region'];
				            $annee[] = $resultat['annee'];
				            $nom_appellation[] = $resultat['appellation'];
				            $type_de_vin[] = $resultat['type'];
				            $lieu_stockage = $resultat['lieu_stockage'];
				            $nbr_bouteilles[] = $resultat['nb_bouteilles'];
				        }
				        $visible = 1;
				    }

				    if($total_posts != 0)
				    {
				        if ($region == "") $recap_region = '0';
				        else $recap_region = '1';
				        if ($appellation == "") $recap_appellation = '0';
				        else $recap_appellation = '1';
				        if ($type == "") $recap_type = '0';
				        else $recap_type = '1';
				        if ($stockage == "") $recap_stockage = '0';
				        else $recap_stockage = '1';
				        $recap = $recap_region.$recap_appellation.$recap_type.$recap_stockage;
				            
				        switch($recap) 
				        {
				            //récapitulatif de la Région, l'AOC, du type de vin et du stockage
				            case '1111': $titre_recap = $nom_region[0]." - ".$nom_appellation[0]." - ".$type_de_vin[0]." - ".$lieu_stockage;
				            break;
				            //récapitulatif de la Région, l'AOC, du type de vin
				            case '1110': $titre_recap = $nom_region[0]." - ".$nom_appellation[0]." - ".$type_de_vin[0];
				            break;
				            //récapitulatif de la Région et l'AOC et du stockage
				            case '1101': $titre_recap = $nom_region[0]." - ".$nom_appellation[0]." - ".$lieu_stockage;
				            break;
				            //récapitulatif de la Région et le type de vin et le stockage
				            case '1011': $titre_recap = $nom_region[0]." - ".$type_de_vin[0]." - ".$lieu_stockage;
				            break;
				            //récapitulatif de région et AOC 
							case '1100': $titre_recap = $nom_region[0]." - ".$nom_appellation[0];
				            break;
			                //récapitulatif de région et type
			                case '1010': $titre_recap = $nom_region[0]." - ".$type_de_vin[0];
				            break;
				            //récapitulatif de région et stockage
			                case '1001': $titre_recap = $nom_region[0]." - ".$lieu_stockage;
				            break;
				            //récapitulatif du type et du stockage 
							case '0011': $titre_recap = $type_de_vin[0]." - ".$lieu_stockage;
				            break;
				            //récapitulatif de region 
							case '1000': $titre_recap = $nom_region[0];
				            break;
				            //récapitulatif du type 
				            case '0010':
				            {
				            	if($type_de_vin[0] == "Mousseux") $titre_recap = "Les Mousseux";
				                else $titre_recap = "Vins ".$type_de_vin[0]."s";
				            }
				            break;
				            //récapitulatif du stockage
				            case '0001': $titre_recap = utf8_decode("Vins présents ").$lieu_stockage;
				            break;
				            //récapitulatif de tous les vins
				            case '0000': $titre_recap = "Liste de tous les vins";
				            break;
				        }
				        $liste .= utf8_encode("<h3>".$titre_recap."</h3><BR>");
				    
					    //puis on affiche la liste avec pagination à 9 items/page
					    $liste .= "<ul id='pagination' class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
					    $j=1;
					    for($i=0;$i<count($nom_vin);$i++)
					    {
					        $icone = '';
					        $txt_bouteilles = "bouteilles";
							//si on a qu'une bouteilles, on enlève le 's' à la fin
							if($nbr_bouteilles[$i] == 1) $txt_bouteilles = " bouteille";
					        switch(utf8_encode($type_de_vin[$i]))
					        {
					            case 'Blanc' : $icone = "img/icone_vin_blanc.png";
					            break;
					            case 'Rouge' : $icone = "img/icone_vin_rouge.png";
					            break;
					            case 'Rosé' : $icone = "img/icone_vin_rose.png";
					            break;
					            case 'Mousseux' : $icone = "img/icone_vin_mousseux.png";
					            break;
					        } 
					        if($i == 0) $liste .= "<div class='row'>";
					        else if ($i == 3 || $i == 6) $liste .= "</div><div class='row'>";
					        //pour chaque résultat dans la 2eme colonne ou la 3eme, on ajoute une colonne vide
					        if($j == 1 || $j == 4 || $j == 7) $class = 'col-xs-12 col-md-3 col-lg-3';
					        else $class = 'col-xs-12 col-md-3 col-md-offset-1 col-lg-3 col-lg-offset-1';
					        $liste .= "<li id='vin_".$id_vin[$i]."' class='mini-fiche ".$class."' onclick=fiche_vin(this.id)>
					        			<div>
					                        <img src='".$icone."' alt='Icone_vin' class='img-responsive' />
					                        <div><span class='nom_mini_fiche'>".utf8_encode($nom_vin[$i])."</span><br>".utf8_encode($nom_appellation[$i])."<br>".$annee[$i]."<br>".$nbr_bouteilles[$i]." ".$txt_bouteilles."
					                        </div>
					                    </div>
					                   </li>
					                ";
					        if($i == 8) $liste .= "</div>";
					        $j++;
					    }
					    $liste .= "</ul></div>";
					    echo $liste; 
					    include("pagination/foot.pagination.php");
				    }
				    else
				    {
				    	echo '';
				    }						
				?>
			<div>
				<?php include 'modal.php' ?>
			</div>
		</section>
	</div><!-- /.container -->
</body>
</html>
<?php 
	}
	//on redirige à la page de connexion
	else  header('location:index.php');
?>