<?php
/**
 * Section vidéo : un montage MP4 déposé dans l'administration,
 * ou à défaut un enchaînement automatique en fondu des photos de la visite guidée.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_video_id  = (int) get_theme_mod( 'vr_video_fichier', 0 );
$vr_video_url = $vr_video_id ? wp_get_attachment_url( $vr_video_id ) : '';
$vr_poster_id = (int) get_theme_mod( 'vr_hero_image', 0 );
$vr_poster    = $vr_poster_id ? wp_get_attachment_image_url( $vr_poster_id, 'vr-hero' ) : '';

$vr_diapos = array();
foreach ( vr_contenus( 'vr_espace', 10 ) as $vr_espace ) {
	$vr_id = get_post_thumbnail_id( $vr_espace );
	if ( $vr_id ) {
		$vr_diapos[] = array( 'id' => $vr_id, 'titre' => get_the_title( $vr_espace ) );
	}
}

if ( ! $vr_video_url && ! $vr_diapos ) {
	return;
}
?>

<section class="vr-film" id="film">
	<div class="vr-wrap">
		<div class="vr-reveal vr-center" style="margin-bottom:40px">
			<p class="vr-eyebrow"><?php echo esc_html( get_theme_mod( 'vr_video_surtitre', 'La villa en mouvement' ) ); ?></p>
			<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl" style="color:var(--linen)"><?php echo esc_html( get_theme_mod( 'vr_video_titre', '' ) ); ?></h2>
		</div>

		<div class="vr-film__cadre vr-reveal">
			<?php if ( $vr_video_url ) : ?>
				<video
					src="<?php echo esc_url( $vr_video_url ); ?>"
					<?php echo $vr_poster ? 'poster="' . esc_url( $vr_poster ) . '"' : ''; ?>
					autoplay muted loop playsinline preload="metadata"
					aria-label="Vidéo de présentation de la villa"></video>
			<?php else : ?>
				<div class="vr-diapo" id="vr-diapo" aria-label="Diaporama des espaces de la villa">
					<?php foreach ( $vr_diapos as $vr_i => $vr_diapo ) : ?>
						<figure class="vr-diapo__vue<?php echo 0 === $vr_i ? ' is-active' : ''; ?>">
							<?php echo wp_get_attachment_image( $vr_diapo['id'], 'vr-hero', false, array( 'loading' => 0 === $vr_i ? 'eager' : 'lazy' ) ); ?>
							<figcaption><?php echo esc_html( $vr_diapo['titre'] ); ?></figcaption>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
