<?php
/**
 * Couleurs du site : les cinq teintes de Personnaliser deviennent les variables
 * CSS du thème, sur le site comme dans l'éditeur. La palette proposée dans les
 * blocs (onglet Couleur) suit les mêmes valeurs.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les couleurs choisies, avec les valeurs d'origine en secours.
 */
function vr_couleurs() {
	$defauts = vr_mod_defauts();
	$noms    = array(
		'canvas'      => 'vr_couleur_fond',
		'canvas-deep' => 'vr_couleur_fond_2',
		'ink'         => 'vr_couleur_encre',
		'brass'       => 'vr_couleur_laiton',
		'night'       => 'vr_couleur_nuit',
	);
	$couleurs = array();
	foreach ( $noms as $variable => $mod ) {
		$valeur = sanitize_hex_color( (string) get_theme_mod( $mod, $defauts[ $mod ] ) );
		$couleurs[ $variable ] = $valeur ? $valeur : $defauts[ $mod ];
	}
	return $couleurs;
}

/**
 * Assombrit une couleur hexadécimale (pour le survol des boutons dorés).
 */
function vr_couleur_assombrir( $hex, $ratio = 0.12 ) {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	$sortie = '#';
	foreach ( str_split( $hex, 2 ) as $c ) {
		$sortie .= str_pad( dechex( (int) round( hexdec( $c ) * ( 1 - $ratio ) ) ), 2, '0', STR_PAD_LEFT );
	}
	return $sortie;
}

/**
 * Le bloc CSS des variables, seulement si une couleur a été changée.
 */
function vr_couleurs_css( $selecteur = ':root' ) {
	$couleurs = vr_couleurs();
	$defauts  = vr_mod_defauts();
	$origine  = array(
		'canvas'      => $defauts['vr_couleur_fond'],
		'canvas-deep' => $defauts['vr_couleur_fond_2'],
		'ink'         => $defauts['vr_couleur_encre'],
		'brass'       => $defauts['vr_couleur_laiton'],
		'night'       => $defauts['vr_couleur_nuit'],
	);
	if ( $couleurs === $origine ) {
		return '';
	}
	$lignes = array();
	foreach ( $couleurs as $variable => $valeur ) {
		$lignes[] = '--' . $variable . ':' . $valeur;
	}
	$lignes[] = '--brass-dark:' . vr_couleur_assombrir( $couleurs['brass'] );
	return $selecteur . '{' . implode( ';', $lignes ) . '}';
}

add_action( 'wp_enqueue_scripts', function () {
	$css = vr_couleurs_css( ':root' );
	if ( $css ) {
		wp_add_inline_style( 'vr-style', $css );
	}
}, 20 );

add_action( 'enqueue_block_editor_assets', function () {
	$css = vr_couleurs_css( '.editor-styles-wrapper' );
	if ( $css ) {
		wp_add_inline_style( 'wp-edit-blocks', $css );
	}
} );

/**
 * La palette de l'onglet Couleur des blocs reprend les couleurs choisies.
 */
add_filter( 'wp_theme_json_data_theme', function ( $theme_json ) {
	if ( ! function_exists( 'vr_mod_defauts' ) ) {
		return $theme_json;
	}
	$couleurs = vr_couleurs();
	$palette  = array(
		array( 'slug' => 'lin', 'color' => $couleurs['canvas'], 'name' => 'Lin ivoire (fond)' ),
		array( 'slug' => 'lin-fonce', 'color' => $couleurs['canvas-deep'], 'name' => 'Lin foncé (fond alterné)' ),
		array( 'slug' => 'creme', 'color' => '#fbf9f4', 'name' => 'Crème (cartes)' ),
		array( 'slug' => 'encre', 'color' => $couleurs['ink'], 'name' => 'Encre' ),
		array( 'slug' => 'taupe', 'color' => '#5d5344', 'name' => 'Taupe (textes secondaires)' ),
		array( 'slug' => 'laiton', 'color' => $couleurs['brass'], 'name' => 'Laiton (doré)' ),
		array( 'slug' => 'nuit', 'color' => $couleurs['night'], 'name' => 'Nuit (sections sombres)' ),
		array( 'slug' => 'blanc', 'color' => '#ffffff', 'name' => 'Blanc' ),
	);
	return $theme_json->update_with( array(
		'version'  => 3,
		'settings' => array( 'color' => array( 'palette' => $palette ) ),
	) );
} );
