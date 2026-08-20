<?php
/**
 * Contenus éditables de la villa, regroupés sous un menu unique
 * pour ne pas noyer le propriétaire dans l'administration.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Menu parent « Ma villa », qui regroupe tous les contenus.
 */
function vr_menu_parent() {
	add_menu_page(
		'Ma villa',
		'Ma villa',
		'edit_posts',
		'vr_contenus',
		'vr_page_contenus',
		'dashicons-admin-home',
		4
	);
}
add_action( 'admin_menu', 'vr_menu_parent' );

function vr_page_contenus() {
	$blocs = array(
		array( 'Chambres', 'Les chambres et suites présentées sur le site.', 'edit.php?post_type=vr_chambre' ),
		array( 'Visite guidée', 'Les pièces qui défilent dans la visite animée de la page d\'accueil.', 'edit.php?post_type=vr_espace' ),
		array( 'Avis voyageurs', 'Les témoignages affichés sur la page d\'accueil.', 'edit.php?post_type=vr_avis' ),
		array( 'Atouts de la villa', 'La liste des équipements avec leurs icônes.', 'edit.php?post_type=vr_atout' ),
		array( 'À découvrir autour', 'Les lieux mis en avant dans la section « La région ».', 'edit.php?post_type=vr_lieu' ),
		array( 'Questions fréquentes', 'La FAQ du bas de page.', 'edit.php?post_type=vr_faq' ),
	);

	echo '<div class="wrap"><h1>Ma villa</h1>';
	echo '<p>Choisissez ce que vous souhaitez modifier. Pour vos coordonnées et les textes principaux, passez plutôt par <a href="' . esc_url( admin_url( 'customize.php' ) ) . '">Apparence → Personnaliser</a>.</p>';
	echo '<div class="vr-admin-grid">';
	foreach ( $blocs as $bloc ) {
		printf(
			'<a class="vr-admin-tile" href="%s"><strong>%s</strong><span>%s</span></a>',
			esc_url( admin_url( $bloc[2] ) ),
			esc_html( $bloc[0] ),
			esc_html( $bloc[1] )
		);
	}
	echo '</div></div>';
}

/**
 * Déclare un type de contenu, rattaché au menu « Ma villa ».
 */
function vr_declarer_type( $cle, $singulier, $pluriel, $supports = array( 'title', 'thumbnail', 'page-attributes' ), $description = '' ) {
	register_post_type( $cle, array(
		'labels'          => array(
			'name'               => $pluriel,
			'singular_name'      => $singulier,
			'add_new'            => 'Ajouter',
			'add_new_item'       => 'Ajouter — ' . $singulier,
			'edit_item'          => 'Modifier — ' . $singulier,
			'new_item'           => 'Nouveau — ' . $singulier,
			'view_item'          => 'Voir',
			'search_items'       => 'Rechercher',
			'not_found'          => 'Aucun élément pour l\'instant.',
			'not_found_in_trash' => 'Aucun élément dans la corbeille.',
			'menu_name'          => $pluriel,
		),
		'description'     => $description,
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => 'vr_contenus',
		'show_in_rest'    => true,
		'supports'        => $supports,
		'has_archive'     => false,
		'rewrite'         => false,
		'capability_type' => 'post',
		'menu_position'   => 5,
	) );
}

