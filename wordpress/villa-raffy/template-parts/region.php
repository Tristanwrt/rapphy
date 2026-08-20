<?php
/**
 * Section « La région » + carte de localisation.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_lieux     = vr_contenus( 'vr_lieu' );
$vr_latitude  = get_theme_mod( 'vr_carte_latitude', '' );
$vr_longitude = get_theme_mod( 'vr_carte_longitude', '' );
$vr_legende   = get_theme_mod( 'vr_carte_legende', '' );
?>

<section class="vr-section vr-region" id="region">
	<div class="vr-wrap">

		<div class="vr-reveal vr-center" style="margin-bottom:48px">
			<p class="vr-eyebrow">La région</p>
			<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl"><?php echo esc_html( get_theme_mod( 'vr_region_titre', '' ) ); ?></h2>
			<?php if ( get_theme_mod( 'vr_region_texte', '' ) ) : ?>
				<p class="vr-lead vr-mx-auto vr-maxw-2xl" style="margin-top:20px"><?php echo esc_html( get_theme_mod( 'vr_region_texte', '' ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $vr_lieux ) : ?>
			<div class="vr-grid-cards vr-grid-cards--3">
				<?php foreach ( $vr_lieux as $vr_i => $vr_lieu ) : ?>
					<article class="vr-tile vr-reveal" style="transition-delay:<?php echo esc_attr( $vr_i * 0.12 ); ?>s">
						<div class="vr-tile__media" style="height:192px">
							<?php vr_image( get_post_thumbnail_id( $vr_lieu ), 'vr-carte', get_the_title( $vr_lieu ), '' ); ?>
						</div>
						<div class="vr-tile__body">
							<h3 class="vr-h3"><?php echo esc_html( get_the_title( $vr_lieu ) ); ?></h3>
							<p class="vr-tile__text"><?php echo esc_html( wp_strip_all_tags( $vr_lieu->post_content ) ); ?></p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $vr_latitude && $vr_longitude ) : ?>
			<?php
			$vr_lat  = (float) $vr_latitude;
			$vr_lon  = (float) $vr_longitude;
			$vr_bbox = sprintf( '%F,%F,%F,%F', $vr_lon - 0.25, $vr_lat - 0.12, $vr_lon + 0.25, $vr_lat + 0.12 );
			$vr_src  = add_query_arg(
				array(
					'bbox'   => $vr_bbox,
					'layer'  => 'mapnik',
					'marker' => $vr_lat . ',' . $vr_lon,
				),
				'https://www.openstreetmap.org/export/embed.html'
			);
			?>
			<div class="vr-map vr-reveal">
				<iframe
					src="<?php echo esc_url( $vr_src ); ?>"
					title="Carte de localisation de la villa"
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"></iframe>

				<div class="vr-map__foot">
					<span><?php echo esc_html( $vr_legende ); ?></span>
					<a href="<?php echo esc_url( 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( get_theme_mod( 'vr_adresse', '' ) ) ); ?>" target="_blank" rel="noopener">
						Ouvrir dans Google Maps
					</a>
				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
