<?php
/**
 * Villa Raffy — fonctions du thème
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VR_VERSION', '1.3.0' );

/* ═══════════════════════════════════════════════════════════
   1. RÉGLAGES DU THÈME
   ═══════════════════════════════════════════════════════════ */

function vr_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 60, 'width' => 220, 'flex-height' => true, 'flex-width' => true ) );

	register_nav_menus( array(
		'principal' => __( 'Menu principal', 'villa-raffy' ),
		'pied'      => __( 'Menu du pied de page', 'villa-raffy' ),
	) );

	// Formats d'images utilisés par le thème.
	add_image_size( 'vr-hero', 2400, 1440, true );
	add_image_size( 'vr-carte', 1000, 750, true );
	add_image_size( 'vr-mosaique', 1400, 1050, true );
}
add_action( 'after_setup_theme', 'vr_setup' );

function vr_content_width() {
	$GLOBALS['content_width'] = 1280;
}
add_action( 'after_setup_theme', 'vr_content_width', 0 );

/* ═══════════════════════════════════════════════════════════
   2. STYLES & SCRIPTS
   ═══════════════════════════════════════════════════════════ */

function vr_assets() {
	// Typographies Fontshare (Sentient + Supreme).
	wp_enqueue_style(
		'vr-fonts',
		'https://api.fontshare.com/v2/css?f[]=sentient@400,500,700&f[]=supreme@300,400,500,700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'vr-style', get_stylesheet_uri(), array( 'vr-fonts' ), VR_VERSION );

	wp_enqueue_script( 'vr-script', get_template_directory_uri() . '/assets/js/theme.js', array(), VR_VERSION, true );

	// Données transmises au JavaScript.
	wp_localize_script( 'vr-script', 'vrData', array(
		'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
		'restUrl'      => esc_url_raw( rest_url( 'villa-raffy/v1/' ) ),
		'nuitsMinimum' => (int) get_theme_mod( 'vr_nuits_minimum', 2 ),
		'capaciteMax'  => (int) get_theme_mod( 'vr_capacite_max', 8 ),
		'whatsapp'     => vr_tel_brut( get_theme_mod( 'vr_whatsapp', '' ) ),
		'email'        => get_theme_mod( 'vr_email', '' ),
		'nomVilla'     => get_bloginfo( 'name' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'vr_assets' );

function vr_admin_assets( $hook ) {
	wp_enqueue_style( 'vr-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), VR_VERSION );
}
add_action( 'admin_enqueue_scripts', 'vr_admin_assets' );

/* ═══════════════════════════════════════════════════════════
   3. FICHIERS INCLUS
   ═══════════════════════════════════════════════════════════ */

require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/metaboxes.php';
require_once get_template_directory() . '/inc/redirections.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/blocs.php';
require_once get_template_directory() . '/inc/couleurs.php';
require_once get_template_directory() . '/inc/images.php';
require_once get_template_directory() . '/inc/anglais.php';

/**
 * Qualité des photos : WordPress compresse à 82/100 par défaut et rétrécit
 * tout ce qui dépasse 2560 px. Pour une villa, on veut des images nettes,
 * y compris sur les écrans Retina.
 */
add_filter( 'jpeg_quality', function () {
	return 90;
} );
add_filter( 'wp_editor_set_quality', function () {
	return 90;
} );
add_filter( 'big_image_size_threshold', function () {
	return 3200;
} );

/* ═══════════════════════════════════════════════════════════
   4. FONCTIONS UTILITAIRES
   ═══════════════════════════════════════════════════════════ */

/**
 * Nettoie un numéro de téléphone pour en faire un lien tel: ou WhatsApp.
 * « 06 83 63 89 66 » devient « 33683638966 ».
 */
function vr_tel_brut( $numero ) {
	$chiffres = preg_replace( '/[^0-9+]/', '', (string) $numero );
	if ( strpos( $chiffres, '0' ) === 0 ) {
		$chiffres = '33' . substr( $chiffres, 1 );
	}
	return ltrim( $chiffres, '+' );
}

/**
 * Affiche une image, ou le dégradé de secours si la photo n'a pas encore été déposée.
 */
function vr_image( $id, $taille = 'large', $label = '', $classe = '' ) {
	if ( $id && wp_get_attachment_image_url( $id, $taille ) ) {
		echo wp_get_attachment_image(
			$id,
			$taille,
			false,
			array(
				'class'   => esc_attr( $classe ),
				'loading' => 'lazy',
			)
		);
		return;
	}

	printf(
		'<div class="vr-photo-fallback %1$s" role="img" aria-label="%2$s" data-label="%2$s"></div>',
		esc_attr( $classe ),
		esc_attr( $label )
	);
}

/**
 * Bibliothèque d'icônes SVG (tracés type Lucide). Jamais d'emoji en icône.
 */
function vr_icone( $nom, $classe = 'vr-icon' ) {
	$traces = array(
		'phone'    => 'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z',
		'mail'     => 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6',
		'pin'      => 'M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6z',
		'star'     => 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
		'users'    => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75',
		'bed'      => 'M2 4v16 M2 8h18a2 2 0 0 1 2 2v10 M2 17h20 M6 8v9',
		'ruler'    => 'M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.4 2.4 0 0 1 0-3.4l2.6-2.6a2.4 2.4 0 0 1 3.4 0zM14.5 12.5l2-2 M11.5 9.5l2-2 M8.5 6.5l2-2 M17.5 15.5l2-2',
		'waves'    => 'M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1',
		'spa'      => 'M12 22a8 8 0 0 0 8-8c0-4.4-3.6-8-8-11-4.4 3-8 6.6-8 11a8 8 0 0 0 8 8z M9 14a3 3 0 0 0 6 0',
		'film'     => 'M2 4c0-1.1.9-2 2-2h16c1.1 0 2 .9 2 2v16c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V4z M7 2v20 M17 2v20 M2 12h20 M2 7h5 M2 17h5 M17 17h5 M17 7h5',
		'dumbbell' => 'M6.5 6.5L17.5 17.5 M21 21l-1.5-1.5 M3 3l1.5 1.5 M18 22l4-4 M2 6l4-4 M3 10l7-7 M14 21l7-7',
		'play'     => 'M5 3l14 9-14 9V3z',
		'beach'    => 'M2 20h20 M12 20V9 M12 9c-4 0-7-2-8-5 3-1 6 0 8 5 2-5 5-6 8-5-1 3-4 5-8 5z',
		'tree'     => 'M12 22v-7 M9 8l3-6 3 6 M8 14l4-8 4 8z',
		'chef'     => 'M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6z M6 17h12',
		'wifi'     => 'M5 13a10 10 0 0 1 14 0 M8.5 16.5a5 5 0 0 1 7 0 M2 8.82a15 15 0 0 1 20 0 M12 20h.01',
		'check'    => 'M20 6L9 17l-5-5',
		'shield'   => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z M9 12l2 2 4-4',
		'calendar' => 'M8 2v4 M16 2v4 M3 10h18 M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z',
		'sun'      => 'M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10z M12 1v2 M12 21v2 M4.22 4.22l1.42 1.42 M18.36 18.36l1.42 1.42 M1 12h2 M21 12h2 M4.22 19.78l1.42-1.42 M18.36 5.64l1.42-1.42',
		'paw'      => 'M12 17c-3 0-5 1.5-5 3 0 1 .8 2 2.5 2h5c1.7 0 2.5-1 2.5-2 0-1.5-2-3-5-3z M4.5 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4z M9 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z M15 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z M19.5 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4z',
		'quote'    => 'M3 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z',
		'arrow'    => 'M5 12h14 M12 5l7 7-7 7',
		'whatsapp' => 'M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z',
		'search'   => 'M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14z M21 21l-4.35-4.35',
		'plus'     => 'M12 5v14M5 12h14',
		'left'     => 'M15 18l-6-6 6-6',
		'right'    => 'M9 6l6 6-6 6',
		'burger'   => 'M3 7h18M3 12h18M3 17h18',
		'close'    => 'M6 6l12 12M18 6L6 18',
	);

	if ( empty( $traces[ $nom ] ) ) {
		return;
	}

	printf(
		'<svg viewBox="0 0 24 24" class="%s" aria-hidden="true" focusable="false"><path d="%s"/></svg>',
		esc_attr( $classe ),
		esc_attr( $traces[ $nom ] )
	);
}

/**
 * Logos des plateformes d'avis (Google, Airbnb), avec leur nom.
 */
function vr_logo_plateforme( $nom ) {
	if ( 'google' === $nom ) {
		echo '<span class="vr-logo vr-logo--google"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96H1.29v3.09C3.26 21.3 7.31 24 12 24z"/><path fill="#FBBC05" d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.98-3.09z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.98 3.09C6.22 6.86 8.87 4.75 12 4.75z"/></svg><span class="vr-logo__nom">Google</span></span>';
		return;
	}
	if ( 'airbnb' === $nom ) {
		echo '<span class="vr-logo vr-logo--airbnb"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#FF5A5F" d="M12 2c-1.6 0-2.7.9-3.4 2.4L4.3 14.2c-.9 2-.2 4.4 1.8 5.4 1.7.9 3.7.5 5-1l.9-1 .9 1c1.3 1.5 3.3 1.9 5 1 2-1 2.7-3.4 1.8-5.4L15.4 4.4C14.7 2.9 13.6 2 12 2zm0 4.6c.6 0 1 .3 1.3 1l2.8 6.3-2.7 3.1c-.6.7-1.2 1.1-1.4 1.1s-.8-.4-1.4-1.1L7.9 13.9l2.8-6.3c.3-.7.7-1 1.3-1zm0 3.2a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8z"/></svg><span class="vr-logo__nom">Airbnb</span></span>';
	}
}

/**
 * Les photos d'une fiche : l'image mise en avant, puis la galerie (vr_galerie).
 */
function vr_photos( $post, $taille = 'large' ) {
	$ids       = array();
	$principal = get_post_thumbnail_id( $post );

	if ( $principal ) {
		$ids[] = (int) $principal;
	}

	foreach ( (array) get_post_meta( $post->ID, 'vr_galerie', true ) as $id ) {
		$id = (int) $id;
		if ( $id && ! in_array( $id, $ids, true ) && wp_get_attachment_image_url( $id, $taille ) ) {
			$ids[] = $id;
		}
	}

	return $ids;
}

/**
 * Affiche les photos d'une fiche : une seule image, ou une petite galerie
 * à flèches quand la fiche en a plusieurs (chambre, salle de bain, petit salon…).
 */
function vr_galerie( $post, $taille = 'vr-carte', $label = '' ) {
	$ids = vr_photos( $post, $taille );

	if ( count( $ids ) < 2 ) {
		vr_image( get_post_thumbnail_id( $post ), $taille, $label, '' );
		return;
	}

	echo '<div class="vr-galerie" data-galerie>';

	foreach ( $ids as $i => $id ) {
		$legende = wp_get_attachment_caption( $id );
		if ( ! $legende ) {
			$legende = get_post_meta( $id, '_wp_attachment_image_alt', true );
		}
		printf(
			'<figure class="vr-galerie__vue%1$s">%2$s%3$s</figure>',
			0 === $i ? ' is-active' : '',
			wp_get_attachment_image( $id, $taille, false, array( 'loading' => 0 === $i ? 'eager' : 'lazy' ) ),
			$legende ? '<figcaption class="vr-galerie__legende">' . esc_html( $legende ) . '</figcaption>' : ''
		);
	}

	echo '<button type="button" class="vr-galerie__fleche vr-galerie__fleche--prec" aria-label="Photo précédente">';
	vr_icone( 'left', 'vr-icon vr-icon--sm' );
	echo '</button>';
	echo '<button type="button" class="vr-galerie__fleche vr-galerie__fleche--suiv" aria-label="Photo suivante">';
	vr_icone( 'right', 'vr-icon vr-icon--sm' );
	echo '</button>';

	echo '<div class="vr-galerie__points">';
	foreach ( $ids as $i => $id ) {
		printf( '<button type="button" class="vr-galerie__point%s" aria-label="Photo %d"></button>', 0 === $i ? ' is-active' : '', $i + 1 );
	}
	echo '</div>';

	echo '</div>';
}

/**
 * Cinq étoiles pleines, pour les blocs d'avis.
 */
function vr_etoiles() {
	echo '<span class="vr-stars">';
	for ( $i = 0; $i < 5; $i++ ) {
		vr_icone( 'star', 'vr-icon vr-icon--sm' );
	}
	echo '</span>';
}

/**
 * Récupère les publications d'un type donné, dans l'ordre défini par glisser-déposer.
 */
function vr_contenus( $type, $limite = -1 ) {
	return get_posts( array(
		'post_type'      => $type,
		'posts_per_page' => $limite,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
}

/* ═══════════════════════════════════════════════════════════
   5. NETTOYAGE DE L'ADMINISTRATION
   ═══════════════════════════════════════════════════════════ */

/**
 * Simplifie le tableau de bord pour le propriétaire : on retire ce qui ne sert pas.
 */
function vr_nettoyer_admin() {
	if ( current_user_can( 'manage_options' ) ) {
		return; // L'administrateur technique garde tout.
	}
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'tools.php' );
}
add_action( 'admin_menu', 'vr_nettoyer_admin', 999 );

function vr_nettoyer_widgets_dashboard() {
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'vr_nettoyer_widgets_dashboard' );

/**
 * Message d'accueil dans le tableau de bord.
 */
function vr_widget_accueil() {
	wp_add_dashboard_widget( 'vr_accueil', 'Gérer la Villa Raffy', function () {
		$accueil = (int) get_option( 'page_on_front' );
		$liens   = array(
			array( 'Modifier les sections de la page d\'accueil', $accueil ? get_edit_post_link( $accueil, '' ) : admin_url( 'edit.php?post_type=page' ) ),
			array( 'Voir et bloquer mes dates', admin_url( 'edit.php?post_type=vr_reservation&page=vr-calendrier' ) ),
			array( 'Mes réservations', admin_url( 'edit.php?post_type=vr_reservation' ) ),
			array( 'Mes coordonnées, menus et pied de page', admin_url( 'customize.php' ) ),
			array( 'Chambres, visite guidée, avis, lieux', admin_url( 'edit.php?post_type=vr_chambre' ) ),
			array( 'Mes photos', admin_url( 'upload.php' ) ),
			array( 'Écrire un article de blog', admin_url( 'post-new.php' ) ),
		);
		echo '<p style="margin-top:0">Bienvenue. Voici les endroits dont vous aurez besoin au quotidien :</p><ul style="margin:0">';
		foreach ( $liens as $lien ) {
			printf(
				'<li style="margin-bottom:8px">→ <a href="%s"><strong>%s</strong></a></li>',
				esc_url( $lien[1] ),
				esc_html( $lien[0] )
			);
		}
		echo '</ul>';
	} );
}
add_action( 'wp_dashboard_setup', 'vr_widget_accueil' );

/**
 * Retire la barre d'administration côté visiteur pour les non-administrateurs.
 */
add_filter( 'show_admin_bar', function ( $afficher ) {
	return current_user_can( 'edit_posts' ) ? $afficher : false;
} );

/* ═══════════════════════════════════════════════════════════
   6. EXTRAIT & PAGINATION
   ═══════════════════════════════════════════════════════════ */

add_filter( 'excerpt_length', function () {
	return 24;
} );

add_filter( 'excerpt_more', function () {
	return '…';
} );
