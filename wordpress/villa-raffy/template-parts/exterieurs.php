<?php
/**
 * Section « Piscine & jardin » — mosaïque sombre.
 * Reprend les étapes de la visite guidée situées dans les zones extérieures.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// On récupère les espaces dont la zone évoque l'extérieur.
$vr_tous     = vr_contenus( 'vr_espace' );
$vr_exte     = array();
$vr_mots     = array( 'extérieur', 'exterieur', 'jardin', 'piscine', 'dehors' );

foreach ( $vr_tous as $vr_espace ) {
	$vr_zone = mb_strtolower( vr_meta( $vr_espace->ID, 'vr_zone', '' ) );
	foreach ( $vr_mots as $vr_mot ) {
		if ( false !== mb_strpos( $vr_zone, $vr_mot ) ) {
			$vr_exte[] = $vr_espace;
			break;
		}
	}
}

// Si aucune zone extérieure n'est identifiée, on prend les dernières étapes.
if ( count( $vr_exte ) < 2 ) {
	$vr_exte = array_slice( $vr_tous, -4 );
}

if ( ! $vr_exte ) {
	return;
}

$vr_mosaique = array_slice( $vr_exte, 0, 4 );
$vr_classes  = array( 'vr-mosaic__a', 'vr-mosaic__b', 'vr-mosaic__c', 'vr-mosaic__d' );
$vr_icones   = array( 'waves', 'spa', 'tree' );
?>

<section class="vr-section vr-dark" id="exterieurs">
	<div class="vr-wrap">

		<div class="vr-reveal vr-center" style="margin-bottom:56px">
			<p class="vr-eyebrow">Piscine &amp; jardin</p>
			<h2 class="vr-h2 vr-mx-auto vr-maxw-2xl">
				<?php echo esc_html( get_theme_mod( 'vr_ext_titre', '' ) ); ?><br />
				<span class="vr-italic-brass"><?php echo esc_html( get_theme_mod( 'vr_ext_titre_2', '' ) ); ?></span>
			</h2>
		</div>

		<div class="vr-mosaic">
			<?php foreach ( $vr_mosaique as $vr_i => $vr_photo ) : ?>
				<div class="vr-media <?php echo esc_attr( $vr_classes[ $vr_i ] ); ?> vr-reveal" style="transition-delay:<?php echo esc_attr( $vr_i * 0.06 ); ?>s;box-shadow:var(--shadow-soft)">
					<?php vr_image( get_post_thumbnail_id( $vr_photo ), 'vr-mosaique', get_the_title( $vr_photo ), '' ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="vr-dark__points">
			<?php foreach ( array_slice( $vr_exte, 0, 3 ) as $vr_i => $vr_point ) : ?>
				<div class="vr-point vr-reveal" style="transition-delay:<?php echo esc_attr( $vr_i * 0.1 ); ?>s">
					<span class="vr-point__icon"><?php vr_icone( $vr_icones[ $vr_i ] ); ?></span>
					<div>
						<h3 class="vr-h3"><?php echo esc_html( get_the_title( $vr_point ) ); ?></h3>
						<?php if ( vr_meta( $vr_point->ID, 'vr_texte' ) ) : ?>
							<p><?php echo esc_html( vr_meta( $vr_point->ID, 'vr_texte' ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
