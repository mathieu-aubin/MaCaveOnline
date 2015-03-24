<?php

	/**
	* Classe Vin
	*/
	class Vin
	{
		private $idVin;
		private $nom;
		private $region;
		private $appellation;
		private $annee;
		private $type;
		private $degreAlcool;
		private $lieuStockage;
        private $lieuAchat;
		private $consoPartir;
		private $consoAvant;
		private $typePlat;
		private $nbBouteilles;
		private $suiviStock;
		private $favori;
		private $note;
		private $prixAchat;
		private $offertPar;
		private $commentaires;

		function __construct($nom, $region, $appellation=null, $annee, $type, $degreAlcool=null, $lieuStockage=null, $lieuAchat=null, $consoPartir=null, $consoAvant=null, $typePlat=null, $nbBouteilles=null, $suiviStock=null, $favori=null, $note=null, $prixAchat=null, $offertPar=null, $commentaires=null)
		{
			$this->setNom($nom);
			$this->setRegion($region);
			$this->setAppellation($appellation);
			$this->setAnnee($annee);
			$this->setType($type);
		}
	
    /**
     * Gets the value of idVin.
     *
     * @return mixed
     */
    public function getIdVin()
    {
        return $this->idVin;
    }

    /**
     * Sets the value of idVin.
     *
     * @param mixed $idVin the id vin
     *
     * @return self
     */
    public function setIdVin($idVin)
    {
        $this->idVin = $idVin;

        return $this;
    }

    /**
     * Gets the value of nom.
     *
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Sets the value of nom.
     *
     * @param mixed $nom the nom
     *
     * @return self
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Gets the value of region.
     *
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Sets the value of region.
     *
     * @param mixed $region the region
     *
     * @return self
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Gets the value of appellation.
     *
     * @return mixed
     */
    public function getAppellation()
    {
        return $this->appellation;
    }

    /**
     * Sets the value of appellation.
     *
     * @param mixed $appellation the appellation
     *
     * @return self
     */
    public function setAppellation($appellation)
    {
        $this->appellation = $appellation;

        return $this;
    }

    /**
     * Gets the value of annee.
     *
     * @return mixed
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Sets the value of annee.
     *
     * @param mixed $annee the annee
     *
     * @return self
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the value of degreAlcool.
     *
     * @return mixed
     */
    public function getDegreAlcool()
    {
        return $this->degreAlcool;
    }

    /**
     * Sets the value of degreAlcool.
     *
     * @param mixed $degreAlcool the degre alcool
     *
     * @return self
     */
    public function setDegreAlcool($degreAlcool)
    {
        $this->degreAlcool = $degreAlcool;

        return $this;
    }

    /**
     * Gets the value of lieuStockage.
     *
     * @return mixed
     */
    public function getLieuStockage()
    {
        return $this->lieuStockage;
    }

    /**
     * Sets the value of lieuStockage.
     *
     * @param mixed $lieuStockage the lieu stockage
     *
     * @return self
     */
    public function setLieuStockage($lieuStockage)
    {
        $this->lieuStockage = $lieuStockage;

        return $this;
    }
/**
     * Gets the value of lieuAchat.
     *
     * @return mixed
     */
    public function getLieuAchat()
    {
        return $this->lieuAchat;
    }

    /**
     * Sets the value of lieuAchat.
     *
     * @param mixed $lieuAchat the lieu stockage
     *
     * @return self
     */
    public function setLieuAchat($lieuAchat)
    {
        $this->lieuAchat = $lieuAchat;

        return $this;
    }

    /**
     * Gets the value of consoPartir.
     *
     * @return mixed
     */
    public function getConsoPartir()
    {
        return $this->consoPartir;
    }

    /**
     * Sets the value of consoPartir.
     *
     * @param mixed $consoPartir the conso partir
     *
     * @return self
     */
    public function setConsoPartir($consoPartir)
    {
        $this->consoPartir = $consoPartir;

        return $this;
    }

    /**
     * Gets the value of consoAvant.
     *
     * @return mixed
     */
    public function getConsoAvant()
    {
        return $this->consoAvant;
    }

    /**
     * Sets the value of consoAvant.
     *
     * @param mixed $consoAvant the conso avant
     *
     * @return self
     */
    public function setConsoAvant($consoAvant)
    {
        $this->consoAvant = $consoAvant;

        return $this;
    }

    /**
     * Gets the value of typePlat.
     *
     * @return mixed
     */
    public function getTypePlat()
    {
        return $this->typePlat;
    }

    /**
     * Sets the value of typePlat.
     *
     * @param mixed $typePlat the type plat
     *
     * @return self
     */
    public function setTypePlat($typePlat)
    {
        $this->typePlat = $typePlat;

        return $this;
    }

    /**
     * Gets the value of nbBouteilles.
     *
     * @return mixed
     */
    public function getNbBouteilles()
    {
        return $this->nbBouteilles;
    }

    /**
     * Sets the value of nbBouteilles.
     *
     * @param mixed $nbBouteilles the nb bouteilles
     *
     * @return self
     */
    public function setNbBouteilles($nbBouteilles)
    {
        $this->nbBouteilles = $nbBouteilles;

        return $this;
    }

    /**
     * Gets the value of suiviStock.
     *
     * @return mixed
     */
    public function getSuiviStock()
    {
        return $this->suiviStock;
    }

    /**
     * Sets the value of suiviStock.
     *
     * @param mixed $suiviStock the suivi stock
     *
     * @return self
     */
    public function setSuiviStock($suiviStock)
    {
        $this->suiviStock = $suiviStock;

        return $this;
    }

    /**
     * Gets the value of favori.
     *
     * @return mixed
     */
    public function getFavori()
    {
        return $this->favori;
    }

    /**
     * Sets the value of favori.
     *
     * @param mixed $favori the favori
     *
     * @return self
     */
    public function setFavori($favori)
    {
        $this->favori = $favori;

        return $this;
    }

    /**
     * Gets the value of note.
     *
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Sets the value of note.
     *
     * @param mixed $note the note
     *
     * @return self
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Gets the value of prixAchat.
     *
     * @return mixed
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Sets the value of prixAchat.
     *
     * @param mixed $prixAchat the prix achat
     *
     * @return self
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    /**
     * Gets the value of offertPar.
     *
     * @return mixed
     */
    public function getOffertPar()
    {
        return $this->offertPar;
    }

    /**
     * Sets the value of offertPar.
     *
     * @param mixed $offertPar the offert par
     *
     * @return self
     */
    public function setOffertPar($offertPar)
    {
        $this->offertPar = $offertPar;

        return $this;
    }

    /**
     * Gets the value of commentaires.
     *
     * @return mixed
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Sets the value of commentaires.
     *
     * @param mixed $commentaires the commentaires
     *
     * @return self
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }
}

	
?>