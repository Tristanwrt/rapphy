<?php
/**
 * La page d'accueil en blocs : chaque section du site est un bloc de l'éditeur WordPress.
 * Le propriétaire ouvre la page « Accueil », clique sur une section, modifie ses textes
 * et ses photos dans la colonne de droite, déplace ou supprime des sections, et peut
 * glisser des blocs classiques (texte, image, galerie) entre deux sections.
 *
 * Chaque bloc réutilise le gabarit de sa section (template-parts/…). Quand un champ
 * du bloc est vide, la valeur vient de Personnaliser : rien n'est perdu.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * La liste des sections et de leurs champs modifiables.
 * « mod » est le réglage de Personnaliser que le champ remplace.
 */
function vr_blocs_definitions() {
	return array(
		'hero' => array(
			'titre'       => 'Grande image d\'accueil',
			'description' => 'La photo plein écran, le titre, la phrase de présentation et la barre de recherche.',
			'icone'       => 'cover-image',
			'part'        => 'hero',
			'champs'      => array(
				'image'     => array( 'type' => 'image', 'label' => 'Photo plein écran', 'mod' => 'vr_hero_image' ),
				'position'  => array( 'type' => 'select', 'label' => 'Point fort de la photo', 'mod' => 'vr_hero_position', 'aide' => 'La partie à garder quand l\'écran est plus large que la photo. « Bas » si la piscine est en bas.', 'options' => array( 'haut' => 'Le haut de la photo', 'centre' => 'Le centre', 'bas' => 'Le bas de la photo' ) ),
				'surtitre'  => array( 'type' => 'text', 'label' => 'Petite ligne au-dessus du titre', 'mod' => 'vr_hero_surtitre' ),
				'titre'     => array( 'type' => 'textarea', 'label' => 'Grand titre', 'mod' => 'vr_hero_titre' ),
				'sousTitre' => array( 'type' => 'textarea', 'label' => 'Phrase de présentation', 'mod' => 'vr_hero_sous_titre' ),
				'preuve1'   => array( 'type' => 'text', 'label' => 'Mention 1 (avec étoiles)', 'mod' => 'vr_preuve_1' ),
				'preuve2'   => array( 'type' => 'text', 'label' => 'Mention 2 (avec bouclier)', 'mod' => 'vr_preuve_2' ),
				'preuve3'   => array( 'type' => 'text', 'label' => 'Mention 3 (avec coche)', 'mod' => 'vr_preuve_3' ),
			),
		),
		'chiffres' => array(
			'titre'       => 'Chiffres clés',
			'description' => 'La bande de chiffres sous la grande image, sur deux colonnes symétriques.',
			'icone'       => 'chart-bar',
			'part'        => 'chiffres',
			'champs'      => array(
				'liste' => array( 'type' => 'textarea', 'label' => 'Les chiffres', 'mod' => 'vr_chiffres_liste', 'aide' => 'Une ligne par chiffre : valeur | légende | icône. Icônes : ruler, users, bed, tree, waves, spa, sun, wifi, film, dumbbell, chef, paw, shield, check, calendar, beach.' ),
			),
		),
		'direct' => array(
			'titre'       => 'Réserver en direct',
			'description' => 'Les trois arguments pour réserver chez vous plutôt que sur une plateforme.',
			'icone'       => 'awards',
			'part'        => 'direct',
			'champs'      => array(
				'titre'     => array( 'type' => 'text', 'label' => 'Titre', 'mod' => 'vr_direct_titre' ),
				'titre2'    => array( 'type' => 'text', 'label' => 'Suite du titre (en doré)', 'mod' => 'vr_direct_titre_2' ),
				'arg1Titre' => array( 'type' => 'text', 'label' => 'Argument 1 — titre', 'mod' => 'vr_direct_1_titre' ),
				'arg1Texte' => array( 'type' => 'textarea', 'label' => 'Argument 1 — texte', 'mod' => 'vr_direct_1_texte' ),
				'arg2Titre' => array( 'type' => 'text', 'label' => 'Argument 2 — titre', 'mod' => 'vr_direct_2_titre' ),
				'arg2Texte' => array( 'type' => 'textarea', 'label' => 'Argument 2 — texte', 'mod' => 'vr_direct_2_texte' ),
				'arg3Titre' => array( 'type' => 'text', 'label' => 'Argument 3 — titre', 'mod' => 'vr_direct_3_titre' ),
				'arg3Texte' => array( 'type' => 'textarea', 'label' => 'Argument 3 — texte', 'mod' => 'vr_direct_3_texte' ),
			),
		),
		'visite' => array(
			'titre'       => 'Visite guidée',
			'description' => 'Le parcours animé pièce par pièce, au défilement.',
			'icone'       => 'location-alt',
			'part'        => 'visite',
			'apercu'      => true,
			'note'        => 'Les pièces, leurs photos et leur ordre se gèrent dans Ma villa → Visite guidée.',
			'champs'      => array(
				'titre' => array( 'type' => 'text', 'label' => 'Titre', 'mod' => 'vr_visite_titre' ),
			),
		),
		'villa' => array(
			'titre'       => 'La villa',
			'description' => 'La présentation du séjour et de la cuisine, avec la galerie de photos.',
			'icone'       => 'admin-home',
			'part'        => 'villa',
			'note'        => 'Les photos de cette section sont celles de la visite guidée (Ma villa → Visite guidée).',
			'champs'      => array(
				'titre'  => array( 'type' => 'textarea', 'label' => 'Titre', 'mod' => 'vr_villa_titre' ),
				'texte1' => array( 'type' => 'textarea', 'label' => 'Premier paragraphe', 'mod' => 'vr_villa_texte_1' ),
				'texte2' => array( 'type' => 'textarea', 'label' => 'Second paragraphe', 'mod' => 'vr_villa_texte_2' ),
			),
		),
		'chambres' => array(
			'titre'       => 'Les chambres',
			'description' => 'Les quatre chambres et suites, chacune avec ses photos.',
			'icone'       => 'admin-multisite',
			'part'        => 'chambres',
			'note'        => 'Chaque chambre, ses textes et ses photos se modifient dans Ma villa → Chambres.',
			'champs'      => array(
				'titre' => array( 'type' => 'textarea', 'label' => 'Titre', 'mod' => 'vr_chambres_titre' ),
				'texte' => array( 'type' => 'textarea', 'label' => 'Introduction', 'mod' => 'vr_chambres_texte' ),
			),
		),
		'sejours' => array(
			'titre'       => 'Deux façons de séjourner',
			'description' => 'Villa complète ou formule Cocooning, avec les prix.',
			'icone'       => 'tickets-alt',
			'part'        => 'sejours',
			'note'        => 'Les prix affichés viennent de Réservations → Tarifs & saisons.',
			'champs'      => array(
				'titre'          => array( 'type' => 'textarea', 'label' => 'Titre', 'mod' => 'vr_sejours_titre' ),
				'texteComplete'  => array( 'type' => 'textarea', 'label' => 'Villa complète — description', 'mod' => 'vr_sejours_haute_texte' ),
				'texteCocooning' => array( 'type' => 'textarea', 'label' => 'Formule Cocooning — description', 'mod' => 'vr_sejours_basse_texte' ),
			),
		),
		'exterieurs' => array(
			'titre'       => 'Piscine, plage & jardin',
			'description' => 'La mosaïque sombre des extérieurs.',
			'icone'       => 'palmtree',
			'part'        => 'exterieurs',
			'note'        => 'Les photos sont celles des espaces extérieurs de la visite guidée (zone « Les extérieurs »).',
			'champs'      => array(
				'titre'  => array( 'type' => 'text', 'label' => 'Titre', 'mod' => 'vr_ext_titre' ),
				'titre2' => array( 'type' => 'text', 'label' => 'Suite du titre (en doré)', 'mod' => 'vr_ext_titre_2' ),
			),
		),
		'film' => array(
			'titre'       => 'Vidéo de la villa',
			'description' => 'Un montage vidéo en boucle, ou un diaporama automatique des photos.',
			'icone'       => 'video-alt3',
			'part'        => 'film',
			'apercu'      => true,
			'champs'      => array(
				'video'    => array( 'type' => 'video', 'label' => 'Fichier vidéo (MP4)', 'mod' => 'vr_video_fichier' ),
				'surtitre' => array( 'type' => 'text', 'label' => 'Petite ligne au-dessus du titre', 'mod' => 'vr_video_surtitre' ),
				'titre'    => array( 'type' => 'textarea', 'label' => 'Titre', 'mod' => 'vr_video_titre' ),
			),
		),
		'avis' => array(
			'titre'       => 'Avis des voyageurs',
			'description' => 'Les notes Google et Airbnb, puis les témoignages.',
			'icone'       => 'star-filled',
			'part'        => 'avis',
			'note'        => 'Les témoignages se gèrent dans Ma villa → Avis. Les liens Google et Airbnb dans Personnaliser → Vos coordonnées.',
			'champs'      => array(
				'titre'       => array( 'type' => 'textarea', 'label' => 'Titre', 'mod' => 'vr_avis_titre' ),
				'badge1Valeur' => array( 'type' => 'text', 'label' => 'Note 1', 'mod' => 'vr_badge_1_valeur' ),
				'badge1Label'  => array( 'type' => 'text', 'label' => 'Légende 1', 'mod' => 'vr_badge_1_label' ),
				'badge2Valeur' => array( 'type' => 'text', 'label' => 'Note 2', 'mod' => 'vr_badge_2_valeur' ),
				'badge2Label'  => array( 'type' => 'text', 'label' => 'Légende 2', 'mod' => 'vr_badge_2_label' ),
				'badge3Valeur' => array( 'type' => 'text', 'label' => 'Note 3', 'mod' => 'vr_badge_3_valeur' ),
				'badge3Label'  => array( 'type' => 'text', 'label' => 'Légende 3', 'mod' => 'vr_badge_3_label' ),
				'badge4Valeur' => array( 'type' => 'text', 'label' => 'Note 4', 'mod' => 'vr_badge_4_valeur' ),
				'badge4Label'  => array( 'type' => 'text', 'label' => 'Légende 4', 'mod' => 'vr_badge_4_label' ),
			),
		),
		'reservation' => array(
			'titre'       => 'Calendrier de réservation',
			'description' => 'Le calendrier avec les formules, les prix et le total.',
			'icone'       => 'calendar-alt',
			'part'        => 'reservation',
			'apercu'      => true,
			'note'        => 'Les dates, prix et règles se gèrent dans le menu Réservations. Les boutons utilisent les coordonnées de Personnaliser.',
			'champs'      => array(
				'titre' => array( 'type' => 'textarea', 'label' => 'Titre', 'mod' => 'vr_resa_titre' ),
				'texte' => array( 'type' => 'textarea', 'label' => 'Texte d\'introduction', 'mod' => 'vr_resa_texte' ),
				'note'  => array( 'type' => 'textarea', 'label' => 'Petite note sous les boutons', 'mod' => 'vr_resa_note' ),
			),
		),
		'region' => array(
			'titre'       => 'La région',
			'description' => 'Les lieux à découvrir et la carte.',
			'icone'       => 'location',
			'part'        => 'region',
			'note'        => 'Les lieux et leurs photos se gèrent dans Ma villa → Lieux à découvrir.',
			'champs'      => array(
				'titre'     => array( 'type' => 'textarea', 'label' => 'Titre', 'mod' => 'vr_region_titre' ),
				'texte'     => array( 'type' => 'textarea', 'label' => 'Introduction', 'mod' => 'vr_region_texte' ),
				'legende'   => array( 'type' => 'text', 'label' => 'Légende sous la carte', 'mod' => 'vr_carte_legende' ),
				'latitude'  => array( 'type' => 'text', 'label' => 'Latitude', 'mod' => 'vr_carte_latitude' ),
				'longitude' => array( 'type' => 'text', 'label' => 'Longitude', 'mod' => 'vr_carte_longitude' ),
			),
		),
		'faq' => array(
			'titre'       => 'Questions fréquentes',
			'description' => 'Les questions-réponses dépliables.',
			'icone'       => 'editor-help',
			'part'        => 'faq',
			'note'        => 'Les questions et réponses se gèrent dans Ma villa → Questions fréquentes.',
			'champs'      => array(
				'titre' => array( 'type' => 'textarea', 'label' => 'Titre', 'mod' => 'vr_faq_titre' ),
			),
		),
	);
}

