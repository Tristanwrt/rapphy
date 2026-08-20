<?php
/**
 * Plugin Name: Villa Raffy — Réservations
 * Description: Calendrier de blocage des dates et carnet de réservations, pour gérer les locations en direct sans passer par une plateforme.
 * Version: 1.0.0
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

define( 'VRR_VERSION', '1.0.0' );
define( 'VRR_CHEMIN', plugin_dir_path( __FILE__ ) );
define( 'VRR_URL', plugin_dir_url( __FILE__ ) );

require_once VRR_CHEMIN . 'includes/reservations.php';
require_once VRR_CHEMIN . 'includes/calendrier.php';
require_once VRR_CHEMIN . 'includes/api.php';

/**
 * Feuille de style de l'administration : on réutilise celle du thème si elle existe,
 * sinon celle fournie avec l'extension.
 */
function vrr_styles_admin( $hook ) {
	$ecrans = array( 'toplevel_page_vr-calendrier', 'ma-villa_page_vr-calendrier', 'vr_reservation' );
	$ecran  = get_current_screen();

	$concerne = in_array( $hook, $ecrans, true )
		|| ( $ecran && 'vr_reservation' === $ecran->post_type )
		|| false !== strpos( (string) $hook, 'vr-calendrier' );

	if ( ! $concerne ) {
		return;
	}

	$css_theme = get_template_directory() . '/assets/css/admin.css';

	if ( file_exists( $css_theme ) ) {
		wp_enqueue_style( 'vr-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), VRR_VERSION );
	} else {
		wp_enqueue_style( 'vrr-admin', VRR_URL . 'assets/admin.css', array(), VRR_VERSION );
	}
}
add_action( 'admin_enqueue_scripts', 'vrr_styles_admin' );

/**
 * À l'activation : on prépare les types de contenu et on rafraîchit les permaliens.
 */
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
   OUTILS DE DATES
   ═══════════════════════════════════════════════════════════ */

/**
 * Liste des dates bloquées manuellement, au format « AAAA-MM-JJ ».
 */
function vrr_dates_bloquees() {
	$dates = get_option( 'vrr_dates_bloquees', array() );
	return is_array( $dates ) ? $dates : array();
}

function vrr_enregistrer_dates_bloquees( $dates ) {
	$dates = array_values( array_unique( array_filter( (array) $dates ) ) );
	sort( $dates );
	update_option( 'vrr_dates_bloquees', $dates );
}

/**
 * Vérifie qu'une chaîne est bien une date au format attendu.
 */
function vrr_date_valide( $texte ) {
	if ( ! is_string( $texte ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $texte ) ) {
		return false;
	}
	$parts = explode( '-', $texte );
	return checkdate( (int) $parts[1], (int) $parts[2], (int) $parts[0] );
}

/**
 * Toutes les dates occupées : blocages manuels + nuits des réservations retenues.
 * La date de départ n'est pas incluse (la villa se libère ce jour-là).
 */
function vrr_dates_occupees() {
	$occupees = vrr_dates_bloquees();

	$reservations = get_posts( array(
		'post_type'      => 'vr_reservation',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key'     => 'vrr_statut',
				'value'   => array( 'option', 'confirmee', 'soldee' ),
				'compare' => 'IN',
			),
		),
	) );

	foreach ( $reservations as $reservation ) {
		$arrivee = get_post_meta( $reservation->ID, 'vrr_arrivee', true );
		$depart  = get_post_meta( $reservation->ID, 'vrr_depart', true );

		if ( ! vrr_date_valide( $arrivee ) || ! vrr_date_valide( $depart ) ) {
			continue;
		}

		$curseur = new DateTimeImmutable( $arrivee );
		$fin     = new DateTimeImmutable( $depart );

		// Garde-fou : on ne dépasse jamais 400 nuits.
		$compteur = 0;
		while ( $curseur < $fin && $compteur < 400 ) {
			$occupees[] = $curseur->format( 'Y-m-d' );
			$curseur    = $curseur->modify( '+1 day' );
			$compteur++;
		}
	}

	return array_values( array_unique( $occupees ) );
}

/**
 * Regroupe une liste de dates en périodes continues,
 * pour alléger la réponse envoyée au calendrier du site.
 * La date de fin renvoyée est le lendemain de la dernière nuit occupée.
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
 * Retrouve la réservation qui occupe une date donnée, s'il y en a une.
 */
function vrr_reservation_du_jour( $date ) {
	static $index = null;

	if ( null === $index ) {
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

			while ( $curseur < $fin && $compteur < 400 ) {
				$index[ $curseur->format( 'Y-m-d' ) ] = $reservation;
				$curseur = $curseur->modify( '+1 day' );
				$compteur++;
			}
		}
	}

	return isset( $index[ $date ] ) ? $index[ $date ] : null;
}
