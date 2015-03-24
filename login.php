<?php
	require 'connexion.php';
	if(isset($_POST['connexion'])) {
		if(empty($_POST['name']) || empty($_POST['password'])) //champ vide
		{
			echo ('<meta http-equiv="refresh" content="0;URL=index.php?erreur">');
		}
		else {
			$query=$connexion->prepare('SELECT id_utilisateur, login, mdp FROM utilisateur WHERE login = :login');
   	    	$query->bindValue(':login',$_POST['name'], PDO::PARAM_STR);
        	$query->execute();
        	$res=$query->fetch();
			if ($res['mdp'] == md5($_POST['password'])) // Acces OK !
			{
				session_start();
			    $_SESSION['user'] = $res['id_utilisateur'];
			    $_SESSION['login'] = $_POST['name'];
			    $_SESSION['pass'] = $_POST['password'];
			    header('location:accueil.php');  
			}
			else // Acces pas OK !
			{
		        echo ('<meta http-equiv="refresh" content="0;URL=index.php?erreur">');    
			}
		    $query->CloseCursor();
		}
	}
	else header('location: index.php');
?>