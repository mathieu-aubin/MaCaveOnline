<?php 
if(isset($_SESSION['login']) && isset($_SESSION['pass'])) {
?>
<div id="conteneur" class="container">
	<header class="page-header">
		<nav id="nav" class="navbar">
			<div class="pull-left">
				<a id="logo_accueil" href="accueil.php"><img src="img/logo.png" id="logo" alt="logo"></a>
			</div>
			<div class="row">
				<ul>
					<li class="col-xs-6 col-sm-2 col-md-2 col-lg-2"><a href="accueil.php" class="navbar-link">Accueil</a></li>
					<li class="col-xs-6 col-sm-2 col-md-2 col-lg-2"><a href="ajouter.php" class="navbar-link">Ajouter un vin</a></li>
				</ul>
				<form action="recherche.php" method="get" class="navbar-form" role="form">
					<div id="btn_recherche" class="input-group col-xs-6 col-sm-4 col-md-4 col-lg-4 col-lg-offset-2">
						<input <?php if (isset($_GET['search'])) echo "value='".$_GET['search']."'" ?>type="text" name="search" id="recherche" class="search-query form-control" placeholder="Recherche" onkeyup="datalist(this.value, 'recherche')" onfocus="z_index();" autocomplete="off">
						<span class="input-group-btn">
							<button id="icone_search" type="submit" value="Rechercher" class="btn btn-primary"><i class="glyphicon glyphicon-search glyphicon-white"></i></button>
						</span>
					</div>
				</form>					
			</div>	
		</nav>
	</header>
<?php } 
else header('location:accueil.php');
?>