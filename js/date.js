function option_date(option, liste, mois) {
	// construction de la liste déroulante des années pour date du vin (ajout)
	var annee = new Date();
	annee = annee.getFullYear();
	if(option == 1) {
		for (i = annee; i > annee-60; i--) {
			document.write("<option value="+i+">"+i+"</option>");
		};
	}
	// construction de la liste déroulante des années pour date conso (ajout)
	if(option == 2) {
		// si on arrive sur la page, les listes affichent les dates par défaut
		if(liste == 'defaut') {
			//if(mois != 'mois') {
				for (i = annee; i <= annee+30; i++) {
					document.write("<option value="+i+">"+i+"</option>");
				}
			//}			else {for (j = 1; j < 13; j++) document.write("<option value="+j+">"+j+"</option>");}
		}
		else {
			var select_annee_avant = document.getElementById('select_annee_conso_avant');
			select_annee_avant.innerHTML = '<option value="">Choisir une année</option>';
			/*var select_mois_avant = document.getElementById('select_mois_conso_avant');
			select_mois_avant.innerHTML = '<option value="">Choisir un mois</option>';*/
			//si on a sélectionné une valeur pour la date de conso_partir
			if(liste != '') {
				//on converti la valeur en entier
				liste = parseInt(liste);
				//on construit la liste conso_avant à partir de cette valeur avec une liste pour les mois aussi
				for (i = liste; i <= liste+30; i++) {
					select_annee_avant.innerHTML += "<option value="+i+">"+i+"</option>";
				}
				//liste des mois
				//for (j = 1; j < 13; j++) select_mois_avant.innerHTML += "<option value="+j+">"+j+"</option>";
			}
			//si on a pas de valeur, on construit la liste par défaut
			else {
				for (i = annee; i <= annee+30; i++) {
					select_annee_avant.innerHTML += "<option value="+i+">"+i+"</option>";
				}
				//liste des mois
				//for (j = 1; j < 13; j++) select_mois_avant.innerHTML += "<option value="+j+">"+j+"</option>";
			}
		} 
	}
	// construction de la liste déroulante des années pour date conso (modif)
	if(option == 3) {
		var select_avant = document.getElementById('select_conso_max');
		select_avant.innerHTML = '<option value="">Choisir une date</option>';
		//si on a sélectionné une valeur pour la date de conso_partir
		if(liste != '') {
			//on converti la valeur en entier
			liste = parseInt(liste);
			//on construit la liste conso_avant à partir de cette valeur
			for (i = liste; i <= liste+30; i++) {
				select_avant.innerHTML += "<option value="+i+">"+i+"</option>";
			}
		}
		//si on a pas de valeur, on construit la liste par défaut
		else {
			for (i = annee; i <= annee+30; i++) {
				select_avant.innerHTML += "<option value="+i+">"+i+"</option>";
			};
		}
	}
}