/**
 * L'ordre des sections sur une page d'accueil neuve.
 */
function vr_blocs_ordre() {
	return array( 'hero', 'chiffres', 'direct', 'visite', 'villa', 'chambres', 'sejours', 'exterieurs', 'film', 'avis', 'reservation', 'region', 'faq' );
}

/* ═══════════════════════════════════════════════════════════
   ENREGISTREMENT DES BLOCS
   ═══════════════════════════════════════════════════════════ */

function vr_enregistrer_blocs() {
	wp_register_script(
		'vr-blocs',
		get_template_directory_uri() . '/assets/js/blocs.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-data' ),
		VR_VERSION,
		true
	);

	// Valeurs actuelles, pour que les champs du bloc soient déjà remplis dans l'éditeur.
	$donnees = array( 'blocs' => array() );

	foreach ( vr_blocs_definitions() as $cle => $def ) {
		$champs    = array();
		$attributs = array();

		foreach ( $def['champs'] as $attr => $champ ) {
			$media = in_array( $champ['type'], array( 'image', 'video' ), true );

			$attributs[ $attr ] = $media
				? array( 'type' => 'integer', 'default' => 0 )
				: array( 'type' => 'string', 'default' => '' );

			$defaut = get_theme_mod( $champ['mod'], '' );

			if ( $media ) {
				$id     = (int) $defaut;
				$url    = '';
				if ( $id ) {
					$url = ( 'image' === $champ['type'] ) ? wp_get_attachment_image_url( $id, 'medium' ) : wp_get_attachment_url( $id );
				}
				$defaut = array( 'id' => $id, 'url' => $url ? $url : '' );
			}

			$champs[ $attr ] = array(
				'type'    => $champ['type'],
				'label'   => $champ['label'],
				'aide'    => isset( $champ['aide'] ) ? $champ['aide'] : '',
				'options' => isset( $champ['options'] ) ? $champ['options'] : null,
				'defaut'  => $defaut,
			);
		}

		$donnees['blocs'][ $cle ] = array(
			'titre'       => $def['titre'],
			'description' => $def['description'],
			'icone'       => $def['icone'],
			'note'        => isset( $def['note'] ) ? $def['note'] : '',
			'champs'      => $champs,
		);

		register_block_type( 'villa-raffy/' . $cle, array(
			'api_version'     => 2,
			'title'           => $def['titre'],
			'description'     => $def['description'],
			'category'        => 'villa-raffy',
			'icon'            => $def['icone'],
			'attributes'      => $attributs,
			'supports'        => array( 'html' => false, 'multiple' => false, 'reusable' => false, 'customClassName' => false ),
			'editor_script'   => 'vr-blocs',
			'render_callback' => function ( $attributs ) use ( $cle ) {
				return vr_bloc_rendre( $cle, $attributs );
			},
		) );
	}

	wp_localize_script( 'vr-blocs', 'vrBlocs', $donnees );
}
add_action( 'init', 'vr_enregistrer_blocs', 20 );

