<?php
/**
 * Section « La villa » : présentation + atouts + galerie.
 * Les trois photos reprennent les premières étapes de la visite guidée,
 * pour que le propriétaire n'ait à les déposer qu'une seule fois.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_atouts = vr_contenus( 'vr_atout' );
$vr_photos = vr_contenus( 'vr_espace', 3 );
?>

<section class="vr-section vr-wrap" id="villa">
	<div class="vr-split">

		<div class="vr-split__body vr-reveal">
			<p class="vr-eyebrow">La villa</p>
			<h2 class="vr-h2"><?php echo esc_html( get_theme_mod( 'vr_villa_titre', '' ) ); ?></h2>

			<?php if ( get_theme_mod( 'vr_villa_texte_1', '' ) ) : ?>
				<p class="vr-lead" style="margin-top:24px"><?php echo esc_html( get_theme_mod( 'vr_villa_texte_1', '' ) ); ?></p>
			<?php endif; ?>

			<?php if ( get_theme_mod( 'vr_villa_texte_2', '' ) ) : ?>
				<p class="vr-lead" style="margin-top:16px"><?php echo esc_html( get_theme_mod( 'vr_villa_texte_2', '' ) ); ?></p>
			<?php endif; ?>

			<?php if ( $vr_atouts ) : ?>
				<ul class="vr-features">
					<?php foreach ( $vr_atouts as $vr_atout ) : ?>
						<li>
							<span class="vr-features__badge">
								<?php vr_icone( vr_meta( $vr_atout->ID, 'vr_icone', 'check' ), 'vr-icon vr-icon--sm' ); ?>
							</span>
							<?php echo esc_html( get_the_title( $vr_atout ) ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php if ( $vr_photos ) : ?>
			<div class="vr-split__media vr-reveal" style="transition-delay:0.15s">
				<div class="vr-bento">
					<?php foreach ( $vr_photos as $vr_i => $vr_photo ) : ?>
						<div class="vr-media <?php echo 0 === $vr_i ? 'vr-bento__wide' : 'vr-bento__cell'; ?>" style="box-shadow:var(--shadow-card)">
							<?php vr_image( get_post_thumbnail_id( $vr_photo ), 'vr-mosaique', get_the_title( $vr_photo ), '' ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
