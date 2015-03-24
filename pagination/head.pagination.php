<?php
	require_once('pagination/pagination.class.php');

	// On décide d'afficher 9 vins par pages
	$posts_per_page = 9;

	// On détermine la page courante
	// Si $_GET['paged'] est vide, on considère que nous sommes sur la première page
	$current_page = ( !empty( $_GET['paged'] ) ) ? intval( $_GET['paged'] ) : 1 ;
	$current_items = ($current_page - 1 ) * $posts_per_page;
?>