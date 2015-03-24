function datalist(valeur, liste) {
	var xhr = getXhr();
	//construction de la datalist pour le nom du vin dans le formulaire d'ajout
	if(valeur != ' ' && liste == 'ajout_nom') {
		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200){
				if(xhr.responseText != '') {
					//on affiche la liste des noms des vins existants
					reponse = xhr.responseText;
					var valeurs = reponse.split(',');
					var resultats = new Array();
					for (i=0; i < valeurs.length; i++) {
						resultats.push(valeurs[i]); 
					};
					$(function() {
					    $("#nom_vin_ajout").autocomplete({
					      source: resultats
					    });
					});
				}
			}
		}
		//on renvoi vers le fichier qui gère le script
		xhr.open("POST","formulaire_ajout.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		//on transmet les variables
		xhr.send("nom="+valeur);
	}
	//construction de la datalist pour la recherche
	if(valeur != ' ' && liste == 'recherche') {
		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200){
				if(xhr.responseText != "") {
					//on affiche la liste des noms des vins existants
					reponse = xhr.responseText;
					var valeurs = reponse.split(',');
					var resultats = new Array();
					for (i=0; i < valeurs.length; i++) {
						resultats.push(valeurs[i]); 
					};
					$(function() {
					    $("#recherche").autocomplete({
					      source: resultats
					    });
					});				}
			}
		}
		//on renvoi vers le fichier qui gère le script
		xhr.open("POST","formulaire_ajout.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		//on transmet les variables
		xhr.send("recherche="+valeur);
	}	
}

function z_index() {
	$('#nav').css('z-index', '1');
}