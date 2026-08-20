<?php
/**
 * Champs personnalisés, écrits à la main pour ne dépendre d'aucune extension payante.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Description de chaque champ, par type de contenu.
 */
function vr_champs_par_type() {
	return array(
		'vr_chambre' => array(
			'titre'  => 'Détails de la chambre',
			'champs' => array(
				'vr_detail' => array( 'label' => 'Ligne de caractéristiques', 'type' => 'text', 'aide' => 'Par exemple : Lit 160 × 200 · salle de bain privée · vue jardin' ),
				'vr_texte'  => array( 'label' => 'Description', 'type' => 'textarea', 'aide' => 'Deux ou trois phrases qui donnent envie.' ),
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
		$net  = ( 'textarea' === $champ['type'] ) ? sanitize_textarea_field( $brut ) : sanitize_text_field( $brut );
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