/**
 * Une catégorie « Villa Raffy » en tête de la liste des blocs.
 */
add_filter( 'block_categories_all', function ( $categories ) {
	array_unshift( $categories, array(
		'slug'  => 'villa-raffy',
		'title' => 'Villa Raffy — sections',
		'icon'  => null,
	) );
	return $categories;
} );

/**
 * Sommes-nous en train de dessiner un aperçu pour l'éditeur (et non le site) ?
 */
function vr_est_apercu_editeur() {
	return defined( 'REST_REQUEST' ) && REST_REQUEST && isset( $_GET['context'] ) && 'edit' === $_GET['context']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/**
 * Dessine un bloc : on remplace temporairement les réglages de Personnaliser
 * par les valeurs du bloc, puis on appelle le gabarit habituel de la section.
 */
function vr_bloc_rendre( $cle, $attributs ) {
	$defs = vr_blocs_definitions();

	if ( empty( $defs[ $cle ] ) ) {
		return '';
	}

	$def     = $defs[ $cle ];
	$filtres = array();

	foreach ( $def['champs'] as $attr => $champ ) {
		if ( ! isset( $attributs[ $attr ] ) ) {
			continue;
		}
		$valeur = $attributs[ $attr ];

		if ( '' === $valeur || null === $valeur || 0 === $valeur || '0' === $valeur ) {
			continue; // Champ vide : on garde la valeur de Personnaliser.
		}

		$callback = function () use ( $valeur ) {
			return $valeur;
		};
		add_filter( 'theme_mod_' . $champ['mod'], $callback, 20 );
		$filtres[] = array( 'theme_mod_' . $champ['mod'], $callback );
	}

	if ( vr_est_apercu_editeur() && ! empty( $def['apercu'] ) ) {
		$html = vr_bloc_apercu( $def );
	} else {
		ob_start();
		get_template_part( 'template-parts/' . $def['part'] );
		$html = ob_get_clean();
	}

	foreach ( $filtres as $filtre ) {
		remove_filter( $filtre[0], $filtre[1], 20 );
	}

	return $html;
}

/**
 * Carte de remplacement dans l'éditeur pour les sections animées
 * (visite guidée, vidéo, calendrier) qui ne peuvent pas s'y jouer.
 */
function vr_bloc_apercu( $def ) {
	$titre = '';
	if ( isset( $def['champs']['titre'] ) ) {
		$titre = get_theme_mod( $def['champs']['titre']['mod'], '' );
	}

	return sprintf(
		'<div class="vr-bloc-apercu"><span class="vr-bloc-apercu__nom">%1$s</span><strong>%2$s</strong><p>%3$s</p><p class="vr-bloc-apercu__note">Cette section est animée : elle s\'affiche telle quelle sur le site. Ses textes se modifient dans la colonne de droite.</p></div>',
		esc_html( $def['titre'] ),
		esc_html( $titre ? $titre : $def['titre'] ),
		esc_html( $def['description'] )
	);
}

/* ═══════════════════════════════════════════════════════════
   ÉDITEUR : STYLES DU THÈME DANS L'APERÇU
   ═══════════════════════════════════════════════════════════ */

function vr_blocs_editeur_styles() {
	add_theme_support( 'editor-styles' );
	add_editor_style( array(
		'https://api.fontshare.com/v2/css?f[]=sentient@400,500,700&f[]=supreme@300,400,500,700&display=swap',
		'style.css',
		'assets/css/editeur.css',
	) );
}
add_action( 'after_setup_theme', 'vr_blocs_editeur_styles' );

/* ═══════════════════════════════════════════════════════════
   INSTALLATION : LA PAGE D'ACCUEIL ET LES MENUS
   ═══════════════════════════════════════════════════════════ */

/**
 * Le contenu d'une page d'accueil neuve : toutes les sections, dans l'ordre.
 */
function vr_contenu_accueil() {
	$lignes = array();
	foreach ( vr_blocs_ordre() as $cle ) {
		$lignes[] = '<!-- wp:villa-raffy/' . $cle . ' /-->';
	}
	return implode( "\n\n", $lignes );
}

/**
 * Crée (ou remplit) la page « Accueil » en blocs, la page « Journal » du blog,
 * et règle WordPress pour les utiliser. Ne touche jamais une page déjà remplie.
 */
function vr_installer_accueil() {
	$accueil_id = ( 'page' === get_option( 'show_on_front' ) ) ? (int) get_option( 'page_on_front' ) : 0;
	$accueil    = $accueil_id ? get_post( $accueil_id ) : null;

	if ( ! $accueil ) {
		$existante = get_page_by_path( 'accueil' );
		if ( $existante ) {
			$accueil = $existante;
		} else {
			$id = wp_insert_post( array(
				'post_title'   => 'Accueil',
				'post_name'    => 'accueil',
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => vr_contenu_accueil(),
			) );
			$accueil = $id ? get_post( $id ) : null;
		}
	}

	if ( $accueil && '' === trim( $accueil->post_content ) ) {
		wp_update_post( array( 'ID' => $accueil->ID, 'post_content' => vr_contenu_accueil() ) );
	}

	if ( $accueil ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $accueil->ID );
	}

	if ( ! (int) get_option( 'page_for_posts' ) ) {
		$journal = get_page_by_path( 'journal' );
		if ( ! $journal ) {
			$id      = wp_insert_post( array(
				'post_title'  => 'Journal',
				'post_name'   => 'journal',
				'post_type'   => 'page',
				'post_status' => 'publish',
			) );
			$journal = $id ? get_post( $id ) : null;
		}
		if ( $journal ) {
			update_option( 'page_for_posts', $journal->ID );
		}
	}
}

