<?php
/**
 * Champs personnalisés, écrits à la main pour ne dépendre d'aucune extension payante.
 * Les chambres et les espaces de la visite guidée acceptent plusieurs photos :
 * l'image mise en avant, plus une petite galerie (salle de bain, petit salon…).
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Nombre maximum de photos supplémentaires par fiche.
 */
define( 'VR_GALERIE_MAX', 3 );

/**
 * Description de chaque champ, par type de contenu.
 */
function vr_champs_par_type() {
	return array(
		'vr_chambre' => array(
			'titre'  => 'Détails de la chambre',
			'champs' => array(
				'vr_detail'  => array( 'label' => 'Ligne de caractéristiques', 'type' => 'text', 'aide' => 'Par exemple : Lit 160 × 200 · salle de bain privée · vue jardin' ),
				'vr_texte'   => array( 'label' => 'Description', 'type' => 'textarea', 'aide' => 'Deux ou trois phrases qui donnent envie.' ),
				'vr_galerie' => array( 'label' => 'Photos supplémentaires', 'type' => 'galerie', 'aide' => 'En plus de l\'image mise en avant (la chambre), ajoutez jusqu\'à ' . VR_GALERIE_MAX . ' photos : la salle de bain, le petit salon… Les visiteurs les font défiler avec des flèches. Pour nommer une photo, remplissez sa « légende » dans la médiathèque.' ),
			),
		),
		'vr_espace'  => array(
			'titre'  => 'Étape de la visite guidée',
			'champs' => array(
				'vr_zone'      => array( 'label' => 'Zone de la maison', 'type' => 'text', 'aide' => 'Par exemple : Espace jour, Espace nuit, L\'étage, Les extérieurs. Les étapes d\'une même zone défilent côte à côte.' ),
				'vr_texte'     => array( 'label' => 'Phrase d\'accroche', 'type' => 'textarea', 'aide' => 'Une seule phrase, courte et évocatrice.' ),
				'vr_direction' => array(
					'label'   => 'Comment arrive-t-on ici ?',
					'type'    => 'select',
					'options' => array( 'droite' => 'En glissant sur le côté (même zone)', 'bas' => 'En descendant (nouvelle zone)' ),
					'aide'    => 'Choisissez « en descendant » pour la première pièce d\'une nouvelle zone.',
				),
				'vr_galerie'   => array( 'label' => 'Photos supplémentaires', 'type' => 'galerie', 'aide' => 'En plus de l\'image mise en avant, jusqu\'à ' . VR_GALERIE_MAX . ' photos de cet espace (le bar immergé, la plage sous un autre angle…). Elles défilent dans la visite guidée et dans la mosaïque des extérieurs.' ),
			),
		),
		'vr_avis'    => array(
			'titre'  => 'Détails de l\'avis',
			'champs' => array(
				'vr_source' => array( 'label' => 'Plateforme', 'type' => 'text', 'aide' => 'Airbnb, Google, Booking…' ),
				'vr_date'   => array( 'label' => 'Date du séjour', 'type' => 'text', 'aide' => 'Par exemple : Mai 2026' ),
			),
		),
		'vr_atout'   => array(
			'titre'  => 'Icône',
			'champs' => array(
				'vr_icone' => array(
					'label'   => 'Icône affichée',
					'type'    => 'select',
					'options' => array(
						'check'    => 'Coche',
						'film'     => 'Cinéma / écran',
						'chef'     => 'Cuisine',
						'wifi'     => 'WiFi',
						'sun'      => 'Soleil / terrasse',
						'paw'      => 'Animaux',
						'shield'   => 'Sécurité / terrain clos',
						'waves'    => 'Piscine',
						'spa'      => 'Jacuzzi / spa',
						'tree'     => 'Jardin',
						'bed'      => 'Literie',
						'users'    => 'Voyageurs',
						'ruler'    => 'Surface',
						'calendar' => 'Calendrier',
						'dumbbell' => 'Salle de sport',
						'beach'    => 'Plage',
					),
				),
			),
		),
	);
}

/**
 * Ajoute les encadrés d'édition.
 */
function vr_ajouter_metaboxes() {
	foreach ( vr_champs_par_type() as $type => $config ) {
		add_meta_box(
			'vr_meta_' . $type,
			$config['titre'],
			'vr_afficher_metabox',
			$type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'vr_ajouter_metaboxes' );

/**
 * La médiathèque et le petit script des galeries, uniquement sur les fiches concernées.
 */
function vr_metabox_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'vr_chambre', 'vr_espace' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'vr-galerie-admin', get_template_directory_uri() . '/assets/js/galerie-admin.js', array( 'jquery' ), VR_VERSION, true );
	wp_localize_script( 'vr-galerie-admin', 'vrGalerie', array( 'max' => VR_GALERIE_MAX ) );
}
add_action( 'admin_enqueue_scripts', 'vr_metabox_assets' );

