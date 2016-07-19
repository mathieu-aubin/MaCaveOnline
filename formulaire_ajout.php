<?php
    session_start();
    if (isset($_SESSION['login']) && isset($_SESSION['pass'])) {
        require 'connexion.php';
    //fonction de construction de l'autocomplétion pour le nom du vin à l'ajout et la recherche
    function datalistes($value)
    {
        include 'connexion.php';
        $req_nom = "SELECT DISTINCT(nom) FROM vins WHERE nom LIKE '%".$value."%' LIMIT 0,20";
        $res_nom = $connexion->query($req_nom);
        $res_nom->setFetchMode(PDO::FETCH_ASSOC);
        while ($ligne_nom = $res_nom->fetch()) {
            $liste_nom = stripslashes(utf8_encode($ligne_nom['nom']));
            echo $liste_nom.',';
        }
        $res_nom->closeCursor();
    }
    //on crée la liste pour le nom du vin
    if (isset($_POST['nom'])) {
        datalistes($_POST['nom']);
    }
    //création de la liste pour la recherche (sur le nom du vin)
    if (isset($_POST['recherche'])) {
        datalistes($_POST['recherche']);
    }
    //on fabrique les listes déroulantes pour la région, le type de vin, l'AOC, la note, le degré d'alcool, les lieux de stockage et d'achat et le type de plat
    //region
    $req_region = 'SELECT * from region';
        $res_region = $connexion->query($req_region);
        $res_region->setFetchMode(PDO::FETCH_ASSOC);
        $region = [];
        while ($row_region = $res_region->fetch()) {
            $ligne_region1[] = $row_region['id_region'];
            $ligne_region2[] = $row_region['region'];
        }
        $region = [$ligne_region1, $ligne_region2];
        $select_region = "<select required id='select_region' name='select_region' onchange='option_autre(this.id, this.options[this.selectedIndex].value, \"ajout\"); liste_appellation(\"ajout\", this.value);' class='form-control col-lg-4'><option value=''>Choisir une région</option>";
        for ($i = 0; $i < count($ligne_region1); $i++) {
            $select_region .= '<option value='.$region[0][$i].'>'.$region[1][$i].'</option>';
        }
        $select_region .= "<option value='autre'>Autre...</option></select>";
        $res_region->closeCursor();

    //AOC
    if (isset($_POST['region']) && $_POST['region'] != '' && $_POST['region'] != 6 && $_POST['region'] != 12) {
        $req_aoc = 'SELECT id_appellation, appellation from appellation WHERE FK_region = '.$_POST['region'];
        $res_aoc = $connexion->query($req_aoc);
        $res_aoc->setFetchMode(PDO::FETCH_ASSOC);
        $aoc = [];
        while ($row_aoc = $res_aoc->fetch()) {
            $ligne_aoc1[] = $row_aoc['id_appellation'];
            $ligne_aoc2[] = $row_aoc['appellation'];
        }
        $aoc = [$ligne_aoc1, $ligne_aoc2];
        $select_aoc = "<label class='control-label' for='select_aoc'>AOC <span class='obligatoire'>*</span></label><br><select required id='select_aoc' name='select_aoc' onchange='option_autre(this.id, this.options[this.selectedIndex].value, \"ajout\")' class='form-control col-lg-4'><option value=''>Choisir une AOC</option>";
        for ($i = 0; $i < count($ligne_aoc1); $i++) {
            $select_aoc .= '<option value='.$aoc[0][$i].'>'.utf8_encode($aoc[1][$i]).'</option>';
        }
        $select_aoc .= "<option value='autre'>Autre...</option></select>";
        echo $select_aoc;
        $res_aoc->closeCursor();
    } else {
        echo ' ';
    }

    //type de vin
    $req_type = 'SELECT * from type_vin';
        $res_type = $connexion->query($req_type);
        $res_type->setFetchMode(PDO::FETCH_ASSOC);
        $type = [];
        while ($row_type = $res_type->fetch()) {
            $ligne_type1[] = $row_type['id_type'];
            $ligne_type2[] = $row_type['type'];
        }
        $type = [$ligne_type1, $ligne_type2];
        $select_type = "<label for='select_type' class='control-label'>Type de vin <span class='obligatoire'>*</span></label><select required id='select_type' name='select_type' class='form-control col-lg-4'><option value=''>Choisir le type de vin</option>";
        for ($i = 0; $i < count($ligne_type1); $i++) {
            $select_type .= '<option value='.$type[0][$i].'>'.utf8_encode($type[1][$i]).'</option>';
        }
        $select_type .= '</select>';
        $res_type->closeCursor();

    //degré alcool
    $select_alcool = "<select id='degre_alcool' name='degre_alcool' class='form-control'><option value=''>Choisir un degré d'alcool</option>";
        for ($i = 10; $i < 21; $i++) {
            $select_alcool .= '<option value='.$i.'>'.$i.'°</option>';
        }
        $select_alcool .= '</select>';

    //note
    $select_note = "<select id='select_note' name='note' class='form-control'><option value=''>Choisir une note</option>";
        for ($i = 0; $i < 21; $i++) {
            $select_note .= '<option value='.$i.'>'.$i.'</option>';
        }
        $select_note .= '</select>';

    //lieu d'achat
    $req_achat = 'SELECT * from lieu_achat';
        $res_achat = $connexion->query($req_achat);
        $res_achat->setFetchMode(PDO::FETCH_ASSOC);
        $achat = [];
        while ($row_achat = $res_achat->fetch()) {
            $ligne_achat1[] = $row_achat['id_lieu_achat'];
            $ligne_achat2[] = $row_achat['lieu_achat'];
        }
        $achat = [$ligne_achat1, $ligne_achat2];
        $select_achat = "<select id='select_achat' name='select_achat' onchange='option_autre(this.id, this.options[this.selectedIndex].value, \"ajout\");' class='form-control col-lg-4'><option value=''>Choisir le lieu d'achat</option>";
        for ($i = 0; $i < count($ligne_achat1); $i++) {
            $select_achat .= '<option value='.$achat[0][$i].'>'.utf8_encode($achat[1][$i]).'</option>';
        }
        $select_achat .= "<option value='autre'>Autre...</option></select>";
        $res_achat->closeCursor();

    //lieu de stockage
    $req_stockage = 'SELECT * from lieu_stockage';
        $res_stockage = $connexion->query($req_stockage);
        $res_stockage->setFetchMode(PDO::FETCH_ASSOC);
        $stockage = [];
        while ($row_stockage = $res_stockage->fetch()) {
            $ligne_stockage1[] = $row_stockage['id_lieu_stockage'];
            $ligne_stockage2[] = $row_stockage['lieu_stockage'];
        }
        $stockage = [$ligne_stockage1, $ligne_stockage2];
        $select_stockage = "<select id='select_stockage' name='select_stockage' onchange='option_autre(this.id, this.options[this.selectedIndex].value, \"ajout\");' class='form-control col-lg-4'><option value=''>Choisir le lieu de stockage</option>";
        for ($i = 0; $i < count($ligne_stockage1); $i++) {
            $select_stockage .= '<option value='.$stockage[0][$i].'>'.utf8_encode($stockage[1][$i]).'</option>';
        }
        $select_stockage .= "<option value='autre'>Autre...</option></select>";
        $res_stockage->closeCursor();
    } else {
        header('location:accueil.php');
    }
