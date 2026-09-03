<?php
/**
 * Plugin Name: Villa Raffy — Réservations
 * Description: Calendrier tarifaire par saison, blocage des dates, carnet de réservations. Tout se gère à la main, sans dépendre d'Airbnb ou de Booking.
 * Version: 2.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Tristan Wiart
 * Text Domain: villa-raffy-reservations
 * License: GPL-2.0-or-later
 *
 * @package VillaRaffyReservations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VRR_VERSION', '2.0.0' );
define( 'VRR_CHEMIN', plugin_dir_path( __FILE__ ) );
define( 'VRR_URL', plugin_dir_url( __FILE__ ) );

require_once VRR_CHEMIN . 'includes/reservations.php';
require_once VRR_CHEMIN . 'includes/reglages.php';
require_once VRR_CHEMIN . 'includes/calendrier.php';
require_once VRR_CHEMIN . 'includes/api.php';

/**
 * Feuille de style de l'administration.
 */
function vrr_styles_admin( $hook ) {
	$ecran    = get_current_screen();
	$concerne = ( $ecran && 'vr_reservation' === $ecran->post_type )
		|| false !== strpos( (string) $hook, 'vr-calendrier' )
		|| false !== strpos( (string) $hook, 'vr-tarifs' );

	if ( ! $concerne ) {
		return;
	}

	wp_enqueue_style( 'vrr-admin', VRR_URL . 'assets/admin.css', array(), VRR_VERSION );
}
add_action( 'admin_enqueue_scripts', 'vrr_styles_admin' );

function vrr_activation() {
	vrr_declarer_reservation();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'vrr_activation' );

function vrr_desactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'vrr_desactivation' );

/* ═══════════════════════════════════════════════════════════
   FORMULES ET SAISONS
   ═══════════════════════════════════════════════════════════ */

/**
 * Les deux formules proposées.
 */
function vrr_formules() {
	$capacites = get_option( 'vrr_capacites', array() );

	return array(
		'complete'  => array(
			'nom'      => 'Villa complète',
			'capacite' => isset( $capacites['complete'] ) ? (int) $capacites['complete'] : 8,
		),
		'cocooning' => array(
			'nom'      => 'Formule Cocooning',
			'capacite' => isset( $capacites['cocooning'] ) ? (int) $capacites['cocooning'] : 4,
		),
	);
}

/**
 * Saisons par défaut, telles que définies par les propriétaires.
 * Tout ce qui n'est couvert par aucune saison est fermé.
 */
function vrr_saisons_defaut() {
	$tous = array( 1, 2, 3, 4, 5, 6, 7 );
	return array(
		array(
			'nom'            => 'Basse saison — printemps',
			'debut'          => '05-01',
			'fin'            => '06-30',
			'type'           => 'basse',
			'min_nuits'      => 2,
			'arrivee'        => $tous,
			'depart'         => $tous,
			'prix_complete'  => 290,
			'prix_cocooning' => 190,
		),
		array(
			'nom'            => 'Haute saison',
			'debut'          => '07-01',
			'fin'            => '08-31',
			'type'           => 'haute',
			'min_nuits'      => 7,
			'arrivee'        => array( 6 ),
			'depart'         => array( 6 ),
			'prix_complete'  => 350,
			'prix_cocooning' => '',
		),
		array(
			'nom'            => 'Basse saison — septembre',
			'debut'          => '09-01',
			'fin'            => '09-30',
			'type'           => 'basse',
			'min_nuits'      => 2,
			'arrivee'        => $tous,
			'depart'         => $tous,
			'prix_complete'  => 290,
			'prix_cocooning' => 190,
		),
	);
}

function vrr_saisons() {
	$saisons = get_option( 'vrr_saisons', null );
	return ( is_array( $saisons ) && $saisons ) ? $saisons : vrr_saisons_defaut();
}

/**
 * Trouve la saison qui couvre une date (« MM-JJ » comparé chaque année).
 * Gère les saisons à cheval sur le Nouvel An (ex. 12-15 → 01-10).
 */
function vrr_saison_du_jour( $date ) {
	$mmjj = substr( $date, 5, 5 );

	foreach ( vrr_saisons() as $saison ) {
		$debut = isset( $saison['debut'] ) ? $saison['debut'] : '';
		$fin   = isset( $saison['fin'] ) ? $saison['fin'] : '';

		if ( ! $debut || ! $fin ) {
			continue;
		}

		$dans = ( $debut <= $fin )
			? ( $mmjj >= $debut && $mmjj <= $fin )
			: ( $mmjj >= $debut || $mmjj <= $fin );

		if ( $dans ) {
			return $saison;
		}
	}

	return null;
}

/* ═══════════════════════════════════════════════════════════
   DATES BLOQUÉES ET TARIFS SPÉCIAUX
   ═══════════════════════════════════════════════════════════ */

