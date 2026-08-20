<?php
/**
 * Bande des chiffres clés.
 *
 * @package VillaRaffy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vr_brut = get_theme_mod(
	'vr_chiffres_liste',
	"180 m² | de villa de plain-pied | ruler\n8 | voyageurs | users\n4 | chambres & suites | bed\n2300 m² | de terrain clos | tree\n9 m | de piscine, bar immergé | waves\n5 places | de jacuzzi | spa"
);

$vr_lignes = array_filter( array_map( 'trim', explode( "\n", (string) $vr_brut ) ) );

if ( ! $vr_lignes ) {
	return;
}
?>

<section class="vr-wrap" style="padding-block:64px">
	<div class="vr-stats">
		<?php foreach ( $vr_lignes as $vr_index => $vr_ligne ) : ?>
			<?php
			$vr_parts   = array_map( 'trim', explode( '|', $vr_ligne ) );
			$vr_valeur  = isset( $vr_parts[0] ) ? $vr_parts[0] : '';
			$vr_legende = isset( $vr_parts[1] ) ? $vr_parts[1] : '';
			$vr_icone   = isset( $vr_parts[2] ) ? $vr_parts[2] : 'check';

			if ( ! $vr_valeur ) {
				continue;
			}
			?>
			<div class="vr-stat vr-reveal" style="transition-delay:<?php echo esc_attr( $vr_index * 0.07 ); ?>s">
				<div class="vr-stat__icon"><?php vr_icone( $vr_icone ); ?></div>
				<div class="vr-stat__value"><?php echo esc_html( $vr_valeur ); ?></div>
				<div class="vr-stat__label"><?php echo esc_html( $vr_legende ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
