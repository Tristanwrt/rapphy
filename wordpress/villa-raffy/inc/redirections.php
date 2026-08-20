<?php
/**
 * Redirections des anciennes adresses Wix vers le nouveau site,
 * pour ne perdre aucun référencement lors de la bascule.
 *
 * Pour en ajouter une, complétez simplement le tableau ci-dessous.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vr_table_redirections() {
	return array(
		// Ancienne adresse Wix  =>  Nouvelle destination
		'/about-1'          => '/#exterieurs',
		'/about-3-1'        => '/#exterieurs',
		'/about'            => '/#villa',
		'/about-2'          => '/#chambres',
		'/contact'          => '/#contact',
		'/contact-1'        => '/#contact',
		'/reservation'      => '/#reserver',
		'/reservations'     => '/#reserver',
		'/tarifs'           => '/#reserver',
		'/galerie'          => '/#exterieurs',
		'/photos'           => '/#exterieurs',
		'/la-villa'         => '/#villa',
		'/avis'             => '/#avis',
		'/blank'            => '/',
		'/blank-1'          => '/',
		'/copie-de-accueil' => '/',
	);
}

function vr_appliquer_redirections() {
	if ( is_admin() ) {
		return;
	}

	$demande = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( ! $demande ) {
		return;
	}

	// On ne compare que le chemin, sans les paramètres, sans le slash final.
	$chemin = wp_parse_url( $demande, PHP_URL_PATH );
	$chemin = untrailingslashit( strtolower( (string) $chemin ) );

	if ( '' === $chemin || '/' === $chemin ) {
		return;
	}

	$table = vr_table_redirections();

	if ( isset( $table[ $chemin ] ) ) {
		wp_safe_redirect( home_url( $table[ $chemin ] ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'vr_appliquer_redirections', 1 );
