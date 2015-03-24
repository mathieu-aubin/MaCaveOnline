function fiche_vin(id_vin) {
	var id = id_vin.split('_');
	id = id[1];

	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			if(xhr.responseText != "") {
				var fiche_vin = document.getElementById("fiche_body");
				fiche_vin.innerHTML = xhr.responseText;
				$('#fiche_vin').modal('show');
			}
			else document.getElementById("fiche_vin").style.display = "none";
		}
	}	
	//on renvoi vers le fichier qui gère le script
	xhr.open("POST","requetes.php",true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	//on transmet les variables
	xhr.send("id_vin="+id);
}
