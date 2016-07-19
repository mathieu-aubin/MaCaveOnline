<?php
    session_start();
?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Connexion</title>
	<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" media="screen">
	<link rel="stylesheet" href="css/bootstrap/bootstrap-responsive.min.css" media="screen">
	<link rel="stylesheet" href="css/bootstrap/bootstrap-theme.min.css" media="screen">
	<link rel="stylesheet" href="css/style.css" media="screen">
	<link href="css/jquery/jquery-custom.min.css" rel="stylesheet" media="screen">
	<script src="js/jquery/jquery2.min.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
	<script src="js/jquery/jquery-custom.js"></script>
	<script src="js/jquery/bootbox.min.js"></script>
	<script src="js/mode_demo.js"></script>
</head>
<body>
	<div id="conteneur" class="container">
		<header id="header_demo" class="row">
			<div class="pull-left">
				<img src="img/logo_demo.png" id="logo_demo" alt="logo">
			</div>
			<div id="header_bg">
				<img src="img/header.png" id="header_img" class="hidden-xs" alt="header">
			</div>
		</header>
		<section class="row" id="section_demo">
			<?php
                if (!empty($_SESSION['user'])) {
                    echo "<script>
							$(function(e) {
	            				bootbox.dialog({
	            					message: 'Vous êtes déjà connecté, voulez-vous accéder au site?',
	 								title: 'Connecté !',
	  								buttons: {
	  									success : {
	  										label : 'Oui',
	  										className: 'btn-success',
	  										callback: function() {
      											window.location = 'accueil.php';
      										}
	  									},
	    								danger: {
								      		label: 'Déconnexion',
								      		className: 'btn-danger',
								      		callback: function() {
      											window.location = 'deco.php';
      										}
								    	}
								    }	
	       						});
							});
					    </script>"; ?>
				<form action='login.php' method='post' id='login_form' role='form'>
					<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
						<label for='name' class='control-label'>Identifiant : </label>
						<input type='text' name='name' id='name' placeholder='Login' required class='form-control'>
					</div>
					<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
						<label for='password' class='control-label'>Mot de passe : </label>
						<input type='password' name='password' id='password' placeholder='Mot de passe' required class='form-control'>
						<button type='submit' name='connexion' id='connexion' class='btn btn-primary pull-right'>Connexion</button>
					</div>
				</form>
				<button id='mode_demo' class='btn btn-info pull-right' onclick='mode_demo("demo", "demo");'>Accéder au mode démo</button>
						<p>
				<?php 
                    if (isset($_GET['erreur'])) {
                        echo "<script>
								$(function(e) {
			           				bootbox.dialog({
			           					message: 'Erreur de connexion. Veuillez essayer à nouveau.',
			 							title: 'Erreur',
			  							buttons: {
			    							danger: {
									      		label: 'Ok',
									      		className: 'btn-danger',
									    	}
									    }	
			       					});
								});
						    </script>";
                    }
                    echo '</p>';
                } else {
                    ?>
			<form action="login.php" method="post" id="login_form" role="form">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label for="name" class="control-label">Identifiant : </label>
					<input type="text" name="name" id="name" placeholder="Login" required class="form-control">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<label for="password" class="control-label">Mot de passe : </label>
					<input type="password" name="password" id="password" placeholder="Mot de passe" required class="form-control">
					<button type="submit" name="connexion" id="connexion" class="btn btn-primary pull-right">Connexion</button>
				</div>
			</form>
			<button id="mode_demo" class="btn btn-info pull-right" onclick="mode_demo('demo', 'demo');">Accéder au mode démo</button>
			<p><?php 
                if (isset($_GET['erreur'])) {
                    echo "<script>
							$(function(e) {
	            				bootbox.dialog({
	            					message: 'Erreur de connexion. Veuillez essayer à nouveau.',
	 								title: 'Erreur',
	  								buttons: {
	    								danger: {
								      		label: 'Ok',
								      		className: 'btn-danger',
								    	}
								    }	
	       						});
							});
					    </script>";
                }
                    echo '</p>';
                }
            ?>
		</section>
		<footer>
			
		</footer>
	</div>
</body>
</html>