function vrr_dates_bloquees() {
	$dates = get_option( 'vrr_dates_bloquees', array() );
	return is_array( $dates ) ? $dates : array();
}

function vrr_enregistrer_dates_bloquees( $dates ) {
	$dates = array_values( array_unique( array_filter( (array) $dates, 'vrr_date_valide' ) ) );
	sort( $dates );
	update_option( 'vrr_dates_bloquees', $dates );
	vrr_vider_cache();
}

/**
 * Tarifs fixés à la main pour une date précise : [ 'AAAA-MM-JJ' => [ 'complete' => 400, 'cocooning' => '' ] ]
 */
function vrr_tarifs_dates() {
	$tarifs = get_option( 'vrr_tarifs_dates', array() );
	return is_array( $tarifs ) ? $tarifs : array();
}

function vrr_enregistrer_tarifs_dates( $tarifs ) {
	update_option( 'vrr_tarifs_dates', $tarifs );
	vrr_vider_cache();
}

function vrr_date_valide( $texte ) {
	if ( ! is_string( $texte ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $texte ) ) {
		return false;
	}
	$parts = explode( '-', $texte );
	return checkdate( (int) $parts[1], (int) $parts[2], (int) $parts[0] );
}

/**
 * Toutes les dates entre deux bornes, bornes incluses (500 jours maximum,
 * de quoi couvrir les 14 mois du calendrier public).
 */
function vrr_plage( $debut, $fin ) {
	if ( ! vrr_date_valide( $debut ) || ! vrr_date_valide( $fin ) ) {
		return array();
	}
	if ( $fin < $debut ) {
		list( $debut, $fin ) = array( $fin, $debut );
	}

	$dates    = array();
	$curseur  = new DateTimeImmutable( $debut );
	$limite   = new DateTimeImmutable( $fin );
	$compteur = 0;

	while ( $curseur <= $limite && $compteur < 500 ) {
		$dates[] = $curseur->format( 'Y-m-d' );
		$curseur = $curseur->modify( '+1 day' );
		$compteur++;
	}

	return $dates;
}

function vrr_bloquer_plage( $debut, $fin ) {
	vrr_enregistrer_dates_bloquees( array_merge( vrr_dates_bloquees(), vrr_plage( $debut, $fin ) ) );
}

function vrr_debloquer_plage( $debut, $fin ) {
	$a_retirer = vrr_plage( $debut, $fin );
	vrr_enregistrer_dates_bloquees( array_diff( vrr_dates_bloquees(), $a_retirer ) );
}

function vrr_fixer_tarif_plage( $debut, $fin, $complete, $cocooning ) {
	$tarifs = vrr_tarifs_dates();
	foreach ( vrr_plage( $debut, $fin ) as $date ) {
		$tarifs[ $date ] = array(
			'complete'  => ( '' === $complete ) ? '' : (int) $complete,
			'cocooning' => ( '' === $cocooning ) ? '' : (int) $cocooning,
		);
	}
	vrr_enregistrer_tarifs_dates( $tarifs );
}

function vrr_retirer_tarif_plage( $debut, $fin ) {
	$tarifs = vrr_tarifs_dates();
	foreach ( vrr_plage( $debut, $fin ) as $date ) {
		unset( $tarifs[ $date ] );
	}
	vrr_enregistrer_tarifs_dates( $tarifs );
}

/* ═══════════════════════════════════════════════════════════
   RÉSERVATIONS
   ═══════════════════════════════════════════════════════════ */

/**
 * Index date → réservation, pour toutes les réservations non annulées.
 */