function vr_types() {
	vr_declarer_type( 'vr_chambre', 'Chambre', 'Chambres', array( 'title', 'thumbnail', 'page-attributes' ), 'Les chambres affichées sur la page d\'accueil.' );
	vr_declarer_type( 'vr_espace', 'Espace', 'Visite guidée', array( 'title', 'thumbnail', 'page-attributes' ), 'Les pièces de la visite animée.' );
	vr_declarer_type( 'vr_avis', 'Avis', 'Avis voyageurs', array( 'title', 'editor', 'page-attributes' ), 'Les témoignages de vos voyageurs.' );
	vr_declarer_type( 'vr_atout', 'Atout', 'Atouts de la villa', array( 'title', 'page-attributes' ), 'Les équipements listés avec une icône.' );
	vr_declarer_type( 'vr_lieu', 'Lieu', 'À découvrir autour', array( 'title', 'editor', 'thumbnail', 'page-attributes' ), 'Les lieux de la section « La région ».' );
	vr_declarer_type( 'vr_faq', 'Question', 'Questions fréquentes', array( 'title', 'editor', 'page-attributes' ), 'Le titre est la question, le contenu est la réponse.' );
}
add_action( 'init', 'vr_types' );

/**
 * Colonnes de listing plus parlantes.
 */
function vr_colonnes( $colonnes ) {
	$nouvelles = array();
	foreach ( $colonnes as $cle => $valeur ) {
		if ( 'title' === $cle ) {
			$nouvelles['vr_ordre'] = 'Ordre';
		}
		$nouvelles[ $cle ] = $valeur;
	}
	return $nouvelles;
}
foreach ( array( 'vr_chambre', 'vr_espace', 'vr_avis', 'vr_atout', 'vr_lieu', 'vr_faq' ) as $type ) {
	add_filter( "manage_{$type}_posts_columns", 'vr_colonnes' );
	add_action( "manage_{$type}_posts_custom_column", function ( $colonne, $post_id ) {
		if ( 'vr_ordre' === $colonne ) {
			echo (int) get_post_field( 'menu_order', $post_id );
		}
	}, 10, 2 );
}

/**
 * Trie les listings par ordre d'affichage plutôt que par date.
 */
