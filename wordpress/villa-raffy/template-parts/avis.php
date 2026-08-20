<?php
/**
 * Section « Avis voyageurs ».
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_avis   = vr_contenus( 'vr_avis' );
$vr_airbnb = get_theme_mod( 'vr_url_airbnb', '' );

if ( ! $vr_avis ) {
	return;
}
?>

<section class="vr-section vr-avis" id="avis">
	<div class="vr-wrap">

		<div class="vr-reveal vr-center" style="margin-bottom:48px">
			<p class="vr-eyebrow">Ils ont séjourné à la villa</p>
			<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl"><?php echo esc_html( get_theme_mod( 'vr_avis_titre', '' ) ); ?></h2>
		</div>

		<div class="vr-badges">
			<?php for ( $vr_n = 1; $vr_n <= 4; $vr_n++ ) : ?>
				<?php $vr_valeur = vr_badge( $vr_n, 'valeur' ); ?>
				<?php if ( ! $vr_valeur ) { continue; } ?>
				<div class="vr-badge vr-reveal" style="transition-delay:<?php echo esc_attr( ( $vr_n - 1 ) * 0.08 ); ?>s">
					<div class="vr-badge__value"><?php echo esc_html( $vr_valeur ); ?></div>
					<div class="vr-badge__label"><?php echo esc_html( vr_badge( $vr_n, 'label' ) ); ?></div>
				</div>
			<?php endfor; ?>
		</div>

		<div class="vr-quotes">
			<?php foreach ( $vr_avis as $vr_i => $vr_temoignage ) : ?>
				<figure class="vr-quote vr-reveal" style="transition-delay:<?php echo esc_attr( $vr_i * 0.1 ); ?>s">
					<?php vr_icone( 'quote', 'vr-quote__mark' ); ?>
					<div class="vr-quote__stars">
						<?php for ( $vr_s = 0; $vr_s < 5; $vr_s++ ) { vr_icone( 'star', 'vr-icon vr-icon--sm' ); } ?>
					</div>
					<blockquote>«&nbsp;<?php echo esc_html( wp_strip_all_tags( $vr_temoignage->post_content ) ); ?>&nbsp;»</blockquote>
					<figcaption>
						<strong><?php echo esc_html( get_the_title( $vr_temoignage ) ); ?></strong>
						<?php if ( vr_meta( $vr_temoignage->ID, 'vr_date' ) ) : ?>
							· <?php echo esc_html( vr_meta( $vr_temoignage->ID, 'vr_date' ) ); ?>
						<?php endif; ?>
						<?php if ( vr_meta( $vr_temoignage->ID, 'vr_source' ) ) : ?>
							<span class="vr-quote__source">Avis 5 étoiles · <?php echo esc_html( vr_meta( $vr_temoignage->ID, 'vr_source' ) ); ?></span>
						<?php endif; ?>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>

		<?php if ( $vr_airbnb ) : ?>
			<div class="vr-reveal vr-center" style="margin-top:40px">
				<a href="<?php echo esc_url( $vr_airbnb ); ?>" target="_blank" rel="noopener" style="font-size:0.875rem;color:var(--ink-soft);text-decoration:underline;text-underline-offset:4px">
					Voir tous les avis sur Airbnb
				</a>
			</div>
		<?php endif; ?>

	</div>
</section>
