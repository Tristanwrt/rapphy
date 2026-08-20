<?php
/**
 * Le carnet de réservations : une fiche par séjour.
 *
 * @package VillaRaffyReservations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Type de contenu « Réservation ».
 */
function vrr_declarer_reservation() {
	register_post_type( 'vr_reservation', array(
		'labels'        => array(
			'name'               => 'Réservations',
			'singular_name'      => 'Réservation',
			'add_new'            => 'Ajouter une réservation',
			'add_new_item'       => 'Nouvelle réservation',
			'edit_item'          => 'Modifier la réservation',
			'new_item'           => 'Nouvelle réservation',
			'view_item'          => 'Voir la réservation',
			'search_items'       => 'Rechercher une réservation',
			'not_found'          => 'Aucune réservation enregistrée.',
			'not_found_in_trash' => 'Aucune réservation dans la corbeille.',
			'all_items'          => 'Toutes les réservations',
			'menu_name'          => 'Réservations',
		),
		'public'        => false,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-calendar-alt',
		'menu_position' => 3,
		'supports'      => array( 'title' ),
		'has_archive'   => false,
		'rewrite'       => false,
	) );
}
add_action( 'init', 'vrr_declarer_reservation' );

/**
 * Les statuts possibles d'un séjour.
 */
function vrr_statuts() {
	return array(
		'option'    => 'Option (pas encore confirmée)',
		'confirmee' => 'Confirmée',
		'soldee'    => 'Soldée (intégralement payée)',
		'annulee'   => 'Annulée',
	);
}

/**
 * Les champs de la fiche.
 */
function vrr_champs() {
	return array(
		'vrr_arrivee'   => array( 'label' => 'Date d\'arrivée', 'type' => 'date' ),
		'vrr_depart'    => array( 'label' => 'Date de départ', 'type' => 'date', 'aide' => 'La villa se libère ce jour-là : cette date reste réservable pour un autre séjour.' ),
		'vrr_statut'    => array( 'label' => 'Statut', 'type' => 'select', 'options' => 'statuts' ),
		'vrr_personnes' => array( 'label' => 'Nombre de voyageurs', 'type' => 'number' ),
		'vrr_telephone' => array( 'label' => 'Téléphone', 'type' => 'tel' ),
		'vrr_email'     => array( 'label' => 'Email', 'type' => 'email' ),
		'vrr_tarif'     => array( 'label' => 'Tarif total (€)', 'type' => 'number' ),
		'vrr_acompte'   => array( 'label' => 'Acompte reçu (€)', 'type' => 'number' ),
		'vrr_origine'   => array( 'label' => 'Provenance', 'type' => 'select', 'options' => 'origines' ),
		'vrr_notes'     => array( 'label' => 'Notes', 'type' => 'textarea', 'pleine' => true, 'aide' => 'Tout ce qu\'il faut retenir : heure d\'arrivée, animal, allergies, demandes particulières…' ),
	);
}

function vrr_origines() {
	return array(
		'direct'  => 'Réservation en direct (site, téléphone, email)',
		'airbnb'  => 'Airbnb',
		'booking' => 'Booking',
		'autre'   => 'Autre plateforme',
	);
}

/**
 * Encadré d'édition de la fiche.
 */
function vrr_metabox() {
	add_meta_box( 'vrr_details', 'Détails du séjour', 'vrr_afficher_metabox', 'vr_reservation', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'vrr_metabox' );

function vrr_afficher_metabox( $post ) {
	wp_nonce_field( 'vrr_enregistrer', 'vrr_nonce' );

	echo '<div class="vr-resa-champs">';

	foreach ( vrr_champs() as $cle => $champ ) {
		$valeur = get_post_meta( $post->ID, $cle, true );
		$classe = ! empty( $champ['pleine'] ) ? ' class="vr-resa-pleine"' : '';

		printf( '<div%s><label for="%s">%s</label>', $classe, esc_attr( $cle ), esc_html( $champ['label'] ) );

		if ( 'textarea' === $champ['type'] ) {
			printf(
				'<textarea id="%s" name="%s" rows="4" class="widefat">%s</textarea>',
				esc_attr( $cle ),
				esc_attr( $cle ),
				esc_textarea( $valeur )
			);
		} elseif ( 'select' === $champ['type'] ) {
			$options = ( 'statuts' === $champ['options'] ) ? vrr_statuts() : vrr_origines();
			printf( '<select id="%s" name="%s" class="widefat">', esc_attr( $cle ), esc_attr( $cle ) );
			foreach ( $options as $valeur_option => $libelle ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $valeur_option ),
					selected( $valeur, $valeur_option, false ),
					esc_html( $libelle )
				);
			}
			echo '</select>';
		} else {
			printf(
				'<input type="%s" id="%s" name="%s" value="%s" class="widefat"%s />',
				esc_attr( $champ['type'] ),
				esc_attr( $cle ),
				esc_attr( $cle ),
				esc_attr( $valeur ),
				( 'number' === $champ['type'] ) ? ' min="0" step="1"' : ''
			);
		}

		if ( ! empty( $champ['aide'] ) ) {
			printf( '<p class="description">%s</p>', esc_html( $champ['aide'] ) );
		}

		echo '</div>';
	}

	echo '</div>';

	$arrivee = get_post_meta( $post->ID, 'vrr_arrivee', true );
	$depart  = get_post_meta( $post->ID, 'vrr_depart', true );

	if ( vrr_date_valide( $arrivee ) && vrr_date_valide( $depart ) ) {
		$nuits = ( new DateTimeImmutable( $arrivee ) )->diff( new DateTimeImmutable( $depart ) )->days;
		printf(
			'<p style="margin-top:18px;padding-top:14px;border-top:1px solid #e5e0d8"><strong>Durée du séjour :</strong> %d nuit%s</p>',
			(int) $nuits,
			$nuits > 1 ? 's' : ''
		);
	}
}

