<?php
    echo "<div id='navPage'>";
    $options = ['range' => 2, 'posts_per_page' => $posts_per_page];
    if (isset($region)) {
        $region = '&region='.$region;
    } else {
        $region = '';
    }
    if (isset($appellation)) {
        $appellation = '&appellation='.$appellation;
    } else {
        $appellation = '';
    }
    if (isset($type)) {
        $type = '&type='.$type;
    } else {
        $type = '';
    }
    if (isset($stockage)) {
        $stockage = '&stockage='.$stockage;
    } else {
        $stockage = '';
    }
    if (isset($_POST['search'])) {
        $search = '&search='.$_POST['search'];
    } elseif (isset($_GET['search'])) {
        $search = '&search='.$_GET['search'];
    } else {
        $search = '';
    }

    $pagination = new Pagination('?paged=%s'.$region.$appellation.$type.$stockage.$search, $current_page, $total_posts, $options); // On fait une instance de la classe Pagination
    $pagination->display(); // On affiche le rendu de la pagination
    switch ($total_posts) {
        //pas de résultats
        case '': {
            echo '';
            break;
        }
        case 'index': {
            echo '';
            break;
        }
        case 1: {
            echo "<p id='nb-enregistrements'>".$total_posts.' résultat</p>';
            break;
        }
        default: echo "<p id='nb-enregistrements'>".$total_posts.' résultats</p>';
    }
    echo '</div>';