function vr_tri_admin( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$types = array( 'vr_chambre', 'vr_espace', 'vr_avis', 'vr_atout', 'vr_lieu', 'vr_faq' );
	if ( in_array( $query->get( 'post_type' ), $types, true ) ) {
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'vr_tri_admin' );

/**
 * Contenus de démonstration créés une seule fois à l'activation du thème,
 * pour que le site ne soit jamais vide.
 */
function vr_contenus_initiaux() {
	if ( get_option( 'vr_contenus_installes' ) ) {
		return;
	}

	$demo = array(
		'vr_chambre' => array(
			array( 'La suite parentale', array( 'vr_detail' => 'Lit 160 × 200 · salle de bain privée · accès direct à la salle de sport', 'vr_texte' => 'Le refuge des parents : un grand lit, une salle de bain rien qu\'à vous, et la salle de sport attenante pour commencer la journée en douceur.' ) ),
			array( 'Chambre Campagne', array( 'vr_detail' => 'Lit 160 × 200 · plain-pied · vue jardin', 'vr_texte' => 'Une chambre paisible ouverte sur la verdure, à deux pas du séjour et de la terrasse. Le calme absolu de Saint-Robert.' ) ),
			array( 'Chambre Garonne', array( 'vr_detail' => 'Lit 160 × 200 · plain-pied · literie hôtelière', 'vr_texte' => 'La troisième chambre de plain-pied, avec la même literie grand format que les autres : ici, personne ne dort dans « la petite chambre ».' ) ),
			array( 'La suite de l\'étage', array( 'vr_detail' => '25 m² · 2 lits électriques 80 cm · salle d\'eau · terrasse privée', 'vr_texte' => 'À l\'étage, 25 m² d\'indépendance totale : literie électrique modulable, salle d\'eau privative et terrasse avec accès direct à la piscine.' ) ),
		),
		'vr_espace'  => array(
			array( 'Le séjour cathédrale', array( 'vr_zone' => 'Espace jour', 'vr_texte' => '70 m² baignés de lumière, où les longues tablées s\'éternisent.', 'vr_direction' => 'droite' ) ),
			array( 'La cuisine & son bar', array( 'vr_zone' => 'Espace jour', 'vr_texte' => 'Équipée pour les chefs, pensée pour l\'apéro.', 'vr_direction' => 'droite' ) ),
			array( 'Le cinéma 3m50', array( 'vr_zone' => 'Espace jour', 'vr_texte' => 'Canal+, PlayStation et un écran géant pour des soirées mémorables.', 'vr_direction' => 'droite' ) ),
			array( 'Le coin cheminée', array( 'vr_zone' => 'Espace jour', 'vr_texte' => 'La flamme qui réchauffe les soirées d\'hiver, un plaid et un bon livre.', 'vr_direction' => 'droite' ) ),
			array( 'La suite parentale', array( 'vr_zone' => 'Espace nuit', 'vr_texte' => 'Lit 160 × 200, salle de bain privée, et la salle de sport attenante.', 'vr_direction' => 'bas' ) ),
			array( 'La chambre Campagne', array( 'vr_zone' => 'Espace nuit', 'vr_texte' => 'Ouverte sur la verdure, à deux pas du séjour. Le calme absolu.', 'vr_direction' => 'droite' ) ),
			array( 'La chambre Garonne', array( 'vr_zone' => 'Espace nuit', 'vr_texte' => 'Literie hôtelière grand format : personne ne dort dans « la petite chambre ».', 'vr_direction' => 'droite' ) ),
			array( 'La suite de l\'étage', array( 'vr_zone' => 'L\'étage', 'vr_texte' => '25 m² d\'indépendance, salle d\'eau privée et terrasse sur la piscine.', 'vr_direction' => 'bas' ) ),
			array( 'La salle de sport', array( 'vr_zone' => 'L\'étage', 'vr_texte' => 'Vélo, elliptique, rameur, banc de musculation : la forme, même en vacances.', 'vr_direction' => 'droite' ) ),
			array( 'La piscine & son bar immergé', array( 'vr_zone' => 'Les extérieurs', 'vr_texte' => '9 mètres plein sud, un cocktail les pieds dans l\'eau.', 'vr_direction' => 'bas' ) ),
			array( 'Le jacuzzi sous les étoiles', array( 'vr_zone' => 'Les extérieurs', 'vr_texte' => '5 places pour prolonger la nuit, éclairages compris.', 'vr_direction' => 'droite' ) ),
			array( 'Plage privée & jardin exotique', array( 'vr_zone' => 'Les extérieurs', 'vr_texte' => 'Sable fin, kiosque zen, trois terrasses et un terrain de pétanque.', 'vr_direction' => 'droite' ) ),
		),
		'vr_atout'   => array(
			array( 'Écran géant 3m50 — Canal+ & PlayStation', array( 'vr_icone' => 'film' ) ),
			array( 'Cuisine ouverte équipée avec bar', array( 'vr_icone' => 'chef' ) ),
			array( 'WiFi fibre + espace de travail dédié', array( 'vr_icone' => 'wifi' ) ),
			array( '3 terrasses exposées plein sud', array( 'vr_icone' => 'sun' ) ),
			array( 'Animaux acceptés (sous conditions)', array( 'vr_icone' => 'paw' ) ),
			array( 'Terrain entièrement clos et privé', array( 'vr_icone' => 'shield' ) ),
		),
		'vr_lieu'    => array(
			array( 'Agen, à 20 minutes', array(), 'La capitale du pruneau, ses ruelles, son musée des Beaux-Arts et les berges de Garonne. Marchés gourmands le samedi matin, restaurants et golf à un quart d\'heure de la villa.' ),
			array( 'Villeneuve-sur-Lot & la vallée du Lot', array(), 'Bastide médiévale, marché couvert, balades en gabarre sur le Lot. Et tout autour, les plus beaux villages de France : Pujols, Penne-d\'Agenais, Monflanquin.' ),
			array( 'La campagne du Lot-et-Garonne', array(), 'Vergers de pruniers, tournesols, chemins de randonnée et routes à vélo au départ de la villa. Le Sud-Ouest authentique, entre Bordeaux et Toulouse.' ),
		),
		'vr_avis'    => array(
			array( 'Virginie', array( 'vr_source' => 'Airbnb', 'vr_date' => 'Mai 2026' ), 'Excellent séjour. Magnifique maison. Intérieur et extérieur conformes à la description. Une bulle de dépaysement. Merci à nos hôtes pour leur réactivité et leur bienveillance. Nous recommandons ce lieu à 400 pour cent.' ),
			array( 'Déborah', array( 'vr_source' => 'Airbnb', 'vr_date' => 'Mai 2026' ), 'Tout était réuni pour passer un très bon séjour : une literie impeccable, des équipements de qualité et un logement parfaitement agréable. Mention spéciale pour les huîtres et crevettes ultra fraîches commandées directement auprès de Stéphane. Des hôtes attentionnés et disponibles.' ),
			array( 'Benoit', array( 'vr_source' => 'Airbnb', 'vr_date' => 'Mai 2025' ), 'La maison de Stéphane est pratique et assez spacieuse pour être à 8 personnes. Les extérieurs sont topissime !! Je recommande vivement.' ),
			array( 'Benny', array( 'vr_source' => 'Airbnb', 'vr_date' => 'Mai 2026' ), 'Maison intérieur et extérieur magique, je reviendrai. Des personnes très accueillantes, nous avons passé un week-end au top.' ),
		),
		'vr_faq'     => array(
			array( 'Où se trouve la Villa Raffy exactement ?', array(), 'À Saint-Robert (47340), en Lot-et-Garonne, à mi-chemin entre Agen et Villeneuve-sur-Lot — environ 20 km de chacune des deux villes. Vous êtes au calme absolu de la campagne, tout en restant à 20 minutes des commerces, restaurants et marchés.' ),
			array( 'Combien de personnes la villa peut-elle accueillir ?', array(), 'La villa accueille jusqu\'à 8 voyageurs dans 4 chambres, toutes équipées de lits 160 × 200. Chaque espace nuit a sa salle de bain ou salle d\'eau, et un lit bébé est disponible.' ),
			array( 'La piscine est-elle adaptée aux enfants ?', array(), 'Oui : la piscine fait 9 × 3,50 m avec une profondeur unique de 1,30 m, idéale pour les enfants accompagnés. Le terrain de 2300 m² est entièrement clos.' ),
			array( 'Les animaux sont-ils acceptés ?', array(), 'Oui, vos compagnons sont acceptés sous conditions : signalez simplement leur présence lors de votre demande. Le terrain clos leur permet de profiter en liberté et en sécurité. À noter : les fêtes ne sont pas autorisées.' ),
			array( 'Comment se passe l\'arrivée ?', array(), 'En toute autonomie, avec une boîte à clé sécurisée : vous arrivez quand vous voulez entre 16h et 23h, sans attendre personne. Le départ se fait avant 10h.' ),
			array( 'Pourquoi réserver en direct plutôt que sur une plateforme ?', array(), 'En direct, vous évitez les frais de service des plateformes, qui peuvent atteindre 15 % du séjour. Vous échangez directement avec vos hôtes, qui répondent personnellement et rapidement.' ),
		),
	);

	foreach ( $demo as $type => $elements ) {
		foreach ( $elements as $ordre => $element ) {
			$post_id = wp_insert_post( array(
				'post_type'    => $type,
				'post_title'   => $element[0],
				'post_content' => isset( $element[2] ) ? $element[2] : '',
				'post_status'  => 'publish',
				'menu_order'   => $ordre + 1,
			) );

			if ( $post_id && ! empty( $element[1] ) ) {
				foreach ( $element[1] as $cle => $valeur ) {
					update_post_meta( $post_id, $cle, $valeur );
				}
			}
		}
	}

	update_option( 'vr_contenus_installes', 1 );
}
add_action( 'after_switch_theme', 'vr_contenus_initiaux' );
