<?php
/**
 * Bloc « Pourquoi réserver en direct ».
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_tel      = get_theme_mod( 'vr_telephone', '' );
$vr_tel_lien = vr_tel_brut( $vr_tel );
$vr_icones   = array( 'check', 'phone', 'calendar' );
?>

<section class="vr-direct">
	<div class="vr-wrap">

		<div class="vr-reveal vr-center">
			<p class="vr-eyebrow">Pourquoi réserver en direct ?</p>
			<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl">
				<?php echo esc_html( get_theme_mod( 'vr_direct_titre', 'Ici, pas d\'intermédiaire.' ) ); ?><br />
				<span class="vr-italic-brass"><?php echo esc_html( get_theme_mod( 'vr_direct_titre_2', 'Juste vous, et la villa.' ) ); ?></span>
			</h2>
		</div>

		<div class="vr-direct__grid">
			<?php for ( $vr_n = 1; $vr_n <= 3; $vr_n++ ) : ?>
				<?php
				$vr_titre = get_theme_mod( "vr_direct_{$vr_n}_titre", '' );
				$vr_texte = get_theme_mod( "vr_direct_{$vr_n}_texte", '' );

				if ( ! $vr_titre ) {
					continue;
				}
				?>
				<div class="vr-card vr-reveal" style="transition-delay:<?php echo esc_attr( ( $vr_n - 1 ) * 0.12 ); ?>s">
					<div class="vr-card__icon"><?php vr_icone( $vr_icones[ $vr_n - 1 ] ); ?></div>
					<h3 class="vr-h3"><?php echo esc_html( $vr_titre ); ?></h3>
					<p><?php echo esc_html( $vr_texte ); ?></p>
				</div>
			<?php endfor; ?>
		</div>

		<?php if ( $vr_tel ) : ?>
			<div class="vr-reveal vr-center" style="margin-top:40px">
				<p class="vr-lead">
					Une question ? Appelez directement le
					<a href="tel:+<?php echo esc_attr( $vr_tel_lien ); ?>" style="color:var(--brass);font-weight:500"><?php echo esc_html( $vr_tel ); ?></a>
				</p>
			</div>
		<?php endif; ?>

	</div>
</section>