/**
 * Crée les deux menus (principal et pied de page) s'ils n'existent pas,
 * pour que le propriétaire les retrouve tout prêts dans Apparence → Menus.
 */
function vr_installer_menus() {
	$emplacements = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! is_array( $emplacements ) ) {
		$emplacements = array();
	}

	$menus = array(
		'principal' => array(
			'nom'   => 'Menu principal',
			'liens' => array(
				array( 'La villa', '/#villa' ),
				array( 'Chambres', '/#chambres' ),
				array( 'Formules & tarifs', '/#formules' ),
				array( 'Piscine & jardin', '/#exterieurs' ),
				array( 'Avis', '/#avis' ),
				array( 'La région', '/#region' ),
				array( 'Contact', '/#contact' ),
			),
		),
		'pied'      => array(
			'nom'   => 'Menu du pied de page',
			'liens' => array(
				array( 'Le séjour & la cuisine', '/#villa' ),
				array( 'Les chambres', '/#chambres' ),
				array( 'Formules & tarifs', '/#formules' ),
				array( 'Piscine, jacuzzi & jardin', '/#exterieurs' ),
				array( 'Avis des voyageurs', '/#avis' ),
				array( 'Réserver en direct', '/#reserver' ),
				array( 'Questions fréquentes', '/#faq' ),
			),
		),
	);

	foreach ( $menus as $emplacement => $menu ) {
		if ( ! empty( $emplacements[ $emplacement ] ) && wp_get_nav_menu_object( $emplacements[ $emplacement ] ) ) {
			continue; // Déjà en place.
		}

		$existant = wp_get_nav_menu_object( $menu['nom'] );
		$menu_id  = $existant ? $existant->term_id : wp_create_nav_menu( $menu['nom'] );

		if ( is_wp_error( $menu_id ) || ! $menu_id ) {
			continue;
		}

		if ( ! $existant ) {
			foreach ( $menu['liens'] as $position => $lien ) {
				wp_update_nav_menu_item( $menu_id, 0, array(
					'menu-item-title'    => $lien[0],
					'menu-item-url'      => home_url( $lien[1] ),
					'menu-item-status'   => 'publish',
					'menu-item-type'     => 'custom',
					'menu-item-position' => $position + 1,
				) );
			}
		}

		$emplacements[ $emplacement ] = (int) $menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $emplacements );
}

/**
 * Lance l'installation à l'activation du thème, et une seule fois après une mise à jour.
 */
function vr_installer_blocs_si_besoin() {
	if ( (int) get_option( 'vr_accueil_version', 0 ) >= 2 ) {
		return;
	}
	vr_installer_accueil();
	vr_installer_menus();
	update_option( 'vr_accueil_version', 2 );
}
add_action( 'after_switch_theme', 'vr_installer_blocs_si_besoin', 20 );
add_action( 'admin_init', 'vr_installer_blocs_si_besoin' );

/**
 * Lien direct « Modifier la page d'accueil » dans la barre d'administration.
 */
add_action( 'admin_bar_menu', function ( $barre ) {
	$id = (int) get_option( 'page_on_front' );
	if ( $id && current_user_can( 'edit_page', $id ) && is_front_page() ) {
		$barre->add_node( array(
			'id'    => 'vr-modifier-accueil',
			'title' => 'Modifier les sections de l\'accueil',
			'href'  => get_edit_post_link( $id ),
		) );
	}
}, 90 );
