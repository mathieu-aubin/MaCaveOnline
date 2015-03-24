function liste_appellation(liste, id) {
	var id_region = id;
	var xhr = getXhr();
	//contruction de la liste d'appellation de la page d'index
	if(liste == "index" && id != 'autre') {
		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200){
				if(xhr.responseText != "") {
					document.getElementById("appellation").innerHTML = xhr.responseText;
					//console.log(xhr.responseText);
				}
				else document.getElementById("appellation").style.display = "none";
			}
		}	
		//on renvoi vers le fichier qui gère le script
		xhr.open("POST","requetes.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		//on transmet les variables
		xhr.send("region="+id_region);
	}
	//contruction de la liste d'appellation de la page d'ajout
	else if(liste == "ajout" && id != 'autre') {
		// On défini ce qu'on va faire quand on aura la réponse
		xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
			if(xhr.readyState == 4 && xhr.status == 200){
				if(xhr.responseText != "") {
					//on affiche la liste déroulante de l'AOC
					var div_aoc = document.getElementById("aoc_ajout");
					div_aoc.innerHTML = xhr.responseText;
					div_aoc.style.display = 'block';
					//on supprime l'input de saisie de l'AOC et le lien s'il est présent dans la div region
					var lien = $('#aoc_new_region');
					if(lien.length) document.getElementById("aoc_new_region").remove();	
					var input = $('#aoc_new');
					if(input.length) document.getElementById("aoc_new").remove();	
				}
			}
		}	
		//on renvoi vers le fichier qui gère le script
		xhr.open("POST","formulaire_ajout.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

		//on transmet les variables
		xhr.send("region="+id_region);
	}
	//si le select de la région à la valeur 'autre', on cache le select de l'AOC
	else {
		//on cache la div
		document.getElementById("aoc_ajout").style.display = "none";
		//on supprime le select
		var select = $('#select_aoc');
		if(select.length) document.getElementById('select_aoc').remove();
		//si on a un input de saisie dans la div d'appelleation, on le supprime
		var input = $('#input_aoc_ajout');
		if(input.length) document.getElementById("input_aoc_ajout").remove();
	} 
}