<?php
/**
 * Bande des chiffres clés : deux colonnes symétriques de part et d'autre d'un axe.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_brut   = get_theme_mod( 'vr_chiffres_liste', '' );
$vr_lignes = array_filter( array_map( 'trim', explode( "\n", (string) $vr_brut ) ) );
$vr_items  = array();

foreach ( $vr_lignes as $vr_ligne ) {
	$vr_parts = array_map( 'trim', explode( '|', $vr_ligne ) );
	if ( empty( $vr_parts[0] ) ) {
		continue;
	}
	$vr_items[] = array(
		'valeur'  => $vr_parts[0],
		'legende' => isset( $vr_parts[1] ) ? $vr_parts[1] : '',
		'icone'   => isset( $vr_parts[2] ) && $vr_parts[2] ? $vr_parts[2] : 'check',
	);
}

if ( ! $vr_items ) {
	return;
}

$vr_moitie   = (int) ceil( count( $vr_items ) / 2 );
$vr_colonnes = array(
	'gauche' => array_slice( $vr_items, 0, $vr_moitie ),
	'droite' => array_slice( $vr_items, $vr_moitie ),
);
$vr_compteur = 0;
?>

<section class="vr-wrap vr-chiffres" id="chiffres">
	<div class="vr-stats">
		<?php foreach ( $vr_colonnes as $vr_cote => $vr_liste ) : ?>
			<?php if ( 'droite' === $vr_cote ) : ?>
				<div class="vr-stats__axe" aria-hidden="true"></div>
			<?php endif; ?>
			<div class="vr-stats__col vr-stats__col--<?php echo esc_attr( $vr_cote ); ?>">
				<?php foreach ( $vr_liste as $vr_item ) : ?>
					<div class="vr-stat vr-reveal" style="transition-delay:<?php echo esc_attr( ( $vr_compteur++ ) * 0.07 ); ?>s">
						<div class="vr-stat__icon"><?php vr_icone( $vr_item['icone'] ); ?></div>
						<div class="vr-stat__txt">
							<div class="vr-stat__value"><?php echo esc_html( $vr_item['valeur'] ); ?></div>
							<?php if ( $vr_item['legende'] ) : ?>
								<div class="vr-stat__label"><?php echo esc_html( $vr_item['legende'] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
