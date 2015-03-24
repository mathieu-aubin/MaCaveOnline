function option_autre(id, option, fiche) {
	nom_select = id.split("_");
	//si fiche ajout, select 2 parties
	if(nom_select.length == 2) nom_select = nom_select[1];
	//si fiche modif, select 3 parties
	else nom_select = nom_select[2];
	
	//si on est sur l'ajout d'un vin dans la BDD
	if(fiche == "ajout" && option == "autre") {
		switch(nom_select) {
			//nouvelle région sur ajout de vin
			case "region" : {
				ajout_input(nom_select, fiche);
				//on ajoute un lien pour proposer d'ajouter une AOC
				var lien_aoc = document.createElement('span');
				//on teste si le lien n'existe pas déjà
				if(!(lien_aoc.id)) {
					//lien_aoc.style.display = 'block';
					//lien_aoc.style.width = '200px';
					lien_aoc.innerHTML = 'Ajoutez aussi une appellation';
					lien_aoc.id = "aoc_new_region";
					lien_aoc.setAttribute('onclick', 'ajout_input("'+lien_aoc.id+'","'+fiche+'")');
					div_region = document.getElementById('region_'+fiche);
					div_region.appendChild(lien_aoc);
				}
			break;
			}
			//nouvelle AOC
			case "aoc" : {
				ajout_input(nom_select, fiche);
			}
			break;
			//nouveau lieu d'achat
			case "achat" : {
				ajout_input(nom_select, fiche);
			break;
			}
			//nouvelle lieu de stockage sur ajout de vin
			case "stockage" : {
				ajout_input(nom_select, fiche); 
			break;
			}
		}
	}
	//si on est sur la modification d'une fiche
	//le nom du select comporte 2 fois "_"
	else if ((fiche == 'modif') && (option == 'autre')) {
		switch (nom_select) {
			case "achat" : {
				ajout_input(nom_select, fiche);
			break;
			}
			case "stockage" : {
				ajout_input(nom_select, fiche);
			break;
			}
		}
	}
	// on supprime l'input permettant d'ajouter une valeur si on resélectionne une valeur existante de la liste
	else if (option != 'autre') {
		cacher_input(nom_select, fiche);
	}
}

//construction de la div et de l'input pour ajouter une nouvelle valeur
function ajout_input(nom_select, option) {
	var input = document.createElement('input');
	input.type = 'text';
	input.className = 'form-control';
	div = document.getElementById(nom_select+"_"+option);
	//pour l'AOC, on ajoute un id à l'input pour gérer son affichage
	if(nom_select == 'aoc') input.id = 'input_aoc_ajout';
	//si on a une nouvelle région, on gère aussi la nouvelle AOC associée (si le lien a été cliqué)
	if(nom_select == 'aoc_new_region') {
		var div = document.getElementById("region_"+option);
		input.id = 'aoc_new';
		nom_select = 'aoc';
	} 
	input.name = nom_select+'_'+option; 
	switch(nom_select){
		case 'region' : {
			type = 'une région';
			input.id = 'input_region_ajout';
			input.setAttribute('required','required');
		}
		break;
		case 'aoc' : {
			type = 'une appellation'; 
			//on supprime le lien qui sera remplacé par l'input
			lien_aoc = $('#aoc_new_region');
			if(lien_aoc.length) document.getElementById('aoc_new_region').remove();	
		} 
		break;
		case 'achat' : type = 'un lieu d\'achat';
		break;
		case 'stockage' : type = 'un lieu de stockage';
		break;
	}
	input.setAttribute('placeholder', 'Saisissez '+type);
	div.appendChild(input); 
}

//on détruit l'input si sélection valeur existante
function cacher_input(nom_select, option) {
	var div = document.getElementById(nom_select+"_"+option);
	var input = div.getElementsByTagName('input')[0];
	if(input) input.remove();
}