/**
 * Affiche les champs.
 */
function vr_afficher_metabox( $post ) {
	$config = vr_champs_par_type();
	if ( empty( $config[ $post->post_type ] ) ) {
		return;
	}

	wp_nonce_field( 'vr_enregistrer_meta', 'vr_meta_nonce' );

	echo '<div class="vr-meta">';

	foreach ( $config[ $post->post_type ]['champs'] as $cle => $champ ) {
		$valeur = get_post_meta( $post->ID, $cle, true );

		printf( '<p class="vr-meta__row"><label for="%s"><strong>%s</strong></label>', esc_attr( $cle ), esc_html( $champ['label'] ) );

		if ( 'textarea' === $champ['type'] ) {
			printf(
				'<textarea id="%s" name="%s" rows="3" class="widefat">%s</textarea>',
				esc_attr( $cle ),
				esc_attr( $cle ),
				esc_textarea( $valeur )
			);
		} elseif ( 'select' === $champ['type'] ) {
			printf( '<select id="%s" name="%s" class="widefat">', esc_attr( $cle ), esc_attr( $cle ) );
			foreach ( $champ['options'] as $option => $libelle ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $option ),
					selected( $valeur, $option, false ),
					esc_html( $libelle )
				);
			}
			echo '</select>';
		} elseif ( 'galerie' === $champ['type'] ) {
			$ids = array_filter( array_map( 'intval', (array) $valeur ) );
			echo '<span class="vr-galerie-admin" data-galerie>';
			echo '<span class="vr-galerie-admin__liste">';
			foreach ( $ids as $id ) {
				$vignette = wp_get_attachment_image_url( $id, 'thumbnail' );
				if ( ! $vignette ) {
					continue;
				}
				printf(
					'<span class="vr-galerie-admin__item" data-id="%1$d"><img src="%2$s" alt="" /><button type="button" class="vr-galerie-admin__retirer" aria-label="Retirer cette photo">×</button></span>',
					$id,
					esc_url( $vignette )
				);
			}
			echo '</span>';
			printf(
				'<input type="hidden" id="%1$s" name="%1$s" value="%2$s" class="vr-galerie-admin__valeur" />',
				esc_attr( $cle ),
				esc_attr( implode( ',', $ids ) )
			);
			echo '<button type="button" class="button vr-galerie-admin__ajouter">Ajouter des photos</button>';
			echo '</span>';
		} else {
			printf(
				'<input type="text" id="%s" name="%s" value="%s" class="widefat" />',
				esc_attr( $cle ),
				esc_attr( $cle ),
				esc_attr( $valeur )
			);
		}

		if ( ! empty( $champ['aide'] ) ) {
			printf( '<span class="description">%s</span>', esc_html( $champ['aide'] ) );
		}

		echo '</p>';
	}

	echo '</div>';
}

/**
 * Enregistre les champs.
 */
function vr_enregistrer_metabox( $post_id ) {
	if ( ! isset( $_POST['vr_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['vr_meta_nonce'] ), 'vr_enregistrer_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$config = vr_champs_par_type();
	$type   = get_post_type( $post_id );

	if ( empty( $config[ $type ] ) ) {
		return;
	}

	foreach ( $config[ $type ]['champs'] as $cle => $champ ) {
		if ( ! isset( $_POST[ $cle ] ) ) {
			continue;
		}
		$brut = wp_unslash( $_POST[ $cle ] );

		if ( 'galerie' === $champ['type'] ) {
			$ids = array_values( array_unique( array_filter( array_map( 'intval', explode( ',', (string) $brut ) ) ) ) );
			$ids = array_slice( $ids, 0, VR_GALERIE_MAX );
			update_post_meta( $post_id, $cle, $ids );
			continue;
		}

		$net = ( 'textarea' === $champ['type'] ) ? sanitize_textarea_field( $brut ) : sanitize_text_field( $brut );
		update_post_meta( $post_id, $cle, $net );
	}
}
add_action( 'save_post', 'vr_enregistrer_metabox' );

/**
 * Raccourci de lecture d'un champ.
 */
function vr_meta( $post_id, $cle, $defaut = '' ) {
	$valeur = get_post_meta( $post_id, $cle, true );
	return ( '' !== $valeur && null !== $valeur ) ? $valeur : $defaut;
}
