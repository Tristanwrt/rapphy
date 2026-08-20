<?php
/**
 * Section « Les chambres ».
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_chambres = vr_contenus( 'vr_chambre' );

if ( ! $vr_chambres ) {
	return;
}
?>

<section class="vr-section vr-chambres" id="chambres">
	<div class="vr-wrap">

		<div class="vr-reveal vr-center" style="margin-bottom:48px">
			<p class="vr-eyebrow">Les chambres</p>
			<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl"><?php echo esc_html( get_theme_mod( 'vr_chambres_titre', '' ) ); ?></h2>
			<?php if ( get_theme_mod( 'vr_chambres_texte', '' ) ) : ?>
				<p class="vr-lead vr-mx-auto vr-maxw-2xl" style="margin-top:20px"><?php echo esc_html( get_theme_mod( 'vr_chambres_texte', '' ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="vr-grid-cards <?php echo count( $vr_chambres ) >= 4 ? 'vr-grid-cards--4' : 'vr-grid-cards--3'; ?>">
			<?php foreach ( $vr_chambres as $vr_i => $vr_chambre ) : ?>
				<article class="vr-tile vr-reveal" style="transition-delay:<?php echo esc_attr( $vr_i * 0.1 ); ?>s">
					<div class="vr-tile__media">
						<?php vr_image( get_post_thumbnail_id( $vr_chambre ), 'vr-carte', get_the_title( $vr_chambre ), '' ); ?>
					</div>
					<div class="vr-tile__body">
						<h3 class="vr-h3"><?php echo esc_html( get_the_title( $vr_chambre ) ); ?></h3>
						<?php if ( vr_meta( $vr_chambre->ID, 'vr_detail' ) ) : ?>
							<p class="vr-tile__detail"><?php echo esc_html( vr_meta( $vr_chambre->ID, 'vr_detail' ) ); ?></p>
						<?php endif; ?>
						<?php if ( vr_meta( $vr_chambre->ID, 'vr_texte' ) ) : ?>
							<p class="vr-tile__text"><?php echo esc_html( vr_meta( $vr_chambre->ID, 'vr_texte' ) ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
