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
$vr_google = get_theme_mod( 'vr_url_google', '' );

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

		<?php if ( $vr_airbnb || $vr_google ) : ?>
			<div class="vr-reveal vr-center" style="margin-top:48px">
				<p class="vr-eyebrow" style="margin-bottom:16px">Retrouvez tous nos avis sur</p>
				<div class="vr-plateformes">
					<?php if ( $vr_google ) : ?>
						<a class="vr-plateforme" href="<?php echo esc_url( $vr_google ); ?>" target="_blank" rel="noopener" aria-label="Voir nos avis sur Google (nouvelle fenêtre)">
							<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96H1.29v3.09C3.26 21.3 7.31 24 12 24z"/><path fill="#FBBC05" d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.98-3.09z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.98 3.09C6.22 6.86 8.87 4.75 12 4.75z"/></svg>
							<span>Avis Google</span>
						</a>
					<?php endif; ?>
					<?php if ( $vr_airbnb ) : ?>
						<a class="vr-plateforme" href="<?php echo esc_url( $vr_airbnb ); ?>" target="_blank" rel="noopener" aria-label="Voir notre annonce Airbnb (nouvelle fenêtre)">
							<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="#FF5A5F" d="M12 2c-1.6 0-2.7.9-3.4 2.4L4.3 14.2c-.9 2-.2 4.4 1.8 5.4 1.7.9 3.7.5 5-1l.9-1 .9 1c1.3 1.5 3.3 1.9 5 1 2-1 2.7-3.4 1.8-5.4L15.4 4.4C14.7 2.9 13.6 2 12 2zm0 4.6c.6 0 1 .3 1.3 1l2.8 6.3-2.7 3.1c-.6.7-1.2 1.1-1.4 1.1s-.8-.4-1.4-1.1L7.9 13.9l2.8-6.3c.3-.7.7-1 1.3-1zm0 3.2a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8z"/></svg>
							<span>Airbnb</span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
