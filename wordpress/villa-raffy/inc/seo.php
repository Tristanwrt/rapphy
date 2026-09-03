<?php
/**
 * Référencement : description, partage sur les réseaux et données structurées.
 * Compatible avec SEOPress ou Yoast — si l'une de ces extensions est active,
 * le thème s'efface et lui laisse la main.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function vr_extension_seo_active() {
	return defined( 'SEOPRESS_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' );
}

/**
 * Description et balises de partage.
 */
function vr_balises_meta() {
	if ( vr_extension_seo_active() ) {
		return;
	}

	$titre       = wp_get_document_title();
	$description = '';
	$image       = '';

	if ( is_front_page() ) {
		$description = sprintf(
			'%s : villa de luxe avec piscine, jacuzzi, salle de sport et cinéma privé à %s. Réservez en direct au meilleur tarif, sans commission.',
			get_bloginfo( 'name' ),
			get_theme_mod( 'vr_adresse', 'Saint-Robert, Lot-et-Garonne' )
		);
		$image_id = (int) get_theme_mod( 'vr_hero_image', 0 );
		$image    = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';
	} elseif ( is_singular() ) {
		$description = get_the_excerpt();
		$image       = get_the_post_thumbnail_url( null, 'full' );
	}

	$description = wp_strip_all_tags( (string) $description );
	$description = trim( preg_replace( '/\s+/', ' ', $description ) );

	if ( $description ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( wp_html_excerpt( $description, 160, '…' ) ) );
	}

	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:locale" content="fr_FR" />' . "\n" );
	printf( '<meta property="og:type" content="%s" />' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $titre ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( home_url( add_query_arg( array() ) ) ) );

	if ( $description ) {
		printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( wp_html_excerpt( $description, 200, '…' ) ) );
	}
	if ( $image ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image ) );
		printf( '<meta name="twitter:card" content="summary_large_image" />' . "\n" );
	}
}
add_action( 'wp_head', 'vr_balises_meta', 2 );

/**
 * Données structurées : hébergement de vacances (schema.org).
 * C'est ce qui permet à Google d'afficher la villa comme un logement,
 * avec ses équipements et sa localisation.
 */
function vr_donnees_structurees() {
	if ( ! is_front_page() ) {
		return;
	}

	$adresse = get_theme_mod( 'vr_adresse', '3 rue Georges Bouyssou, 47340 Saint-Robert' );
	$parts   = array_map( 'trim', explode( ',', $adresse ) );
	$rue     = isset( $parts[0] ) ? $parts[0] : '';
	$ville   = '';
	$cp      = '';

	if ( isset( $parts[1] ) && preg_match( '/(\d{5})\s*(.*)/', $parts[1], $m ) ) {
		$cp    = $m[1];
		$ville = trim( $m[2] );
	}

	$equipements = array();
	foreach ( vr_contenus( 'vr_atout' ) as $atout ) {
		$equipements[] = array(
			'@type' => 'LocationFeatureSpecification',
			'name'  => wp_strip_all_tags( $atout->post_title ),
			'value' => true,
		);
	}

	$donnees = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'VacationRental',
		'name'          => get_bloginfo( 'name' ),
		'description'   => wp_strip_all_tags( get_theme_mod( 'vr_hero_sous_titre', '' ) ),
		'url'           => home_url( '/' ),
		'telephone'     => '+' . vr_tel_brut( get_theme_mod( 'vr_telephone', '' ) ),
		'address'       => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $rue,
			'addressLocality' => $ville,
			'postalCode'      => $cp,
			'addressCountry'  => 'FR',
		),
		'containsPlace' => array(
			'@type'            => 'Accommodation',
			'occupancy'        => array(
				'@type' => 'QuantitativeValue',
				'value' => (int) get_theme_mod( 'vr_capacite_max', 8 ),
			),
			'numberOfBedrooms'       => count( vr_contenus( 'vr_chambre' ) ),
			'numberOfBathroomsTotal' => 3,
			'amenityFeature'   => $equipements,
		),
	);

	$identifiant = get_theme_mod( 'vr_classement', '' );
	if ( $identifiant ) {
		$donnees['identifier'] = $identifiant;
	}

	$latitude  = get_theme_mod( 'vr_carte_latitude', '' );
	$longitude = get_theme_mod( 'vr_carte_longitude', '' );
	if ( $latitude && $longitude ) {
		$donnees['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $latitude,
			'longitude' => (float) $longitude,
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $donnees, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	);
}
add_action( 'wp_head', 'vr_donnees_structurees', 5 );

/**
 * Questions fréquentes en données structurées, pour gagner de la place dans Google.
 */
function vr_donnees_faq() {
	if ( ! is_front_page() ) {
		return;
	}

	$questions = vr_contenus( 'vr_faq' );
	if ( ! $questions ) {
		return;
	}

	$items = array();
	foreach ( $questions as $question ) {
		$items[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $question->post_title ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $question->post_content ),
			),
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode(
			array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $items,
			),
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		)
	);
}
add_action( 'wp_head', 'vr_donnees_faq', 6 );
