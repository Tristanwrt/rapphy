<?php
/**
 * Section « Avis voyageurs » : les notes avec le logo de leur plateforme
 * (cliquables vers Google et Airbnb), puis les témoignages.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_avis   = vr_contenus( 'vr_avis' );
$vr_liens  = array(
	'airbnb' => get_theme_mod( 'vr_url_airbnb', '' ),
	'google' => get_theme_mod( 'vr_url_google', '' ),
);
$vr_icones = array( 3 => 'shield', 4 => 'check' );

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
				<?php
				$vr_valeur = vr_badge( $vr_n, 'valeur' );
				$vr_label  = vr_badge( $vr_n, 'label' );

				if ( ! $vr_valeur ) {
					continue;
				}

				$vr_plateforme = '';
				if ( false !== stripos( $vr_label, 'airbnb' ) ) {
					$vr_plateforme = 'airbnb';
				} elseif ( false !== stripos( $vr_label, 'google' ) ) {
					$vr_plateforme = 'google';
				}

				$vr_url = ( $vr_plateforme && ! empty( $vr_liens[ $vr_plateforme ] ) ) ? $vr_liens[ $vr_plateforme ] : '';
				$vr_tag = $vr_url ? 'a' : 'div';
				?>
				<<?php echo $vr_tag; // phpcs:ignore ?> class="vr-badge vr-reveal<?php echo $vr_url ? ' vr-badge--lien' : ''; ?>" style="transition-delay:<?php echo esc_attr( ( $vr_n - 1 ) * 0.08 ); ?>s"<?php echo $vr_url ? ' href="' . esc_url( $vr_url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( 'Voir nos avis sur ' . ucfirst( $vr_plateforme ) . ' (nouvelle fenêtre)' ) . '"' : ''; ?>>
					<span class="vr-badge__logo">
						<?php
						if ( $vr_plateforme ) {
							vr_logo_plateforme( $vr_plateforme );
						} else {
							vr_icone( isset( $vr_icones[ $vr_n ] ) ? $vr_icones[ $vr_n ] : 'star', 'vr-icon' );
						}
						?>
					</span>
					<span class="vr-badge__value"><?php echo esc_html( $vr_valeur ); ?></span>
					<span class="vr-badge__label"><?php echo esc_html( $vr_label ); ?></span>
					<?php if ( $vr_url ) : ?>
						<span class="vr-badge__voir">Voir les avis<?php vr_icone( 'arrow', 'vr-icon vr-icon--xs' ); ?></span>
					<?php endif; ?>
				</<?php echo $vr_tag; // phpcs:ignore ?>>
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

	</div>
</section>
