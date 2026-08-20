<?php
/**
 * En-tête du site.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_tel      = get_theme_mod( 'vr_telephone', '' );
$vr_tel_lien = vr_tel_brut( $vr_tel );
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

<header class="vr-header<?php echo is_front_page() ? '' : ' is-scrolled'; ?>" id="vr-header">
	<nav class="vr-wrap vr-nav" aria-label="Navigation principale">

		<a class="vr-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="vr-brand__name"><?php bloginfo( 'name' ); ?></span>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<span class="vr-brand__tag"><?php bloginfo( 'description' ); ?></span>
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
			?>
			<ul class="vr-nav__menu">
				<li><a href="<?php echo esc_url( home_url( '/#villa' ) ); ?>">La villa</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#chambres' ) ); ?>">Chambres</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#exterieurs' ) ); ?>">Piscine &amp; jardin</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#avis' ) ); ?>">Avis</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#region' ) ); ?>">La région</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Contact</a></li>
			</ul>
			<?php
		}
		?>

		<?php if ( $vr_tel ) : ?>
			<a class="vr-nav__tel" href="tel:+<?php echo esc_attr( $vr_tel_lien ); ?>">
				<?php vr_icone( 'phone', 'vr-icon vr-icon--sm' ); ?>
				<?php echo esc_html( $vr_tel ); ?>
			</a>
		<?php endif; ?>

		<a class="vr-btn vr-btn--primary vr-nav__cta" href="<?php echo esc_url( home_url( '/#reserver' ) ); ?>">Réserver en direct</a>

		<button type="button" class="vr-burger" id="vr-burger" aria-expanded="false" aria-controls="vr-mobile" aria-label="Ouvrir le menu">
			<?php vr_icone( 'burger', 'vr-icon' ); ?>
		</button>
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
			?>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/#villa' ) ); ?>">La villa</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#chambres' ) ); ?>">Chambres</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#exterieurs' ) ); ?>">Piscine &amp; jardin</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#avis' ) ); ?>">Avis</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#region' ) ); ?>">La région</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Contact</a></li>
			</ul>
			<?php
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
