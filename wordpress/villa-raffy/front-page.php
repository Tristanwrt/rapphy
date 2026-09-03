<?php
/**
 * Page d'accueil.
 *
 * Si la page « Accueil » contient des blocs (c'est le cas dès l'installation),
 * ce sont eux qui s'affichent, dans l'ordre choisi par le propriétaire.
 * Sinon, on affiche les sections dans l'ordre d'origine.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$vr_accueil = ( 'page' === get_option( 'show_on_front' ) ) ? get_post( (int) get_option( 'page_on_front' ) ) : null;

if ( $vr_accueil && has_blocks( $vr_accueil->post_content ) ) {

	$GLOBALS['post'] = $vr_accueil; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	setup_postdata( $vr_accueil );

	foreach ( parse_blocks( $vr_accueil->post_content ) as $vr_bloc ) {
		$vr_est_section = ! empty( $vr_bloc['blockName'] ) && 0 === strpos( $vr_bloc['blockName'], 'villa-raffy/' );

		if ( empty( $vr_bloc['blockName'] ) && '' === trim( $vr_bloc['innerHTML'] ) ) {
			continue; // Simple retour à la ligne entre deux blocs.
		}

		$vr_html = render_block( $vr_bloc );

		if ( ! $vr_est_section ) {
			// Bloc classique (texte, image, galerie…) : centré dans la largeur du site.
			$vr_html = '<div class="vr-libre"><div class="vr-wrap">' . $vr_html . '</div></div>';
		}

		echo $vr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	wp_reset_postdata();

} else {

	foreach ( vr_blocs_ordre() as $vr_section ) {
		get_template_part( 'template-parts/' . $vr_section );
	}
}

get_footer();
