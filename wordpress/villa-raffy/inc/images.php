<?php
/**
 * Grandes tailles d'images à la demande.
 *
 * Quand le thème change la taille de ses images (par exemple 2000 → 2400 px),
 * WordPress ne recalcule pas les photos déjà déposées. Ici, la première fois
 * qu'une taille manque, elle est créée à partir de l'original puis mémorisée :
 * aucune photo ancienne ne reste floue faute de grand format.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vr_taille_a_la_demande( $resultat, $id, $taille ) {
	static $en_cours = array();

	if ( false !== $resultat || ! is_string( $taille ) || ! in_array( $taille, array( 'vr-hero', 'vr-mosaique', 'vr-carte' ), true ) ) {
		return $resultat;
	}

	$cle = $id . '|' . $taille;
	if ( isset( $en_cours[ $cle ] ) ) {
		return $resultat;
	}
	$en_cours[ $cle ] = true;

	$meta = wp_get_attachment_metadata( $id );
	if ( empty( $meta['width'] ) || empty( $meta['height'] ) || ! empty( $meta['sizes'][ $taille ] ) ) {
		return $resultat;
	}

	$formats = wp_get_registered_image_subsizes();
	if ( empty( $formats[ $taille ] ) ) {
		return $resultat;
	}
	$format = $formats[ $taille ];

	// Trop petite pour ce format : WordPress affichera l'original, comme d'habitude.
	if ( $meta['width'] < $format['width'] && $meta['height'] < $format['height'] ) {
		return $resultat;
	}

	$fichier = get_attached_file( $id );
	if ( ! $fichier || ! file_exists( $fichier ) ) {
		return $resultat;
	}

	$editeur = wp_get_image_editor( $fichier );
	if ( is_wp_error( $editeur ) ) {
		return $resultat;
	}

	$nouvelle = $editeur->make_subsize( $format );
	if ( is_wp_error( $nouvelle ) ) {
		return $resultat;
	}

	$meta['sizes'][ $taille ] = $nouvelle;
	wp_update_attachment_metadata( $id, $meta );

	return $resultat; // WordPress relit les métadonnées et trouve la taille.
}
add_filter( 'image_downsize', 'vr_taille_a_la_demande', 5, 3 );
