//vérification des champs dans le formulaire d'ajout
function verif() {
	//on initialise la variable qui valide le test
	var verif = true;
	//nom du vin
	var nb_bouteilles = document.getElementById('nb_bouteilles').value;
	var div_nb_bouteilles = document.getElementById('nb_bouteilles_ajout');
	var erreur_bt = document.getElementById('erreur_bt');
	if(nb_bouteilles != '') {
		if(isNaN(nb_bouteilles)) verif = false;
		if(nb_bouteilles.split('.').length > 1) verif = false;
		if(!verif) {
			div_nb_bouteilles.className = div_nb_bouteilles.className+' has-error';
			erreur_bt.className = 'help-block';
			erreur_bt.innerHTML = "Veuillez saisir un nombre valide.";
		}
		else {
			erreur_bt.innerHTML = '';
			$("#nb_bouteilles_ajout").removeClass('has-error');
		}
	}
	//on test si on a un entier positif
	 
	//vérification du prix saisi
	var input_prix= document.getElementById('prix_achat').value;
	var div_prix = document.getElementById('prix_achat_ajout');
	var erreur_prix = document.getElementById('erreur_prix');
	if(input_prix != '') {
		if(input_prix.split(',').length > 1) input_prix = input_prix.replace(/,/g, '.');
		if(isNaN(input_prix)) verif = false;
		if(input_prix.split('.').length > 1){
			var decimal = input_prix.split('.')[1];
			if(isNaN(decimal) || decimal.length > 2) verif = false;
		}
	
		if(!verif) {
			div_prix.className = div_prix.className+' has-error';
			erreur_prix.className = 'help-block';
			erreur_prix.innerHTML = "Veuillez saisir un prix valide.";
		}
		else {
			erreur_bt.innerHTML = '';
			$("#prix_achat_ajout").removeClass('has-error');
		} 
	}

	//vérification champ offert par
	var input_offert= document.getElementById('offert_par').value;
	var div_offert = document.getElementById('offert_par_ajout');
	var erreur_offert = document.getElementById('erreur_offert');
	if(input_offert != '') {
		if(typeof input_offert != "string") verif = false;
		if(!verif) {
			div_offert.className = div_offert.className+' has-error';
			erreur_offert.className = 'help-block';
			erreur_offert.innerHTML = "Veuillez saisir une valeur valide.";
		}
		else {
			erreur_bt.innerHTML = '';
			$("#offert_par_ajout").removeClass('has-error');
		} 
	}
	return verif;
}

//vérification champs formulaire modification
function verif_modif() {
	var faux = true;
	//vérification nombre de bouteilles
	var nb_bouteilles = document.getElementById('nb_bouteilles').value;
	var div_nb_bouteilles = document.getElementById('nb_bouteilles_modif');
	var erreur_bt = document.getElementById('erreur_bt');
	//on test si on a un entier positif
	if(isNaN(nb_bouteilles)) faux = false;
	if(nb_bouteilles.split('.').length > 1) verif = false;
	if(!faux) {
		div_nb_bouteilles.className = div_nb_bouteilles.className+' has-error';
		erreur_bt.className = 'help-block';
		erreur_bt.innerHTML = "Veuillez saisir un nombre valide.";
	}
	else {
		erreur_bt.innerHTML = '';
		$("#nb_bouteilles_modif").removeClass('has-error');
	} 
	//vérification du prix saisi
	var input_prix= document.getElementById('prix_achat').value;
	var div_prix = document.getElementById('prix_achat_modif');
	var erreur_prix = document.getElementById('erreur_prix');
	if(input_prix != '') {
		if(input_prix.split(',').length > 1) input_prix = input_prix.replace(/,/g, '.');
		if(isNaN(input_prix)) faux = false;
		/*var re = new RegExp("/^\d+\.\d{0,2}$/");
		var matchPos2 = re.test(input_prix);*/
		if(input_prix.split('.').length > 1){
			var decimal = input_prix.split('.')[1];
			if(isNaN(decimal) || decimal.length > 2) faux = false;
		}
	
		if(!faux) {
			div_prix.className = div_prix.className+' has-error';
			erreur_prix.className = 'help-block';
			erreur_prix.innerHTML = "Veuillez saisir un prix valide.";
		}
		else {
			erreur_bt.innerHTML = '';
			$("#prix_achat_modif").removeClass('has-error');
		} 
	}

	//vérification champ offert par
	var input_offert= document.getElementById('offert_par').value;
	var div_offert = document.getElementById('offert_par_modif');
	var erreur_offert = document.getElementById('erreur_offert');
	if(input_offert != '') {
		if(typeof input_offert != "string") faux = false;
		if(!faux) {
			div_offert.className = div_offert.className+' has-error';
			erreur_offert.className = 'help-block';
			erreur_offert.innerHTML = "Veuillez saisir une valeur valide.";
		}
		else {
			erreur_bt.innerHTML = '';
			$("#offert_par_modif").removeClass('has-error');
		} 
	}
	return faux;
}