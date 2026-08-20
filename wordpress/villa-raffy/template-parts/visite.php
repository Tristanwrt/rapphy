<?php
/**
 * Visite guidée : le scroll fait avancer la visite latéralement dans une zone,
 * puis descendre d'une zone à l'autre — comme un parcours dans la maison.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_etapes = vr_contenus( 'vr_espace' );

if ( count( $vr_etapes ) < 2 ) {
	return;
}

// Position de chaque étape sur le grand plan, en écrans.
$vr_positions = array();
$vr_x         = 0;
$vr_y         = 0;

foreach ( $vr_etapes as $vr_index => $vr_etape ) {
	if ( $vr_index > 0 ) {
		if ( 'bas' === vr_meta( $vr_etape->ID, 'vr_direction', 'droite' ) ) {
			$vr_y++;
		} else {
			$vr_x++;
		}
	}
	$vr_positions[] = array( $vr_x, $vr_y );
}

$vr_total = count( $vr_etapes );
?>

<section class="vr-tour" id="visite" aria-label="Visite guidée de la villa" style="height:<?php echo esc_attr( $vr_total * 100 ); ?>vh" data-total="<?php echo esc_attr( $vr_total ); ?>">
	<div class="vr-tour__sticky">

		<div class="vr-tour__head">
			<p class="vr-eyebrow">La visite guidée<span id="vr-tour-zone"></span></p>
			<h2>Poussez la porte, laissez-vous guider</h2>
		</div>

		<div class="vr-tour__plan" id="vr-tour-plan">
			<?php foreach ( $vr_etapes as $vr_index => $vr_etape ) : ?>
				<?php
				$vr_pos       = $vr_positions[ $vr_index ];
				$vr_zone      = vr_meta( $vr_etape->ID, 'vr_zone', '' );
				$vr_texte     = vr_meta( $vr_etape->ID, 'vr_texte', '' );
				$vr_direction = vr_meta( $vr_etape->ID, 'vr_direction', 'droite' );
				?>
				<div class="vr-tour__step"
					style="left:<?php echo esc_attr( $vr_pos[0] * 100 ); ?>vw;top:<?php echo esc_attr( $vr_pos[1] * 100 ); ?>vh"
					data-zone="<?php echo esc_attr( $vr_zone ); ?>"
					data-titre="<?php echo esc_attr( get_the_title( $vr_etape ) ); ?>"
					data-direction="<?php echo esc_attr( $vr_direction ); ?>">

					<?php vr_image( get_post_thumbnail_id( $vr_etape ), 'vr-hero', get_the_title( $vr_etape ), '' ); ?>

					<div class="vr-tour__caption">
						<div class="vr-tour__caption-inner">
							<span class="vr-tour__num"><?php echo esc_html( str_pad( $vr_index + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
							<div>
								<?php if ( $vr_zone ) : ?>
									<p class="vr-tour__zone"><?php echo esc_html( $vr_zone ); ?></p>
								<?php endif; ?>
								<h3 class="vr-tour__title"><?php echo esc_html( get_the_title( $vr_etape ) ); ?></h3>
								<?php if ( $vr_texte ) : ?>
									<p class="vr-tour__text"><?php echo esc_html( $vr_texte ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="vr-tour__hud">
			<div class="vr-tour__meta">
				<span id="vr-tour-compteur">01 / <?php echo esc_html( str_pad( $vr_total, 2, '0', STR_PAD_LEFT ) ); ?></span>
				<span class="vr-tour__next" id="vr-tour-suivant">
					<span id="vr-tour-suivant-texte"></span>
					<?php vr_icone( 'arrow', 'vr-icon vr-icon--sm' ); ?>
				</span>
			</div>
			<div class="vr-tour__bar"><span id="vr-tour-barre"></span></div>
			<p class="vr-tour__hint">Faites défiler pour visiter les <?php echo esc_html( $vr_total ); ?> espaces</p>
		</div>

	</div>
</section>
