<?php
/**
 * En-tête du site : un bandeau d'information, puis la barre de navigation.
 * Le menu se modifie dans Apparence → Menus (emplacement « Menu principal »).
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_tel      = get_theme_mod( 'vr_telephone', '' );
$vr_tel_lien = vr_tel_brut( $vr_tel );
$vr_email    = get_theme_mod( 'vr_email', '' );
$vr_bandeau  = get_theme_mod( 'vr_bandeau_texte', '' );

$vr_menu_secours = array(
	array( 'La villa', '/#villa' ),
	array( 'Chambres', '/#chambres' ),
	array( 'Formules & tarifs', '/#formules' ),
	array( 'Piscine & jardin', '/#exterieurs' ),
	array( 'Avis', '/#avis' ),
	array( 'La région', '/#region' ),
	array( 'Contact', '/#contact' ),
);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="preconnect" href="https://api.fontshare.com" crossorigin />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="vr-skip" href="#contenu">Aller au contenu</a>

<header class="vr-header" id="vr-header">

	<?php if ( $vr_bandeau || $vr_tel || $vr_email ) : ?>
		<div class="vr-topbar">
			<div class="vr-wrap vr-topbar__inner">
				<?php if ( $vr_bandeau ) : ?>
					<span class="vr-topbar__info">
						<?php vr_icone( 'pin', 'vr-icon vr-icon--xs' ); ?>
						<?php echo esc_html( $vr_bandeau ); ?>
					</span>
				<?php endif; ?>
				<span class="vr-topbar__contact">
					<?php if ( $vr_tel ) : ?>
						<a href="tel:+<?php echo esc_attr( $vr_tel_lien ); ?>"><?php vr_icone( 'phone', 'vr-icon vr-icon--xs' ); ?><?php echo esc_html( $vr_tel ); ?></a>
					<?php endif; ?>
					<?php if ( $vr_email ) : ?>
						<a href="mailto:<?php echo esc_attr( $vr_email ); ?>"><?php vr_icone( 'mail', 'vr-icon vr-icon--xs' ); ?><?php echo esc_html( $vr_email ); ?></a>
					<?php endif; ?>
				</span>
			</div>
		</div>
	<?php endif; ?>

	<nav class="vr-wrap vr-nav" aria-label="Navigation principale">

		<a class="vr-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'medium', false, array( 'class' => 'vr-brand__logo', 'alt' => get_bloginfo( 'name' ) ) ); ?>
			<?php else : ?>
				<span class="vr-brand__name"><?php bloginfo( 'name' ); ?></span>
				<?php if ( get_bloginfo( 'description' ) ) : ?>
					<span class="vr-brand__tag"><?php bloginfo( 'description' ); ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</a>

		<?php
		if ( has_nav_menu( 'principal' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'principal',
				'container'      => false,
				'menu_class'     => 'vr-nav__menu',
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
		} else {
			echo '<ul class="vr-nav__menu">';
			foreach ( $vr_menu_secours as $vr_lien ) {
				printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( $vr_lien[1] ) ), esc_html( $vr_lien[0] ) );
			}
			echo '</ul>';
		}
		?>

		<div class="vr-nav__actions">
			<a class="vr-btn vr-btn--primary vr-nav__cta" href="<?php echo esc_url( home_url( '/#reserver' ) ); ?>">Réserver en direct</a>

			<button type="button" class="vr-burger" id="vr-burger" aria-expanded="false" aria-controls="vr-mobile" aria-label="Ouvrir le menu">
				<?php vr_icone( 'burger', 'vr-icon' ); ?>
			</button>
		</div>
	</nav>

	<div class="vr-mobile" id="vr-mobile">
		<?php
		if ( has_nav_menu( 'principal' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'principal',
				'container'      => false,
				'menu_class'     => '',
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
		} else {
			echo '<ul>';
			foreach ( $vr_menu_secours as $vr_lien ) {
				printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( $vr_lien[1] ) ), esc_html( $vr_lien[0] ) );
			}
			echo '</ul>';
		}
		?>
		<a class="vr-btn vr-btn--primary" href="<?php echo esc_url( home_url( '/#reserver' ) ); ?>">Réserver en direct</a>
		<?php if ( $vr_tel ) : ?>
			<a class="vr-btn" href="tel:+<?php echo esc_attr( $vr_tel_lien ); ?>">
				<?php vr_icone( 'phone', 'vr-icon vr-icon--sm' ); ?>
				<?php echo esc_html( $vr_tel ); ?>
			</a>
		<?php endif; ?>
	</div>
</header>

<main id="contenu">
