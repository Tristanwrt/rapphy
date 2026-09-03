<?php
/**
 * Page d'accueil.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/chiffres' );
get_template_part( 'template-parts/direct' );
get_template_part( 'template-parts/visite' );
get_template_part( 'template-parts/villa' );
get_template_part( 'template-parts/chambres' );
get_template_part( 'template-parts/sejours' );
get_template_part( 'template-parts/exterieurs' );
get_template_part( 'template-parts/film' );
get_template_part( 'template-parts/avis' );
get_template_part( 'template-parts/reservation' );
get_template_part( 'template-parts/region' );
get_template_part( 'template-parts/faq' );

get_footer();