/**
 * Enregistrement de la fiche.
 */
function vrr_enregistrer( $post_id ) {
	if ( ! isset( $_POST['vrr_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['vrr_nonce'] ), 'vrr_enregistrer' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( vrr_champs() as $cle => $champ ) {
		if ( ! isset( $_POST[ $cle ] ) ) {
			continue;
		}

		$brut = wp_unslash( $_POST[ $cle ] );

		switch ( $champ['type'] ) {
			case 'email':
				$net = sanitize_email( $brut );
				break;
			case 'number':
				$net = ( '' === $brut ) ? '' : (string) abs( (float) $brut );
				break;
			case 'textarea':
				$net = sanitize_textarea_field( $brut );
				break;
			case 'date':
				$net = vrr_date_valide( $brut ) ? $brut : '';
				break;
			default:
				$net = sanitize_text_field( $brut );
		}

		update_post_meta( $post_id, $cle, $net );
	}
}
add_action( 'save_post_vr_reservation', 'vrr_enregistrer' );

/**
 * Colonnes du carnet de réservations.
 */
function vrr_colonnes( $colonnes ) {
	return array(
		'cb'             => isset( $colonnes['cb'] ) ? $colonnes['cb'] : '',
		'title'          => 'Voyageur',
		'vrr_dates'      => 'Dates',
		'vrr_nuits'      => 'Nuits',
		'vrr_personnes'  => 'Pers.',
		'vrr_statut'     => 'Statut',
		'vrr_tarif'      => 'Tarif',
		'vrr_origine'    => 'Provenance',
	);
}
add_filter( 'manage_vr_reservation_posts_columns', 'vrr_colonnes' );

function vrr_afficher_colonne( $colonne, $post_id ) {
	switch ( $colonne ) {
		case 'vrr_dates':
			$arrivee = get_post_meta( $post_id, 'vrr_arrivee', true );
			$depart  = get_post_meta( $post_id, 'vrr_depart', true );
			if ( vrr_date_valide( $arrivee ) && vrr_date_valide( $depart ) ) {
				printf(
					'%s → %s',
					esc_html( wp_date( 'j M Y', strtotime( $arrivee ) ) ),
					esc_html( wp_date( 'j M Y', strtotime( $depart ) ) )
				);
			} else {
				echo '—';
			}
			break;

		case 'vrr_nuits':
			$arrivee = get_post_meta( $post_id, 'vrr_arrivee', true );
			$depart  = get_post_meta( $post_id, 'vrr_depart', true );
			if ( vrr_date_valide( $arrivee ) && vrr_date_valide( $depart ) ) {
				echo (int) ( new DateTimeImmutable( $arrivee ) )->diff( new DateTimeImmutable( $depart ) )->days;
			} else {
				echo '—';
			}
			break;

		case 'vrr_personnes':
			echo esc_html( get_post_meta( $post_id, 'vrr_personnes', true ) ?: '—' );
			break;

		case 'vrr_statut':
			$statut  = get_post_meta( $post_id, 'vrr_statut', true );
			$statuts = vrr_statuts();
			if ( isset( $statuts[ $statut ] ) ) {
				$court = array( 'option' => 'Option', 'confirmee' => 'Confirmée', 'soldee' => 'Soldée', 'annulee' => 'Annulée' );
				printf(
					'<span class="vr-statut vr-statut--%s">%s</span>',
					esc_attr( $statut ),
					esc_html( $court[ $statut ] )
				);
			} else {
				echo '—';
			}
			break;

		case 'vrr_tarif':
			$tarif = get_post_meta( $post_id, 'vrr_tarif', true );
			if ( '' !== $tarif ) {
				$acompte = get_post_meta( $post_id, 'vrr_acompte', true );
				printf(
					'%s €%s',
					esc_html( number_format_i18n( (float) $tarif ) ),
					( '' !== $acompte && (float) $acompte > 0 )
						? '<br /><small style="color:#6b6459">acompte ' . esc_html( number_format_i18n( (float) $acompte ) ) . ' €</small>'
						: ''
				);
			} else {
				echo '—';
			}
			break;

		case 'vrr_origine':
			$origines = vrr_origines();
			$origine  = get_post_meta( $post_id, 'vrr_origine', true );
			echo esc_html( isset( $origines[ $origine ] ) ? $origines[ $origine ] : '—' );
			break;
	}
}
add_action( 'manage_vr_reservation_posts_custom_column', 'vrr_afficher_colonne', 10, 2 );

/**
 * Trie le carnet par date d'arrivée, la plus proche en premier.
 */
function vrr_tri( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'vr_reservation' !== $query->get( 'post_type' ) ) {
		return;
	}
	if ( ! $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', 'vrr_arrivee' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'DESC' );
	}
}
add_action( 'pre_get_posts', 'vrr_tri' );

/**
 * Renomme le champ titre pour qu'il soit évident.
 */
function vrr_placeholder_titre( $texte, $post ) {
	return ( 'vr_reservation' === $post->post_type ) ? 'Nom du voyageur' : $texte;
}
add_filter( 'enter_title_here', 'vrr_placeholder_titre', 10, 2 );
