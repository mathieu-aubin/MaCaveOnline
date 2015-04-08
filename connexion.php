<?php
$PARAM_hote='localhost'; // serveur
$PARAM_port='3306';
$PARAM_nom_bd='macaveon25'; // nom BDD
$PARAM_utilisateur='root'; // nom d'utilisateur pour se connecter
$PARAM_mot_passe='root'; // mot de passe de l'utilisateur pour se connecter

try {
    $connexion = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe);
}
  
catch(Exception $e) {
    echo 'Erreur : '.$e->getMessage().'<br />';
    echo 'N° : '.$e->getCode();
}
?>