function vrr_index_reservations() {
	static $index = null;

	if ( null !== $index ) {
		return $index;
	}

	$index        = array();
	$reservations = get_posts( array(
		'post_type'      => 'vr_reservation',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	foreach ( $reservations as $reservation ) {
		$arrivee = get_post_meta( $reservation->ID, 'vrr_arrivee', true );
		$depart  = get_post_meta( $reservation->ID, 'vrr_depart', true );
		$statut  = get_post_meta( $reservation->ID, 'vrr_statut', true );

		if ( 'annulee' === $statut || ! vrr_date_valide( $arrivee ) || ! vrr_date_valide( $depart ) ) {
			continue;
		}

		$curseur  = new DateTimeImmutable( $arrivee );
		$fin      = new DateTimeImmutable( $depart );
		$compteur = 0;

		// La nuit du départ n'est pas occupée : la villa se libère ce jour-là.
		while ( $curseur < $fin && $compteur < 400 ) {
			$index[ $curseur->format( 'Y-m-d' ) ] = $reservation;
			$curseur = $curseur->modify( '+1 day' );
			$compteur++;
		}
	}

	return $index;
}

function vrr_reservation_du_jour( $date ) {
	$index = vrr_index_reservations();
	return isset( $index[ $date ] ) ? $index[ $date ] : null;
}

/* ═══════════════════════════════════════════════════════════
   LE CŒUR : QUE VAUT UNE JOURNÉE ?
   ═══════════════════════════════════════════════════════════ */

/**
 * Tout ce qu'il faut savoir sur une date : saison, disponibilité, prix, règles.
 */
function vrr_jour( $date ) {
	$saison   = vrr_saison_du_jour( $date );
	$bloquee  = in_array( $date, vrr_dates_bloquees(), true );
	$reservee = null !== vrr_reservation_du_jour( $date );
	$fermee   = null === $saison;
	$special  = vrr_tarifs_dates();
	$jour_sem = (int) ( new DateTimeImmutable( $date ) )->format( 'N' );

	$prix = array( 'complete' => null, 'cocooning' => null );

	if ( $saison ) {
		$prix['complete']  = ( '' !== $saison['prix_complete'] && null !== $saison['prix_complete'] ) ? (int) $saison['prix_complete'] : null;
		$prix['cocooning'] = ( '' !== $saison['prix_cocooning'] && null !== $saison['prix_cocooning'] ) ? (int) $saison['prix_cocooning'] : null;
	}

	if ( isset( $special[ $date ] ) ) {
		foreach ( array( 'complete', 'cocooning' ) as $formule ) {
			if ( isset( $special[ $date ][ $formule ] ) && '' !== $special[ $date ][ $formule ] ) {
				$prix[ $formule ] = (int) $special[ $date ][ $formule ];
			}
		}
	}

	return array(
		'date'     => $date,
		'type'     => $fermee ? 'fermee' : ( isset( $saison['type'] ) ? $saison['type'] : 'basse' ),
		'saison'   => $saison ? $saison['nom'] : 'Fermé',
		'dispo'    => ! $fermee && ! $bloquee && ! $reservee,
		'bloquee'  => $bloquee,
		'reservee' => $reservee,
		'special'  => isset( $special[ $date ] ),
		'prix'     => $prix,
		'arrivee'  => $saison ? in_array( $jour_sem, array_map( 'intval', (array) $saison['arrivee'] ), true ) : false,
		'depart'   => $saison ? in_array( $jour_sem, array_map( 'intval', (array) $saison['depart'] ), true ) : false,
		'min'      => $saison ? max( 1, (int) $saison['min_nuits'] ) : 1,
	);
}

/**
 * Le calendrier complet d'une plage, pour le site public et l'administration.
 */
function vrr_calendrier( $debut, $fin ) {
	$jours = array();
	foreach ( vrr_plage( $debut, $fin ) as $date ) {
		$jours[] = vrr_jour( $date );
	}
	return $jours;
}

/**
 * Toutes les dates indisponibles (fermées, bloquées, réservées), sur 400 jours.
 */
function vrr_dates_occupees() {
	$occupees = array();
	$debut    = wp_date( 'Y-m-d' );
	$fin      = ( new DateTimeImmutable( $debut ) )->modify( '+399 days' )->format( 'Y-m-d' );

	foreach ( vrr_calendrier( $debut, $fin ) as $jour ) {
		if ( ! $jour['dispo'] ) {
			$occupees[] = $jour['date'];
		}
	}

	return $occupees;
}

/**
 * Regroupe des dates en périodes continues ; la fin est le lendemain de la dernière nuit.
 */
function vrr_periodes( $dates ) {
	$dates = array_values( array_unique( array_filter( $dates, 'vrr_date_valide' ) ) );
	sort( $dates );

	$periodes = array();
	$debut    = null;
	$attendue = null;

	foreach ( $dates as $date ) {
		if ( null === $debut ) {
			$debut    = $date;
			$attendue = ( new DateTimeImmutable( $date ) )->modify( '+1 day' )->format( 'Y-m-d' );
			continue;
		}
		if ( $date === $attendue ) {
			$attendue = ( new DateTimeImmutable( $date ) )->modify( '+1 day' )->format( 'Y-m-d' );
			continue;
		}
		$periodes[] = array( 'debut' => $debut, 'fin' => $attendue );
		$debut      = $date;
		$attendue   = ( new DateTimeImmutable( $date ) )->modify( '+1 day' )->format( 'Y-m-d' );
	}

	if ( null !== $debut ) {
		$periodes[] = array( 'debut' => $debut, 'fin' => $attendue );
	}

	return $periodes;
}

/**
 * Vide les caches dès qu'un réglage, un blocage ou une réservation change.
 */
function vrr_vider_cache() {
	delete_transient( 'vrr_indisponibilites' );
	delete_transient( 'vrr_calendrier_public' );
}
add_action( 'save_post_vr_reservation', 'vrr_vider_cache' );
add_action( 'deleted_post', 'vrr_vider_cache' );
add_action( 'update_option_vrr_saisons', 'vrr_vider_cache' );
add_action( 'update_option_vrr_capacites', 'vrr_vider_cache' );
