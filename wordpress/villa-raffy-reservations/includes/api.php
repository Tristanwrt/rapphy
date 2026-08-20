<?php
/**
 * Point d'accès consulté par le calendrier du site public.
 *
 * @package VillaRaffyReservations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vrr_enregistrer_route() {
	register_rest_route( 'villa-raffy/v1', '/indisponibilites', array(
		'methods'             => WP_REST_Server::READABLE,
		'callback'            => 'vrr_reponse_indisponibilites',
		'permission_callback' => '__return_true',
	) );
}
add_action( 'rest_api_init', 'vrr_enregistrer_route' );

/**
 * Renvoie les périodes indisponibles, regroupées.
 * Aucune information personnelle n'est exposée : uniquement des dates.
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
 * Vide le cache dès qu'une réservation ou un blocage change.
 */
function vrr_vider_cache() {
	delete_transient( 'vrr_indisponibilites' );
}
add_action( 'save_post_vr_reservation', 'vrr_vider_cache' );
add_action( 'deleted_post', 'vrr_vider_cache' );
add_action( 'update_option_vrr_dates_bloquees', 'vrr_vider_cache' );
add_action( 'add_option_vrr_dates_bloquees', 'vrr_vider_cache' );
