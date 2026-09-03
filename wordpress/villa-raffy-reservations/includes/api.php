<?php
/**
 * Points d'accès consultés par le calendrier du site public.
 * Aucune information personnelle n'est exposée : uniquement des dates, des prix et des règles.
 *
 * @package VillaRaffyReservations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vrr_enregistrer_routes() {
	register_rest_route( 'villa-raffy/v1', '/indisponibilites', array(
		'methods'             => WP_REST_Server::READABLE,
		'callback'            => 'vrr_reponse_indisponibilites',
		'permission_callback' => '__return_true',
	) );

	register_rest_route( 'villa-raffy/v1', '/calendrier', array(
		'methods'             => WP_REST_Server::READABLE,
		'callback'            => 'vrr_reponse_calendrier',
		'permission_callback' => '__return_true',
	) );
}
add_action( 'rest_api_init', 'vrr_enregistrer_routes' );

/**
 * Périodes indisponibles regroupées (compatibilité avec les anciens thèmes).
 */
function vrr_reponse_indisponibilites() {
	$cache = get_transient( 'vrr_indisponibilites' );
	if ( false !== $cache ) {
		return rest_ensure_response( $cache );
	}

	$periodes = vrr_periodes( vrr_dates_occupees() );
	set_transient( 'vrr_indisponibilites', $periodes, HOUR_IN_SECONDS );

	return rest_ensure_response( $periodes );
}

/**
 * Le calendrier complet : 14 mois à partir d'aujourd'hui, jour par jour,
 * avec disponibilité, prix par formule et règles de séjour.
 */
function vrr_reponse_calendrier() {
	$cache = get_transient( 'vrr_calendrier_public' );
	if ( false !== $cache ) {
		return rest_ensure_response( $cache );
	}

	$aujourdhui = wp_date( 'Y-m-d' );
	$debut      = substr( $aujourdhui, 0, 8 ) . '01';
	$fin        = ( new DateTimeImmutable( $debut ) )->modify( '+14 months' )->modify( '-1 day' )->format( 'Y-m-d' );

	$jours = array();
	foreach ( vrr_calendrier( $debut, $fin ) as $jour ) {
		$jours[] = array(
			'd'  => $jour['date'],
			't'  => $jour['type'],
			'ok' => (bool) $jour['dispo'],
			'p'  => $jour['prix'],
			'a'  => (bool) $jour['arrivee'],
			'r'  => (bool) $jour['depart'],
			'm'  => (int) $jour['min'],
		);
	}

	$reponse = array(
		'formules' => vrr_formules(),
		'jours'    => $jours,
	);

	set_transient( 'vrr_calendrier_public', $reponse, HOUR_IN_SECONDS );

	return rest_ensure_response( $reponse );
}
